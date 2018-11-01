<?php
/**
 * Created by PhpStorm.
 * User: evenl
 * Date: 10/25/2018
 * Time: 6:54 PM
 */

class ActionRepository implements iRepository
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAll()
    {

        $query = "SELECT
                    a.action_id,
                    a.name,
                    CONCAT(o.first_name, ' ', o.last_name) owner,
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

        $result = mysqli_query($this->db, $query);

        $actions = [];
        while ($row = $result->fetch_assoc()) {
            $action = new Action();
            $action->actionId = $row['action_id'];
            $action->name = $row['name'];
            //$action->owner = $row['action_id'];
            $action->statusId = $row['action_id'];


            //$actions[$id]["name"] = $row["name"];
            $actions[$row["action_id"]]["description"] = $row["description"];
            $actions[$row["action_id"]]["owner"] = $row["owner"];
            if (isset($row['user_id'])) {
                $actions[$row["action_id"]]["assignments"][$row["user_id"]] = array(
                    "name" => $row['first_name'] . ' ' . $row['last_name'],
                    "user_id" => $row["user_id"],
                );
            }
        }
    }

    public function add(Action $action)
    {

    }

}

class Action
{
    public $actionId;
    public $name;
    public $ownerId;
    public $statusId;
    public $assignments;
}

class Assignment
{
    public $name;
    public $userId;
}