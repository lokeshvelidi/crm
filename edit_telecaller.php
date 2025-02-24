<?php
session_start();

// Include database connection
include('db_connection.php');

// Check if the user is logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Handle when a telecaller is selected
$telecaller = null;
if (isset($_POST['telecaller_id']) && !empty($_POST['telecaller_id'])) {
    $telecaller_id = $_POST['telecaller_id'];

    // Fetch selected telecaller details
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ? AND role = 'telecaller'");
    $stmt->bind_param("i", $telecaller_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $telecaller = $result->fetch_assoc();
    } else {
        echo "Telecaller not found.";
        exit();
    }
    $stmt->close();
}

// Fetch all telecallers to display in the dropdown
$stmt = $conn->prepare("SELECT * FROM users WHERE role = 'telecaller'");
$stmt->execute();
$telecallers_result = $stmt->get_result();
$stmt->close();

// Handle update operation
if (isset($_POST['update_telecaller'])) {
    $telecaller_id = $_POST['telecaller_id'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // If password is not empty, update it
    if (!empty($password)) {
        $stmt = $conn->prepare("UPDATE users SET username = ?, password = ? WHERE id = ? AND role = 'telecaller'");
        $stmt->bind_param("ssi", $username, $password, $telecaller_id);
    } else {
        // If password is empty, only update the username
        $stmt = $conn->prepare("UPDATE users SET username = ? WHERE id = ? AND role = 'telecaller'");
        $stmt->bind_param("si", $username, $telecaller_id);
    }

    if ($stmt->execute()) {
        // Success message
        $success_message = "Telecaller details updated successfully!";
    } else {
        $error_message = "Error updating telecaller details.";
    }

    $stmt->close();
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
    /* General reset and styling */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Lucida Sans', 'Lucida Grande', sans-serif;
    background-color: #f4f7fb;
    color: #333;
    padding: 20px;
}

.container {
    width: 80%;
    max-width: 800px;
    margin: 0 auto;
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

h1 {
    font-size: 28px;
    margin-bottom: 20px;
    text-align: center;
}

h2 {
    font-size: 22px;
    margin-bottom: 15px;
}

form {
    display: flex;
    flex-direction: column;
}

label {
    margin-bottom: 8px;
    font-weight: bold;
    color: #555;
}

select, input[type="text"], input[type="password"] {
    padding: 12px;
    margin-bottom: 20px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
}

select:focus, input[type="text"]:focus, input[type="password"]:focus {
    outline: none;
    border-color: #007BFF;
}

button {
    padding: 12px;
    background-color: #007BFF;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #0056b3;
}

button:disabled {
    background-color: #cccccc;
    cursor: not-allowed;
}

/* Success and Error message styling */
.success-message {
    background-color: #d4edda;
    color: #155724;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
    border: 1px solid #c3e6cb;
}

.error-message {
    background-color: #f8d7da;
    color: #721c24;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
    border: 1px solid #f5c6cb;
}

/* Telecaller selection form */
select {
    background-color: #fff;
}

/* Media queries for responsive design */
@media (max-width: 768px) {
    .container {
        width: 100%;
        padding: 15px;
    }

    h1 {
        font-size: 24px;
    }

    h2 {
        font-size: 20px;
    }

    label {
        font-size: 14px;
    }

    select, input[type="text"], input[type="password"], button {
        font-size: 14px;
    }
}
/* Back Button Styles */
.back-button {
    display: inline-block;
    margin-bottom: 20px;
}

.back-btn {
    padding: 12px;
    background-color: #6c757d;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    text-align: center;
    width: 200px;
}

.back-btn:hover {
    background-color: #5a6268;
}

.back-btn:focus {
    outline: none;
}

</style>
</head>
<body>
    <div class="container">
        <h1>Edit Telecaller Details</h1>

        <!-- Telecaller Selection Form -->
        <form method="POST" action="">
            <label for="telecaller_id">Choose Telecaller:</label>
            <select id="telecaller_id" name="telecaller_id" required>
                <option value="">Select a telecaller</option>
                <?php while ($row = $telecallers_result->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>" <?php echo (isset($telecaller['id']) && $telecaller['id'] == $row['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($row['username']); ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <button type="submit">Choose Telecaller</button>
        </form>

        <?php if ($telecaller): ?>
            <!-- Display success or error message -->
            <?php if (isset($success_message)): ?>
                <div class="success-message"><?php echo $success_message; ?></div>
            <?php elseif (isset($error_message)): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <!-- Edit Telecaller Form -->
            <h2>Edit Details for <?php echo htmlspecialchars($telecaller['username']); ?></h2>

            <form method="POST" action="">
                <input type="hidden" name="telecaller_id" value="<?php echo $telecaller['id']; ?>">
                
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($telecaller['username']); ?>" required>
                
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Leave blank to keep current password">
                
                <button type="submit" name="update_telecaller">Update Telecaller</button>
				<a href="admin_dashboard.php" class="back-button">
    <button type="button" class="back-btn">Back to Admin Dashboard</button>
</a>

            </form>
        <?php endif; ?>
    </div>
</body>
</html>
