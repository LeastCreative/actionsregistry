<?php
/**
 * Created by PhpStorm.
 * User: evenl
 * Date: 12/1/2018
 * Time: 12:34 AM
 */

class UsersController extends Controller
{
    /**
     * shows the user index page
     */
    function index()
    {
        $model = new stdClass();
        $query = "SELECT *, t.name as team_name fROM users u LEFT JOIN teams t ON u.team_id = t.team_id";
        $result = $this->query($query);

        $users = [];
        while ($row = $result->fetch_object()) {
            $users[$row->user_id] = $row;
        }

        //setup view model
        $model->users = $users;
        $this->render_view($model);
    }

    /**
     * shows the edit user page
     *
     * @param $id integer        the id of the user to edit
     */
    function edit($id)
    {
        $query = "SELECT * FROM users where user_id = '$id'";
        $result = mysqli_query($this->db, $query);
        $user = $result->fetch_object();

        $query = "SELECT * FROM teams";
        $teamResult = $this->query($query);
        $teams = [];
        while ($team = $teamResult->fetch_object()) {
            $teams[$team->team_id] = $team->name;
        }

        //setup view model
        $model = new stdClass();
        $model->teams = $teams;
        $model->user = $user;
        $this->render_view($model);
    }

    /**
     * updates a user and redirects to the index page on success
     *
     * @param $id integer       the id of the user to update
     */
    function update($id)
    {
        $this->requireFields(
            'first_name',
            'last_name',
            'team_id'
        );

        $first_name = $this->escape('first_name');
        $last_name = $this->escape('last_name');
        $team_id = $this->escape('team_id');

        $sql = "UPDATE users SET 
                      first_name = '$first_name', 
                      last_name = '$last_name', 
                      team_id = $team_id
                    WHERE user_id = $id";

        $this->query($sql);
        $this->redirect('../index');
    }
}