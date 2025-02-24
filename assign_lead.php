<?php
// Include database connection file
include('db_connection.php');

// Fetch all telecallers
$telecallers_sql = "SELECT * FROM users WHERE role = 'telecaller'";
$telecallers_result = $conn->query($telecallers_sql);

// Check if the ID is passed in the URL
if(isset($_GET['id'])){
    $id = $_GET['id'];

    // Fetch the lead details
    $lead_sql = "SELECT * FROM leads WHERE id = ?";
    $stmt = $conn->prepare($lead_sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $lead_result = $stmt->get_result();

    if($lead_result->num_rows > 0){
        $lead = $lead_result->fetch_assoc();
        $lead_name = $lead['name'];
    } else {
        echo "Lead not found!";
        exit();
    }

    // Update the lead assignment if the form is submitted
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $telecaller_id = $_POST['telecaller_id'];

        // Update the lead assignment
        $update_sql = "UPDATE leads SET telecaller_id = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ii", $telecaller_id, $id);
        if($stmt->execute()){
            echo "Lead assigned to telecaller successfully!";
            header('Location: view_leads.php');
        } else {
            echo "Error assigning lead!";
        }
    }
} else {
    echo "No lead selected!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vinnu CRM</title>
	<link rel="icon" type="image/png" href="https://t3.ftcdn.net/jpg/01/69/63/44/360_F_169634442_WOTYC516BNftu8SnBbvVBEV3UvRBpYHB.jpg"> <!-- If you saved as PNG -->
	
</head>
<body>

<h2>Assign Lead: <?php echo $lead_name; ?></h2>

<form action="assign_lead.php?id=<?php echo $id; ?>" method="POST">
    <label for="telecaller_id">Select Telecaller:</label><br>
    <select name="telecaller_id" id="telecaller_id" required>
        <option value="">-- Select Telecaller --</option>
        <?php
        if ($telecallers_result->num_rows > 0) {
            while ($row = $telecallers_result->fetch_assoc()) {
                echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
            }
        } else {
            echo "<option>No telecallers found</option>";
        }
        ?>
    </select><br><br>

    <button type="submit">Assign Lead</button>
</form>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
