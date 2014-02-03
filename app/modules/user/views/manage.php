<h2>Your Account</h2>
<h3>Your Profile Picture</h3>
<?= form_open('', array('class'=>'form user-details')); ?>
<?php // user account details ?>
<?= form_input('firstname', $user->firstname, 'First Name*', 'class="input-xlarge" placeholder="Enter your first name"', $user->error->firstname) ?> 
<?= form_input('lastname', $user->lastname, 'Last Name*', 'class="input-xlarge" placeholder="Enter your last name"', $user->error->lastname) ?>
<?= form_email('email', $user->email,  'Email*', 'class="input-medium" placeholder="Enter your email"', $user->error->email) ?>
<? $exist = ($user->password != '' ? ' exists' : '') ?>
<?= form_password('password', '', 'Password*', 'class="input-medium'.$exist.' userpassword inline-valid" placeholder="Enter a password" id="password"', $user->error->password) ?>
<?= form_password('confirmpassword', '', 'Confirm Password*', 'class="input-medium matchpassword" placeholder="Re-enter password" id="confirmpassword"', $user->error->confirmpassword, '', false) ?>
</fieldset>
<?php // address details ?>
<?= form_submit(array('name' => 'submit', 'id' => 'submit', 'class' => 'btn btn-default', 'value' =>'Save')) ?>
<?= form_close() ?>
<? $this->load->view('candidate/jstemplates/avatar') ?>