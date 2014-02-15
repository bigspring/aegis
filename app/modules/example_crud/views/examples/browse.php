<a href="<?= site_url('example_crud/examples/add'); ?>" class="btn btn-primary pull-right">Add Example</a>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Created</th>
            <th>Modified</th>
            <th></th>
        </tr>
    </thead>
    <?php foreach($main AS $example) { ?>
        <tr>
            <td><?= $example->id ?></td>
            <td><a href="<?= site_url('example_crud/examples/view/' . $example->id); ?>"><?= $example->name ?></a></td>
            <td><?= format_date($example->created); ?></td>
            <td><?= format_date($example->modified); ?></td>
            <td><a href="<?= site_url('example_crud/examples/edit/' . $example->id); ?>">Edit</a> | <a href="<?= site_url('example_crud/examples/delete/' . $example->id); ?>">Delete</a></td>
        </tr>
    <? } ?>
</table>