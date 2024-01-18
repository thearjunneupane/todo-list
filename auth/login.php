<?php
session_start();
if (isset($_SESSION['userid'])) {
    // Redirect to login page if not logged in
    header("Location: /");
    exit();
}

include("../conn/conn.php");

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $useremail = filter_input(INPUT_POST, 'useremail', FILTER_VALIDATE_EMAIL);
    $userpassword = $_POST['userpassword'];

    // Validate email
    if (!$useremail) {
        $errors['useremail'] = "Invalid email format";
    }

    // If no validation errors, proceed with login attempt
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT * FROM $dbname.users WHERE useremail = ? LIMIT 1;");
        $stmt->bind_param("s", $useremail);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if ($userpassword == $row['userpassword']) {
                // Set session or token for logged-in user
                $_SESSION['userid'] = $row['userid'];
                $_SESSION['username'] = $row['username'];
                header("Location: ../index.php");
                exit();
            } else {
                $errors['userpassword'] = "Invalid Password!";
            }
        } else {
            $errors['useremail'] = "User not found!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Login</title>
</head>

<body class="light">
    <header id="header">
        <h1 id="title">Login</h1>
        <div class="flexrow-container">
            <div class="theme-selector light-theme"></div>
            <div class="theme-selector darker-theme"></div>
        </div>
    </header>

    <div id="container">
        <div id="myUnOrdList" style="justify-content: flex-start; flex-grow: 0; margin: auto; margin-top: 0; align-items: normal;">
            <div id="form">
                <form style="align-items: baseline; margin-top: 2%;" id="loginForm" name="loginForm" action="login.php" method="POST" onsubmit="return validateLoginForm()">
                    <label for="useremail">Email:</label>
                    <input placeholder="Enter your email" type="email" id="useremail" name="useremail" value="<?= isset($_POST['useremail']) ? htmlspecialchars($_POST['useremail']) : '' ?>">
                    <span class="text-error"><?= isset($errors['useremail']) ? $errors['useremail'] : '' ?></span><br>
                    <label for="userpassword">Password:</label>
                    <input placeholder="Enter your password" type="password" id="userpassword" name="userpassword">
                    <span class="text-error"><?= isset($errors['userpassword']) ? $errors['userpassword'] : '' ?></span><br>
                    <button type="submit" style="align-self: center; width: 100%; margin-top: 20px;" class="light-button">Login</button>
                    <div style="margin-top: 30px; align-self: center;">
                        <a href="/auth/register.php">Register new account?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../js/index.js"></script>
</body>

</html>