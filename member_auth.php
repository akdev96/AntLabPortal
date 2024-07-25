<?php

// API URLs
$auth_init_url = 'https://api.antlabs.com/auth_init';
$auth_login_url = 'https://api.antlabs.com/auth_login';

// API credentials
$api_password = 'your_api_password';

// initialize a session
function initializeSession($client_mac, $client_ip, $location_index, $ppli) {
    global $auth_init_url, $api_password;

    $postData = [
        'client_mac' => $client_mac,
        'client_ip' => $client_ip,
        'location_index' => $location_index,
        'ppli' => $ppli
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $auth_init_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', 'api_password: ' . $api_password));
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// validate Member ID and PIN with external API
function validateMember($member_id, $pin) {
    // validate url
    $external_api_url = 'https://external-api.com/validate_member';

    $postData = [
        'member_id' => $member_id,
        'pin' => $pin
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $external_api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// log in the user
function loginUser($sid, $member_id, $pin) {
    global $auth_login_url, $api_password;

    $postData = [
        'sid' => $sid,
        'userid' => $member_id,
        'password' => $pin,
        'mode' => 'login'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $auth_login_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', 'api_password: ' . $api_password));
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['member_id']) && isset($_POST['pin'])) {
        $member_id = $_POST['member_id'];
        $pin = $_POST['pin'];

        // env data
        $client_mac = 'your_client_mac';
        $client_ip = 'your_client_ip';
        $location_index = 'your_location_index';
        $ppli = 'your_ppli';

        // Initialize session
        $sessionResponse = initializeSession($client_mac, $client_ip, $location_index, $ppli);

        if ($sessionResponse['resultcode'] == 0) {
            $sid = $sessionResponse['sid'];

            // Validate Member ID and PIN
            $validationResponse = validateMember($member_id, $pin);

            if ($validationResponse['valid']) {
                // Log in user
                $loginResponse = loginUser($sid, $member_id, $pin);

                if ($loginResponse['resultcode'] == 0) {
                    // Redirect to success URL
                    header('Location: success_url.php');
                    exit;
                } else {
                    echo "Login error: " . $loginResponse['error'];
                }
            } else {
                echo "Invalid Member ID or PIN.";
            }
        } else {
            echo "Session initialization error: " . $sessionResponse['error'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Guest Wi-Fi Member Login</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <?php include('templates/header.php') ?>
    <section class="heading-container">
        <h2 class="center title">Welcome to ANTLabs</h2>
        <p class="center">To use WiFi, please select access type to login</p>
    </section>
    <section class="authentication-selector">
        <form method="post">
            <label for="member_id">Enter your Member ID:</label>
            <input type="text" id="member_id" name="member_id" class="auth-method" required>
            <label for="pin">Enter your PIN:</label>
            <input type="password" id="pin" name="pin" class="auth-method" required>
            <button type="submit" class="req-otp-btn">Login</button>
        </form>
    </section>
    <?php include('templates/footer.php') ?>
</body>
</html>
