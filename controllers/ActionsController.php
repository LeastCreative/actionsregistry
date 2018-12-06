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

        //get sources
        $query = "SELECT * fROM ceremonies";
        $sourceResult = $this->query($query);
        $sources = [];
        while ($source = $sourceResult->fetch_assoc()) {
            $sources[$source['ceremony_id']] = $source['name'];
        }

        //setup view model
        $model = new stdClass();
        $model->users = $users;
        $model->statuses = $statuses;
        $model->sources = $sources;
        $this->render_view($model);
    }

    public function create()
    {
        $this->requireFields(
            'name',
            'owner_id',
            'status_id'
        );

        $name = $this->escape('name');
        $ownerId = $this->escape('owner_id');
        $statusId = $this->escape('status_id');
        $sourceId = $this->escape('source_id');


        $sql = "INSERT INTO actions(name, owner_id, status_id, source_id, created_date, updated_date)
                    VALUES  ('$name', $ownerId, $statusId, $sourceId, NOW(), NOW())";

        $this->query($sql);
        $this->redirect('index');
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
                    a.source_id,
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
            $action["action_id"] = $row["action_id"];
            $action["name"] = $row["name"];
            $action["status_id"] = $row["status_id"];
            $action["owner_id"] = $row["owner_id"];
            $action["source_id"] = $row["source_id"];
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

        //get sources
        $query = "SELECT * fROM ceremonies";
        $sourceResult = $this->query($query);
        $sources = [];
        while ($source = $sourceResult->fetch_assoc()) {
            $sources[$source['ceremony_id']] = $source['name'];
        }

        //setup view model
        $model = new stdClass();
        $model->id = $id;
        $model->action = $action;
        $model->users = $users;
        $model->statuses = $statuses;
        $model->sources = $sources;
        $this->render_view($model);
    }

    public function update($id)
    {
        $this->requireFields(
            'name',
            'owner_id',
            'status_id'
        );

        $name = $this->escape('name');
        $ownerId = $this->escape('owner_id');
        $statusId = $this->escape('status_id');
        $sourceId = $this->escape('source_id');


        $sql = "UPDATE actions 
                    SET 
                      name = '$name',
                      owner_id = $ownerId,
                      status_id = $statusId,
                      status_id = $sourceId,
                      updated_date = NOW()
                    WHERE action_id = $id";
        $this->query($sql);
        $this->redirect('..');

    }
}