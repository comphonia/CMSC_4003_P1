<?php
include_once('Controllers/FormController.php');

$query = "";
if (isset($_GET['query'])) {
    $query = urlencode($_GET['query']);
}
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
    <link rel="stylesheet" href="  https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">

    <title>Springfield University | Login</title>
</head>

<body>
<header>
    <a href="/" style="text-decoration: none"><h2 class="title">
            <span class="logo"><img src="assets/images/uni_logo.png"></span> Springfield University</h2>
    </a>
    <nav class="active">
        <ul>
            <li><a href="/user.php">Student</a></li>
            <li><a href="/admin.php" class="active">Administrator</a></li>
        </ul>
    </nav>
</header>
<main class="container-fluid">
    <div class="shade"></div>
    <div class="content container mt-5">
        <div class="profilecard">
            <h2 class="mb-3">Manage Users</h2>
            <button class="btn btn-success" title="Add User" data-toggle="modal"
                    data-target="#modalForm"
                    onclick="addUserModal(<?php echo $formController->getLastInsertedId(); ?>)">Add New User
            </button>
            <form class="d-block my-5" method="get">
                <div class="col-12 d-flex justify-content-center  mx-auto">
                    <div class="col-sm-8 col-md-6 my-1  ">
                        <label class="sr-only" for="searchbox">Name</label>
                        <input type="search" class="form-control " name="query" id="searchbox"
                               placeholder="Search user_id, name or role">
                        <small class="text-muted text-center my-1 d-block mx-auto">Search an empty field for all
                            results</small>

                    </div>

                    <div class="col-auto my-1">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>

                </div>
            </form>


            <table class="table">
                <thead>
                <tr>
                    <th scope="col">user_id</th>
                    <th scope="col">FirstName</th>
                    <th scope="col">LastName</th>
                    <th scope="col">Role</th>
                    <th scope="col">Manage User</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($formController->searchUser($query) as $user) {
                    $a = array($user["user_id"], $user["firstname"], $user["lastname"], $user["role_id"]);
                    $a = "'" . implode("','", $a) . "'";
                    echo "
                    <tr>
                        <th scope=\"row\">{$user["user_id"]} </th>
                        <td>{$user["firstname"]} </td>
                        <td>{$user["lastname"]} </td>
                        <td>{$user["role"]} </td>
                        <td>
                            <button class=\"btn btn-danger mx-2\" title=\"Delete User\" onclick=\"deleteUser( {$user["user_id"]})\"><i class=\"far fa-trash-alt\"></i>
                            </button>
                            <button class=\"btn btn-info mx-2\" title=\"Edit User\" data-toggle=\"modal\"
                                    data-target=\"#modalForm\" onclick=\"updateUserModal($a) \"><i class=\"fas fa-pencil-alt\"></i></button>
                            <button class=\"btn btn-warning mx-2\" title=\"Reset Password\" onclick=\"resetUserPwd({$user["user_id"]})\"><i class=\"fas fa-sync-alt\"></i>
                            </button>
                        </td> 
                    </tr>";
                }
                ?>


                </tbody>
            </table>
        </div>
    </div>


    <div class="modal fade" id="modalForm" tabindex="-1" role="dialog"
         aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Update User id: 1</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" id="modalForm" autocomplete="off">
                        <div class="form-group">
                            <label for="id">Id:</label>
                            <input type="text" class="form-control" id="id2" placeholder="" disabled
                                   value="1">
                            <input type="hidden" name="id" id="id" value="">
                            <label for="firstname">FirstName:</label>
                            <input type="text" class="form-control" id="firstname" name="firstname" placeholder="">
                            <label for="lastname">LastName:</label>
                            <input type="text" class="form-control" id="lastname" name="lastname" placeholder="">
                            <label for="password">Password:</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder=""
                                   value="password" autocomplete="new-password" disabled>
                            <div class="input-group mt-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="role">Role</label>
                                </div>
                                <select class="custom-select" id="role" name="role">
                                    <option value="1">Student</option>
                                    <option value="2">Administrator</option>
                                    <option value="3">Student-Administrator</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" id="modalBtn" class="btn" name="update">Submit</button>
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

<script>

    let modalTitle = document.getElementById("modalTitle");
    let modalBtn = document.getElementById("modalBtn");
    let id = document.getElementById("id");
    let id2 = document.getElementById("id2");
    let firstname = document.getElementById("firstname");
    let lastname = document.getElementById("lastname");
    let password = document.getElementById("password");
    let role = document.getElementById("role");


    function deleteUser(id) {
        let i = confirm("Delete user id " + id + "?");
        if (i == true)
            window.location = "/admin.php?action=delete&id=" + id;
    }

    function resetUserPwd(id) {
        let j = confirm("Reset password for user id " + id + "?");
        if (j == true)
            window.location = "/admin.php?action=reset&id=" + id;
    }

    function updateUserModal(_id, _firstname, _lastname, _role) {
        modalTitle.innerHTML = "Update User id: " + _id;
        id.value = _id;
        id2.value = _id;
        firstname.value = _firstname;
        lastname.value = _lastname;
        role.value = _role;
        password.value = 'password'

        modalBtn.setAttribute("name", "update");

        id2.setAttribute("disabled", "disabled");
        password.setAttribute("disabled", "disabled");
    }

    function addUserModal(_id) {
        modalTitle.innerHTML = "Add User id: " + _id;
        id.value = _id;
        id2.value = _id;
        firstname.value = '';
        lastname.value = '';
        role.value = '1';
        password.value = ''

        modalBtn.setAttribute("name", "create");

        // id2.removeAttribute("disabled");
        password.removeAttribute("disabled");
    }
</script>

</body>
</html>