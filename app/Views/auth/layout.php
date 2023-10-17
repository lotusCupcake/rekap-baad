<!DOCTYPE html>
<html lang="en" class="body-full-height">

<head>
    <!-- META SECTION -->
    <title><?= lang('Auth.appName') ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="icon" href="favicon.ico" type="image/x-icon" />
    <!-- END META SECTION -->

    <!-- CSS INCLUDE -->
    <link rel="stylesheet" type="text/css" id="theme" href="<?= base_url('theme/css/theme-default.css'); ?>" />
    <!-- EOF CSS INCLUDE -->
</head>

<body>

    <div class="login-container lightmode">
        <div class="login-box animated fadeInDown">
            <?= $this->renderSection('main'); ?>

            <!-- START FOOTER -->

            <div class="login-footer">
                <div class="pull-left">
                    &copy; 2021 <?= lang('Auth.appName') ?>
                </div>
                <div class="pull-right">
                    <p>Berniat Ikhlas, Bekerja Tuntas</p>
                </div>
            </div>
        </div>
    </div>

    <!-- END FOOTER -->
</body>

</html>