<?php

switch ($action) {
    /**
     * shows index of all action records
     */
    case 'index':
        $query = "SELECT
                    a.action_id,
                    a.name,
                    CONCAT(o.first_name, ' ', o.last_name) owner,
                    s.description,
                    u.user_id,
                    u.first_name,
                    u.last_name
                  FROM actions a 
                    LEFT JOIN statuses s ON a.status_id = s.status_id 
                    LEFT JOIN assignments asgn ON a.action_id = asgn.action_id
                    LEFT JOIN users u ON asgn.user_id = u.user_id
                    LEFT JOIN users o ON a.owner_id = o.user_id
                  ORDER BY a.status_id";
        $result = mysqli_query($db, $query);
        $actions = [];
        while ($row = $result->fetch_assoc()) {
            $actions[$row["action_id"]]["name"] = $row["name"];
            $actions[$row["action_id"]]["description"] = $row["description"];
            $actions[$row["action_id"]]["owner"] = $row["owner"];
            if (isset($row['user_id'])) {
                $actions[$row["action_id"]]["assignments"][$row["user_id"]] = array(
                    "name" => $row['first_name'] . ' ' . $row['last_name'],
                    "user_id" => $row["user_id"],
                );
            }
        }
        ?>
        <h1>Actions</h1>
        <table id="actions" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>Name</th>
                <th>Owner</th>
                <th>Assigned To</th>
                <th>Status</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($actions as $actionId => $action): ?>
                <tr>
                    <td><?= $action['name'] ?></td>
                    <td><?= $action['owner'] ?></td>
                    <td>
                        <?php
                        if (isset($action['assignments']))
                            echo implode(', ', array_map(function ($user) {
                                return $user['name'];
                            }, $action['assignments']));
                        ?>
                    </td>
                    <td><?= $action['description'] ?></td>
                    <td>
                        <a class="btn btn-sm btn-secondary" href="actions/edit/<?= $actionId ?>">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <script>
            $(document).ready(function () {

                $("#actions").DataTable({
                    order: [],
                    dom: 'Bfrtip',
                    lengthChange: false,
                    buttons: [{
                        text: 'Add',
                        action: function (e, dt, node, config) {
                            window.location.href = "actions/new"
                        }
                    }]
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

        $query = "SELECT
                    a.action_id,
                    a.name,
                    a.owner_id,
                    s.status_id,
                    u.user_id,
                    u.first_name + ' ' + u.last_name as assigned_name
                  FROM actions a 
                    LEFT JOIN statuses s ON a.status_id = s.status_id 
                    LEFT JOIN assignments asgn ON a.action_id = asgn.action_id
                    LEFT JOIN users u ON asgn.user_id = u.user_id
                  WHERE a.action_id = $id";
        $result = mysqli_query($db, $query);
        $action = [];
        while ($row = $result->fetch_assoc()) {
            $action["name"] = $row["name"];
            $action["status_id"] = $row["status_id"];
            $action["owner_id"] = $row["owner_id"];
            if (isset($row['user_id'])) {
                $action["assignments"][$row["user_id"]] = array(
                    "name" => $row['assigned_name'],
                    "user_id" => $row["user_id"],
                );
            }
        }

        //get users
        $query = "SELECT * fROM users ORDER BY last_name";
        $userResult = mysqli_query($db, $query);
        $users = [];
        while ($user = $userResult->fetch_assoc()) {
            $users[$user['user_id']] = $user['last_name'] . ', ' . $user['first_name'];
        }

        //get statuses
        $query = "SELECT * fROM statuses";
        $statusResult = mysqli_query($db, $query);
        $statuses = [];
        while ($status = $statusResult->fetch_assoc()) {
            $statuses[$status['status_id']] = $status['description'];
        }

        ?>
        <form action="actions/update" method="post">
            <input name="id" type="hidden" value="<?= $id ?>">
            <div class="form-group">
                <label>Name</label>
                <input name="name" class="form-control" value="<?= $action['name'] ?>">
            </div>
            <div class="form-group">
                <label>Owner</label>
                <select name="ownerId" class="form-control">
                    <?php foreach ($users as $userId => $user) {
                        $selected = $userId == $action['owner_id'] ? 'selected' : '';
                        echo "<option value='$userId' $selected>$user</option>";
                    } ?>
                </select>            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="statusId" class="form-control">
                    <?php foreach ($statuses as $statusId => $status) {
                        $selected = $statusId == $action['status_id'] ? 'selected' : '';
                        echo "<option value='$statusId' $selected>$status</option>";
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
                    WHERE action_id = '$id'";
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











