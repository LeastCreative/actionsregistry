<?php
/**
 * Created by PhpStorm.
 * User: evenl
 * Date: 12/6/2018
 * Time: 12:05 PM
 */

class AddView
{
    function render()
    {
        ?>
        <form action="statuses/create" method="post">
            <div class="form-group">
                <label>Description</label>
                <input name="description" type="text" class="form-control">
            </div>
            <input type="submit">
        </form>
    <?php }
}