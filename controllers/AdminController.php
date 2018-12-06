<?php
/**
 * Created by PhpStorm.
 * User: evenl
 * Date: 12/6/2018
 * Time: 1:31 PM
 */

class AdminController extends Controller
{
    /**
     * shows the admin import page
     */
    public function import()
    {
        $this->render_view();
    }

    /**
     * shows the admin export page
     */
    public function export()
    {
        $this->render_view();
    }

    /**
     * shows the admin configuration page
     */
    public function config()
    {

        $db = $this->db;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_POST['team_id']))
                if (!empty($_POST['team_id']))
                    $config = [];
            $config['team_id'] = $_POST['team_id'];
            $_SESSION['config'] = $config;
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


        //setup view model
        $model = new stdClass();
        $model->teams = $teams;
        $model->config = $config;
        $this->render_view($model);
    }

    /**
     * shows the admin reports page
     */
    public function reports()
    {

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




        $this->render_view();
    }

}