<?php
require '../config.php';

if (isset($_POST["actionId"]) && isset($_POST["statusId"])) {
    $actionId = mysqli_real_escape_string($db, $_POST['actionId']);
    $statusId = mysqli_real_escape_string($db, $_POST['statusId']);
    $query = "UPDATE actions SET status_id = $statusId, updated_date = NOW() WHERE action_id = $actionId";
    $actionResult = mysqli_query($db, $query);
}
