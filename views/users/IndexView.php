<?php
/**
 * Created by PhpStorm.
 * User: evenl
 * Date: 12/1/2018
 * Time: 1:10 AM
 */

class IndexView
{
    function render($model)
    { ?>

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
            <?php foreach ($model->users as $user_id => $user): ?>
                <tr>
                    <td><?= $user->user_name ?></td>
                    <td><?= $user->first_name ?></td>
                    <td><?= $user->last_name ?></td>
                    <td><?= $user->team_name ?></td>
                    <td>
                        <a class="btn btn-sm edit btn-secondary" href="users/edit/<?= $user_id ?>">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <script>
            $(document).ready(function () {
                $("#users").DataTable({
                    order: [],
                    dom: 'Bfrtip',
                    buttons: [
                        'pageLength'
                    ]
                });
            });
        </script>
    <?php }
}