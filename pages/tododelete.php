<?php
include("../conn/session.php");
include_once("../conn/conn.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Assuming you have a user ID stored in the session
    $userId = isset($_SESSION['userid']) ? $_SESSION['userid'] : null;

    $stmt = $conn->prepare("DELETE FROM $dbname.todos WHERE todoid = ? AND userid = ?");

    if ($stmt) {
        $stmt->bind_param("ii", $id, $userId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Success Deletion";
        } else {
            echo "Todo Not Found";
        }

        $stmt->close();
    } else {
        echo "Error Preparing stmt!";
    }

    $conn->close();
    header('Location: /todo-list/');
} else {
    echo "Nothing to show up here!";
}
