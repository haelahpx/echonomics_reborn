<?php
include "../config.php";
session_start();

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>admin home</h1>
    <a href="ticket.php">ticket schedule</a>
    <a href="../logout.php">logout</a>
</body>
</html>