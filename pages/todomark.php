<?php
include("../conn/session.php");
include("../conn/conn.php");

if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['id'])) {
    $todoId = $_GET['id'];

    // Fetch the current status of the todo
    $statusQuery = "SELECT isdone FROM $dbname.todos WHERE todoid = $todoId;";
    $statusResult = mysqli_query($conn, $statusQuery);

    if ($statusResult) {
        $currentStatus = mysqli_fetch_assoc($statusResult)['isdone'];

        // Toggle the status (1 for done, 0 for undone)
        $newStatus = $currentStatus == 0 ? 1 : 0;

        // Update the todo status
        $updateQuery = "UPDATE $dbname.todos SET isdone = $newStatus WHERE todoid = $todoId;";
        $updateResult = mysqli_query($conn, $updateQuery);

        if ($updateResult) {
            // Redirect back to the main page after updating the status
            header("Location: ../index.php");
            exit();
        } else {
            echo "<p>Error updating database: " . mysqli_error($conn) . "</p>";
        }
    } else {
        echo "<p>Error querying database: " . mysqli_error($conn) . "</p>";
    }
} else {
    // Redirect to the main page if the request method or todo ID is not valid
    header("Location: ../index.php");
    exit();
}
?>
