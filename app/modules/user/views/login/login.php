<div class="container">
  	<div class="row">
  		<div class="col-md-12">
		<?php $this->load->view('notifications') // display the notitification partial ?>
		<div class="page-header">
                <h1>Login</h1>
            </div>
		<div id="login">			
				<div>
					<h4>Access to this area is restricted.  Please log in to continue.</h4>
				</div>
				<?= form_open(''); ?>
				
				<div class="form-group">
					<input type="text" value="" name="username" placeholder="Enter email Address" class="col-md-5"><br/>
				</div>
				
				<div class="form-group">
					<input type="password" value="" name="password" placeholder="Enter password" class="col-md-5"><br/>
				</div>
				
				<?= form_submit(array('name' => 'submit', 'id' => 'submit', 'class' => 'btn btn-large btn-success pull-right', 'value' => 'Login')) ?>
				
				<?= form_close() ?>
								
		</div> <!--/#login-->
    </div><!--/span-->
  </div><!--/row-->        
</div><!--/container-->