<?php
session_start();

/*
    FormController.php
    Entry point for form post data:
    - sanitize and validate data
    - liaison to the data Models
*/

require_once __DIR__ . '/../Configs/dbCredentials.php';
require_once __DIR__ . '/../Configs/Database.php';
require_once __DIR__ . '/../Models/User.php';
require_once 'whoami.php';

$formController = new FormController($dbCredentials);

// POST & GET valet
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST)) {
    // login form
    if (isset($_POST['login'])) {
        $formController->loginUser();
    }
    // create form
    if (isset($_POST['create'])) {
        $formController->createUser();
    }
    // update form
    if (isset($_POST['update'])) {
        $formController->updateUser();
    }
    // password change form
    if (isset($_POST['change'])) {
        $formController->updatePassword();
    }


}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET)) {

    if (isset($_GET['action']) && isset($_GET['id'])) {
        if ($_GET['action'] === 'delete')
            $formController->deleteUser(urlencode($_GET['id']));
        if ($_GET['action'] === 'reset')
            $formController->resetUserPassword(urlencode($_GET['id']));
    }

}

class FormController
{
    private $User;
    public $userData;

    function __construct($dbCredentials)
    {
        $db = new Database($dbCredentials);
        $this->User = new User($db->GetDb());
        if (isset($_SESSION["userData"])) {
            $this->userData = $_SESSION["userData"];
        }
    }

    function loginUser()
    {
        // sanitize input
        $id = trim(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING));
        $password = trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));

        if (empty($id)) {
            $this->throwError("Please enter a valid id");
        }
        if (empty($password)) {
            $this->throwError("Please enter a valid password");
        }
        // verify if user exists
        if ($this->User->verifyUser($id, md5($password))) {
            $_SESSION["userData"] = $this->User->getUserById($id);
            header("Location: /user.php?role=" . $this->userData['role']);
            exit(0);
        } else {
            $this->throwError("Could not find user with that combination");
        }
    }

    // adds a new user to the database
    function createUser()
    {
        // sanitize input
        $firstname = trim(filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING));
        $lastname = trim(filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING));
        $password = trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));
        $role = trim(filter_input(INPUT_POST, 'role', FILTER_SANITIZE_NUMBER_INT));

        if (empty($password) && empty($firstname) && empty($lastname) && empty($role)) {
            exit("Invalid input, cannot update user");
        }

        if ($this->User->createUser([$firstname, $lastname, md5($password), $role])) {
            header("Location: /admin.php");
            exit(0);
        } else {
            exit("Could not add user");
        }
    }

    // updates a user based on their id
    function updateUser()
    {
        // sanitize input
        $id = trim(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING));
        $firstname = trim(filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING));
        $lastname = trim(filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING));
        $role = trim(filter_input(INPUT_POST, 'role', FILTER_SANITIZE_NUMBER_INT));

        if (empty($id) && empty($firstname) && empty($lastname) && empty($role)) {
            exit("Invalid input, cannot update user");
        }

        if ($this->User->updateUser($id, [$firstname, $lastname, $role])) {
            header("Location: /admin.php");
            exit(0);
        } else {
            exit("Could not update user");
        }
    }

    // updates a user's password based on their id
    function updatePassword()
    {
        // sanitize input
        $id = trim(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING));
        $password = trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));

        if (empty($id) && empty($password)) {
            exit("Invalid input, cannot update user");
        }

        if ($this->User->updatePassword($id, md5($password))) {
            header("Location: /user.php");
            exit(0);
        } else {
            exit("Could not update user password");
        }
    }

    public function getLastInsertedId()
    {
        return $this->User->getLastInsertedId();
    }

    // search users in db
    function searchUser($query)
    {
        return $this->User->searchUser($query);
    }

    // deletes a user from the database
    function deleteUser($id)
    {
        return $this->User->deleteUser($id);
    }

    // resets a user password to a default one
    function resetUserPassword($id)
    {
        return $this->User->resetUserPassword($id);
    }

    // retrieves users from the database for the data table
    function getAllUsers()
    {
        return $this->User->getAllUsers();
    }

    function getCurrentUserData()
    {
        return $this->userData;
    }

    function isAdmin()
    {
        return $this->userData['role_id'] == 2 || $this->userData['role_id'] == 3;
    }


//Invalid data - exits with a response code and message
    function throwError($err)
    {
        $_SESSION["isError"] = true;
        $_SESSION["errorMsg"] = $err;
        http_response_code(400);
        header("Location: /index.php");
        exit($err);
    }

}



