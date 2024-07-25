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

// create a visitor account
function createVisitorAccount() {
    // TODO

    // Example of a 4-hour validity account
    return [
        'userid' => 'day_visitor_' . time(),
        'password' => 'visitor_password'
    ];
}

// Function to log in the user
function loginUser($sid, $userid, $password) {
    global $auth_login_url, $api_password;

    $postData = [
        'sid' => $sid,
        'userid' => $userid,
        'password' => $password,
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

// Main logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['accept_terms'])) {
        // These values should be obtained dynamically based on your environment
        $client_mac = 'your_client_mac';
        $client_ip = 'your_client_ip';
        $location_index = 'your_location_index';
        $ppli = 'your_ppli';

        // Initialize session
        $sessionResponse = initializeSession($client_mac, $client_ip, $location_index, $ppli);

        if ($sessionResponse['resultcode'] == 0) {
            $sid = $sessionResponse['sid'];

            // Create visitor account
            $visitorAccount = createVisitorAccount();

            // Log in user
            $loginResponse = loginUser($sid, $visitorAccount['userid'], $visitorAccount['password']);

            if ($loginResponse['resultcode'] == 0) {
                // Redirect to success URL
                header('Location: templates/success.php');
                exit;
            } else {
                echo "Login error: " . $loginResponse['error'];
            }
        } else {
            echo "Session initialization error: " . $sessionResponse['error'];
        }
    } else {
        echo "You must accept the terms and conditions to get internet access.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Guest Wi-Fi Day Visitor Login</title>
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
            <h1>Terms and Conditions</h1>
            <p>Please read and accept the terms and conditions to proceed.</p>
            <input type="checkbox" id="accept_terms" name="accept_terms" value="1" required>
            <label for="accept_terms">I accept the terms and conditions</label>
            <br>
            <button type="submit" class="req-otp-btn">Continue</button>
        </form>
    </section>
    <?php include('templates/footer.php') ?>
</body>
</html>
