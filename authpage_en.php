<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication Method</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <?php include('templates/header.php') ?>
    
    <section class="heading-container">
        <h2 class="center title">Welcome to ANTLabs</h2>
        <p class="center">To use WiFi, please select access type to login</p>
    </section>

    <section class="authentication-selector">
        <!-- <form action="functions/login.php" method="POST">
            <input type="submit" name="AuthMethod" value="SMS Authentication" class="auth-method">
            <input type="submit" name="AuthMethod" value="Member Login" class="auth-method">
            <input type="submit" name="AuthMethod" value="Day Visitor (4hr)" class="auth-method">
        </form> -->

        <div class="lang-selector">
            <a type="button" href="sms_auth.php" class="button">SMS Authentication</a>
            <a type="button" href="member_auth.php" class="button">Member Login</a>
            <a type="button" href="dayvisitor_auth.php" class="button">Day Visitor (4hr)</a>
        </div>

    </section>
    <?php include('templates/footer.php') ?>
</body>
</html>