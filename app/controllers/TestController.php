<?php

require_once __DIR__ . "/../../config/database.php";

class TestController
{
    public function test()
    {
        $database = new Database();
        $connection = $database->connect();

        echo "MVC → Database Connection Successful!";
    }
}

$controller = new TestController();
$controller->test();

?>