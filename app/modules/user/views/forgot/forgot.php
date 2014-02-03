<div class="row">
	<div class="col-sm-6 col-sm-offset-3">

		<header class="page-header">
			<h1>Password Recovery</h1>
			<p class="lead">Enter your email address below to reset your password.</p>
		</header>
	
		<section class="password-reset">
		<?= form_open('', array('class' => 'form')); ?>	
			<input type="text" value="" name="email" placeholder="Email Address" class="form-control"><br/>
			<?= form_submit(array('name' => 'submit', 'id' => 'submit', 'class' => 'btn btn-primary', 'value' => 'Reset my password')) ?> <a class="btn btn-link" href="<?= site_url('user/login')?>">Already have a login?</a>
		<?= form_close() ?>
		</section>

	</div>
</div>