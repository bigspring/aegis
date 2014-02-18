<?= form_open('', array('class' => 'form')); ?>
<?= form_input('name', $main->name, 'Name', 'class="form-control required"', $main->error->name) ?>
<?= form_input('another_field', $main->another_field, 'Another Field', 'class="form-control required"', $main->error->another_field) ?>
<div class="form-group form-buttons">
    <?= form_button(array('name' => 'submit', 'type' => 'submit', 'class' => 'btn btn-success'), get_submit_text('Example')) ?>
    <a href="<?= site_url('example_crud/examples/browse') ?>" class="btn">Cancel</a>
</div>
<?= form_close(); ?>