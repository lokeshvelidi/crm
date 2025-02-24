<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$host = "localhost";
$dbname = "call_management";
$username = "root";
$password = "lokesh";

// Connect to the database
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch telecallers
$telecallers = $conn->query("SELECT id, username FROM users WHERE role = 'telecaller'");

// Fetch leads
$leads = $conn->query("SELECT * FROM leads ORDER BY id DESC");

// Assign leads
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['assign_lead'])) {
    $lead_id = $_POST['lead_id'];
    $telecaller_id = $_POST['telecaller_id'];
    $stmt = $conn->prepare("UPDATE leads SET assigned_to = ? WHERE id = ?");
    $stmt->bind_param("ii", $telecaller_id, $lead_id);
    if ($stmt->execute()) {
        $success = "Lead assigned successfully!";
    } else {
        $error = "Error assigning lead!";
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
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Lucida Sans', 'Lucida Grande', sans-serif;
        }
         body {
            background-image: url('https://t4.ftcdn.net/jpg/05/08/77/97/360_F_508779720_mevGw0UiCDurA6A195ayIk5sxaGFwuEu.jpg');
            background-size: cover;
            background-position: center;
            color: white; /* Set text color to white for contrast */
            font-family: Arial, sans-serif;
            display: flex;
            height: 100vh;
            padding: 0 20px;
        }
        .menu-bar {
            background: linear-gradient(180deg, #4e73df, #2e59d9);
            width: 250px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            padding: 20px;
            gap: 20px;
            position: fixed;
            height: 100%;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .menu-bar a {
            color: white;
            padding: 12px 18px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            background: #007bff;
            width: 100%;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: 0.3s;
        }
        .menu-bar a:hover {
            background: #0056b3;
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        .menu-bar a.active {
            background: #0056b3;
        }
        .menu-bar .material-icons {
            font-size: 20px;
        }
        .main-content {
            margin-left: 270px;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
        }
        .container {
            background: white;
            width: 70%;
            max-width: 800px;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.8s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        h1, h2 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
        }
        label {
            font-weight: 600;
        }
        select, button {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background: #28a745;
            color: white;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background: #218838;
            transform: scale(1.05);
        }
        .header {
            background: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: 600;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="menu-bar">
        <a href="add_lead.php" class="active">
            <span class="material-icons">add_circle</span> Add New Lead
        </a>
        <a href="view_leads.php">
            <span class="material-icons">visibility</span> View Leads
        </a>
		<a href="add_telecaller.php">
			<span class="material-icons">person</span> Add Telecaller
		</a>
<a href="edit_telecaller.php">
    <span class="material-icons">edit</span> Edit Telecaller
</a>
<a href="lead_updates.php">
<span class="material-icons">update</span> Leads Data
</a>
        <a href="logout.php">
            <span class="material-icons">exit_to_app</span> Logout
        </a>
		
    </div>

    <div class="main-content">
        <div class="container">
            <div class="header">
                Welcome, Admin!
            </div>
<br>
            <h2>Assign Leads</h2>
            <?php if (!empty($success)): ?>
                <p style="color: green;"><?= $success; ?></p>
            <?php elseif (!empty($error)): ?>
                <p style="color: red;"><?= $error; ?></p>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="lead">Select Lead:</label>
                    <select name="lead_id" id="lead" required>
                        <?php while ($lead = $leads->fetch_assoc()): ?>
                            <option value="<?= $lead['id']; ?>">
                                <?= $lead['name']; ?> (<?= $lead['phone']; ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="telecaller">Select Telecaller:</label>
                    <select name="telecaller_id" id="telecaller" required>
                        <?php while ($telecaller = $telecallers->fetch_assoc()): ?>
                            <option value="<?= $telecaller['id']; ?>"><?= $telecaller['username']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="submit" name="assign_lead">Assign Lead</button>
            </form>
        </div>
    </div>
</body>
</html>
