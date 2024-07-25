<?php

// API URLs
$otp_add_url = 'https://api.antlabs.com/otp_add';
$otp_check_url = 'https://api.antlabs.com/otp_check';
$smpp_post_url = 'https://api.antlabs.com/smpp_post';

// API credentials
$api_password = 'pass';
$form_uid = 'form_uid';

// generate OTP
function generateOTP($mobile) {
    global $otp_add_url, $api_password, $form_uid;

    $postData = [
        'form_uid' => $form_uid,
        'verify_by_id' => $mobile,
        'otp_expiry_duration' => 300 // OTP expiry time in seconds (5 minutes)
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $otp_add_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', 'api_password: ' . $api_password));
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// send OTP via SMS
function sendOTPSMS($mobile, $otp) {
    global $smpp_post_url, $api_password;

    $postData = [
        'mobile' => $mobile,
        'message' => "Your OTP is: $otp"
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $smpp_post_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', 'api_password: ' . $api_password));
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// validate OTP
function validateOTP($mobile, $otp) {
    global $otp_check_url, $api_password, $form_uid;

    $postData = [
        'form_uid' => $form_uid,
        'verify_by_id' => $mobile,
        'otp' => $otp
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $otp_check_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', 'api_password: ' . $api_password));
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['mobile'])) {
        $mobile = $_POST['mobile'];

        // Generate OTP
        $otpResponse = generateOTP($mobile);

        if ($otpResponse['resultcode'] == 0) {
            $otp = $otpResponse['otp'];

            // Send OTP via SMS
            sendOTPSMS($mobile, $otp);

            echo '<h3 class="notification">OTP sent to your mobile number.</h3>';
        } else {
            echo '<h3 class="notification">Error generating OTP: ' . $otpResponse['error'] . '</h3>';
        }
    } elseif (isset($_POST['otp'])) {
        $mobile = $_POST['mobile'];
        $otp = $_POST['otp'];

        // Validate OTP
        $validateResponse = validateOTP($mobile, $otp);

        if ($validateResponse['resultcode'] == 0) {
            echo "OTP validation successful. You are now connected.";
        } else {
            echo "Invalid OTP: " . $validateResponse['error'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Guest Wi-Fi Login</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <?php include('templates/header.php') ?>

    <section class="heading-container">
        <h2 class="center title">Welcome to ANTLabs</h2>
        <p class="center">To use WiFi, please select access type to login</p>
    </section>
    <section class="authentication-selector">
    <?php if ($_SERVER['REQUEST_METHOD'] == 'GET' || isset($_POST['mobile']) && !isset($_POST['otp'])): ?> <!-- $_SERVER['REQUEST_METHOD'] == 'GET' || isset($_POST['mobile']) && !isset($_POST['otp']) -->
        <form method="post">
            <label for="mobile">Enter your mobile number:</label>
            <input type="text" id="mobile" name="mobile" class="auth-method" required>
            <button type="submit" class="req-otp-btn">Send OTP</button>
        </form>
    <?php elseif (isset($_POST['mobile'])): ?>
        <form method="post">
            <input type="hidden" name="mobile" value="<?php echo htmlspecialchars($_POST['mobile']); ?>">
            <label for="otp">Enter the OTP:</label>
            <input type="text" id="otp" name="otp" required>
            <button type="submit" class="req-otp-btn">Validate OTP</button>
        </form>
    <?php endif; ?>
    </section>
    <?php include('templates/footer.php') ?>
</body>
</html>
