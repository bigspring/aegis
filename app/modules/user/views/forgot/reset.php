<h1>Reset Password</h1>
<?php $this->load->view('notifications') // display the notitification partial ?>
<?= form_open(''); ?>
<?= form_password('password', '', 'Password*', 'class="col-md-5" placeholder="Enter a password" data-validation-required-message="Please enter your password" required', $user->error->password) ?>
<?= form_password('confirmpassword', '', 'Confirm Password*', 'class="col-md-5" placeholder="Re-enter password" data-validation-required-message="Please enter your first name" required data-validation-match-match="password"', $user->error->confirmpassword) ?>                
<?= form_submit(array('name' => 'submit', 'id' => 'submit', 'class' => 'btn btn-large btn-success pull-right', 'value' => 'Reset')) ?>
<?= form_close() ?>   