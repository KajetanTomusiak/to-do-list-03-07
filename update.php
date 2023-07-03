<?php
    session_start();
    require_once 'db.php';
    $id = base64_decode($_GET['id']);
    $data = "SELECT * FROM tasks WHERE id=$id";
    $data_from_db = $dbcon->query($data);
    $f_result = $data_from_db->fetch_assoc();

    $userId = $_SESSION['user_id'];

    $username_query = "SELECT username FROM users WHERE id = $userId";
    $username_result = $dbcon->query($username_query);
    $username_row = $username_result->fetch_assoc();
    $username = $username_row['username'];

    if (isset($_POST['update'])) {
        $update_text = $_POST['update_text'];
        $update_description = $_POST['update_description'];
        $update_date = $_POST['update_date'];
        $update_query = "UPDATE tasks SET task_name='$update_text', description='$update_description', task_datetime='$update_date' WHERE id=$id";
        $update_date = $dbcon->query($update_query);

        if ($update_date) {
            $_SESSION['update_success'] = "Task updated successfully!";
        }

        header('location: index.php');
    }
?>


<?php
    require_once 'header.php';  
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
                    <!-- <a class="dropdown-item" href="logout.php"><i class="fa fa-sign-out mr-2" aria-hidden="true"></i>Logout</a> -->
                </div>
            </li>
        </ul>
    </div>
</nav>

<div class="container">
    <div class='row'>
        <div class="pt-5 pb-5 col-8 m-auto">
            <h2 class="display-4 mx-auto mt-2 text-center">Update Task</h2>
            <form class="mt-5" action="" method="post">
                <div class="form-group">
                    <label for="tasknameInput">Task name</label>
                    <input class="form-control form-control-lg" type="text" name="update_text"
                        value="<?= $f_result['task_name'] ?>">
                </div>
                <div class="form-group">
                    <label for="descriptionInput">Description</label>
                    <input class="form-control form-control-lg" id="descriptionInput" type="text"
                        name="update_description" id="taskdatetime" placeholder="Describe the task"
                        value="<?= $f_result['description'] ?>" required>
                </div>
                <div class="form-group">
                    <label for="deadlineInput">Deadline</label>
                    <input class="form-control form-control-lg" id="deadlineInput" type="datetime-local"
                        name="update_date" id="taskdatetime" value="<?= $f_result['task_datetime'] ?>" required>
                </div>

                <div class="">
                    <input class="btn btn-warning btn-block" type="submit" name="update" value="Update">
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
</script>

</body>

</html>