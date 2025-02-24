<?php
// Include database connection file
include('db_connection.php');

// Check if the ID is passed in the URL
if(isset($_GET['id'])){
    $id = $_GET['id'];

    // Delete the lead from the database
    $sql = "DELETE FROM leads WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if($stmt->execute()){
        echo "Lead deleted successfully!";
        header('Location: view_leads.php');
    } else {
        echo "Error deleting lead!";
    }
} else {
    echo "No lead selected!";
    exit();
}

// Close the database connection
$conn->close();
?>
