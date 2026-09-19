<?php

class Database
{
    private $connection;

    public function connect()
    {
        $username = "ICMS";
        $password = "icms05";
        $connection_string = "localhost/XE";

        $this->connection = oci_connect(
            $username,
            $password,
            $connection_string
        );

        if (!$this->connection) {
            $error = oci_error();
            die("Database Connection Failed: " . $error['message']);
        }

        return $this->connection;
    }
}

?>