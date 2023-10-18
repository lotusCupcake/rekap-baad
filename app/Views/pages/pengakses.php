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
        <!-- END BREADCRUMB  ->getBody()-->
        <div class="row">
            <div class="col-md-12">
                <?php if (!empty(session()->getFlashdata('success'))) : ?>
                    <?= view('layout/templateAlert', ['msg' => ['success', session()->getFlashdata('success')]]); ?>
                <?php endif; ?>
                <?php if (!empty(session()->getFlashdata('failed'))) : ?>
                    <?= view('layout/templateAlert', ['msg' => ['danger', session()->getFlashdata('failed')]]); ?>
                <?php endif; ?>
                <?php if ($validation->hasError('prodi')) : ?>
                    <?= view('layout/templateAlert', ['msg' => ['danger', "<strong>Failed ! </strong>" . $validation->getError('prodi')]]); ?>
                <?php endif; ?>
                <?= view('layout/templateAlert', ['msg' => ['info', "<strong>Info ! </strong>Jika Ingin Menampilkan <strong>Mahasiswa Luar</strong> Pilih <strong>Prodi Mahasiswa Luar</strong> Pada Prodi"]]); ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <form autocomplete="off" class="form-horizontal" action="/pengakses/proses" method="POST">
                            <div class="col-md-2">
                                <label>Prodi</label>
                                <select class="form-control select" name="prodi">
                                    <option value="">--Select--</option>
                                    <option value="99" <?php if (99 == $prodi) echo " selected" ?>>Prodi Mahasiswa Luar</option>
                                    <?php foreach ($dataProdi as $prd) : ?>
                                        <option value="<?= $prd->Department_Id  ?>" <?php if ($prd->Department_Id == $prodi) echo " selected" ?>><?= $prd->Department_Name ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Pengguna</label>
                                <select class="form-control select" name="type">
                                    <option value="">--Select--</option>
                                    <?php foreach ($dataType as $key => $value) : ?>
                                        <option value="<?= $key ?>" <?php if ($key ==  $type) echo " selected" ?>><?= $value ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Tanggal Awal</label>
                                <div class="input-group date" id="dp-2" data-date-format="yyyy-mm-dd">
                                    <input type="text" class="form-control datepicker" value="<?= date("Y-m-d", strtotime(($tanggalAwal != null) ? $tanggalAwal : "-1 day"));  ?>" name="tanggalAwal" />
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label>Tanggal Akhir</label>
                                <div class="input-group date" id="dp-2" data-date-format="yyyy-mm-dd">
                                    <input type="text" class="form-control datepicker" value="<?= date("Y-m-d", strtotime(($tanggalAkhir != null) ? $tanggalAkhir : "now"));  ?>" name="tanggalAkhir" />
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                            <ul class="panel-controls">
                                <?php if ($prodi != null  && $type != null && $tanggalAwal != null && $tanggalAkhir != null) : ?>
                                    <!-- <button style="display: inline-block; margin-top: 11px;;margin-right: 5px;" type="submit" form="cetak" class="btn btn-info"><span class="glyphicon glyphicon-print"></span>
                                        Export</button> -->
                                <?php endif ?>
                                <button style="display: inline-block; margin-top: 11px" type="submit" class="btn btn-success"><span class="fa fa-search"></span>
                                    Cari</button>
                            </ul>
                        </form>
                    </div>
                    <div class="panel-body col-md-12">
                        <?php if (count($dataResult) < 1) : ?>
                            <center>
                                <lottie-player src="https://assets10.lottiefiles.com/packages/lf20_s6bvy00o.json" background="transparent" speed="1" style="width: 100%; height: 500px;" loop autoplay></lottie-player>
                            </center>
                        <?php else : ?>
                            <?php if ($prodi != null  && $type != null && $tanggalAwal != null && $tanggalAkhir != null) : ?>
                                <form name="cetak" action="/pengakses/cetak" method="POST" id="cetak">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="prodi" value="<?= $prodi; ?>">
                                    <input type="hidden" name="type" value="<?= $type; ?>">
                                    <input type="hidden" name="tanggalAwal" value="<?= $tanggalAwal; ?>">
                                    <input type="hidden" name="tanggalAkhir" value="<?= $tanggalAkhir; ?>">
                                </form>
                            <?php endif ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Mahasiswa</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-actions table datatable">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center;">No.</th>
                                                    <th>Nama</th>
                                                    <th>Jenis Kelamin</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $no = 1;
                                                foreach ($dataResult as $row) : ?>
                                                    <tr>
                                                        <td style="text-align: center;"><?= $no++; ?></td>
                                                        <td><?= $row->name; ?></td>
                                                        <td><?= ($row->gender != null) ? $row->gender : '-'; ?></td>
                                                    </tr>
                                                <?php endforeach ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT -->
    </div>
    <!-- END PAGE CONTAINER -->



    <?= $this->endSection(); ?>