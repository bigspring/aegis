<?php $this->load->view('notifications') // display the notitification partial ?>
<div class="alert alert-warning">Access to this area is restricted.  Please log in to continue.</div>
<h1>Login</h1>
<?= form_open(''); ?>
<input type="text" value="" name="username" placeholder="Enter email Address" class="col-md-5"><br/>
<input type="password" value="" name="password" placeholder="Enter password" class="col-md-5"><br/>
<?= form_submit(array('name' => 'submit', 'id' => 'submit', 'class' => 'btn btn-default', 'value' => 'Login')) ?>			
<?= form_close() ?>						