<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS Authentication</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <?php include('templates/header.php') ?>
    
    <section class="heading-container">
        <h2 class="center title">Welcome to ANTLabs</h2>
        <p class="center">To use WiFi, please select access type to login</p>
    </section>

    <section class="authentication-selector">
        <form action="functions/smsauth.php" method="POST">
            <label for="mobile-no">Mobile No</label>
            <input type="text" id="mobileNo" name="mobileNo" value="" class="auth-method">
            <input type="hidden" id="formId" name="formId" value="smsauth">
            <input type="submit" value="Request OTP" class="req-otp-btn">
        </form>
    </section>
    <?php include('templates/footer.php') ?>
</body>
</html>