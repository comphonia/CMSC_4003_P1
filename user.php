<?php
include_once('Controllers/FormController.php');

$currSession = "";
if (isset($_SESSION["sessionid"])) {
    $currSession = $_SESSION["sessionid"];
}
$formController->verifySession($currSession);

// if user and admin but is not a student-admin
if($formController->isAdmin() && !$formController->isStudentAdmin() ){
    header("Location: admin.php?sessionid=" . $currSession);
    exit(0);
}
?>

?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/styles.css">

    <title>Springfield University | Login</title>
</head>

<body>
<header>
    <a href="/index.php?sessionid=<?php echo $currSession; ?>" style="text-decoration: none"><h2 class="title">
            <span class="logo"><img src="assets/images/uni_logo.png"></span> Springfield University</h2>
    </a>
    <nav class="active">
        <ul>
            <li><a href="/user.php?sessionid=<?php echo $currSession; ?>" class="active">Student</a></li>
            <li><a href="/admin.php?sessionid=<?php echo $currSession; ?>">Administrator</a></li>
        </ul>
    </nav>
</header>
<main class="container-fluid">
    <div class="shade"></div>
    <div class="content container mt-5">
        <div class="profilecard">
            <?php
            echo "
            <h2>Welcome, {$formController->userData["firstname"]} </h2>
            <p>Id: {$formController->userData["user_id"]}</p>
            <p class=\" mb-5\">Role: {$formController->userData["role"]}</p>
            "
            ?>
            <?php echo $formController->isAdmin() ? "<a href='admin.php?sessionid=$currSession' class=\"btn btn-info mr-3\">Manage Users</a>" : ""; ?>
            <button class="btn" data-toggle="modal" data-target="#modalForm">Change Password</button>

            <a href="index.php?logout=true" ><button class="btn btn-danger mt-4 d-block">Logout</button></a>

        </div>
    </div>


    <div class="modal fade" id="modalForm" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Change Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <div class="form-group">
                            <input type="hidden" name="id" id="id"
                                   value="<?php echo $formController->userData['user_id']; ?>">
                            <label for="password">New Password:</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="">
                        </div>
                        <button type="submit" name="change" class="btn">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</main>
<footer>
    Created by Everistus Akpabio
</footer>

<!--    Modal-->


<!-- Optional JavaScript -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>


</body>
</html>