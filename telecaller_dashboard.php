<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'telecaller') {
    header("Location: login.php");
    exit();
}

// Include the database connection
include('db_connection.php');
// Set your default time zone
date_default_timezone_set('Asia/Kolkata');  // Change this to your local time zone

// Convert UTC to local time (this part is kept, but we won't display the time now)
$utc_time = '2025-02-18 13:40:00'; // This would be the UTC timestamp from the database
$date = new DateTime($utc_time, new DateTimeZone('UTC'));
$date->setTimezone(new DateTimeZone('Asia/Kolkata'));  // Change this to your local time zone

// The line below is removed as you no longer want to display the time
// echo $date->format('Y-m-d H:i:s');  // Output will be in your local time zone (e.g., IST)


// Fetch assigned leads
$telecaller_id = $_SESSION['user_id'];
$leads = $conn->query("SELECT * FROM leads WHERE assigned_to = $telecaller_id");

// Update lead outcome
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_lead'])) {
    $lead_id = $_POST['lead_id'];
    $status = $_POST['status'];
    $notes = $_POST['notes'];
    $update_time = date('Y-m-d H:i:s'); // Get the current timestamp

    // Update the lead in the database
    $stmt = $conn->prepare("UPDATE leads SET status = ?, notes = ?, updated_at = ? WHERE id = ?");
    $stmt->bind_param("sssi", $status, $notes, $update_time, $lead_id);

    // Check if the update was successful
    if ($stmt->execute()) {
        $success = "Lead updated successfully!";
    } else {
        $error = "Error updating lead: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vinnu CRM</title>
	<link rel="icon" type="image/png" href="https://t3.ftcdn.net/jpg/01/69/63/44/360_F_169634442_WOTYC516BNftu8SnBbvVBEV3UvRBpYHB.jpg"> <!-- If you saved as PNG -->
    <style>
/* Global Styles */
body {
    font-family: 'Lucida Sans', 'Lucida Grande', sans-serif;
    background-color: #f4f7fa;
    color: #333;
    margin: 0;
    padding: 0;
}

/* Container for the page content */
.container {
    width: 80%;
    margin: 30px auto;
    background-color: #ffffff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

/* Header */
h1 {
    text-align: center;
    color: #007bff;
    font-size: 2.5rem;
    margin-bottom: 20px;
}

/* Success and Error messages */
p {
    font-size: 1rem;
    padding: 10px;
    text-align: center;
}

p[style*="color: green"] {
    background-color: #e9f7e9;
    color: #28a745;
    border: 1px solid #28a745;
}

p[style*="color: red"] {
    background-color: #f8d7da;
    color: #dc3545;
    border: 1px solid #dc3545;
}

/* Table Styles */
table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    font-size: 1rem;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

table th,
table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

table th {
    background-color: #007bff;
    color: #fff;
}

table td a {
    color: #007bff;
    text-decoration: none;
}

table td a:hover {
    text-decoration: underline;
}

/* Form Styles */
textarea {
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ddd;
    font-size: 1rem;
}

select {
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ddd;
    font-size: 1rem;
}

/* Buttons */
.btn {
    padding: 12px 20px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn:hover {
    background-color: #0056b3;
}

.btn:focus {
    outline: none;
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        width: 90%;
        padding: 20px;
    }

    h1 {
        font-size: 2rem;
    }

    table th,
    table td {
        padding: 10px;
    }

    .btn {
        width: 100%;
        padding: 15px;
    }
}

    </style>
</head>
<body>

    <div class="container">
        <h1>Telecaller Dashboard</h1>
        <?php if (!empty($success)): ?>
            <p style="color: green;"><?= $success; ?></p>
        <?php elseif (!empty($error)): ?>
            <p style="color: red;"><?= $error; ?></p>
        <?php endif; ?>

        <h2>Assigned Leads</h2>
        <table>
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Notes</th>
                <th>Update</th>
            </tr>
            <?php while ($lead = $leads->fetch_assoc()): ?>
            <tr>
                <form method="POST" action="">
                    <td><?= $lead['name']; ?></td>
                    <td><a href="tel:<?= $lead['phone']; ?>"><?= $lead['phone']; ?></a></td> <!-- Clickable Phone Link -->
                    <td>
                        <select name="status" required>
                            <option value="new" <?= $lead['status'] == 'new' ? 'selected' : ''; ?>>New</option>
                            <option value="contacted" <?= $lead['status'] == 'contacted' ? 'selected' : ''; ?>>Contacted</option>
                            <option value="converted" <?= $lead['status'] == 'converted' ? 'selected' : ''; ?>>Converted</option>
                            <option value="not interested" <?= $lead['status'] == 'not interested' ? 'selected' : ''; ?>>Not Interested</option>
                        </select>
                    </td>
                    <td><textarea name="notes" rows="2"><?= $lead['notes']; ?></textarea></td>
                    <td>
                        <input type="hidden" name="lead_id" value="<?= $lead['id']; ?>">
                        <button type="submit" name="update_lead" class="btn">Update</button>
                    </td>
                </form>
            </tr>
            <?php endwhile; ?>
        </table>
        <a href="logout.php" class="btn">Logout</a>
    </div>
</body>
</html>
