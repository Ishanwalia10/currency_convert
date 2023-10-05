

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $amount = floatval($_POST["amount"]);

    $from_currency = $_POST["from_currency"];

    $to_currency = $_POST["to_currency"];

    

    // Replace this with the URL of the exchange rate API you want to use.

    $api_url = "https://v6.exchangerate-api.com/v6/129fae8f4b7498369f267b5e/latest/USD"; // Your API URL

    

    // Set up the cURL session

    $curl = curl_init($api_url);

    

    // Set cURL options

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    

    // Execute cURL session and fetch the response

    $api_response = curl_exec($curl);

    

    // Check if the API request was successful

    if ($api_response === false) {

        echo "Failed to fetch exchange rates.";

    } else {

        // Parse the JSON response from the API

        $exchange_rates_data = json_decode($api_response, true);

        

        // Check if the desired currency conversion is supported

        if (isset($exchange_rates_data['conversion_rates'][$to_currency])) {

            $conversion_rate = $exchange_rates_data['conversion_rates'][$to_currency];

            $converted_amount = $amount * $conversion_rate;

            echo "Converted amount: $converted_amount $to_currency";

// Assuming you have a MySQL database set up and connected.

$servername = "localhost";

$username = "root";

$password = "Welcome@123";

$database = "currency_exchange";

 

// Create a connection to the database

$conn = new mysqli($servername, $username, $password, $database);

 

// Check if the connection is successful

if ($conn->connect_error) {

    die("Connection failed: " . $conn->connect_error);

}

 

// Insert or update the exchange rate data in the database

if (isset($exchange_rates_data['conversion_rates'])) {

    foreach ($exchange_rates_data['conversion_rates'] as $to_currency => $conversion_rate) {

        // Check if the record already exists (you can use a unique key for the source and target currencies)

        $check_sql = "SELECT * FROM exchange_rates WHERE from_currency = '$from_currency' AND to_currency = '$to_currency'";

        $result = $conn->query($check_sql);

 

        if ($result->num_rows > 0) {

            // Update the existing record

            $update_sql = "UPDATE exchange_rates SET rate = $conversion_rate WHERE from_currency = '$from_currency' AND to_currency = '$to_currency'";

            $conn->query($update_sql);

        } else {

            // Insert a new record

            $insert_sql = "INSERT INTO exchange_rates (from_currency, to_currency, rate) VALUES ('$from_currency', '$to_currency', $conversion_rate)";

            $conn->query($insert_sql);

        }

    }

}

 

// Close the database connection

$conn->close();

 

// ... The rest of your code to display the converted amount ...


        } else {

            echo "Conversion not supported.";

        }

    }

    

    // Close cURL session

    curl_close($curl);

}

?>