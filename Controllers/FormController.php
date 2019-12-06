<?php
session_start();

/*
    FormController.php
    Entry point for form post data:
    - sanitize and validate data
    - liaison to the data Models
*/

define('__SUB__' , 'localhost:8080');

require_once __DIR__ . '/../Configs/dbCredentials.php';
require_once __DIR__ . '/../Configs/Database.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/UserSession.php';

$formController = new FormController();

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
    private $UserSession;
    public $userData;

    function __construct()
    {
        $this->User = new User();
        $this->UserSession = new UserSession();

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
        //create new session id
        $sessionid = md5(uniqid(rand()));

        // verify if user exists
        if ($this->User->verifyUser($id, md5($password)) && $this->UserSession->createUserSession($id, $sessionid)) {
            $_SESSION["userData"] = $this->User->getUserById($id);
            var_dump($_SESSION["userData"]);
            $_SESSION["sessionid"] = $sessionid;
            header("Location: http://cs2.uco.edu/~gq001/su/user.php?role=" . $this->userData['role'] . "&sessionid=" . $sessionid);
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
            header("Location: http://cs2.uco.edu/~gq001/su/admin.php");
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
            //$_SESSION["userData"] = $this->User->getUserById($id);
            header("Location: http://cs2.uco.edu/~gq001/su/admin.php");
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
            header("Location: http://cs2.uco.edu/~gq001/su/user.php");
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
        // if user deletes self
        if ($this->User->deleteUser($id) && $id == $this->userData['user_id']) {
            // log-out current user
            $_SESSION = null;
            $this->throwError("Your account has been deleted");
        }
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

    function isStudentAdmin()
    {
        return $this->userData['role_id'] == 3;
    }

    function verifySession($currSession)
    {
        if (!$this->UserSession->verifySession($currSession)) {
            header("Location: http://cs2.uco.edu/~gq001/su/index.php?logout=true");
            exit(0);
        }
        return $currSession;
    }

    public function deleteSession($user_id)
    {
        return $this->UserSession->deleteSession($user_id);
    }


//Invalid data - exits with a response code and message
    function throwError($err)
    {
        $_SESSION["isError"] = true;
        $_SESSION["errorMsg"] = $err;
        http_response_code(400);
        header("Location: http://cs2.uco.edu/~gq001/su/index.php?logout=true");
        exit($err);
    }

}



