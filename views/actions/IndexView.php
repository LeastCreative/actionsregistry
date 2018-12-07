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
        $actions = $model->actions;
        ?>

        <h1>List</h1>
        <table id="actions" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>Name</th>
                <th>Owner</th>
                <th>Assigned To</th>
                <th>Status</th>
                <th>Created</th>
                <th>Updated</th>
                <th style="white-space: nowrap; width: 0"></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($actions as $actionId => $action): ?>
                <tr>
                    <td><?= $action['name'] ?></td>
                    <td><?= $action['owner'] ?></td>
                    <td>
                        <?php
                        if (isset($action['assignments']))
                            echo implode(', ', array_map(function ($user) {
                                return $user['name'];
                            }, $action['assignments']));
                        ?>
                    </td>
                    <td><?= $action['description'] ?></td>
                    <td><?= $action['created_date'] ?></td>
                    <td><?= $action['updated_date'] ?></td>
                    <td>
                        <a class="btn btn-sm btn-secondary" href="actions/edit/<?= $actionId ?>">Edit</a>
                        <a class="btn btn-sm btn-warning" href="actions/archive/<?= $actionId ?>">Archive</a>
                        <a class="btn btn-sm btn-danger" href="actions/delete/<?= $actionId ?>">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
            <tr>
                <th>Name</th>
                <th>Owner</th>
                <th>Assigned To</th>
                <th>Status</th>
                <th></th>
            </tr>
            </tfoot>
        </table>
        <script>
            $(document).ready(function () {

                $('#actions tfoot th').each(function () {
                    var title = $(this).text();
                    if (title) {
                        $(this).html('<input type="text" placeholder="Search ' + title + '" />');
                    }
                });

                var table = $("#actions").DataTable({
                    order: [],
                    dom: 'Bfrtip',
                    buttons: [
                        'pageLength',
                        {
                            extend: 'copy',
                            exportOptions: {
                                columns: 'th:not(:last-child)'
                            }
                        }, {
                            extend: 'csv',
                            exportOptions: {
                                columns: 'th:not(:last-child)'
                            }
                        },
                        {
                            extend: 'excel',
                            exportOptions: {
                                columns: 'th:not(:last-child)'
                            }
                        },
                        {
                            extend: 'pdf',
                            exportOptions: {
                                columns: 'th:not(:last-child)'
                            }
                        },
                        {
                            text: 'Add',
                            className: "btn-success",
                            action: function () {
                                window.location.href = "actions/add"
                            },
                            init: function (api, node) {
                                $(node).removeClass('btn-default')
                            }
                        }]
                });


            });
        </script>

        <?php
    }
}