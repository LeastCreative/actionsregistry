<?php
/**
 * Created by PhpStorm.
 * User: evenl
 * Date: 12/6/2018
 * Time: 2:08 PM
 */

class ConfigView
{
    function render($model)
    {
        $teams = $model->teams;
        $config = $model->config;
        ?>
        <h1>Admin - Configuration</h1>
        <form action="admin/config" method="post">
            <div class="form-group">
                <label>Active Team</label>
                <select name="team_id" class="form-control">
                    <option value='null'>None</option>
                    <?php foreach ($teams as $teamId => $team) {
                        $selected = $teamId == $config['team_id'] ? 'selected' : '';
                        echo "<option value='$teamId' $selected>$team</option>";
                    } ?>
                </select>
            </div>
            <input type="submit">
        </form>
        <?php
    }
}