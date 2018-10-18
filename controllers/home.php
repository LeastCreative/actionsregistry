<style>
    #statuses {
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

        ?>

        <div id="statuses">
            <?php foreach ($statuses as $statusid => $status) { ?>
                <div class="lane" ondrop="drop(event, <?= $statusid ?>)" ondragover="allowDrop(event)">
                    <h3><?= $status ?></h3>
                    <div class="actions">
                        <?php foreach ($actionGroups[$statusid] as $actionId => $action) {
                            echo "<div id='$actionId' class='action' draggable='true' ondragstart='drag(event)'>$action</div>";
                        } ?>
                    </div>
                </div>

            <?php } ?>
            <div style="clear: left"></div>
        </div>

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
        $statuses[$row['id']] = $row['description'];
    }
    return $statuses;
}

function getActionGroups($db, $statuses)
{
    $actionGroups = [];
    foreach ($statuses as $id => $status) {
        $actionGroups[$id] = [];
    }

    $query = "SELECT * fROM actions";
    $actionResult = mysqli_query($db, $query);
    while ($row = $actionResult->fetch_assoc()) {
        $statusId = $row['status_id'] ?: 1;
        $actionGroups[$statusId][$row['id']] = $row['name'];
    }
    return $actionGroups;
}












