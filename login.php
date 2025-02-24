<?php
session_start();
$host = "localhost";
$dbname = "call_management";
$username = "root";
$password = "lokesh";

// Connect to the database
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check login credentials
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, role FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $user, $pass);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $role);
        $stmt->fetch();

        $_SESSION['user_id'] = $id;
        $_SESSION['role'] = $role;

        if ($role == 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: telecaller_dashboard.php");
        }
        exit();
    } else {
        $error = "Invalid username or password!";
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
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Lucida Sans', 'Lucida Grande', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #667eea, #764ba2);
            overflow: hidden;
        }

        .login-container {
            width: 350px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            text-align: center;
            animation: fadeIn 1s ease-in-out;
        }

        .login-container h2 {
            margin-bottom: 20px;
            font-weight: 600;
            color: white;
        }

        .input-container {
            position: relative;
            margin-bottom: 20px;
        }

        .input-container input {
            width: 100%;
            padding: 12px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            outline: none;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            transition: 0.3s;
        }

        .input-container label {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
            transition: 0.3s;
            pointer-events: none;
        }

        .input-container input:focus,
        .input-container input:valid {
            background: rgba(255, 255, 255, 0.3);
        }

        .input-container input:focus + label,
        .input-container input:valid + label {
            top: 5px;
            font-size: 12px;
            color: white;
        }

        .btn {
            width: 100%;
            padding: 12px;
            background: #ff7eb3;
            color: white;
            border: none;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn:hover {
            background: #ff4d94;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(255, 126, 179, 0.4);
        }

        .signup {
            margin-top: 15px;
            color: white;
        }

        .signup a {
            color: #ff7eb3;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }

        .signup a:hover {
            color: #ff4d94;
            text-decoration: underline;
        }

        .error {
            color: #ff4d94;
            margin-top: 10px;
            font-size: 14px;
            font-weight: bold;
        }

        /* Background animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form method="POST" action="">
            <div class="input-container">
                <input type="text" name="username" required>
                <label>Username</label>
            </div>
            <div class="input-container">
                <input type="password" name="password" required>
                <label>Password</label>
            </div>
            <button class="btn" type="submit">Login</button>
        </form>
        <?php if (!empty($error)): ?>
            <div class="error"><?= $error; ?></div>
        <?php endif; ?>
        
    </div>
</body>
</html>
