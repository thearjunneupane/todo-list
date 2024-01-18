<?php
include("./conn/session.php");
include("./conn/conn.php");

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $todoTitle = $_POST['todo_title'];
    $todoDescription = $_POST['todo_description'];
    $userId = isset($_SESSION['userid']) ? $_SESSION['userid'] : null;

    // Validation (Add more as needed)
    if (empty($todoTitle)) {
        $errors['todo_title'] = "Todo Title is required";
    }

    if (strlen($todoTitle) > 50) {
        $errors['todo_title'] = "Only 50 words allowed in Todo Title";
    }

    if (empty($todoDescription)) {
        $errors['todo_description'] = "Todo Description is required";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO $dbname.todos(userid, todotitle, tododescription) VALUES (?, ?, ?);");
        $stmt->bind_param("iss", $userId, $todoTitle, $todoDescription);

        if ($stmt->execute()) {
            echo "<script>alert('Added Todo Successfully!'); location.replace('/todo-list')</script>";
            exit();
        } else {
            $errors['database'] = "Error Executing Statement!";
        }

        $stmt->close();
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#062e3f">
    <meta name="Description" content="A dynamic and aesthetic To-Do List WebApp.">

    <!-- Google Font: Quick Sand -->
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300&display=swap" rel="stylesheet">

    <!-- font awesome (https://fontawesome.com) for basic icons; source: https://cdnjs.com/libraries/font-awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="shortcut icon" type="image/png" href="assets/favicon.png" />
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="CSS/corner.css">
    <title>TODO LIST</title>
</head>

<body>
    <div id="header">
        <h1 id="title">Welcome <?php echo $_SESSION['username']; ?>!</h1>
        <div class="flexrow-container">
            <div class="light-theme theme-selector"></div>
            <div class="darker-theme theme-selector"></div>
            <form action="index.php" method="GET">
                <input type="hidden" name="logout">
                <button class="logout-btn" title="Logout" type="submit"><i class="fa-solid fa-person-walking-arrow-right"></i></button>
            </form>
        </div>
    </div>
    <div id="container">
        <div id="form">
            <form action="<?= $_SERVER['PHP_SELF']; ?>" method="POST">
                <label for="todo_title">Todo Title:</label>
                <input class="todo-input" type="text" id="todo_title" name="todo_title" placeholder="Enter Todo Title" value="<?= isset($todoTitle) ? htmlspecialchars($todoTitle) : '' ?>" />
                <span id="todo_title_error" class="text-error"><?= isset($errors['todo_title']) ? $errors['todo_title'] : '' ?></span>

                <label for="todo_description">Todo Description:</label>
                <textarea id="todo_description" name="todo_description" placeholder="Enter Todo Description"><?= isset($todoDescription) ? htmlspecialchars($todoDescription) : '' ?></textarea>
                <span class="text-error"><?= isset($errors['todo_description']) ? $errors['todo_description'] : '' ?></span>

                <button class="todo-btn" type="submit">Add Todo</button>
            </form>
            <div id="live-datetime"></div>
        </div>

        <script src="./js/time.js"></script>

        <div id="myUnOrdList">
            <?php
            $userId = $_SESSION['userid'];
            $sql = "SELECT * FROM $dbname.todos WHERE userid = $userId;";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<ul class='todo-list'>";
                    echo "<div class='todo'>";
                    echo "<button class='check-btn'><a href='pages/tododetails.php?id=" . $row['todoid'] . "' title='See'><i class='fas fa-check'></i></a></button>";
                    echo "<p class='todo-item'>" . $row['todotitle'] . "</p>";
                    echo "<div class='btn-group'>";
                    echo "<button class='details-btn'><a href='pages/tododetails.php?id=" . $row['todoid'] . "' title='Details'><i class='fa-solid fa-eye'></i></a></button>";
                    echo "<button class='update-btn'><a href='pages/todoupdate.php?id=" . $row['todoid'] . "' title='Update'><i class='fa-solid fa-pen-to-square'></i></a></button>";
                    echo "<button class='delete-btn'><a href='pages/tododelete.php?id=" . $row['todoid'] . "' title='Delete'><i class='fas fa-trash'></i></a></button>";
                    echo "</div>";
                    echo "</div>";
                    echo "</ul>";
                }

                if (mysqli_num_rows($result) == 0) {
                    echo "<span class='text-error' style='font-size: 2rem; margin: 0 1rem; text-align: center;'>No Todos Available For Now <br> Add Some!</span>";
                }
            } else {
                echo "<p>Error querying database: " . mysqli_error($conn) . "</p>";
            }
            ?>
        </div>
    </div>


    <script src="./js/index.js" type="text/javascript"> </script>
</body>

</html>