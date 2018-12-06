<?php
/**
 * Created by PhpStorm.
 * User: evenl
 * Date: 12/6/2018
 * Time: 12:07 PM
 */

class EditView
{
    function render($model)
    {
        $status = $model->status;
        ?>
        <form action="statuses/update/<?= $status->status_id ?>" method="post">
            <div class="form-group">
                <label>Description</label>
                <input name="description" type="text" class="form-control" value="<?= $status->description ?>">
            </div>
            <input type="submit">
        </form>

    <?php }
}