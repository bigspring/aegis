<?php $this->load->view('notifications') // display the notitification partial ?>
<h4>Access to this area is restricted.  Please log in to continue.</h4>

<h1>Login</h1>
<div id="login">			

<hr/>
<?= form_open(''); ?>
<input type="text" value="" name="username" placeholder="Enter email Address" class="col-md-5"><br/>
<input type="password" value="" name="password" placeholder="Enter password" class="col-md-5"><br/>
<?= form_submit(array('name' => 'submit', 'id' => 'submit', 'class' => 'btn btn-default', 'value' => 'Login')) ?>			
<?= form_close() ?>						
</div> <!--/#login-->