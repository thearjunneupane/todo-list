<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Todo Details</title>
</head>

<body class="light">

    <header id="header">
        <h1 id="title">Todo Details</h1>
        <div class="flexrow-container">
            <div class="theme-selector light-theme"></div>
            <div class="theme-selector darker-theme"></div>
        </div>
    </header>

    <div id="container">
        <div id="myUnOrdList" style="padding: 0 20px;justify-content: flex-start;">
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
                ?> <div class="title">
                                <h4>Title:</h4>
                                <div style="width: 100%;" class="detail todo-detail"><?php echo $row['todotitle']; ?></div>
                            </div>
                            <div class="description">
                                <h4>Description:</h4>
                                <div style="width: 100%;" class="detail description-detail"><?php echo $row['tododescription']; ?></div>
                            </div>
                <?php
                        } else {
                            echo "<span class='text-error' style='font-size: 2rem; margin-bottom: 50%; text-align: center;'>Todo Not Found</span>";
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
                    <button class="back-btn"><a href="/"><i class="fa-solid fa-circle-left"></i></a></button>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/index.js"></script>
</body>

</html>