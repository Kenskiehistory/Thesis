<?php
session_start();
require_once('../includes/db_connect.php');

// Initialize variables to store form data and errors
$amount = $description = '';
$errors = [];
$paymentUrl = '';

// Check if user is logged in and is a student
if (!isset($_SESSION['id']) || $_SESSION['roles'] !== 'Student') {
    header('Location: login.php');
    exit();
}

// Fetch user_profile_id
$user_id = $_SESSION['id'];
$stmt = $conn->prepare("SELECT id FROM user_profile WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_profile = $result->fetch_assoc();
$stmt->close();

if (!$user_profile) {
    die("Error: User profile not found.");
}

$user_profile_id = $user_profile['id'];

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);
    $description = htmlspecialchars(strip_tags($_POST['description'])); // Replace FILTER_SANITIZE_STRING

    if ($amount === false || $amount < 1) {
        $errors[] = "Please enter a valid amount of at least 1.00.";
    }

    if (empty($description)) {
        $errors[] = "Please enter a description for the payment.";
    }

    // Check if the description contains a User ID pattern
    if (preg_match('/\(User ID: \d+\)/', $description)) {
        $errors[] = "Please remove any '(User ID: numbers)' from your description.";
    }

    // If no errors, process the payment
    if (empty($errors)) {
        // Append user_profile_id to the description
        $description_with_id = $description . " (User ID: " . $user_profile_id . ")";

        // Check if cURL is available
        if (!function_exists('curl_init')) {
            die("cURL is not enabled. Please enable the cURL extension in your PHP configuration.");
        }

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.paymongo.com/v1/links",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                "data" => [
                    "attributes" => [
                        "amount" => intval($amount * 100), // Convert to cents and ensure it's an integer
                        "description" => $description_with_id,
                        "remarks" => "Payment for Student ID: " . $user_profile_id,
                        "metadata" => [
                            "user_profile_id" => $user_profile_id,
                            "description" => $description
                        ]
                    ]
                ]
            ]),
            CURLOPT_HTTPHEADER => [
                "accept: application/json",
                "authorization: Basic c2tfdGVzdF9ROEFqRmt1N3pQWVN3NWVwbkp1U3REQUo6", // Replace with your secret key
                "content-type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $result = json_decode($response, true);
            if (isset($result['data']['attributes']['checkout_url'])) {
                $paymentUrl = $result['data']['attributes']['checkout_url'];
            } else {
                echo "Error creating payment link. Please try again.";
            }
        }
    }
}

?>
<!-- partial -->
<div class="container-fluid page-body-wrapper">
  <div class="main-panel">
    <div class="content-wrapper">
      <div class="row">
        <div class="col-12 grid-margin">
          <div class="card">
            <div class="card-body">
              <h1 class="card-title">Payment Form</h1>

              <!-- Display errors if any -->
              <?php if (!empty($errors)): ?>
                  <div class="alert alert-danger">
                      <?php foreach ($errors as $error): ?>
                          <p><?php echo $error; ?></p>
                      <?php endforeach; ?>
                  </div>
              <?php endif; ?>

              <!-- Payment form -->
              <form id="paymentForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="mb-3">
                  <label for="amount" class="form-label">Amount (in PHP):</label>
                  <input type="number" class="form-control" id="amount" name="amount" step="0.01" min="1.00" value="<?php echo htmlspecialchars($amount); ?>" required>
                </div>

                <div class="mb-3">
                  <label for="description" class="form-label">Description:</label>
                  <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($description); ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary w-100">Proceed to Payment</button>
              </form>

              <!-- Payment URL for redirection if applicable -->
              <?php if ($paymentUrl): ?>
                  <meta name="payment-url" content="<?php echo htmlspecialchars($paymentUrl); ?>">
              <?php endif; ?>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- main-panel ends -->
</div>

<?php
include('../includes/footer.php');
?>
    