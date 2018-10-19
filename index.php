<?php
require 'config.php';
$app = 'registry';
$root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . "/$app/";
$requestUri = str_replace("/$app", '', $_SERVER['REQUEST_URI']);
$uri_segments = explode('/', $requestUri);
$controller = !empty($uri_segments[1]) ? $uri_segments[1] : 'home';
$action = !empty($uri_segments[2]) ? $uri_segments[2] : 'index';
$id = !empty($uri_segments[3]) ? $uri_segments[3] : null;
$path = "controllers/$controller.php";
?>


<!DOCTYPE html>
<head>
    <base href="<?= "/$app/" ?>">

    <link rel="stylesheet" type="text/css" href="content/css/bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="content/css/dataTables.bootstrap4.css"/>
    <link rel="stylesheet" type="text/css" href="content/css/site.css">

    <script type="text/javascript" src="content/js/jquery-3.3.1.js"></script>
    <script type="text/javascript" src="content/js/bootstrap.js"></script>
    <script type="text/javascript" src="content/js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="content/js/dataTables.bootstrap4.js"></script>

    <title>Action Registry</title>

    <style>
        .nav {
            display: inline-block;
            margin: 0 auto;
            padding: 20px
        }

        .nav a {
            padding: 10px;
            margin: 10px;
            background-color: #eeeeee;
        }
    </style>
</head>

<body style="background-color: #b8daff">
<div style="text-align: center;">
    <div class="nav">
        <a href="home">Home</a>
        <a href="actions">Actions</a>
        <a href="statuses">Statuses</a>
        <a href="users">Users</a>
        <a href="users">Teams</a>
    </div>
</div>
<div style="padding: 10px; width: 70%; margin: auto; background-color: #eee">

    <?php

    if (file_exists($path)) {
        include($path);
    } else {
        echo "$path not found";
    }

    ?>
</div>
</body>
