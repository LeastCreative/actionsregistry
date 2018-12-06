<?php
/**
 * Created by PhpStorm.
 * User: evenl
 * Date: 12/6/2018
 * Time: 1:31 PM
 */

class ActionsController extends Controller
{
    public function index()
    {
        $query = "SELECT
                    a.action_id,
                    a.name,
                    CONCAT(o.first_name, ' ', o.last_name) owner,
                    s.description,
                    u.user_id,
                    u.first_name,
                    u.last_name,
                    DATE_FORMAT(a.created_date , '%m/%d/%Y') as created_date,
                    DATE_FORMAT(a.updated_date, '%m/%d/%Y') as updated_date
                  FROM actions a 
                    LEFT JOIN statuses s ON a.status_id = s.status_id 
                    LEFT JOIN assignments asgn ON a.action_id = asgn.action_id
                    LEFT JOIN users u ON asgn.user_id = u.user_id
                    LEFT JOIN users o ON a.owner_id = o.user_id
                  ORDER BY a.created_date, a.action_id";
        $result = $this->query($query);
        $actions = [];
        while ($row = $result->fetch_assoc()) {
            $actions[$row["action_id"]]["name"] = $row["name"];
            $actions[$row["action_id"]]["description"] = $row["description"];
            $actions[$row["action_id"]]["owner"] = $row["owner"];
            $actions[$row["action_id"]]["created_date"] = $row["created_date"];
            $actions[$row["action_id"]]["updated_date"] = $row["updated_date"];
            if (isset($row['user_id'])) {
                $actions[$row["action_id"]]["assignments"][$row["user_id"]] = array(
                    "name" => $row['first_name'] . ' ' . $row['last_name'],
                    "user_id" => $row["user_id"],
                );
            }
        }

        //setup view model
        $model = new stdClass();
        $model->actions = $actions;
        $this->render_view($model);

    }

    public function add()
    {
        $this->render_view();
    }

    public function create()
    {
        if (isset($_POST['name'])) {
            $name = $this->escape('name');
            $sql = "INSERT INTO actions(name, status_id, created_date, updated_date) VALUES ('$name', 1, NOW(), NOW())";
            $this->query($sql);

            $this->redirect('index');
        } else {
            echo 'error';
        }
    }

    public function archive($id)
    {
        $sql = "INSERT INTO actions_archive SELECT * FROM actions WHERE action_id = $id";
        if ($this->query($sql) != false) {
            $sql = "DELETE FROM actions WHERE action_id = $id";
            $this->query($sql);
        }

        $this->redirect('..');
    }

    public function delete($id)
    {
        $sql = "DELETE FROM actions WHERE action_id = $id";
        echo $this->query($sql);
        $this->redirect('..');
    }

    public function edit($id)
    {
        $query = "SELECT
                    a.action_id,
                    a.name,
                    a.owner_id,
                    s.status_id,
                    u.user_id,
                    u.first_name + ' ' + u.last_name as assigned_name
                  FROM actions a 
                    LEFT JOIN statuses s ON a.status_id = s.status_id 
                    LEFT JOIN assignments asgn ON a.action_id = asgn.action_id
                    LEFT JOIN users u ON asgn.user_id = u.user_id
                  WHERE a.action_id = $id";
        $result = $this->query($query);
        $action = [];
        while ($row = $result->fetch_assoc()) {
            $action["name"] = $row["name"];
            $action["status_id"] = $row["status_id"];
            $action["owner_id"] = $row["owner_id"];
            if (isset($row['user_id'])) {
                $action["assignments"][$row["user_id"]] = array(
                    "name" => $row['assigned_name'],
                    "user_id" => $row["user_id"],
                );
            }
        }

        //get users
        $query = "SELECT * fROM users ORDER BY last_name";
        $userResult = $this->query($query);
        $users = [];
        while ($user = $userResult->fetch_assoc()) {
            $users[$user['user_id']] = $user['last_name'] . ', ' . $user['first_name'];
        }

        //get statuses
        $query = "SELECT * fROM statuses";
        $statusResult = $this->query($query);
        $statuses = [];
        while ($status = $statusResult->fetch_assoc()) {
            $statuses[$status['status_id']] = $status['description'];
        }

        //setup view model
        $model = new stdClass();
        $model->id = $id;
        $model->action = $action;
        $model->users = $users;
        $model->statuses = $statuses;
        $this->render_view($model);
    }

    public function update($id)
    {
        if (isset($_POST['name']) && isset($_POST['statusid'])) {
            $name = $this->escape('name');
            $statusid = $this->escape('statusid');
            $sql = "UPDATE actions 
                    SET 
                      name = '$name',
                      status_id = $statusid,
                      updated_date = NOW()
                    WHERE action_id = '$id'";
            $this->query($sql);
            $this->redirect('index');
        } else {
            echo 'error';
        }
    }
}