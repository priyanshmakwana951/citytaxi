<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "taxi";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user_data = $result->fetch_assoc();

        if ($user_data['password'] == $password) {
            $_SESSION['user_id'] = $user_data['id'];
            header("location: index.html");
            die;
        } else {
            echo "<script>alert('Wrong password or Email');</script>";
        }
    } else {
        echo "<script>alert('Wrong email or user does not exist');</script>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Taxi Zone Login Page</title>
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            height: 100vh;
            width: 100vw;
            overflow: hidden;
            font-family: "Lato", sans-serif;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #555;
            background: #ecf0f3;
            background-size: cover;
            background-repeat: no-repeat;
        }

        .gfg-div {
            width: 430px;
            height: 500px;
            padding: 20px 35px 15px 35px;
            border-radius: 35px;
            background: #ecf0f3;
            box-shadow: -6px -6px 6px rgba(255, 255, 255, 0.8),
                6px 6px 6px rgba(0, 0, 0, 0.2);
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

        .gfg-title {
            text-align: center;
            font-size: 28px;
            padding-top: 24px;
            letter-spacing: 0.5px;
            color: #5ed887;
        }

        .gfg-sub-title {
            text-align: center;
            font-size: 15px;
            padding-top: 7px;
            letter-spacing: 3px;
            color: #5eaa77;
        }

        .gfg-input-fields {
            width: 100%;
            padding: 20px 5px 10px 5px;
        }

        .gfg-input-fields input {
            border: none;
            outline: none;
            background: none;
            font-size: 18px;
            color: #555;
            padding: 15px 10px 15px 5px;
            width: 100%;
            box-sizing: border-box;
        }

        .gfg-email,
        .gfg-password {
            margin-bottom: 20px;
            border-radius: 20px;
            box-shadow: inset 5px 5px 5px #cbced1,
                inset -5px -5px 5px #ffffff;
        }

        .gfg-input-fields svg {
            height: 22px;
            margin: 0 10px -3px 25px;
        }

        .gfg-button {
            outline: none;
            border: none;
            cursor: pointer;
            width: 100%;
            height: 60px;
            border-radius: 55px;
            font-size: 25px;
            font-weight: 700;
            font-family: "Lato", sans-serif;
            color: #fff;
            text-align: center;
            background: #67d18a;
            box-shadow: 7px 7px 9px #cbced1, -9px -9px 10px #ffffff;
            transition: 1s;
        }

        .gfg-button:hover {
            background: #5fb87d;
            transition: 1.5s;
        }

        .gfg-button:active {
            background: #4a9764;
        }

        .gfg-link {
            padding-top: 20px;
            text-align: center;
        }

        .gfg-link a {
            text-decoration: none;
            color: #aaa;
            font-size: 15px;
            transition: 0.5s;
        }

        .gfg-link a:hover {
            text-decoration: none;
            color: #67d18a;
            font-size: 15px;
        }
    </style>
</head>

<body>
    <div class="gfg-div">
        <div class="gfg-logo"></div>
        <div class="gfg-title">Taxi Zone</div>
        <div class="gfg-sub-title">Login Page Of Taxi Zone</div>
        <form method="post">
            <div class="gfg-input-fields">
                <div class="gfg-email">
                    <input type="email" name="email" placeholder="email" required />
                </div>
                <div class="gfg-password">
                    <input type="password" name="password" placeholder="password" required />
                </div>
            </div>
            <button class="gfg-button" type="submit">Login</button>
        </form>
        <div class="gfg-link">
            <a href="#"> Forgot password?</a> OR <a href="#">Signup</a>
        </div>
    </div>
</body>
</html>