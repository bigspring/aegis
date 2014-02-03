<header class="page-header" role="banner">
	<h1>Reset Password</h1>
</header>

<main class="password-reset-form" role="main">
	<?= form_open('', array('class' => 'form')); ?>	
	<?= form_password('password', '', 'Password*', 'class="form-control" placeholder="Enter a password" data-validation-required-message="Please enter your password" required', $user->error->password) ?>
	<?= form_password('confirmpassword', '', 'Confirm Password*', 'class="form-control" placeholder="Re-enter password" data-validation-required-message="Please enter your first name" required data-validation-match-match="password"', $user->error->confirmpassword) ?>                
	<hr />
	<?= form_submit(array('name' => 'submit', 'id' => 'submit', 'class' => 'btn btn-primary', 'value' => 'Reset password')) ?>
	<a href="<?= site_url('user/login')?>" class="btn btn-link">Cancel password reset</a>
	<?= form_close() ?>   
</main>