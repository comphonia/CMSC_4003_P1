<?php
/*
User.php
Defines a User Model
 */

require_once 'utility_functions.php';

class User
{
    private $conn;
    private $defaultPass;

    public function __construct()
    {
        // $this->conn = $db;
        $this->defaultPass = md5(1);
    }

    public function __destruct()
    {
        //close connection
        $this->conn = null;
    }

    // adds a new user to the database

    //verifies if user exists
    public function verifyUser($id, $password)
    {
        $stmt = "SELECT user_id
                                        FROM \"User\"
                                        WHERE user_id = '$id' AND password = '$password'";

        $result = execute_sql_in_oracle($stmt);

        $data = [];
        $cursor = $result["cursor"];

        if ($result["flag"]) {
            while ($row = oci_fetch_assoc($cursor)) {
                array_push($data, $row);
            }
            oci_free_statement($cursor);
            if (count($data) > 0)
                return true;
        }

        return false;
    }

    // returns a single user
    public function getUserById($id)
    {
        $stmt = "SELECT user_id, firstname, lastname, U.role_id, role_name as role
                                        FROM \"User\" U
                                        JOIN  Role R on U.role_id = R.role_id
                                         WHERE user_id = '$id'";

        $result = execute_sql_in_oracle($stmt);

        $data = [];
        $cursor = $result["cursor"];

        if ($result["flag"]) {
            while ($row = oci_fetch_assoc($cursor)) {
                array_push($data, $row);
            }
            oci_free_statement($cursor);
            if (count($data) > 0)
                return array_change_key_case_recursive($data)[0];
        }

        return false;
    }

    // returns all users
    public function getAllUsers()
    {
        $stmt = "SELECT user_id, firstname, lastname, U.role_id, role_name as role
                                        FROM \"User\" U
                                        JOIN  Role R on U.role_id = R.role_id
                                        ORDER BY user_id";

        $result = execute_sql_in_oracle($stmt);

        $data = [];
        $cursor = $result["cursor"];

        if ($result["flag"]) {
            while ($row = oci_fetch_assoc($cursor)) {
                array_push($data, $row);
            }
            oci_free_statement($cursor);
            if (count($data) > 0)
                return array_change_key_case_recursive($data);
        }

        return false;
    }

    // returns the id of the last inserted user, not setup for concurrency.
    public function getLastInsertedId()
    {
        $stmt = "SELECT MAX(user_id) as id FROM \"User\"";

        $result = execute_sql_in_oracle($stmt);

        $data = [];
        $cursor = $result["cursor"];

        if ($result["flag"]) {
            while ($row = oci_fetch_assoc($cursor)) {
                array_push($data, $row);
            }
            oci_free_statement($cursor);
            if (count($data) > 0)
                return array_change_key_case_recursive($data)[0]["id"];
        }
        return false;

    }

    // search users in db
    public function searchUser($query)
    {
        $stmt = "SELECT user_id, firstname, lastname, U.role_id, role_name as role
                                        FROM \"User\" U
                                        JOIN  Role R on U.role_id = R.role_id
                                        WHERE user_id LIKE CONCAT(CONCAT('%', '$query'), '%')
                                        OR firstname LIKE CONCAT(CONCAT('%', '$query'), '%')
                                        OR lastname LIKE CONCAT(CONCAT('%', '$query'), '%')
                                        OR role_name LIKE CONCAT(CONCAT('%', '$query'), '%')
                                        ORDER BY user_id
                                        ";

        $result = execute_sql_in_oracle($stmt);

        $data = [];
        $cursor = $result["cursor"];

        if ($result["flag"]) {
            while ($row = oci_fetch_assoc($cursor)) {
                array_push($data, $row);
            }
            oci_free_statement($cursor);
            if (count($data) > 0)
                return array_change_key_case_recursive($data);
        }

        return false;
    }

    // adds a new user to the database
    public function createUser($body)
    {
        $stmt = "INSERT INTO \"User\" (firstname,lastname,password,role_id)
                                      VALUES ( '$body[0]', '$body[1]', '$body[2]', '$body[3]')";

        $result = execute_sql_in_oracle($stmt);

        if ($result) {
            return $result["flag"];
        }
        return false;
    }

    // updates a user based on their id
    public function updateUser($id, $body)
    {
        $stmt = "UPDATE \"User\"
                                      SET firstname = '$body[0]' , lastname = '$body[1]' , role_id = '$body[2]'
                                        WHERE user_id = '$id'";

        $result = execute_sql_in_oracle($stmt);

        if ($result) {
            return $result["flag"];
        }
        return false;
    }

    // deletes a user from the database
    public function deleteUser($id)
    {
        $stmt = "DELETE FROM \"User\"
                                        WHERE user_id = '$id'";

        $result = execute_sql_in_oracle($stmt);

        if ($result) {
            return $result["flag"];
        }
        return false;
    }

    // updates a user's password based on their id
    public function updatePassword($id, $newPassword)
    {
        $stmt = "UPDATE \"User\"
                                      SET password = '$newPassword'
                                        WHERE user_id = '$id'";

        $result = execute_sql_in_oracle($stmt);

        if ($result) {
            return $result["flag"];
        }
        return false;

    }

    // resets a user password to a default one
    public function resetUserPassword($id)
    {
        $stmt = "UPDATE \"User\"
                                      SET password = '$this->defaultPass'
                                        WHERE user_id = '$id'";

        $result = execute_sql_in_oracle($stmt);

        if ($result) {
            return $result["flag"];
        }
        return false;
    }

}
