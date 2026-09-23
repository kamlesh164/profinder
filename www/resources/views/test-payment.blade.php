<!DOCTYPE html>
<html>
<head><title>Test Payment</title></head>
<body>
    <h2>🧪 Mock Payment Success</h2>
    <p>Payment Session: {{ $session_id }}</p>
    <button onclick="simulateSuccess()">Mark as PAID ✅</button>
    <button onclick="simulateFailed()">Mark as FAILED ❌</button>
    
    <script>
    function simulateSuccess() {
        // Simulate return_url callback
        window.location.href = '/payment/return?order_id=ORDER_1_1734029760&cf_payment_id=12345';
    }
    </script>
</body>
</html>
