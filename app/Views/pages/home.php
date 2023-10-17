<?= $this->extend('layout/templateHome'); ?>

<?= $this->section('content'); ?>
<!-- START PAGE CONTAINER -->
<div class="page-container">
    <?= view('layout/templateSidebar', ['menus' => $menu]); ?>
    <!-- PAGE CONTENT -->
    <div class="page-content">
        <?= $this->include('layout/templateHead'); ?>
        <!-- START BREADCRUMB -->
        <ul class="breadcrumb">
            <li><a href="/home"><?= $breadcrumb[0]; ?></a></li>
            <li class="active"><?= $breadcrumb[1]; ?></li>
        </ul>
        <!-- END BREADCRUMB -->
        <!-- PAGE CONTENT WRAPPER -->
        <div class="page-content-wrap">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="panel-title-box">
                                <h3>UNIVERSITAS MUHAMMADIYAH SUMATERA UTARA</h3>
                                <span>Aplikasi UMSU BAAD</span>
                            </div>
                            <!-- <ul class="panel-controls">
                                <li>
                                    <a href="/home" data-toggle="tooltip" data-placement="left" title data-original-title="Refresh">
                                        <span class=" fa fa-refresh"></span>
                                    </a>
                                </li>
                            </ul> -->
                        </div>
                    </div>
                    <!-- <div class="panel panel-colorful">
                        <div class="panel-body">
                            <div class="alert alert-info" role="alert">
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <strong>Info!</strong> Jumlah yang tertera dibawah ini merupakan jumlah di <strong>tahun ajar berjalan</strong>.
                            </div>
                            <div class="row">
                                <div class=" col-md-4">
                                    <div class="widget widget-default widget-item-icon">
                                        <div class="widget-item-left">
                                            <span class="fa fa-users"></span>
                                        </div>
                                        <div class="widget-data">
                                            <div class="widget-int num-count"><? //count($dataResultPendaftar); 
                                                                                ?></div>
                                            <div class="widget-title">Jumlah</div>
                                            <div class="widget-subtitle">Pendaftar</div>
                                        </div>
                                        <div class="widget-controls">
                                            <a href="#!" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Remove Widget"><span class="fa fa-times"></span></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="widget widget-default widget-item-icon">
                                        <div class="widget-item-left">
                                            <span class="fa fa-graduation-cap"></span>
                                        </div>
                                        <div class="widget-data">
                                            <div class="widget-int num-count">1750</div>
                                            <div class="widget-title">Jumlah</div>
                                            <div class="widget-subtitle">Calon Mahasiswa</div>
                                        </div>
                                        <div class="widget-controls">
                                            <a href="#!" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Remove Widget"><span class="fa fa-times"></span></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="widget widget-default widget-item-icon">
                                        <div class="widget-item-left">
                                            <span class="fa fa-external-link"></span>
                                        </div>
                                        <div class="widget-data">
                                            <div class="widget-int num-count">1750</div>
                                            <div class="widget-title">Jumlah</div>
                                            <div class="widget-subtitle">Registrasi Ulang</div>
                                        </div>
                                        <div class="widget-controls">
                                            <a href="#!" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Remove Widget"><span class="fa fa-times"></span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="widget widget-primary widget-item-icon">
                                        <div class="widget-item-left">
                                            <span class="fa fa-user"></span>
                                        </div>
                                        <div class="widget-data">
                                            <div class="widget-int num-count">1750</div>
                                            <div class="widget-title">Jumlah</div>
                                            <div class="widget-subtitle">Mahasiswa Aktif Keseluruhan</div>
                                        </div>
                                        <div class="widget-controls">
                                            <a href="#!" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Remove Widget"><span class="fa fa-times"></span></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="widget widget-success widget-item-icon">
                                        <div class="widget-item-left">
                                            <span class="fa fa-male"></span>
                                        </div>
                                        <div class="widget-data">
                                            <div class="widget-int num-count">1750</div>
                                            <div class="widget-title">Jumlah</div>
                                            <div class="widget-subtitle">Mahasiswa Aktif Laki-Laki</div>
                                        </div>
                                        <div class="widget-controls">
                                            <a href="#!" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Remove Widget"><span class="fa fa-times"></span></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="widget widget-danger widget-item-icon">
                                        <div class="widget-item-left">
                                            <span class="fa fa-female"></span>
                                        </div>
                                        <div class="widget-data">
                                            <div class="widget-int num-count">1750</div>
                                            <div class="widget-title">Jumlah</div>
                                            <div class="widget-subtitle">Mahasiswa Aktif Perempuan</div>
                                        </div>
                                        <div class="widget-controls">
                                            <a href="#!" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Remove Widget"><span class="fa fa-times"></span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT -->
    </div>
    <!-- END PAGE CONTAINER -->


    <?= $this->endSection(); ?>