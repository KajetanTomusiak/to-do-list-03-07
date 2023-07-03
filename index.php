<?php
// session_start();
require_once "header.php";
require_once "db.php";
require_once "auth.php";
redirectIfNotLoggedIn();

$userId = $_SESSION['user_id'];

$username_query = "SELECT username FROM users WHERE id = $userId";
$username_result = $dbcon->query($username_query);
$username_row = $username_result->fetch_assoc();
$username = $username_row['username'];

$task_show_query = "SELECT * FROM tasks WHERE user_id = $userId";

$result = $dbcon->query($task_show_query);



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['addtask'])) {
        $taskName = $_POST['textfield'];
        $taskDateTime = $_POST['taskdatetime'];
        $description = $_POST['description'];

        $dateTime = new DateTime($taskDateTime);
        $formattedDateTime = $dateTime->format('Y-m-d H:i:s');

        $add_task_query = "INSERT INTO tasks (user_id, task_name, task_datetime, added_time, description) VALUES ($userId, '$taskName', '$formattedDateTime', NOW(), '$description')";
        $add_task_result = $dbcon->query($add_task_query);

        if ($add_task_result) {
            header("Location: index.php");
            exit();
        } else {
            echo "Wystąpił błąd podczas dodawania zadania.";
        }
    }
}
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#" onclick="window.location.reload(true);">To do list</a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="navbar-collapse justify-content-end" id="navbarSupportedContent">
        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <?php echo $username; ?>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="index.php"><i class="fa fa-home mr-2" aria-hidden="true"></i>Home</a>
                    <a class="dropdown-item" href="logout.php"><i class="fa fa-sign-out mr-2" aria-hidden="true"></i>Logout</a>
                </div>
            </li>
        </ul>
    </div>
</nav>

<div class="container">
    <div class="row">
        <div class="pt-5 pb-5 col-8 m-auto">
            <form class="mt-5" action="index.php" method="post">
                <div class="form-group">
                    <label for="tasknameInput">Task name</label>
                    <input class="form-control form-control-lg" id="tasknameInput" type="text" name="textfield"
                        placeholder="Enter your task" required>
                </div>
                <div class="form-group">
                    <label for="descriptionInput">Description</label>
                    <input class="form-control form-control-lg" id="descriptionInput" type="text" name="description"
                        id="taskdatetime" placeholder="Describe the task" required>
                </div>
                <div class="form-group">
                    <label for="deadlineInput">Deadline</label>
                    <input class="form-control form-control-lg" id="deadlineInput" type="datetime-local"
                        name="taskdatetime" id="taskdatetime" required>
                </div>

                <div class="">
                    <input class="btn btn-success btn-block" type="submit" name="addtask" value="Add Task">
                </div>
            </form>
        </div>
    </div>
    <h2 class="text-center display-4 mb-5">Your tasks:</h2>
    <?php

    if (isset($_SESSION['delete_success'])) { ?>

    <div class="alert alert-warning text-dark mx-auto slide" role="alert" style="width:66%; display: block;">
        <?= $_SESSION['delete_success']; ?>
    </div>

    <?php
        unset($_SESSION['delete_success']);
    }

    ?>

    <?php

    if (isset($_SESSION['update_success'])) { ?>

    <div class="alert alert-warning text-dark mx-auto slide" role="alert" style="width:66%;">
        <?= $_SESSION['update_success']; ?>
    </div>

    <?php
        unset($_SESSION['update_success']);
    }

    ?>

    <ul style="width:66%;" class="list-group mx-auto mb-3">

        <?php
        if ($result->num_rows != 0) {
            foreach ($result as $row) {
                $temp_date_time = (explode(' ', $row['added_time']));
                $date = $temp_date_time[0];
                $time = $temp_date_time[1];
                $taskDateTime = $row['task_datetime'];
                $description = $row['description'];
                $formattedTaskDateTime = date('Y-m-d H:i', strtotime($taskDateTime));
                $taskDate = date('Y-m-d', strtotime($taskDateTime));
                $taskTime = date('H:i', strtotime($taskDateTime));
        ?>

        <li class="list-group-item mb-2">
            <div class="row">
                <div class="col-10">
                    <h5 class="mb-1" style="word-wrap: break-word;"><?= $row['task_name'] ?></h5>
                    <small>Description: <?= $description ?> <br>
                        Deadline: <?= $taskDate  . " " . $taskTime ?> <br>
                        <small class="text-muted">Added: <?= $date . " " . $time ?> </small></small>
                </div>
                <div class="col-2 d-flex align-items-center">
                    <div class="btn-group">
                        <a class="btn btn-sm btn-success" href="update.php?id=<?php echo base64_encode($row['id']); ?>">
                            <i class="fa fa-fw fa-pencil"></i>
                        </a>
                        <a class="btn btn-sm btn-danger" href="delete.php?id=<?php echo base64_encode($row['id']); ?>">
                            <i class="fa fa-fw fa-trash"></i>
                        </a>
                    </div>
                </div>
            </div>
        </li>
        <?php
            }
        } else {
            ?>
        <li class="list-group-item text-center display-4 p-5" style="pointer-events: none !important;">No tasks</li>
        <?php
        }
        ?>
    </ul>

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
</script>
<script src="animation.js"></script>

</body>

</html>