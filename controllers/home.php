<style>

    #status-content {
        display: block;
        width: 100%;
        overflow-x: scroll;
    }

    #statuses {
        clear: both;
        height: 100%;
        box-sizing: padding-box;
        display: flex;
    }

    .lane h3 {
        white-space: nowrap;
    }

    .lane {
        min-width: 300px;
        background-color: #ccc;
        height: 750px;
        float: left;
        padding: 10px;
        border-top: #e2e6ea solid 15px;
        border-left: #e2e6ea solid 15px;
        border-bottom: #e2e6ea solid 15px;

        overflow-y: scroll;
    }

    div.lane:last-child {

        border-right: #e2e6ea solid 15px;

    }

    .actions {

    }

    .action {
        background-color: #e2e6ea;
        margin-bottom: 10px;
        padding: 5px;
        font-weight: bold;
    }
</style>
<script>
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

switch ($action) {
    /**
     * shows index of all status records
     */
    case 'index':

        $statuses = getStatuses($db);
        $teams = getTeams($db);
        $actionGroups = getActionGroups($db, $statuses);
        $users = getUsers($db);
        $teamName = getTeamName($db);

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
                        <?php foreach ($teams as $teamId => $teamName) {
                            $selected = isset($_GET['teamId']) && $_GET['teamId'] == $teamId ? 'selected' : '';
                            echo "<option value='$teamId' $selected>$teamName</option>";
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
        </script>
        <?php
        break;

    /**
     * action not found
     */
    default:
        echo 404;

}

function getTeamName($db)
{
    if (isset($_GET['teamId'])) {

        $teamId = mysqli_real_escape_string($db, $_GET['teamId']);

        $query = "SELECT name fROM teams where team_id = $teamId";
        $statusResult = mysqli_query($db, $query);
        $row = $statusResult->fetch_assoc();
        return $row['name'];
    }
    return null;
}

function getStatuses($db)
{
    $query = "SELECT * fROM statuses";
    $statusResult = mysqli_query($db, $query);
    $statuses = [];
    while ($row = $statusResult->fetch_assoc()) {
        $statuses[$row['status_id']] = $row['description'];
    }
    return $statuses;
}

function getActionGroups($db, $statuses)
{

    $where_clause = '';
    if (isset($_GET["teamId"])) {
        $teamId = mysqli_real_escape_string($db, $_GET['teamId']);
        $where_clause = 'WHERE u.team_id = ' . $teamId;
    }

    $query = "SELECT
                    a.action_id,
                    a.name,
                    CONCAT(o.first_name, ' ', o.last_name) owner,
                    s.status_id,
                    s.description,
                    u.user_id,
                    u.first_name,
                    u.last_name
                  FROM actions a 
                    LEFT JOIN statuses s ON a.status_id = s.status_id 
                    LEFT JOIN assignments asgn ON a.action_id = asgn.action_id
                    LEFT JOIN users u ON asgn.user_id = u.user_id
                    LEFT JOIN users o ON a.owner_id = o.user_id
                  $where_clause
                  ORDER BY a.status_id";
    $result = mysqli_query($db, $query);
    $actions = [];
    while ($row = $result->fetch_assoc()) {
        $actions[$row["action_id"]]["name"] = $row["name"];
        $actions[$row["action_id"]]["description"] = $row["description"];
        $actions[$row["action_id"]]["owner"] = $row["owner"];
        $actions[$row["action_id"]]["status_id"] = $row["status_id"];
        if (isset($row['user_id'])) {
            $actions[$row["action_id"]]["assignments"][$row["user_id"]] = array(
                "name" => $row['first_name'] . ' ' . $row['last_name'],
                "user_id" => $row["user_id"],
            );
        }
    }

    $actionGroups = [];
    foreach ($statuses as $status_id => $status) {
        $actionGroups[$status_id] = [];
    }
    foreach ($actions as $action_id => $action) {
        $actionGroups[$action['status_id']][$action_id] = $action;
    }
    return $actionGroups;
}


function getTeams($db)
{
    $query = "SELECT * fROM teams";
    $teamResult = mysqli_query($db, $query);
    $teams = [];
    while ($row = $teamResult->fetch_assoc()) {
        $teams[$row['team_id']] = $row['name'];
    }
    return $teams;
}


function getUsers($db)
{
    $where_clause = '';
    if (isset($_GET["teamId"])) {
        $teamId = mysqli_real_escape_string($db, $_GET['teamId']);
        $where_clause = 'WHERE u.team_id = ' . $teamId;
    }

    $query = "SELECT
                u.user_id,
                CONCAT(u.last_name, ' ', u.first_name) full_name
              FROM users u 
              $where_clause
              ORDER BY u.last_name";
    $result = mysqli_query($db, $query);
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[$row['user_id']] = $row['full_name'];
    }
    return $users;
}












