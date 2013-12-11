<div id="main">
    <div class="container">    
        <div id="content">
            <div class="row">
                <div class="col-md-12">
                    <div id="details-form">
                        <h2>Your Account</h2>
                        <div class="well">
                            <h3>Your Profile Picture</h3>
                            <?= form_open_multipart('', array('class'=>'form fileupload')); ?>
                                    <div class="row fileupload-buttonbar">
                                        <div class="col-md-3">
                                            <div class="files">
                                                <? if($user->avatar == '') { ?>
                                                    <div class="template-download in">
                                                        <div class="preview">
                                                            <img class="img-rounded default-profile" width="220" height="220" src="<?= get_avatar($user) ?>"/>
                                                        </div>
                                                    </div>
                                                <? } ?>                                        
                                            </div>
                                            <br/>
                                            <div class="btn btn-primary btn-block fileinput-button">
                                                <i class="icon-plus icon-white"></i>
                                                <span>Upload profile picture...</span>
                                                <input type="file" name="files">
                                            </div>                              
                                        </div> 
                                    </div> 
                                    <div class="fileupload-loading"></div>
                                    <?= form_hidden('id', $user->id) ?>
                                <?= form_close() ?>
                            </div>
                        <?= form_open('', array('class'=>'form user-details')); ?>
                            <?php // user account details ?>
                            <fieldset class="well">
                                <?= form_input('firstname', $user->firstname, 'First Name*', 'class="input-xlarge" placeholder="Enter your first name"', $user->error->firstname) ?>
                                <?= form_input('lastname', $user->lastname, 'Last Name*', 'class="input-xlarge" placeholder="Enter your last name"', $user->error->lastname) ?>
                                <?= form_email('email', $user->email,  'Email*', 'class="input-medium" placeholder="Enter your email"', $user->error->email) ?>
                                <? $exist = ($user->password != '' ? ' exists' : '') ?>
                                <?= form_password('password', '', 'Password*', 'class="input-medium'.$exist.' userpassword inline-valid" placeholder="Enter a password" id="password"', $user->error->password) ?>
                                <?= form_password('confirmpassword', '', 'Confirm Password*', 'class="input-medium matchpassword" placeholder="Re-enter password" id="confirmpassword"', $user->error->confirmpassword, '', false) ?>
                            </fieldset>
                            <?php // address details ?>
                            <div class="form-actions">
                                <?= form_submit(array('name' => 'submit', 'id' => 'submit', 'class' => 'btn btn-large btn-success pull-right', 'value' =>'Save')) ?>
                            </div> <!-- /form-actions -->
                        <?= form_close() ?>
                    </div> <!-- /.well -->
                </div>
            </div>  
        </div> <!-- /#content -->
    </div> <!-- /.container -->
</div> <!-- /#main -->
<? $this->load->view('candidate/jstemplates/avatar') ?>