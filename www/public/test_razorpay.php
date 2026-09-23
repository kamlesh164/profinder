<!DOCTYPE html>
<html>
<head>
    <title>Razorpay Payment Test</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 500px; margin: 50px auto; padding: 20px; }
        button { background: #3399cc; color: white; padding: 15px 30px; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; }
        button:hover { background: #2874a6; }
        #response { margin-top: 20px; padding: 15px; background: #f5f5f5; border-radius: 5px; }
        .error { background: #ffebee; color: #c62828; }
        .success { background: #e8f5e8; color: #2e7d32; }
    </style>
</head>
<body>
    <h2>🧪 Razorpay Payment Test</h2>
    
    <div id="order-info">
        <p><strong>Order ID:</strong> <span id="order-id">order_Rt0VSLW28IzMrx</span></p>
        <p><strong>Amount:</strong> <span id="amount"></span></p>
        <p><strong>Key:</strong> <span id="key">rzp_test_Rr2zEvFJFFy4Yx</span></p>
    </div>

    <button id="rzp-button">Pay Now</button>
    <div id="response"></div>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        // Tumhare API response ka data yaha paste karo
        const razorpayData = {
            razorpay_key: "rzp_test_Rr2zEvFJFFy4Yx",
            order_id: "order_Rt0VSLW28IzMrx",
            amount_paise: 100,
            currency: "INR",
            receipt: "PLAN_19_1766046228"
        };

        // UI update
        document.getElementById('order-id').textContent = razorpayData.order_id;
        document.getElementById('amount').textContent = '₹' + (razorpayData.amount_paise / 100);
        document.getElementById('key').textContent = razorpayData.razorpay_key;

        document.getElementById('rzp-button').onclick = function(e) {
            e.preventDefault();

            const options = {
                key: razorpayData.razorpay_key,
                amount: razorpayData.amount_paise,
                currency: razorpayData.currency,
                name: "Your Company Name",
                description: "Booking Payment Test",
                order_id: razorpayData.order_id,
                handler: function (response) {
                    showResponse('success', `
                        ✅ Payment Success!<br>
                        <strong>Payment ID:</strong> ${response.razorpay_payment_id}<br>
                        <strong>Order ID:</strong> ${response.razorpay_order_id}<br>
                        <strong>Signature:</strong> ${response.razorpay_signature}<br><br>
                        Ye 3 values backend ko POST karo: /payment/verify
                    `);
                },
                prefill: {
                    name: "Test User",
                    email: "test@example.com",
                    contact: "9999999999"
                },
                theme: {
                    color: "#3399cc"
                },
                modal: {
                    ondismiss: function() {
                        showResponse('info', 'Payment cancelled by user');
                    }
                }
            };

            const rzp = new Razorpay(options);
            rzp.open();
        };

        function showResponse(type, message) {
            const responseDiv = document.getElementById('response');
            responseDiv.innerHTML = message;
            responseDiv.className = type;
        }
    </script>
</body>
</html>
