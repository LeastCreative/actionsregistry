<?php
require '../config.php';


$apidb = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
$apidb->set_charset("utf8");

if (isset($_POST["actionId"]) && isset($_POST["statusId"])) {
    $actionId = mysqli_real_escape_string($apidb, $_POST['actionId']);
    $statusId = mysqli_real_escape_string($apidb, $_POST['statusId']);
    $query = "UPDATE actions SET status_id = $statusId, updated_date = NOW() WHERE action_id = $actionId";
    $actionResult = mysqli_query($apidb, $query);
}
