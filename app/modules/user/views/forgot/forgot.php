<header class="page-header" role="banner">
	<h1>Password Recovery</h1>
	<p class="lead">Enter your email address below to reset your password.</p>
</header>

<main class="password-reset" role="main">
<?= form_open('', array('class' => 'form')); ?>	
	<input type="text" value="" name="email" placeholder="Email Address" class="form-control"><br/>
	<?= form_submit(array('name' => 'submit', 'id' => 'submit', 'class' => 'btn btn-primary', 'value' => 'Reset my password')) ?> <a class="btn btn-link" href="<?= site_url('user/login')?>">Already have a login?</a>
<?= form_close() ?>
</main>