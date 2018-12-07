<?php
/**
 * Created by PhpStorm.
 * User: evenl
 * Date: 12/6/2018
 * Time: 12:05 PM
 */

class IndexView
{
    function render($model)
    {
        $statuses = $model->statuses;
        ?>
        <h1>Statuses</h1>
        <table id="statuses" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>Description</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($statuses as $status): ?>
                <tr>
                    <td><?= $status->description ?></td>
                    <td>
                        <a class="btn btn-sm btn-secondary edit" href="statuses/edit/<?= $status->status_id ?>">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <script>
            $(document).ready(function () {
                $("#statuses").DataTable({
                    order: [],
                    dom: 'Bfrtip',
                    buttons: [
                        'pageLength',
                        {
                            text: 'Add',
                            className: "btn-success",
                            action: function () {
                                window.location.href = "statuses/add"
                            },
                            init: function (api, node) {
                                $(node).removeClass('btn-default')
                            }
                        }
                    ]
                });
            });
        </script>
        <?php
    }
}