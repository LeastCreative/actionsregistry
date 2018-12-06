<?php

/**
 * Created by PhpStorm.
 * User: evenl
 * Date: 11/30/2018
 * Time: 11:39 PM
 */
class Controller
{
    protected $db;
    protected $name;
    protected $action;

    /**
     * Controller constructor.
     * @param $name string      the name of the controller
     * @param $action string    the name of the action
     */
    public function __construct($name, $action)
    {
        $this->name = strtolower($name);
        $this->action = strtolower($action);
        $this->db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
        $this->db->set_charset("utf8");
    }

    /**
     * Controller destructor
     *
     * close the db connection
     */
    function __destruct()
    {
        $this->db->close();
    }


    /**
     * @param $model mixed      the object to send to the view
     */
    protected function render_view($model = null)
    {
        $className = ucfirst(isset($name) ? $name : $this->action) . 'View';
        $fileName = "views/$this->name/$className.php";

        if (file_exists($fileName)) {
            require_once($fileName);
            $view = new $className;
            $view->render($model);
        } else {
            echo $fileName;
        }
    }

    /**
     * @param $url string       the url to redirect to
     */
    protected function redirect($url)
    {
        header('Location: ' . $url);
        die();
    }

    /**
     * @param $key string       the form post key
     * @return string           the escaped value
     */
    protected function escape($key)
    {
        return mysqli_real_escape_string($this->db, $_POST[$key]);
    }


    /**
     * assert required fields exist, return 400 if not
     */
    protected function requireFields()
    {
        $fields = func_get_args();
        foreach ($fields as $field) {
            if (!isset($_POST[$field])) {
                http_response_code(400);
                echo "'$field' is required";
                die();
            }
        }
    }

    /**
     * returns the result of the input query
     * shows 500 error on failure
     *
     * @param $query string     the query to run
     * @return mysqli_result    the query result
     */
    protected function query($query)
    {
        $result = mysqli_query($this->db, $query);
        if ($result != false)
        {
            return $result;
        }
        else{
            http_response_code(500);
            echo 'database error: ' . "'$query'";
            die();
        }
    }

}

class ControllerFactory
{
    /**
     * @param $name string      name of the requested controller
     * @param $action string    name of the requested action
     * @return Controller       an instance of the controller found
     */
    public function get_controller($name, $action)
    {
        $className = ucfirst(strtolower($name)) . 'Controller';
        $fileName = "controllers/$className.php";
        if (file_exists($fileName)) {
            require_once($fileName);
            return new $className($name, $action);
        } else {
            return null;
        }
    }
}