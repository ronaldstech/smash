<?php
// 1. --- SECURITY AND ERROR HANDLING ---
// Check if the form was actually submitted via POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    // Redirect or display an error if accessed directly
    header("Location: /index.html"); // Replace index.html with your form page if needed
    exit;
}

// 2. --- CONFIGURATION ---
// Set the recipient email address where you want to receive the booking requests
$recipient_email = "your_academy_email@example.com"; // **CHANGE THIS TO YOUR EMAIL**
$subject = "NEW BOOKING REQUEST - Smash Squad Academy";
$sender_email = "noreply@yourdomain.com"; // **CHANGE THIS DOMAIN**

// 3. --- COLLECTING AND SANITIZING DATA ---
// NOTE: For simplicity, the HTML inputs need 'name' attributes added.
// Assuming you have added names like this to your HTML inputs:
/*
   <input type="text" name="full_name" required>
   <input type="email" name="email" required>
   ... and so on.
*/

// Sanitize user input to prevent basic injection attacks
$full_name = filter_var($_POST['full_name'], FILTER_SANITIZE_STRING);
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
$service = filter_var($_POST['service'], FILTER_SANITIZE_STRING);
$skill_level = filter_var($_POST['skill_level'], FILTER_SANITIZE_STRING);
$coach = filter_var($_POST['coach'], FILTER_SANITIZE_STRING);
$date = filter_var($_POST['date'], FILTER_SANITIZE_STRING);
$time = filter_var($_POST['time'], FILTER_SANITIZE_STRING);
$message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);

// 4. --- BUILD THE EMAIL CONTENT ---
$email_content = "A new booking request has been submitted:\n\n";
$email_content .= "Full Name: $full_name\n";
$email_content .= "Email: $email\n";
$email_content .= "Phone: $phone\n";
$email_content .= "---------------------------------------\n";
$email_content .= "Service Requested: $service\n";
$email_content .= "Skill Level: $skill_level\n";
$email_content .= "Preferred Coach: $coach\n";
$email_content .= "Preferred Date: $date\n";
$email_content .= "Preferred Time: $time\n";
$email_content .= "---------------------------------------\n";
$email_content .= "Message:\n$message\n";

// Set email headers
$email_headers = "From: " . $full_name . " <$sender_email>\r\n";
$email_headers .= "Reply-To: $email\r\n";
$email_headers .= "Content-type: text/plain; charset=utf-8\r\n";

// 5. --- SEND EMAIL AND PROVIDE FEEDBACK ---
if (mail($recipient_email, $subject, $email_content, $email_headers)) {
    // Success - display a thank you message
    echo "<h2>✅ Success!</h2>";
    echo "<p>Thank you, $full_name. Your booking request has been sent! We will contact you at <strong>$email</strong> soon to confirm the details.</p>";
    echo "<p><a href='index.html'>Click here to return to the form.</a></p>";
} else {
    // Failure - display an error message
    echo "<h2>❌ Error!</h2>";
    echo "<p>Sorry, there was an issue sending your request. Please try again or contact us directly at $recipient_email.</p>";
}
?>