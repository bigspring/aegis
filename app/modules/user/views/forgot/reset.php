<div class="header-wrap">
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span9">
                <div class="page-header">
                    <h1>Reset Password</h1>
                </div> <!-- /.page-header -->
            </div> <!-- /.span12 -->
        </div> <!-- /.row -->
    </div>
</div>

<div class="container-fluid">
    <div class="row-fluid">
        <div class="span6 offset3">
        <?php $this->load->view('notifications') // display the notitification partial ?>
        <div id="login">            
            <div class="well login-well">
                <?= form_open(''); ?>
                <?= form_password('password', '', 'Password*', 'class="span5" placeholder="Enter a password" data-validation-required-message="Please enter your password" required', $user->error->password) ?>
                <?= form_password('confirmpassword', '', 'Confirm Password*', 'class="span5" placeholder="Re-enter password" data-validation-required-message="Please enter your first name" required data-validation-match-match="password"', $user->error->confirmpassword) ?>                
                <?= form_submit(array('name' => 'submit', 'id' => 'submit', 'class' => 'btn btn-large btn-success pull-right', 'value' => 'Reset')) ?>
                <?= form_close() ?>   
            </div><!-- //well -->
                
        </div>
    </div><!--/span-->
  </div><!--/row-->        
</div><!--/container-->