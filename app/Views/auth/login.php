<?= $this->extend($config->viewLayout) ?>


<?= $this->section('main') ?>
<div class="login-logo"></div>
<div class="login-body">
	<div class="login-title"><strong>Welcome</strong>, Please login</div>
	<?= view('\App\Views\auth\_message_block') ?>
	<form action="<?= route_to('login') ?>" class="form-horizontal" method="post">
		<?= csrf_field() ?>

		<?php if ($config->validFields === ['email']) : ?>
			<div class="form-group">
				<div class="col-md-12">
					<input type="email" class="form-control <?php if (session('errors.login')) : ?>is-invalid<?php endif ?>" name="login" placeholder="<?= lang('Auth.email') ?>">
				</div>
				<div class="invalid-feedback">
					<?= session('errors.login') ?>
				</div>
			</div>
		<?php else : ?>
			<div class="form-group">
				<div class="col-md-12">
					<input type="text" class="form-control <?php if (session('errors.login')) : ?>is-invalid<?php endif ?>" name="login" placeholder="<?= lang('Auth.emailOrUsername') ?>">
				</div>

				<div class="invalid-feedback">
					<?= session('errors.login') ?>
				</div>
			</div>
		<?php endif; ?>
		<div class="form-group">
			<div class="col-md-12">
				<input type="password" name="password" class="form-control  <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>" placeholder="<?= lang('Auth.password') ?>">
			</div>
			<div class="invalid-feedback">
				<?= session('errors.password') ?>
			</div>
		</div>
		<?php if ($config->allowRemembering) : ?>
			<div class="form-check">
				<label class="form-check-label">
					<input type="checkbox" name="remember" class="form-check-input" <?php if (old('remember')) : ?> checked <?php endif ?>>
					<?= lang('Auth.rememberMe') ?>
				</label>
			</div>
		<?php endif; ?>
		<div class="form-group">
			<div class="col-md-12">
				<br>
				<button type="submit" class="btn btn-info btn-block"><?= lang('Auth.loginAction') ?></button>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-8 form-text text-muted">
				<?php if ($config->activeResetter) : ?>
					<p class="login-subtitle"><?= lang('Auth.forgotYourPassword') ?> <a href="<?= route_to('forgot') ?>"><?= lang('Auth.forgot') ?></a></p>
				<?php endif; ?>
				<?php if ($config->allowRegistration) : ?>
					<p class="login-subtitle"><?= lang('Auth.needAnAccount') ?> <a href="<?= route_to('register') ?>"><?= lang('Auth.register') ?></a></p>
				<?php endif; ?>
			</div>
		</div>
	</form>
</div>

<?= $this->endSection() ?>