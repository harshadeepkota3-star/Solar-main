<?php
    // Only process POST requests.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the form fields and remove whitespace.
        $name = isset($_POST["name"]) ? strip_tags(trim($_POST["name"])) : '';
        $name = str_replace(array("\r","\n"),array(" "," "),$name);
        
        $phone = isset($_POST["phone"]) ? strip_tags(trim($_POST["phone"])) : '';
        
        $email = isset($_POST["email"]) && !empty($_POST["email"]) ? filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL) : 'No Email Provided';
        
        $location = isset($_POST["location"]) ? strip_tags(trim($_POST["location"])) : '';
        
        $interest = isset($_POST["interest"]) ? strip_tags(trim($_POST["interest"])) : '';
        
        // Handle property type variation between index.html and contact.html
        $propertyTypeRaw = isset($_POST["property-type"]) ? $_POST["property-type"] : (isset($_POST["property_type"]) ? $_POST["property_type"] : '');
        $propertyType = strip_tags(trim($propertyTypeRaw));
        
        // Handle electricity bill variation between index.html and contact.html
        $electricityBillRaw = isset($_POST["electricity-bill"]) ? $_POST["electricity-bill"] : (isset($_POST["electricity_bill"]) ? $_POST["electricity_bill"] : '');
        $electricityBill = strip_tags(trim($electricityBillRaw));
        
        // Handle message/requirements variation
        $messageRaw = isset($_POST["message"]) ? $_POST["message"] : (isset($_POST["requirements"]) ? $_POST["requirements"] : '');
        $message = strip_tags(trim($messageRaw));

        // Basic Validation
        if (empty($name) || empty($phone) || empty($location)) {
            http_response_code(400);
            echo "Please fill out all required fields (Name, Phone, Location).";
            exit;
        }

        // Set the recipient email address.
        $recipient = "mnenterprises.atp@gmail.com";

        // Set a subject
        $subject = "MN Enterprises Website - New Consultation Request from $name";

        // Build the email content.
        $email_content = "You have received a new consultation request from the website.\n\n";
        $email_content .= "--- Client Details ---\n";
        $email_content .= "Name: $name\n";
        $email_content .= "Phone: $phone\n";
        $email_content .= "Email: $email\n";
        $email_content .= "Location: $location\n\n";
        $email_content .= "--- Project Details ---\n";
        $email_content .= "Interested In: $interest\n";
        $email_content .= "Property Type: $propertyType\n";
        $email_content .= "Monthly Electricity Bill: $electricityBill\n\n";
        $email_content .= "--- Requirements / Message ---\n";
        $email_content .= "$message\n";

        // Build the email headers.
        $email_headers = "From: MN Enterprises Website <noreply@mnenterprises.co.in>\r\n";
        if ($email !== 'No Email Provided') {
            $email_headers .= "Reply-To: $name <$email>\r\n";
        }

        // Send the email.
        if (mail($recipient, $subject, $email_content, $email_headers)) {
            // Set a 200 (okay) response code.
            http_response_code(200);
            echo "Thank You! Your free consultation request has been successfully sent to our team.";
        } else {
            // Set a 500 (internal server error) response code.
            http_response_code(500);
            echo "Oops! Something went wrong, and we couldn't send your request. Please call us directly.";
        }

    } else {
        // Not a POST request, set a 403 (forbidden) response code.
        http_response_code(403);
        echo "There was a problem with your submission, please try again.";
    }
?>