<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Update Todo</title>
</head>

<body class="light">
    <div style="display: none; position: absolute; top: 10px; right: 10px; background-color: greenyellow; padding:
    20px; border-radius: 20px;" id="toast">
        Updated Success!
    </div>
    <header id="header">
        <h1 id="title">Update Todo</h1>
        <div class="flexrow-container">
            <div class="theme-selector light-theme"></div>
            <div class="theme-selector darker-theme"></div>
        </div>
    </header>

    <div id="container">
        <div id="myUnOrdList" style="justify-content: flex-start;">
            <div class="todo-details">

                <?php
                include("../conn/session.php");
                include("../conn/conn.php");
                $errors = [];

                if (isset($_GET['id'])) {
                    $id = $_GET['id'];

                    // Assuming you have a user ID stored in the session
                    $userId = isset($_SESSION['userid']) ? $_SESSION['userid'] : null;

                    $stmt = $conn->prepare("SELECT * FROM $dbname.todos WHERE todoid = ? AND userid = ?");

                    if ($stmt) {
                        $stmt->bind_param("ii", $id, $userId);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();

                            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                                $title = $_POST['todotitle'];
                                $description = $_POST['tododescription'];

                                // Validation (Add more as needed)
                                if (empty($title)) {
                                    $errors['todotitle'] = "Todo Title is required";
                                }

                                if (empty($description)) {
                                    $errors['tododescription'] = "Todo Description is required";
                                }

                                if ($title == $row['todotitle'] && $description == $row['tododescription']) {
                                    $errors['todounchange'] = "Both is unchanged.";
                                }

                                if (empty($errors)) {
                                    $updateStmt = $conn->prepare("UPDATE $dbname.todos SET todotitle=?, tododescription=? WHERE todoid=?");
                                    $updateStmt->bind_param("ssi", $title, $description, $id);

                                    if ($updateStmt->execute()) {
                                        echo "<script>alert('Todo Updated Successfully!'); location.replace(location.href);</script>";
                                        exit();
                                    } else {
                                        echo "<script>alert('Error Updating Todo!')</script>";
                                    }

                                    $updateStmt->close();
                                }
                            }
                ?>

                            <form id="updateForm" action="todoupdate.php?id=<?php echo $id; ?>" method="POST">
                                <div class="title" style="margin-bottom: 15px;">
                                    <h4>Title:</h4>
                                    <div style="display: flex; flex-direction: column; row-gap: 5px; width: 100%;">
                                        <input type="text" class="detail todo-detail" id="todo_title" name="todotitle" value="<?= htmlspecialchars($row['todotitle']) ?>" />
                                        <span style="align-self: center; margin: 0;" class="text-error" id="todo_title_error"><?= isset($errors['todotitle']) ? $errors['todotitle'] : '' ?></span>
                                    </div>
                                </div>
                                <div class="description">
                                    <h4>Description:</h4>
                                    <div style="display: flex; flex-direction: column; row-gap: 5px; width: 100%;">
                                        <textarea class="detail description-detail" name="tododescription"><?= htmlspecialchars($row['tododescription']) ?></textarea>
                                        <span style="align-self: center; margin: 0;" class="text-error"><?= isset($errors['tododescription']) ? $errors['tododescription'] : (isset($errors['todounchange']) ? $errors['todounchange'] : '') ?></span>
                                    </div>
                                </div>
                            </form>

                <?php
                        } else {
                            echo "<span class='text-error' style='font-size: 2rem; margin-bottom: 50%; text-align: center;'>Todo Not Found!</span>";
                        }

                        $stmt->close();
                    } else {
                        echo "<span class='text-error' style='font-size: 2rem; margin-bottom: 50%; text-align: center;'>Error Preparing stmt!</span>";
                    }

                    $conn->close();
                } else {
                    echo "<span class='text-error' style='font-size: 2rem; margin-bottom: 50%; text-align: center;'>Nothing to show up here!</span>";
                }
                ?>

                <div class="btn-group" style="justify-content: space-around;">
                    <button class="back-btn"><a href="/todo-list/"><i class="fa-solid fa-circle-left"></i></a></button>
                    <button type="submit" form="updateForm" class="confirm-btn"><i class="fa-solid fa-circle-check"></i></button>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/index.js"></script>
</body>

</html>