<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Status</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container vh-100 d-flex justify-content-center align-items-center">
        <div id="status-container" class="text-center">
            <!-- الرسالة ستظهر هنا بناءً على القيمة -->
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // قيمة الإرجاع من الـ backend
        const paymentStatus = {{ $paymentStatus }};

        // تحديد العنصر الذي سيتم وضع الرسالة فيه
        const statusContainer = document.getElementById('status-container');

        if (paymentStatus === 1) {
            // إذا كانت القيمة 1، عرض رسالة النجاح
            statusContainer.innerHTML = `
                <div class="alert alert-success" role="alert">
                    <h1 class="display-4">Payment Successful</h1>
                    <p class="lead">Your payment was processed successfully. Thank you!</p>
                </div>
            `;
        } else if (paymentStatus === 0) {
            // إذا كانت القيمة 0، عرض رسالة الإلغاء
            statusContainer.innerHTML = `
                <div class="alert alert-danger" role="alert">
                    <h1 class="display-4">Payment Cancelled</h1>
                    <p class="lead">Payment was cancelled by the user. Please try again if you wish to proceed.</p>
                </div>
            `;
        }
        else{
            // إذا كانت القيمة2، عرض رسالة الإلغاء
            statusContainer.innerHTML = `
                <div class="alert alert-danger" role="alert">
                    <h1 class="display-4">404</h1>
                    <p class="lead">Payment session not found.. Please try again if you wish to proceed.</p>
                </div>
            `;
        }
    </script>
</body>
</html>
