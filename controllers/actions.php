<?php

switch ($action) {
    /**
     * shows index of all action records
     */
    case 'index':
        $query = "SELECT *, a.id as action_id FROM actions a LEFT JOIN statuses s ON a.status_id = s.id ORDER BY a.status_id";
        $result = mysqli_query($db, $query); ?>
        <h1>Actions</h1>
        <table id="actions" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>Name</th>
                <th>Status</th>
                <th style="font-weight: bold">
                    <a class="btn btn-sm edit" href="actions/new">Add</a>
                </th>
            </tr>
            </thead>
            <tbody>
            <?php while ($action = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $action['name'] ?></td>
                    <td><?= $action['description'] ?></td>
                    <td style="font-weight: bold">
                        <a class="btn btn-default btn-sm edit" href="actions/edit/<?= $action['action_id'] ?>">Edit</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        <script>
            $(document).ready(function () {
                $("#actions").DataTable({
                    "order": []
                });
            });
        </script>
        <?php
        break;

    case "new": ?>
        <form action="actions/create" method="post">
            <div class="form-group">
                <label>Name</label>
                <input name="name" type="text" class="form-control">
            </div>
            <input type="submit">
        </form>
        <?php break;

    /**
     * create a new action
     */
    case "create":
        if (isset($_POST['name'])) {
            $name = mysqli_real_escape_string($db, $_POST['name']);
            $sql = "INSERT INTO actions(name, created_date, updated_date) VALUES ('$name', NOW(), NOW())";
            mysqli_query($db, $sql);
            header('location: index');
        } else {
            echo 'error';
        }
        break;

    /**
     * form to edit an action
     */
    case 'edit':
        //get actions
        $query = "SELECT * fROM actions where id = '$id'";
        $result = mysqli_query($db, $query);
        $action = $result->fetch_assoc();

        $query = "SELECT * fROM statuses";
        $statusResult = mysqli_query($db, $query);
        $statuses = [];
        while ($status = $statusResult->fetch_assoc()) {
            $statuses[$status['id']] = $status['description'];
        }
        ?>
        <form action="actions/update" method="post">
            <input name="id" type="hidden" value="<?= $action['id'] ?>">
            <div class="form-group">
                <label>Name</label>
                <input name="name" class="form-control" value="<?= $action['name'] ?>">
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="statusid" class="form-control">
                    <?php foreach ($statuses as $id => $status) {
                        echo "<option value='$id'>$status</option>";
                    } ?>
                </select>
            </div>
            <input type="submit">
        </form>
        <?php
        break;

    /**
     * form to edit a status
     */
    case 'update':
        if (isset($_POST['id']) && isset($_POST['name']) && isset($_POST['statusid'])) {
            $id = mysqli_real_escape_string($db, $_POST['id']);
            $name = mysqli_real_escape_string($db, $_POST['name']);
            $statusid = mysqli_real_escape_string($db, $_POST['statusid']);
            $sql = "UPDATE actions 
                    SET 
                      name = '$name',
                      status_id = $statusid,
                      updated_date = NOW()
                    WHERE id = $id";
            mysqli_query($db, $sql);
            header('location: index');
        } else {
            echo 'error';
        }
        break;

    /**
     * action not found
     */
    default:
        echo 404;

}











