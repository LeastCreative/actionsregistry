<?php

switch ($action) {
    /**
     * shows index of all status records
     */
    case 'index':
        $query = "SELECT * 
                  FROM statuses";
        $result = mysqli_query($db, $query); ?>
        <h1>Statuses</h1>
        <table id="statuses" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>Description</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['description'] ?></td>
                    <td>
                        <a class="btn btn-sm edit" href="statuses/edit/<?= $row['status_id'] ?>">Edit</a>
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
        $query = "SELECT * 
                  FROM statuses 
                  WHERE status_id = '$id'";
        $result = mysqli_query($db, $query);
        $row = $result->fetch_assoc();
        ?>
        <form action="statuses/update" method="post">
            <input name="statusId" type="hidden" value="<?= $row['status_id'] ?>">
            <div class="form-group">
                <label>Description</label>
                <input name="description" type="text" class="form-control" value="<?= $row['description'] ?>">
            </div>
            <input type="submit">
        </form>
        <?php
        break;

    /**
     * form to edit a status
     */
    case 'update':
        if (isset($_POST['description']) && isset($_POST['statusId'])) {
            $description = mysqli_real_escape_string($db, $_POST['description']);
            $id = mysqli_real_escape_string($db, $_POST['statusId']);
            $sql = "UPDATE statuses 
                    SET description = '$description' 
                    WHERE status_id = $id";
            mysqli_query($db, $sql);
            header('location: index');
        } else {
            echo 'error';
        }
        break;

}











