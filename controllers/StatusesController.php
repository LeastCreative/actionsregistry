<?php
/**
 * Created by PhpStorm.
 * User: evenl
 * Date: 12/6/2018
 * Time: 12:03 PM
 */

class StatusesController extends Controller
{

    /**
     * shows the status index page
     */
    public function index()
    {
        $query = "SELECT * 
                  FROM statuses";
        $result = $this->query($query);

        $statuses = [];
        while ($row = $result->fetch_object()) {
            $statuses[] = $row;
        }

        //setup view model
        $model = new stdClass();
        $model->statuses = $statuses;
        $this->render_view($model);
    }

    /**
     * shows the add new status page
     */
    public function add()
    {
        $this->render_view();
    }

    /**
     * creates a new status and redirects to the index page on success
     */
    public function create()
    {
        $this->requireFields('description');

        $description = $this->escape('description');

        $sql = "INSERT INTO statuses(description) 
                VALUES ('$description')";

        $this->query($sql);
        $this->redirect('index');

    }

    /**
     * shows the edit status page
     * @param $id integer       the id of the status to edit
     */
    public function edit($id)
    {
        $query = "SELECT * 
                  FROM statuses 
                  WHERE status_id = '$id'";
        $result = $this->query($query);
        $status = $result->fetch_object();

        //setup view model
        $model = new stdClass();
        $model->status = $status;
        $this->render_view($model);
    }

    /**
     * updates an existing status and redirects to the index page on success
     * @param $id integer       the id of the status being updated
     */
    public function update($id)
    {
        $this->requireFields(
            'description'
        );

        $description = $this->escape('description');

        $sql = "UPDATE statuses 
                    SET description = '$description' 
                    WHERE status_id = $id";
        $this->query($sql);
        $this->redirect('../index');
    }

}