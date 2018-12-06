<?php
/**
 * Created by PhpStorm.
 * User: evenl
 * Date: 12/1/2018
 * Time: 1:10 AM
 */

class EditView
{
    function render($model)
    {
        $user = $model->user;
        $teams = $model->teams;
        ?>

        <form action="users/update/<?= $user->user_id ?>" method="post">
            <div class="form-group">
                <label>User Name</label>
                <input name="user_name" class="form-control" value="<?= $user->user_name ?>" readonly/>
            </div>
            <div class="form-group">
                <label>First Name</label>
                <input name="first_name" class="form-control" value="<?= $user->first_name ?>"/>
            </div>
            <div class="form-group">
                <label>Last Name</label>
                <input name="last_name" class="form-control" value="<?= $user->last_name ?>"/>
            </div>
            <div class="form-group">
                <label>Team</label>
                <select name="team_id" class="form-control">
                    <option value='null'>None</option>
                    <?php foreach ($teams as $teamId => $team) {
                        $selected = $teamId == $user->team_id ? 'selected' : '';
                        echo "<option value='$teamId' $selected>$team</option>";
                    } ?>
                </select>
            </div>
            <input type="submit">
        </form>


    <?php }
}