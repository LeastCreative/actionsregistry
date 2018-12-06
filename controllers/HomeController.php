<?php

/**
 * Created by PhpStorm.
 * User: evenl
 * Date: 12/6/2018
 * Time: 1:55 PM
 */

class HomeController extends Controller
{

    /**
     * shows the home page kan ban board
     */
    public function index()
    {
        //setup view model
        $model = new stdClass();
        $model->statuses = $this->getStatuses();
        $model->teams = $this->getTeams();
        $model->actionGroups = $this->getActionGroups($model->statuses);
        $model->users = $this->getUsers();
        $model->teamName = $this->getTeamName();
        $this->render_view($model);
    }


    private function getTeamName()
    {
        $db = $this->db;
        $teamId = $this->getTeamId();

        if (!empty($teamId) && $teamId != 'all') {
            $query = "SELECT name fROM teams where team_id = $teamId";
            $result = mysqli_query($db, $query);
            $row = $result->fetch_assoc();
            return $row['name'];
        } else {
            return null;
        }
    }


    private function getStatuses()
    {
        $db = $this->db;
        $query = "SELECT * fROM statuses";
        $statusResult = mysqli_query($db, $query);
        $statuses = [];
        while ($row = $statusResult->fetch_assoc()) {
            $statuses[$row['status_id']] = $row['description'];
        }
        return $statuses;
    }


    private function getActionGroups($statuses)
    {
        $db = $this->db;
        $where_clause = '';
        $teamId = $this->getTeamId();
        if (isset($teamId) && $teamId != 'all') {
            $where_clause = 'WHERE u.team_id = ' . $teamId;
        }

        //check config
        $expirationDays = null;
        if (!empty($_SESSION['config'])) {
            $config = $_SESSION['config'];
            if (!empty($config['max_action_age'])) {
                $expirationDays = $config['max_action_age'] + 0;
            }

        }


        $query = "SELECT
                    a.action_id,
                    a.name,
                    CONCAT(o.first_name, ' ', o.last_name) owner,
                    s.status_id,
                    s.description,
                    u.user_id,
                    u.first_name,
                    u.last_name,
                    CASE
                        WHEN a.created_date BETWEEN DATE_SUB(NOW(), INTERVAL $expirationDays DAY) AND NOW()
                          THEN 0
                        ELSE 1
                    END as expired
                  FROM actions a 
                    LEFT JOIN statuses s ON a.status_id = s.status_id 
                    LEFT JOIN assignments asgn ON a.action_id = asgn.action_id
                    LEFT JOIN users u ON asgn.user_id = u.user_id
                    LEFT JOIN users o ON a.owner_id = o.user_id
                  $where_clause
                  ORDER BY a.status_id";
        $result = $this->query($query);
        $actions = [];
        while ($row = $result->fetch_assoc()) {
            $actions[$row["action_id"]]["name"] = $row["name"];
            $actions[$row["action_id"]]["description"] = $row["description"];
            $actions[$row["action_id"]]["owner"] = $row["owner"];
            $actions[$row["action_id"]]["status_id"] = $row["status_id"];
            $actions[$row["action_id"]]["expired"] = $row["expired"] == '1' ? true : false;
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


    private
    function getTeams()
    {
        $db = $this->db;
        $query = "SELECT * fROM teams";
        $teamResult = mysqli_query($db, $query);
        $teams = [];
        while ($row = $teamResult->fetch_assoc()) {
            $teams[$row['team_id']] = $row['name'];
        }
        return $teams;
    }


    private
    function getUsers()
    {
        $db = $this->db;
        $where_clause = '';
        $teamId = $this->getTeamId();
        if ($teamId && $teamId != 'all') {
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


    /**
     * returns the requested team id
     *
     * @return null|string
     */
    private function getTeamId()
    {
        //request id takes priority
        if (isset($_GET['teamId'])) {
            return mysqli_real_escape_string($this->db, $_GET['teamId']);
        }

        if (isset($_SESSION['config'])) {
            // otherwise check config
            $config = $_SESSION['config'];
            if (isset($config['team_id'])) {
                return $config['team_id'];
            }
        }

        //no team id found
        return null;

    }

}