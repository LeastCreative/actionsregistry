<style>
    #statuses {
        clear: both;
        width: 100%;
        height: 100%;
        box-sizing: padding-box;
        border-bottom: #e2e6ea solid 15px;
        border-right: #e2e6ea solid 15px;
    }

    .lane h3 {
        white-space: nowrap;
    }

    .lane {
        background-color: #ccc;
        width: 20%;
        height: 600px;
        float: left;
        padding: 10px;
        border-top: #e2e6ea solid 15px;
        border-left: #e2e6ea solid 15px;
        overflow-y: scroll;
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
        $actionGroups = getActionGroups($db, $statuses);
        $users = getUsers($db);

        ?>
        <h1 style="display: inline-block;">Board</h1>
        <form class="form-inline float-right" style="margin-top: 10px;">
            <div class="form-group">
                <label for="user-filter"><b>Show items for:&nbsp;</b></label>
                <select class="form-control" id="user-filter">
                    <option value='all'>All</option>
                    <?php foreach ($users as $userId => $userName) {
                        echo "<option value='$userId'>$userName</option>";
                    } ?>
                </select>
            </div>
        </form>
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
            <div style="clear: left"></div>
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


function getUsers($db)
{
    $query = "SELECT
                u.user_id,
                CONCAT(u.last_name, ' ', u.first_name) full_name
              FROM users u 
              ORDER BY u.last_name";
    $result = mysqli_query($db, $query);
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[$row['user_id']] = $row['full_name'];
    }
    return $users;
}












