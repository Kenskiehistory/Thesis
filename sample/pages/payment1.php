<?php
require '../vendor/autoload.php';
require_once '../includes/db_connect.php';
require_once '../includes/ledger_functions.php';
include('../includes/header.php');
use GuzzleHttp\Client;

if (isset($_POST['action']) && $_POST['action'] === 'update_payments') {
    try {
        $payments = getAllPayments();
        $creditResults = [];
        foreach ($payments as $payment) {
            if ($payment['attributes']['status'] === 'paid') {
                $creditResults[$payment['id']] = processPaymentCredit($payment);
            } else {
                $creditResults[$payment['id']] = ["status" => "not_paid", "message" => "Payment not yet paid"];
            }
        }

        $response = [
            'success' => true,
            'payments' => $payments,
            'creditResults' => $creditResults
        ];
    } catch (Exception $e) {
        $response = [
            'success' => false,
            'message' => "An error occurred while processing payments: " . $e->getMessage()
        ];
    }

    // Clear any output that might have been generated
    ob_end_clean();

    // Set the content type and output the JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
function getAllPayments() {
    $secretKey = 'sk_test_Q8AjFku7zPYSw5epnJuStDAJ'; // Replace with your PayMongo secret key
    $url = 'https://api.paymongo.com/v1/payments';

    // Create a new Guzzle HTTP client
    $client = new Client([
        'base_uri' => 'https://api.paymongo.com/',
    ]);

    try {
        // Send GET request to fetch all payments
        $response = $client->request('GET', $url, [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($secretKey . ':'),
                'Accept' => 'application/json',
            ]
        ]);

        // Decode the JSON response
        $payments = json_decode($response->getBody(), true);
        return $payments['data']; // Return the payment data
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
}
function isPaymentProcessed($payment_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT COUNT(*) FROM processed_payments WHERE payment_id = ?");
    $stmt->bind_param("s", $payment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_row()[0];
    return $count > 0;
}

function markPaymentAsProcessed($payment_id) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO processed_payments (payment_id, processed_at) VALUES (?, NOW())");
    $stmt->bind_param("s", $payment_id);
    $stmt->execute();
}

function processPaymentCredit($payment) {
    if (isPaymentProcessed($payment['id'])) {
        return ["status" => "already_processed", "message" => "Amount already added to ledger"];
    }

    $description = $payment['attributes']['description'];
    $amount = $payment['attributes']['amount'] / 100;

    if (preg_match('/^(.*?)\s*\(User ID: (\d+)\)/', $description, $matches)) {
        $user_profile_id = $matches[2];
        $particulars = 'T/F Payment: '.trim($matches[1]);  // This is the description without the User ID part
        $original_receipt = "Payment received: " . $payment['id'];
        
        add_credit_paymongo($user_profile_id, $amount, $particulars, $original_receipt);
        markPaymentAsProcessed($payment['id']);
        
        return ["status" => "processed", "message" => "Credit added for User ID: $user_profile_id, Amount: $amount"];
    } else {
        return ["status" => "error", "message" => "Unable to extract User ID from description"];
    }
}

$payments = getAllPayments();

// Process credits for successful payments
$creditResults = [];
foreach ($payments as $payment) {
    if ($payment['attributes']['status'] === 'paid') {
        $creditResults[$payment['id']] = processPaymentCredit($payment);
    } else {
        $creditResults[$payment['id']] = ["status" => "not_paid", "message" => "Payment not yet paid"];
    }
}

?>

<!-- partial -->
<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-12 grid-margin">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Payments List</h4>
            <p class="card-description">All payment records are displayed below</p>

            <!-- Buttons: Back and Update -->
            <div class="mb-3">
            <button type="button" class="btn btn-secondary" id="payment_form">Back</button>
              <button type="button" class="btn btn-primary" id="updateButton">Update</button>
            </div>

            <div class="table-responsive">
              <table class="table table-bordered" id="paymentsTable">
                <thead>
                  <tr>
                    <th>Payment ID</th>
                    <th>Amount (PHP)</th>
                    <th>Status</th>
                    <th>Description</th>
                    <th>Created At</th>
                    <th>Email</th>
                    <th>Payer Name</th>
                    <th>Ledger Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($payments)) : ?>
                      <?php foreach ($payments as $payment) : 
                          $billing = $payment['attributes']['billing'];
                          $payerEmail = isset($billing['email']) ? $billing['email'] : 'N/A';
                          $payerName = isset($billing['name']) ? $billing['name'] : 'N/A';
                          $ledgerStatus = $creditResults[$payment['id']];
                      ?>
                          <tr>
                              <td><?php echo $payment['id']; ?></td>
                              <td><?php echo number_format($payment['attributes']['amount'] / 100, 2); ?></td>
                              <td><?php echo ucfirst($payment['attributes']['status']); ?></td>
                              <td><?php echo $payment['attributes']['description']; ?></td>
                              <td><?php echo date('Y-m-d H:i:s', $payment['attributes']['created_at']); ?></td>
                              <td><?php echo $payerEmail; ?></td>
                              <td><?php echo $payerName; ?></td>
                              <td>
                                  <?php
                                  if ($ledgerStatus['status'] === 'processed') {
                                      echo '<span class="text-success">Amount added to ledger</span>';
                                  } elseif ($ledgerStatus['status'] === 'already_processed') {
                                      echo '<span class="text-info">Amount already added to ledger</span>';
                                  } elseif ($ledgerStatus['status'] === 'not_paid') {
                                      echo '<span class="text-warning">Payment not yet paid</span>';
                                  } else {
                                      echo '<span class="text-danger">' . $ledgerStatus['message'] . '</span>';
                                  }
                                  ?>
                              </td>
                          </tr>
                      <?php endforeach; ?>
                  <?php else : ?>
                      <tr>
                          <td colspan="8">No payments found</td>
                      </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
include('../includes/footer.php');
?>