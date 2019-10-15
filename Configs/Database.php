<?php

/*
    Database.php
    Manages the db connection
*/

class Database
{
    private $db = null;

    function __construct($dbCredentials)
    {
        $servername = $dbCredentials['servername'];
        $dbname = $dbCredentials['dbname'];
        $username = $dbCredentials['username'];
        $password = $dbCredentials['password'];
        try {
            $this->db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
            //set fetch mode to return an associative array
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    function __destruct()
    {
        //close connection
        $this->db = null;
    }

    function GetDb()
    {
        return $this->db;
    }
}