<div class="container-fluid">
  	<div class="row-fluid">
  		<div class="span12">
		<?php $this->load->view('notifications') // display the notitification partial ?>
		<div class="page-header">
                <h1>Login</h1>
            </div>
		<div id="login">			
				<div>
					<h4>Access to this area is restricted.  Please log in to continue.</h4>
				</div>
				<?= form_open(''); ?>
				<input type="text" value="" name="username" placeholder="Email Address" class="span5"><br/>
				<input type="password" value="" name="password" placeholder="Password" class="span5"><br/>
				
				<?= form_submit(array('name' => 'submit', 'id' => 'submit', 'class' => 'btn btn-large btn-success pull-right', 'value' => 'Login')) ?>
				
				<?= form_close() ?>
								
		</div> <!--/#login-->
    </div><!--/span-->
  </div><!--/row-->        
</div><!--/container-->