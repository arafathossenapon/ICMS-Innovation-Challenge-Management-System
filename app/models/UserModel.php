<?php

require_once __DIR__ . '/../../config/database.php';

class UserModel
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function login($username)
    {
        $sql = "SELECT USER_ID, USERNAME, PASSWORD_HASH, ROLE, STATUS
                FROM SYSTEM_USER
                WHERE USERNAME = :username";

        $statement = oci_parse($this->db, $sql);

        oci_bind_by_name($statement, ":username", $username);

        oci_execute($statement);

        return oci_fetch_assoc($statement);
    }
}
?>