<?php
session_start();
require 'config.php';
require 'lib/Controller.php';
//get rewritten url
$requestUri = !empty($_GET['uri']) ? $_GET['uri'] : '';
$uri_segments = explode('/', $requestUri);

//parse segments
$controller = !empty($uri_segments[0]) ? $uri_segments[0] : 'home';
$action = !empty($uri_segments[1]) ? $uri_segments[1] : 'index';
$id = !empty($uri_segments[2]) ? $uri_segments[2] : null;

//path to controller file
$path = "controllers/$controller.php";
$base = str_ireplace($requestUri, '', $_SERVER['REQUEST_URI'])
?>

<!DOCTYPE html>
<head>
    <base href="<?= $base ?>">

    <link rel="stylesheet" type="text/css" href="content/css/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="content/css/dataTables.bootstrap4.min.css"/>
    <link rel="stylesheet" type="text/css" href="content/css/buttons.bootstrap4.min.css"/>
    <link rel="stylesheet" type="text/css" href="content/css/site.css">
    <link rel="stylesheet" type="text/css" href="content/css/board.css"/>


    <script type="text/javascript" src="content/js/jquery-3.3.1.js"></script>
    <script type="text/javascript" src="content/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="content/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript" src="content/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="content/js/buttons.bootstrap4.min.js"></script>
    <script type="text/javascript" src="content/js/jszip.min.js"></script>
    <script type="text/javascript" src="content/js/pdfmake.min.js"></script>
    <script type="text/javascript" src="content/js/vfs_fonts.js"></script>
    <script type="text/javascript" src="content/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="content/js/buttons.print.min.js"></script>
    <script type="text/javascript" src="content/js/buttons.colVis.min.js"></script>

    <title>Action Registry</title>

</head>

<body>
<div style="width:100%;text-align:center;background-color: #30abff;">
    <div class="nav">
        <a href="home">Home</a>
        <a href="actions">List</a>
        <a href="statuses">Statuses</a>
        <a href="users">Users</a>
        <div class="dropdown">
            Admin
            <div class="dropdown-content">
                <a href="admin/import">Import</a>
                <a href="admin/export">Export</a>
                <a href="admin/config">Configure</a>
                <a href="admin/reports">Reports</a>
            </div>
        </div>
    </div>
</div>
<div style="padding:10px;width:90%;margin:auto;background-color:#fff">
    <?php
    $factory = new ControllerFactory();
    $controllerObj = $factory->get_controller($controller, $action);
    if (!empty($controllerObj)) {
        echo $controllerObj->$action($id);
    } else {
        http_response_code(404);
        echo 'page not found';
        die();
    }
    ?>
</div>
</body>



