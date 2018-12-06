<?php
/**
 * Created by PhpStorm.
 * User: evenl
 * Date: 12/6/2018
 * Time: 12:05 PM
 */

class AddView
{
    function render($model)
    {
        $users = $model->users;
        $statuses = $model->statuses;
        $sources = $model->sources;

        ?>
        <form action="actions/create" method="post">
            <div class="form-group">
                <label>Name</label>
                <input name="name" type="text" class="form-control">
            </div>
            <div class="form-group">
                <label>Owner</label>
                <select name="owner_id" class="form-control">
                    <?php foreach ($users as $userId => $user) {
                        echo "<option value='$userId'>$user</option>";
                    } ?>
                </select>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status_id" class="form-control">
                    <?php foreach ($statuses as $statusId => $status) {
                        echo "<option value='$statusId'>$status</option>";
                    } ?>
                </select>
            </div>
            <div class="form-group">
                <label>Source</label>
                <select name="source_id" class="form-control">
                    <?php foreach ($sources as $sourceId => $source) {
                        echo "<option value='$sourceId'>$source</option>";
                    } ?>
                </select>
            </div>
            <input type="submit">
        </form>
    <?php }
}