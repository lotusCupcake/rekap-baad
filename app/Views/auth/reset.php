<?= $this->extend($config->viewLayout) ?>


<?= $this->section('main') ?>
<div class="login-logo"></div>
<div class="login-body">
    <div class="login-title"><strong>Reset</strong> Your Password</div>
    <?= view('Myth\Auth\Views\_message_block') ?>
    <p class="form-text text-muted"><?= lang('Auth.enterCodeEmailPassword') ?></p>
    <form action="<?= route_to('reset-password') ?>" class="form-horizontal" method="post">
        <?= csrf_field() ?>
        <div class="form-group">
            <div class="col-md-12">
                <label for="token"><?= lang('Auth.token') ?></label>
                <input type="text" class="form-control <?php if (session('errors.token')) : ?>is-invalid<?php endif ?>" name="token" placeholder="<?= lang('Auth.token') ?>" value="<?= old('token', $token ?? '') ?>">
                <div class="invalid-feedback">
                    <?= session('errors.token') ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-12">
                <label for="email"><?= lang('Auth.email') ?></label>
                <input type="email" class="form-control <?php if (session('errors.email')) : ?>is-invalid<?php endif ?>" name="email" aria-describedby="emailHelp" placeholder="<?= lang('Auth.email') ?>" value="<?= old('email') ?>">
                <div class="invalid-feedback">
                    <?= session('errors.email') ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-12">
                <label for="password"><?= lang('Auth.newPassword') ?></label>
                <input type="password" class="form-control <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>" name="password">
                <div class="invalid-feedback">
                    <?= session('errors.password') ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-12">
                <label for="pass_confirm"><?= lang('Auth.newPasswordRepeat') ?></label>
                <input type="password" class="form-control <?php if (session('errors.pass_confirm')) : ?>is-invalid<?php endif ?>" name="pass_confirm">
                <div class="invalid-feedback">
                    <?= session('errors.pass_confirm') ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-6">
                <br>
                <p><?= lang('Auth.changeMind') ?> <a href="<?= route_to('login') ?>"><?= lang('Auth.signIn') ?></a></p>
            </div>
            <div class="col-md-6">
                <br>
                <button type="submit" class="btn btn-info btn-block"><?= lang('Auth.resetPassword') ?></button>
            </div>
        </div>
    </form>
</div>

<?= $this->endSection() ?>