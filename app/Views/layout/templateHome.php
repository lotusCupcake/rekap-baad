<!DOCTYPE html>
<html lang="en">

<head>
    <!-- META SECTION -->
    <title><?= $title . " | " . $appName; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="icon" href="favicon.ico" type="image/x-icon" />
    <!-- END META SECTION -->

    <!-- CSS INCLUDE -->
    <link rel="stylesheet" type="text/css" id="theme" href="<?= base_url('theme/css/theme-default.css'); ?>" />
    <!-- EOF CSS INCLUDE -->

</head>

<body class="page-container-boxed">

    <?= $this->renderSection('content'); ?>

    <!-- MESSAGE BOX-->
    <div class="message-box animated fadeIn" data-sound="alert" id="mb-signout">
        <div class="mb-container">
            <div class="mb-middle">
                <div class="mb-title"><span class="fa fa-sign-out"></span> Log <strong>Out</strong> ?</div>
                <div class="mb-content">
                    <p>Are you sure you want to log out?</p>
                    <p>Press No if youwant to continue work. Press Yes to logout current user.</p>
                </div>
                <div class="mb-footer">
                    <div class="pull-right">
                        <form action="/logout" method="POST">
                            <button type="submit" class="btn btn-success btn-lg">Yes</button>
                            <button class=" btn btn-default btn-lg mb-control-close">No</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END MESSAGE BOX-->

    <!-- START PRELOADS -->
    <audio id="audio-alert" src="<?= base_url('theme/audio/alert.mp3'); ?>" preload="auto"></audio>
    <audio id="audio-fail" src="<?= base_url('theme/audio/fail.mp3'); ?>" preload="auto"></audio>
    <!-- END PRELOADS -->

    <!-- START SCRIPTS -->
    <!-- START PLUGINS -->
    <script type="text/javascript" src="<?= base_url('theme/js/plugins/jquery/jquery.min.js'); ?>"></script>
    <script type="text/javascript" src="<?= base_url('theme/js/plugins/jquery/jquery-ui.min.js'); ?>"></script>
    <script type="text/javascript" src="<?= base_url('theme/js/plugins/bootstrap/bootstrap.min.js'); ?>"></script>
    <!-- <script type='text/javascript' src='theme/js/plugins/popper/popper.min.js'></script> -->
    <!-- END PLUGINS -->

    <!-- START THIS PAGE PLUGINS-->
    <script type='text/javascript' src='theme/js/plugins/icheck/icheck.min.js'></script>
    <script type="text/javascript" src="<?= base_url('theme/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js'); ?>"></script>
    <script type="text/javascript" src="<?= base_url('theme/js/plugins/scrolltotop/scrolltopcontrol.js'); ?>"></script>

    <script type="text/javascript" src="<?= base_url('theme/js/plugins/morris/raphael-min.js'); ?>"></script>
    <script type="text/javascript" src="<?= base_url('theme/js/plugins/morris/morris.min.js'); ?>"></script>
    <script type='text/javascript' src='<?= base_url("theme/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"); ?>'></script>
    <script type='text/javascript' src='<?= base_url("theme/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"); ?>'></script>
    <script type='text/javascript' src='<?= base_url("theme/js/plugins/bootstrap/bootstrap-datepicker.js"); ?>'></script>
    <script type="text/javascript" src="<?= base_url('theme/js/plugins/owl/owl.carousel.min.js'); ?>"></script>
    <script type="text/javascript" src="<?= base_url('theme/js/plugins/bootstrap/bootstrap-file-input.js'); ?>"></script>
    <script type="text/javascript" src="<?= base_url('theme/js/plugins/bootstrap/bootstrap-select.js'); ?>"></script>
    <script type="text/javascript" src="<?= base_url('theme/js/plugins/bootstrap/bootstrap-timepicker.min.js'); ?>"></script>
    <script type="text/javascript" src="<?= base_url('theme/js/plugins/bootstrap/bootstrap-datepicker.js'); ?>"></script>
    <script type="text/javascript" src="<?= base_url('theme/js/plugins/dropzone/dropzone.min.js'); ?>"></script>
    <script type='text/javascript' src='<?= base_url("theme/js/plugins/icheck/icheck.min.js"); ?>'></script>
    <script type="text/javascript" src="<?= base_url('theme/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js'); ?>"></script>
    <script type="text/javascript" src="<?= base_url('theme/js/plugins/fileinput/fileinput.min.js'); ?>"></script>
    <script type='text/javascript' src="<?= base_url('theme/js/plugins/datatables/jquery.dataTables.min.js'); ?>"></script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script>
        $(function() {
            $("#file-simple").fileinput({
                showUpload: false,
                showCaption: false,
                browseClass: "btn btn-danger",
                fileType: "any"
            });
        })
    </script>

    <!-- END THIS PAGE PLUGINS-->

    <!-- START TEMPLATE -->
    <script type="text/javascript" src="<?= base_url('theme/js/plugins.js'); ?>"></script>
    <script type="text/javascript" src="<?= base_url('theme/js/actions.js'); ?>"></script>
    <script src="<?= base_url() ?>/js/script.js"></script>
    <!-- END TEMPLATE -->
    <!-- END TIME -->
    <!-- END SCRIPTS -->
    <script>
        function previewImg() {
            const flayer = document.querySelector('#flayer');
            const flayerPrev = document.querySelector('.img-preview');

            const fileFlayer = new FileReader();
            fileFlayer.readAsDataURL(flayer.files[0]);

            fileFlayer.onload = function(e) {
                flayerPrev.src = e.target.result;
            }
        }

        function previewImgEdit() {
            const flayer = document.querySelector('#flayeredit');
            const id = document.getElementById("flayeredit").getAttribute("data-id");
            console.log(id);
            const flayerPrev = document.querySelector('.img-preview' + id);

            const fileFlayer = new FileReader();
            fileFlayer.readAsDataURL(flayer.files[0]);

            fileFlayer.onload = function(e) {
                flayerPrev.src = e.target.result;
            }
        }

        function previewImgEditSet(id) {
            const flayer = document.querySelector('#flayeredit' + id);
            const flayerPrev = document.querySelector('.img-preview' + id);

            console.log(flayer.files[0]);
            const fileFlayer = new FileReader();
            fileFlayer.readAsDataURL(flayer.files[0]);

            fileFlayer.onload = function(e) {
                flayerPrev.src = e.target.result;
            }
        }
    </script>

    <!-- <script type="text/javascript">
        function update() {

            var fakultas = document.getElementById("fakultas");
            var tahap = document.getElementById("tahap");
            var valueFakultas = fakultas.options[fakultas.selectedIndex].value;
            var len = tahap.length;
            if (valueFakultas == 'FK') {
                for (let i = 0; i < tahap.length; i++) {
                    if (tahap.options[i].value == '3' || tahap.options[i].value == '4') {
                        console.log(tahap.options[i].value);
                        tahap.remove(i);
                    }
                }
            } else {
                if (len == 2) {}
            }
            // console.log(len);
        }
    </script> -->


</body>

</html>