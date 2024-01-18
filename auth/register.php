<?php
session_start();
if (isset($_SESSION['userid'])) {
    // Redirect to login page if not logged in
    header("Location: /todo-list/");
    exit();
}
include("../conn/conn.php");

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Validate and sanitize input
    $useremail = filter_input(INPUT_POST, 'useremail', FILTER_VALIDATE_EMAIL);
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $userpassword = filter_input(INPUT_POST, 'userpassword', FILTER_SANITIZE_STRING);

    // Validate email
    if (!$useremail) {
        $errors['useremail'] = "Invalid email format";
    }

    // Validate username
    if (empty($username)) {
        $errors['username'] = "Username is required";
    }

    // Validate password (add more rules as needed)
    if (strlen($_POST['userpassword']) < 8) {
        $errors['userpassword'] = "Password must be at least 8 characters long";
    }

    // If no validation errors, proceed with database insertion
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO $dbname.users(useremail, username, userpassword) VALUES (?, ?, ?);");
        $stmt->bind_param("sss", $useremail, $username, $userpassword);

        if ($stmt->execute()) {
            echo "<script>alert('Registration Successful!'); location.replace('login.php')</script>";
            exit();
        } else {
            $errors['database'] = "Error Registering User!";
        }
    }
}

?>

<!-- HTML for registration form -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Register</title>
</head>

<body class="light">
    <header id="header">
        <h1 id="title">Register</h1>
        <div class="flexrow-container">
            <div class="theme-selector light-theme"></div>
            <div class="theme-selector darker-theme"></div>
        </div>
    </header>

    <div id="container">
        <div id="myUnOrdList" style="justify-content: flex-start; flex-grow: 0; margin: auto; margin-top: 0; align-items: normal;">
            <div id="form">
                <form style="align-items: baseline; margin-top: 2%;" id="registrationForm" name="registrationForm" action="register.php" method="POST" onsubmit="return validateRegistrationForm()">
                    <label for="useremail">Email:</label>
                    <input type="email" id="useremail" name="useremail" placeholder="Enter your email" value="<?= isset($_POST['useremail']) ? htmlspecialchars($_POST['useremail']) : '' ?>">
                    <span class="text-error"><?= isset($errors['useremail']) ? $errors['useremail'] : '' ?></span>

                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" placeholder="Choose a username" value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
                    <span class="text-error"><?= isset($errors['username']) ? $errors['username'] : '' ?></span>

                    <label for="userpassword">Password:</label>
                    <input type="password" id="userpassword" name="userpassword" placeholder="Enter your password" value="">
                    <span class="text-error"><?= isset($errors['userpassword']) ? $errors['userpassword'] : '' ?></span>

                    <button type="submit" style="align-self: center; width: 100%; margin-top: 20px;" class="light-button">Register</button>
                    <div style="margin-top: 30px; align-self: center;">
                        <a href="/todo-list/auth/login.php">Already have an account?</a>
                    </div>
                </form>

                <?php if (isset($errors['database'])) : ?>
                    <div class="text-error"><?= $errors['database'] ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="../js/index.js"></script>
</body>

</html>