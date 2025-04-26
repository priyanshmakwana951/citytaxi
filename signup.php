<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "taxi";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$signupSuccess = false;

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST["fullname"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"]; // Raw password (no hash)

    // Check if email already exists
    $check_stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        echo "<script>alert('This email is already registered. Please use another.');</script>";
    } else {
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $fullname, $email, $password);

        if ($stmt->execute()) {
            // Set session
            $_SESSION["user_email"] = $email;
            $_SESSION["user_name"] = $fullname;

            // Set flag for JS alert and redirect
            $signupSuccess = true;
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    }

    $check_stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* CSS from before (unchanged) */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .gfg-logo {
            background: url("logo.jpeg");
            background-size: cover;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 0 auto;
            box-shadow: 0px 0px 2px #5f5f5f, 0px 0px 0px 5px #ecf0f3,
                8px 8px 15px #a7aaaf, -8px -8px 15px #ffffff;
        }

        .signup-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 35px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .signup-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .signup-header h1 {
            font-size: 24px;
            color: #333;
        }

        .signup-header p {
            color: #666;
            font-size: 14px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .cta-btn {
            width: 100%;
            padding: 12px;
            background-color: #007BFF;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .cta-btn:hover {
            background-color: #0056b3;
        }

        .social-login {
            text-align: center;
            margin-top: 20px;
        }

        .social-login button {
            background-color: #ddd;
            border: none;
            padding: 10px 20px;
            margin: 5px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .social-login button:hover {
            background-color: #ccc;
        }

        form input {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <div class="gfg-logo"></div>
        <div class="signup-header">
            <h1>City Taxi</h1>
            <p>Sign-up Page</p>
        </div>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="text" name="fullname" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="cta-btn">Create Account</button>
        </form>
        <div class="social-login">
            <button>Sign up with Google</button>
            <button>Sign up with Facebook</button>
        </div>
    </div>

    <?php if ($signupSuccess): ?>
        <script>
            alert("Signup complete! ");
            window.location.href = "index.html";
        </script>
    <?php endif; ?>
</body>
</html>
