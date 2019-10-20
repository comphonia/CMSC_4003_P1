<?php
/*
 User.php
 Defines a User Model
*/

require_once 'utility_functions.php';

class UserSession
{
    private $conn;

    function __construct()
    {
        //  $this->conn = $db;
    }

    function __destruct()
    {
        //close connection
        $this->conn = null;
    }

    // adds a new user to the database
    function createUserSession($user_id, $session_id)
    {
        $stmt = "INSERT INTO UserSession 
                                      VALUES ('$session_id', '$user_id', sysdate)";

        $result = execute_sql_in_oracle($stmt);
        $cursor = $result["cursor"];
        oci_free_statement($cursor);
        return $result["flag"];

    }

    //verifies if session exists
    public function verifySession($session_id)
    {
        $stmt = "SELECT session_id
                                        FROM UserSession 
                                        WHERE session_id = '$session_id'";

        $result = execute_sql_in_oracle($stmt);

        $data = [];
        $cursor = $result["cursor"];

        if ($result["flag"]) {
            while ($row = oci_fetch_assoc($cursor)){
                array_push($data,$row);
            }
            oci_free_statement($cursor);
            if (count($data) > 0)
                return true;
        }

        return false;
    }

    //deletes a user session
    public function deleteSession($user_id)
    {
        $stmt = "DELETE FROM UserSession
                                        WHERE user_id = '$user_id'";

        $result = execute_sql_in_oracle($stmt);

        if ($result) {
            return $result["flag"];
        }
        return false;
    }

    // returns current users session
    public function getSessionById($id)
    {
        $stmt = "SELECT session_id
                                        FROM UserSession 
                                        WHERE user_id = '$id'";

        $result = execute_sql_in_oracle($stmt);

        if ($result) {
            return $result["data"]['session_id'];
        }
        return false;

    }

}