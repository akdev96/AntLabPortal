<?php

if($_SERVER["REQUEST_METHOD"] == "POST"){
    createOTP($_POST['formId'], $_POST['mobileNo']);
}

function createOTP($formId, $mobileNo){
    $url = 'https://antlabs.com/api/v1/otp_add';
    $req =  curl_init();
    $data = [
        'form_uid' => $formId,
        'verify_by_id' => $mobileNo,
        'otp_expiry_duration' => '300' // 5mins in seconds
    ];

    // !commenting below lines to prevent breaking due to dummy url
    curl_setopt($req, CURLOPT_URL, $url);
    curl_setopt($req, CURLOPT_POST, true);
    curl_setopt($req, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($req, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    $res = curl_exec($req);

    // to here
    session_start(); 
    if ($response === false) {
        echo "cURL Error while requesting OTP: " . curl_error($curl);
    } else {
        // Process the response
        $responseData = json_decode($response, true);
        if($responseData.result === 'ok'){
            $otpGen = $responseData.otp;
            $_SESSION["OTP"]=$otpGen;
            // redirect to otp enter page
            // validate otp there

        } else {
            echo 'ERROR: '. $responseData.resultresultcode; 
            header("Location: sms_auth.php");
        }
    }
    
    curl_close($req); // and this
}

?>