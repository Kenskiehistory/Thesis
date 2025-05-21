<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayMongo Payment Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            color: #2c3e50;
        }
        form {
            background: #f4f4f4;
            padding: 20px;
            border-radius: 5px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background: #3498db;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background: #2980b9;
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <h1>PayMongo Payment Form</h1>
    <form id="paymentForm" method="post" action="process_payment.php">
        <label for="paymentType">Payment Type:</label>
        <select id="paymentType" name="paymentType" required>
            <option value="">Select payment type</option>
            <option value="card">Credit Card</option>
            <option value="gcash">GCash</option>
            <option value="grab_pay">GrabPay</option>
        </select>

        <div id="cardDetails" class="hidden">
            <label for="cardNumber">Card Number:</label>
            <input type="text" id="cardNumber" name="cardNumber" placeholder="1234 5678 9012 3456">
            
            <label for="expMonth">Expiry Month:</label>
            <input type="number" id="expMonth" name="expMonth" placeholder="MM" min="1" max="12">
            
            <label for="expYear">Expiry Year:</label>
            <input type="number" id="expYear" name="expYear" placeholder="YYYY" min="2023" max="2099">
            
            <label for="cvc">CVC:</label>
            <input type="text" id="cvc" name="cvc" placeholder="123">
        </div>

        <h2>Billing Information</h2>
        <label for="name">Full Name:</label>
        <input type="text" id="name" name="name" required>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        
        <label for="phone">Phone:</label>
        <input type="tel" id="phone" name="phone" required>
        
        <label for="address1">Address Line 1:</label>
        <input type="text" id="address1" name="address1" required>
        
        <label for="address2">Address Line 2:</label>
        <input type="text" id="address2" name="address2">
        
        <label for="city">City:</label>
        <input type="text" id="city" name="city" required>
        
        <label for="state">State:</label>
        <input type="text" id="state" name="state" required>
        
        <label for="postalCode">Postal Code:</label>
        <input type="text" id="postalCode" name="postalCode" required>
        
        <label for="country">Country Code:</label>
        <input type="text" id="country" name="country" placeholder="e.g., PH" required>

        <button type="submit">Submit Payment</button>
    </form>

    <script>
        document.getElementById('paymentType').addEventListener('change', function() {
            var cardDetails = document.getElementById('cardDetails');
            if (this.value === 'card') {
                cardDetails.classList.remove('hidden');
            } else {
                cardDetails.classList.add('hidden');
            }
        });
    </script>
</body>
</html>