<?= form_open('', array('class' => 'form form-horizontal')); ?>
<?= form_input('email', $main->email, 'Email', 'class="input required email"', $main->error->email) ?>
<?= form_input('firstname', $main->firstname, 'First Name', 'class="input required"', $main->error->firstname) ?>
<?= form_input('lastname', $main->lastname, 'Last Name', 'class="input required"', $main->error->lastname) ?>
<?= form_dropdown('group_id', $groups, $main->group_id, 'User Group', 'class="chzn-select required"', $main->error->group_id, '', true) ?>
<?= form_radio('enabled', get_yes_no_options(), $main->enabled ? $main->enabled : 1, 'Enabled?', 'class="input required"') ?>        
<?= form_password('password', '', 'Password', 'class="input required"', $main->error->password) ?>
<?= form_password('confirmpassword', '', 'Confirm Password', 'class="input required"', $main->error->confirmpassword) ?>
<?= !$main->createdby ? form_hidden('createdby', $this->session->userdata('id')) : '' // only load the created form element if the user hasn't been created ?>
<?= form_hidden('modifiedby', $this->session->userdata('id')) ?>
<div class="form-actions">
    <?= form_button(array('name' => 'submit', 'type' => 'submit', 'class' => 'btn btn-warning', 'value' =>'Save'), 'Save') ?>
    <a href="<?= site_url('user/users/browse') ?>" class="btn btn-warning">Cancel</a>
</div>
<?= form_close() ?>