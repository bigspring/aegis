<?= form_open('', array('class' => 'form')); ?>
<?= form_input('email', $main->email, 'Email', 'class="form-control required email"', $main->error->email) ?>
<?= form_input('firstname', $main->firstname, 'First Name', 'class="form-control required"', $main->error->firstname) ?>
<?= form_input('lastname', $main->lastname, 'Last Name', 'class="form-control required"', $main->error->lastname) ?>
<?= form_dropdown('group_id', $groups, $main->group_id, 'User Group', 'class="form-control required"', $main->error->group_id, '', true) ?>
<?= form_radio('enabled', get_yes_no_options(), $main->enabled ? $main->enabled : 1, 'Enabled?', 'class="required"') ?>        
<?= form_password('password', '', 'Password', 'class="form-control required"', $main->error->password) ?>
<?= form_password('confirmpassword', '', 'Confirm Password', 'class="form-control required"', $main->error->confirmpassword) ?>
<?= !$main->createdby ? form_hidden('createdby', $this->session->userdata('id')) : '' // only load the created form element if the user hasn't been created ?>
<?= form_hidden('modifiedby', $this->session->userdata('id')) ?>
<hr />
<!-- form buttons -->
<div class="form-group form-buttons">
    <?= form_button(array('name' => 'submit', 'type' => 'submit', 'class' => 'btn btn-success', 'value' =>'Save'), 'Save') ?>
    <a href="<?= site_url('user/users/browse') ?>" class="btn btn-link">Cancel</a>
</div>
<?= form_close() ?>