<?php $this->load->view('notifications') // display the notitification partial ?>
<h2>Reset your password</h2>
<p>Lost your password? Enter your email address into the form below to reset it.</p>
<p>We'll send you an email with a link to reset it and create new one.</p>                
<?= form_open('') ?>
<input type="text" value="" name="email" placeholder="Email Address" class="col-md-5"><br/>
<?= form_submit(array('name' => 'submit', 'id' => 'submit', 'class' => 'btn btn-success', 'value' => 'Reset my password')) ?>&nbsp;&nbsp;or <a href="<?= site_url('user/login')?>">Click here to log in</a>
<?= form_close() ?>