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
        $action = $model->action;
        $users = $model->users;
        $statuses = $model->statuses;
        $sources = $model->sources;

        ?>
        <form action="actions/update/<?= $action['action_id'] ?>" method="post">
            <div class="form-group">
                <label>Name</label>
                <input name="name" class="form-control" value="<?= $action['name'] ?>">
            </div>
            <div class="form-group">
                <label>Owner</label>
                <select name="owner_id" class="form-control">
                    <?php foreach ($users as $userId => $user) {
                        $selected = $userId == $action['owner_id'] ? 'selected' : '';
                        echo "<option value='$userId' $selected>$user</option>";
                    } ?>
                </select></div>
            <div class="form-group">
                <label>Status</label>
                <select name="status_id" class="form-control">
                    <?php foreach ($statuses as $statusId => $status) {
                        $selected = $statusId == $action['status_id'] ? 'selected' : '';
                        echo "<option value='$statusId' $selected>$status</option>";
                    } ?>
                </select>
            </div>
            <div class="form-group">
                <label>Source</label>
                <select name="source_id" class="form-control">
                    <?php foreach ($sources as $sourceId => $source) {
                        $selected = $sourceId == $action['source_id'] ? 'selected' : '';
                        echo "<option value='$sourceId' $selected>$source</option>";
                    } ?>
                </select>
            </div>
            <input type="submit">
        </form>

    <?php }
}