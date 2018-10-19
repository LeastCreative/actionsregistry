<?php

switch ($action) {
    /**
     * shows index of all status records
     */
    case 'index':
        $query = "SELECT * fROM statuses";
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
                        <a class="btn btn-sm edit" href="statuses/edit/<?= $row['id'] ?>">Edit</a>
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
        $query = "SELECT * fROM statuses where id = '$id'";
        $result = mysqli_query($db, $query);
        $row = $result->fetch_assoc();
        ?>
        <form action="statuses/update" method="post">
            <input name="id" type="hidden" value="<?= $row['id'] ?>">
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
        if (isset($_POST['description']) && isset($_POST['id'])) {
            $description = mysqli_real_escape_string($db, $_POST['description']);
            $id = mysqli_real_escape_string($db, $_POST['id']);
            $sql = "UPDATE statuses SET description = '$description' WHERE id = $id";
            mysqli_query($db, $sql);
            header('location: index');
        } else {
            echo 'error';
        }
        break;

}











