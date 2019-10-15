<?php
include_once('Controllers/FormController.php');
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
    <a href="/" style="text-decoration: none"><h2 class="title">
            <span class="logo"><img src="assets/images/uni_logo.png"></span> Springfield University</h2>
    </a>
    <nav>
        <ul>
            <li><a href="/user.php">Student</a></li>
            <li><a href="/admin.php">Administrator</a></li>
        </ul>
    </nav>
</header>
<main class="container-fluid">
    <div class="shade"></div>
    <div class="content container mt-5">
        <?php
        if (isset($_SESSION["isError"]) && $_SESSION["isError"] === true && isset($_SESSION['errorMsg'])) {
            echo "<div class=\"alert alert-danger\" role=\"alert\">
                      {$_SESSION["errorMsg"]}
                    </div>";
            unset($_SESSION['isError']);
        }
        ?>
        <div class="formcard">
            <form method="post">
                <h3 class="text-center">Login</h3>
                <div class="form-group">
                    <label for="id">Id:</label>
                    <input type="text" class="form-control" id="id" name="id" aria-describedby="id"
                           placeholder="Enter user id">
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password"
                           placeholder="Enter user password">
                </div>

                <button type="submit" name="login" class="btn">Submit</button>
            </form>
        </div>
    </div>
</main>
<footer>
    Created by Everistus Akpabio
</footer>

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