<?php
/*
 User.php
 Defines a User Model
*/

class UserSession
{
    private $conn;

    function __construct($db)
    {
        $this->conn = $db;
    }

    function __destruct()
    {
        //close connection
        $this->conn = null;
    }

    // adds a new user to the database
    function createUserSession($user_id, $session_id)
    {
        $stmt = $this->conn->prepare("INSERT INTO `UserSession` 
                                      VALUES ( ?, ?, sysdate())");

        $stmt->bindParam(1, $session_id);
        $stmt->bindParam(2, $user_id);
        return $stmt->execute();
    }

    //verifies if session exists
    public function verifySession($session_id)
    {
        $stmt = $this->conn->prepare("SELECT `session_id`
                                        FROM `UserSession` 
                                        WHERE `session_id` = ?");

        $stmt->bindParam(1, $session_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0)
            return true;

        return false;
    }

    //deletes a user session
    public function deleteSession($user_id)
    {
        $stmt = $this->conn->prepare("DELETE FROM `UserSession`
                                        WHERE `user_id` = ?");

        $stmt->bindParam(1, $user_id);
        return $stmt->execute();
    }

        // returns current users session
        public function getSessionById($id)
        {
            $stmt = $this->conn->prepare("SELECT `session_id`
                                        FROM `UserSession` 
                                        WHERE `user_id` = ?");

        $stmt->bindParam(1, $id);
        $result = $stmt->execute();

        if($result)
            return $result['session_id'];
        }

}