<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $service = mysqli_real_escape_string($conn, $_POST['service']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    $sql = "INSERT INTO enquiries (name, email, service, message) VALUES ('$name', '$email', '$service', '$message')";

    if ($conn->query($sql)) {
        echo "<script>alert('Message Sent Successfully!'); window.location.href='index.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
