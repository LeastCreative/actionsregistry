<?php
/**
 * Created by PhpStorm.
 * User: evenl
 * Date: 12/6/2018
 * Time: 12:07 PM
 */

class EditView
{
    function render($model)
    {
        $id = $model->id;
        $action = $model->action;
        $users = $model->users;
        $statuses = $model->statuses;

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
                </select></div>
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

    <?php }
}