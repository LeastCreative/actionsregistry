<?php
/**
 * Created by PhpStorm.
 * User: evenl
 * Date: 12/6/2018
 * Time: 12:05 PM
 */

class IndexView
{
    function render($model)
    {
        $statuses = $model->statuses;
        $teams = $model->teams;
        $actionGroups = $model->actionGroups;
        $users = $model->users;
        $teamName = $model->teamName;

        echo '<h1 style="display: inline-block;">Board';
        if (isset($teamName)) {
            echo ' - ' . $teamName;
        }
        echo '</h1>';
        ?>

        <form class="form-inline float-right" style="margin-top: 10px;">
            <div class="form-group">
                <label><b>Show items for:&nbsp;</b>
                    <select class="form-control" id="team-filter" style="margin-right: 5px">
                        <option value='all'>All Teams</option>
                        <?php foreach ($teams as $teamId => $name) {
                            $selected = isset($teamName) && $teamName == $name ? 'selected' : '';
                            echo "<option value='$teamId' $selected>$name</option>";
                        } ?>
                    </select>
                    <select class="form-control" id="user-filter">
                        <option value='all'>All Users</option>
                        <?php foreach ($users as $userId => $userName) {
                            echo "<option value='$userId'>$userName</option>";
                        } ?>
                    </select>
                </label>
            </div>
        </form>
        <div id="status-content">

            <div id="statuses">
                <?php foreach ($statuses as $statusId => $status) { ?>
                    <div class="lane" ondrop="drop(event, <?= $statusId ?>)" ondragover="allowDrop(event)">
                        <h3><?= $status ?></h3>
                        <div class="actions">
                            <?php foreach ($actionGroups[$statusId] as $actionId => $action) {
                                echo "<div id='$actionId' class='action' draggable='true' ondragstart='drag(event)'>";
                                echo "<h4>" . $action['name'] . "</h4>";

                                if (isset($action['assignments'])) {
                                    echo "<h6>Assigned To:</h6>";
                                    echo "<ul style='font-size: .75em'>";
                                    foreach ($action['assignments'] as $userId => $user) {
                                        echo "<li class='user-$userId'>" . $user['name'] . "</li>";
                                    }
                                    echo "</ul>";
                                } else {
                                    echo "<h6>Unassigned</h6>";
                                }
                                echo "</div>";
                            } ?>
                        </div>
                    </div>

                <?php } ?>
            </div>

        </div>
        <script>
            $(document).ready(function () {
                $('#user-filter').on('change', function () {
                    var userId = $(this).val();
                    if (userId == 'all') {
                        //show all
                        $(".action").show();
                    } else {
                        $(".action").hide();
                        $(".user-" + userId).closest('.action').show();
                    }
                });
                $('#team-filter').on('change', function () {
                    var teamId = $(this).val();
                    var query;
                    if (teamId == 'all') {
                        //show all
                        query = ""
                    } else {
                        query = "?" + $.param({'teamId': teamId});
                    }
                    window.location.href = window.location.pathname + query;
                });
            });

            function allowDrop(ev) {
                ev.preventDefault();
            }

            function drag(ev) {
                ev.dataTransfer.setData("text", ev.target.id);
            }

            function drop(ev, status) {
                ev.preventDefault();
                var data = ev.dataTransfer.getData("text");

                $(ev.target).find(".actions").append($("#" + data));

                $.ajax({
                    url: "api/updatestatus.php",
                    type: "post",
                    data: {
                        actionId: data,
                        statusId: status
                    },
                    success: function (s) {
                        console.log(s);
                    }
                })

            }
        </script>

        <?php
    }
}