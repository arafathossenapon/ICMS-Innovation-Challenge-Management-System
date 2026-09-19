<?php

require_once __DIR__ . '/../models/UserModel.php';

$database = new Database();
$db = $database->connect();

$password = "12345678";
$hash = password_hash($password, PASSWORD_DEFAULT);

$sql = "UPDATE SYSTEM_USER
        SET PASSWORD_HASH = :password_hash
        WHERE USERNAME = 'admin'";

$stmt = oci_parse($db, $sql);

oci_bind_by_name($stmt, ":password_hash", $hash);

oci_execute($stmt);
oci_commit($db);

echo "Password Updated Successfully!";
?>