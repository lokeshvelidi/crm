<?php
session_start();

// Include database connection
include('db_connection.php');

// Check if the user is logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Handle form submission to update telecaller details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $telecaller_id = $_POST['telecaller_id'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // If a new password is provided, hash it
    if (!empty($password)) {
        $password = password_hash($password, PASSWORD_BCRYPT);
    } else {
        // If no new password is provided, retain the existing password
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $telecaller_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $password = $user['password']; // retain the existing password
        $stmt->close();
    }

    // Update the telecaller details in the database
    $stmt = $conn->prepare("UPDATE users SET username = ?, password = ? WHERE id = ? AND role = 'telecaller'");
    $stmt->bind_param("ssi", $username, $password, $telecaller_id);

    if ($stmt->execute()) {
        echo "Telecaller details updated successfully!";
    } else {
        echo "Error updating telecaller details: " . $stmt->error;
    }

    $stmt->close();
}
?>
