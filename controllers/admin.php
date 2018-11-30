<?php
session_start();
switch ($action) {
    /**
     * shows index of admin page
     */
    case 'import':
        echo '<h1>Admin - Import</h1>';
        break;
    case    'export':
        echo '<h1>Admin - Export</h1>';
        break;

    /**
     * shows config page
     */
    case    'config':

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(!empty($_POST['team_id']))
                if(!empty($_POST['team_id']))
                    $config = [];
                    $config['team_id'] = $_POST['team_id'];
                    $_SESSION['config'] = $config;
            break;
        }


        $config = [];
        if (!empty($_SESSION['config']))
            $config = $_SESSION['config'];



        $query = "SELECT
                    team_id,
                    name
                  FROM teams t
                  ORDER BY t.name";

        $result = mysqli_query($db, $query);

        $teams = [];
        while ($row = $result->fetch_assoc()) {
            $teams[$row['team_id']] = $row['name'];
        }

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


        break;

    /**
     * reports page
     */
    case      'reports':
        echo '<h1>Admin - Reports</h1>';


        $query = "SELECT
                    COUNT(1) as action_count,
                    CASE 
                      WHEN u.last_name IS NULL THEN 'Unassigned'
                      ELSE CONCAT(u.last_name, ', ', u.first_name)
                    END AS name
                  FROM actions a 
                    LEFT JOIN assignments asgn ON a.action_id = asgn.action_id
                    LEFT JOIN users u ON asgn.user_id = u.user_id
                  GROUP BY asgn.user_id
                  ORDER BY u.last_name";

        $result = mysqli_query($db, $query);

        $assignees = [];
        while ($row = $result->fetch_assoc()) {
            $assignee = [];
            $assignee[] = $row['name'];
            $assignee[] = intval($row['action_count']);
            $assignees[] = $assignee;
        }


        $query = "SELECT
                    COUNT(1) as action_count,
                    s.description
                  FROM actions a 
                    JOIN statuses s on a.status_id = s.status_id
                  GROUP BY a.status_id
                  ORDER BY s.status_id";

        $result = mysqli_query($db, $query);

        $statuses = [];
        while ($row = $result->fetch_assoc()) {
            $status = [];
            $status[] = $row['description'];
            $status[] = intval($row['action_count']);
            $statuses[] = $status;
        }

        ?>

        <div id="assigned-chart"></div>
        <div id="status-chart"></div>

        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">

            google.charts.load('current', {'packages': ['corechart']});

            google.charts.setOnLoadCallback(drawAssigneeChart);
            google.charts.setOnLoadCallback(drawStatusChart);

            function drawAssigneeChart() {

                // Create the data table.
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Name');
                data.addColumn('number', 'Actions');
                data.addRows(<?php echo json_encode($assignees)?>);

                // Set chart options
                var options = {
                    title: 'Actions by Assignee',
                    'height': 600
                };

                var chart = new google.visualization.BarChart(document.getElementById('assigned-chart'));
                chart.draw(data, options);
            }

            function drawStatusChart() {

                // Create the data table.
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Status');
                data.addColumn('number', 'Actions');
                data.addRows(<?php echo json_encode($statuses)?>);

                // Set chart options
                var options = {
                    title: 'Actions by Status',
                    'height': 600
                };

                var chart = new google.visualization.BarChart(document.getElementById('status-chart'));
                chart.draw(data, options);
            }
        </script>


        <?php
        break;
    default:
        echo 404;

}











