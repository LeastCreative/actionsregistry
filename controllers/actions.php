<?php

switch ($action) {
    /**
     * shows index of all action records
     */
    case 'index':
        $query = "SELECT * fROM actions a JOIN statuses s ON a.status_id = s.id ORDER BY a.status_id";
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
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['description'] ?></td>
                    <td style="font-weight: bold">
                        <a class="btn btn-default btn-sm edit" href="actions/edit/<?= $row['id'] ?>">Edit</a>
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
     * action not found
     */
    default:
        echo 404;

}











