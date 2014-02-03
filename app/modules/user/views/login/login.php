<?php $this->load->view('notifications') // display the notitification partial ?>
<div class="alert alert-warning">Access to this area is restricted.  Please log in to continue.</div>
<h1>Login</h1>
<?= form_open('', array('class' => 'form form-horizontal')); ?>

 <div class="form-group">
	 <label class="col-sm-2 control-label" for="username">Username</label>
	 <div class="col-sm-10">
	 	<input class="form-control" type="text" value="" name="username" placeholder="Enter email Address"/>
	 </div>
 </div>
 <div class="form-group">
	 <label class="col-sm-2 control-label" for="password">Password</label>
	 <div class="col-sm-10">
	 	<input class="form-control" type="password" value="" name="password" placeholder="Enter password"/>
	 </div>
 </div>
 
 
 
 <?= form_submit(array('name' => 'submit', 'id' => 'submit', 'class' => 'btn btn-default', 'value' => 'Login')) ?>			
<?= form_close() ?>						