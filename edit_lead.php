<?php
// Include database connection file
include('db_connection.php');

// Check if the ID is passed in the URL
if(isset($_GET['id'])){
    $id = $_GET['id'];

    // Fetch the lead details
    $sql = "SELECT * FROM leads WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        $name = $row['name'];
        $phone = $row['phone'];
        $email = $row['email'];
    } else {
        $message = "Lead not found!";
    }
} else {
    $message = "No lead selected!";
}

// Update the lead if the form is submitted
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    // Update the lead in the database
    $sql = "UPDATE leads SET name = ?, phone = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $name, $phone, $email, $id);
    
    if($stmt->execute()){
        $message = "✅ Lead updated successfully!";
    } else {
        $message = "❌ Error updating lead!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vinnu CRM</title>
	<link rel="icon" type="image/png" href="https://t3.ftcdn.net/jpg/01/69/63/44/360_F_169634442_WOTYC516BNftu8SnBbvVBEV3UvRBpYHB.jpg"> <!-- If you saved as PNG -->
    <style>
        body {
            font-family: 'Lucida Sans', 'Lucida Grande', sans-serif;
            background: url('https://static.vecteezy.com/system/resources/thumbnails/002/106/276/small/geometric-black-and-gold-background-free-vector.jpg') no-repeat center center/cover;
            background-size: cover;
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: rgba(0, 0, 0, 0.8);
            padding: 20px;
            border-radius: 10px;
            color: white;
            width: 350px;
            text-align: center;
            box-shadow: 0px 0px 10px rgba(255, 215, 0, 0.8);
        }
        .message {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
            background: rgba(255, 255, 255, 0.2);
            display: <?php echo isset($message) ? 'block' : 'none'; ?>;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            background-color: gold;
            color: black;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: orange;
        }
        .back-btn {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: white;
            background: red;
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 16px;
        }
        .back-btn:hover {
            background: darkred;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Message Box -->
    <?php if(isset($message)) { echo "<div class='message'>$message</div>"; } ?>

    <h2>Edit Lead</h2>

    <form action="edit_lead.php?id=<?php echo $id; ?>" method="POST">
        <label for="name">Lead Name:</label>
        <input type="text" name="name" id="name" value="<?php echo $name; ?>" required>
        
        <label for="phone">Phone:</label>
        <input type="text" name="phone" id="phone" value="<?php echo $phone; ?>" required>
        
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?php echo $email; ?>" required>
        
        <button type="submit">Update Lead</button>
    </form>

    <!-- Back Button -->
    <a href="view_leads.php" class="back-btn">← Back</a>
</div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
