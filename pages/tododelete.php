<?php
include("../conn/session.php");
include("../conn/conn.php");

$error = "";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Assuming you have a user ID stored in the session
    $userId = isset($_SESSION['userid']) ? $_SESSION['userid'] : null;

    $stmt = $conn->prepare("DELETE FROM $dbname.todos WHERE todoid = ? AND userid = ?");

    if ($stmt) {
        $stmt->bind_param("ii", $id, $userId);
        $stmt->execute();

        if (!($stmt->affected_rows > 0)) {
            $error = "Todo Not Found";
        }

        $stmt->close();
    } else {
        $error = "Error Preparing stmt!";
    }

    $conn->close();
} else {
    $error = "Nothing to show up here!";
}

if (!empty($error)) {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/styles.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <title>Todo Details</title>
    </head>

    <body>
        <h1><?= $error ?></h1>
        <a href="/">Home</a>
    </body>

    </html>
<?php
} else {
    header('Location: /');
}
?>