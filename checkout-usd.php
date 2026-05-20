<?php
// 1. Initialize your Razorpay API Credentials
$key_id = 'rzp_live_SrL691Dr4FN3Nh'; 
$key_secret = 'B4Dt7SlT4PrJi76R6KxBLlbE';

// 2. Set up the exact payment link configuration forcing USD
$payload = array(
    "amount" => 10000, // $100.00 USD (Razorpay counts in cents: $100 * 100)
    "currency" => "USD",
    "accept_partial" => false,
    "description" => "International Cardiology Review - Prof. Dr. Agrawal",
    "customer" => array(
        "name" => "", // Left blank for patient to fill out safely
        "email" => "",
        "contact" => ""
    ),
    "notify" => array(
        "sms" => false,
        "email" => true // Razorpay will auto-email them a professional receipt
    ),
    "reminder_enable" => false,
    "notes" => array(
        "clinic_branch" => "Pristine Heart Care Clinic"
    ),
    // Crucial: This forces the patient's browser to redirect back to your site instantly
    "callback_url" => "https://professoragrawal.com",
    "callback_method" => "get"
);

// 3. Make the secure backend call to Razorpay Server
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.razorpay.com/v1/payment_links');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_USERPWD, $key_id . ':' . $key_secret);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

$result = curl_exec($ch);
curl_close($ch);

// 4. Extract the live checkout URL and direct the patient instantly
$response = json_decode($result, true);

if (isset($response['short_url'])) {
    header("Location: " . $response['short_url']);
    exit();
} else {
    echo "Gateway Configuration Error. Please contact consult@professoragrawal.com";
}
?>
