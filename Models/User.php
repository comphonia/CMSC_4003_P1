<?php
/*
User.php
Defines a User Model
 */

class User
{
    private $conn;
    private $defaultPass;

    public function __construct($db)
    {
        $this->conn = $db;
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
        $stmt = $this->conn->prepare("SELECT `user_id`
                                        FROM `User`
                                        WHERE `user_id` = ? AND `password` = ?");

        $stmt->bindParam(1, $id);
        $stmt->bindParam(2, $password);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return true;
        }

        return false;
    }

    // returns a single user
    public function getUserById($id)
    {
        $stmt = $this->conn->prepare("SELECT `user_id`, `firstname`, `lastname`, U.role_id, `role_name` as role
                                        FROM `User` U
                                        JOIN  `Role` R on U.role_id = R.role_id
                                         WHERE `user_id` = ?");

        $stmt->bindParam(1, $id);
        $stmt->execute();

        return $stmt->fetch();
    }

    // returns all users
    public function getAllUsers()
    {
        $stmt = $this->conn->prepare("SELECT `user_id`, `firstname`, `lastname`, U.role_id, `role_name` as role
                                        FROM `User` U
                                        JOIN  `Role` R on U.role_id = R.role_id");

        $stmt->execute();

        return $stmt->fetchAll();
    }

    // returns the id of the last inserted user, not setup for concurrency.
    public function getLastInsertedId()
    {
        $stmt = $this->conn->prepare("SELECT MAX(user_id) as id FROM `User`");

        $stmt->execute();

        $result = $stmt->fetch();

        if ($result) {
            return $result["id"];
        }

    }

    // search users in db
    public function searchUser($query)
    {
        $stmt = $this->conn->prepare("SELECT `user_id`, `firstname`, `lastname`, U.role_id, `role_name` as role
                                        FROM `User` U
                                        JOIN  `Role` R on U.role_id = R.role_id
                                        WHERE `user_id` LIKE CONCAT('%', :query, '%')
                                        OR `firstname` LIKE CONCAT('%', :query, '%')
                                        OR `lastname` LIKE CONCAT('%', :query, '%')
                                        OR `role_name` LIKE CONCAT('%', :query, '%')
                                        ");

        $stmt->bindParam(":query", $query);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // adds a new user to the database
    public function createUser($body)
    {
        $stmt = $this->conn->prepare("INSERT INTO `User`
                                      VALUES (null, ?, ?, ?, ?)");

        $stmt->bindParam(1, $body[0]);
        $stmt->bindParam(2, $body[1]);
        $stmt->bindParam(3, $body[2]);
        $stmt->bindParam(4, $body[3]);
        return $stmt->execute();
    }

    // updates a user based on their id
    public function updateUser($id, $body)
    {
        $stmt = $this->conn->prepare("UPDATE `User`
                                      SET `firstname` = ? , `lastname` = ? , `role_id` = ?
                                        WHERE `user_id` = ?");

        $stmt->bindParam(1, $body[0]);
        $stmt->bindParam(2, $body[1]);
        $stmt->bindParam(3, $body[2]);
        $stmt->bindParam(4, $id);
        return $stmt->execute();
    }

    // deletes a user from the database
    public function deleteUser($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM `User`
                                        WHERE `user_id` = ?");

        $stmt->bindParam(1, $id);
        return $stmt->execute();
    }

    // updates a user's password based on their id
    public function updatePassword($id, $newPassword)
    {
        $stmt = $this->conn->prepare("UPDATE `User`
                                      SET `password` = ?
                                        WHERE `user_id` = ?");

        $stmt->bindParam(1, $newPassword);
        $stmt->bindParam(2, $id);

        return $stmt->execute();

    }

    // resets a user password to a default one
    public function resetUserPassword($id)
    {
        $stmt = $this->conn->prepare("UPDATE `User`
                                      SET `password` = ?
                                        WHERE `user_id` = ?");

        $stmt->bindParam(1, $this->defaultPass);
        $stmt->bindParam(2, $id);
        return $stmt->execute();
    }

}
