<?php
/**
 * Created by PhpStorm.
 * User: evenl
 * Date: 12/6/2018
 * Time: 2:08 PM
 */

class ReportsView
{
    function render($model)
    {
        ?>
        <h1>Admin - Reports</h1>
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
    }
}