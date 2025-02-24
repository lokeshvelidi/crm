<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vinnu CRM</title>
    <link rel="icon" type="image/png" href="https://t3.ftcdn.net/jpg/01/69/63/44/360_F_169634442_WOTYC516BNftu8SnBbvVBEV3UvRBpYHB.jpg"> <!-- If you saved as PNG -->
    <style>
        /* Full-Screen Centering */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #007bff, #00c853);
            font-family: Arial, sans-serif;
            text-align: center;
        }

        /* Main Wrapper */
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        /* Heading Text */
        h1 {
            font-size: 28px;
            color: white;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
            margin-bottom: 10px;
        }

        p {
            font-size: 18px;
            color: white;
            opacity: 0.8;
            margin-bottom: 30px;
        }

        /* Call Button Styling */
        .call-icon {
            width: 120px;
            height: 120px;
            background-color: #28a745;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.4);
            transition: transform 0.2s ease-in-out;
            animation: pulseEffect 1.5s infinite;
        }

        /* Call Icon Image */
        .call-icon img {
            width: 60px;
            height: 60px;
            transition: transform 0.2s ease;
        }

        /* Hover & Click Effects */
        .call-icon:hover {
            transform: scale(1.1);
        }
        
        .call-icon:active {
            transform: scale(0.9);
        }

        /* Glowing & Expanding Animation */
        @keyframes pulseEffect {
            0% {
                box-shadow: 0 0 10px rgba(40, 167, 69, 0.6);
                transform: scale(1);
            }
            50% {
                box-shadow: 0 0 30px rgba(40, 167, 69, 1);
                transform: scale(1.1);
            }
            100% {
                box-shadow: 0 0 10px rgba(40, 167, 69, 0.6);
                transform: scale(1);
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Vinnu`s CRM</h1>
        <p>Get connected instantly with just one tap!</p>
        
        <!-- Call Icon -->
        <div class="call-icon" onclick="redirectToLogin()">
            <img src="https://cdn-icons-png.flaticon.com/512/724/724664.png" alt="Call Icon">
        </div>
    </div>

    <script>
        function redirectToLogin() {
            window.location.href = "login.php";
        }
    </script>

</body>
</html>
