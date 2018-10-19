<?php

switch ($action) {
    /**
     * shows index of all status records
     */
    case 'index':
        $query = "SELECT *, u.id as user_id, t.name as team_name fROM users u LEFT JOIN teams t ON u.team_id = t.id";
        $result = mysqli_query($db, $query); ?>
        <h1>Users</h1>
        <table id="users" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>User Name</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Team</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['user_name'] ?></td>
                    <td><?= $row['first_name'] ?></td>
                    <td><?= $row['last_name'] ?></td>
                    <td><?= $row['team_name'] ?></td>
                    <td>
                        <a class="btn btn-sm edit" href="users/edit/<?= $row['user_id'] ?>">Edit</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        <script>
            $(document).ready(function () {
                $("#statuses").DataTable({
                    "order": []
                });
            });
        </script>
        <?php
        break;

    /**
     * form to edit a status
     */
    case 'edit':
        $query = "SELECT * fROM users where id = '$id'";
        $result = mysqli_query($db, $query);
        $row = $result->fetch_assoc();

        $query = "SELECT * fROM teams";
        $teamResult = mysqli_query($db, $query);
        $teams = [];
        while ($team = $teamResult->fetch_assoc()) {
            $teams[$team['id']] = $team['name'];
        }
        ?>
        <form action="users/update" method="post">
            <input name="id" type="hidden" value="<?= $row['id'] ?>">
            <div class="form-group">
                <label>User Name</label>
                <input name="user_name" class="form-control" value="<?= $row['user_name'] ?>" readonly/>
            </div>
            <div class="form-group">
                <label>First Name</label>
                <input name="first_name" class="form-control" value="<?= $row['description'] ?>"/>
            </div>
            <div class="form-group">
                <label>Last Name</label>
                <input name="last_name" class="form-control" value="<?= $row['description'] ?>"/>
            </div>
            <div class="form-group">
                <label>Description</label>
                <select name="team_id" class="form-control">
                    <option value='null'>None</option>
                    <?php foreach ($teams as $id => $team) {
                        echo "<option value='$id'>$team</option>";
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
        if (isset($_POST['id']) && isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['team_id'])) {
            $first_name = mysqli_real_escape_string($db, $_POST['first_name']);
            $last_name = mysqli_real_escape_string($db, $_POST['last_name']);
            $team_id = mysqli_real_escape_string($db, $_POST['team_id']);
            $id = mysqli_real_escape_string($db, $_POST['id']);
            $sql = "UPDATE users SET 
                      first_name = '$first_name' 
                      last_name = '$last_name' 
                      team_id = $team_id 
                    WHERE id = $id";
            mysqli_query($db, $sql);
            header('location: index');
        } else {
            echo 'error';
        }
        break;

}










