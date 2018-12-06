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
        <style>
            .config-caption{
                font-size: .75em;
                font-weight: bold;
                padding-left: 20px;
            }
        </style>

        <h1>Admin - Configuration</h1>
        <form action="admin/config" method="post">
            <div class="form-group">
                <label>Active Team</label>
                <p class="config-caption">
                    Set the current team to show actions for
                </p>
                <select name="team_id" class="form-control">
                    <option value='null'>None</option>
                    <?php foreach ($teams as $teamId => $team) {
                        $selected = $teamId == $config['team_id'] ? 'selected' : '';
                        echo "<option value='$teamId' $selected>$team</option>";
                    } ?>
                </select>
            </div>
            <div class="form-group">
                <label>Age Threshold</label>
                <p class="config-caption">
                    Set age threshold of tasks in days
                </p>
                <input name="max_action_age" type="number" class="form-control" value="<?= $config['max_action_age'] ?>">
            </div>
            <input type="submit">
        </form>
        <?php
    }
}