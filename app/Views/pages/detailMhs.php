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
            <li><a href="/detailMhs"><?= $breadcrumb[1]; ?></a></li>
            <li class="active"><?= $breadcrumb[2]; ?></li>
        </ul>
        <!-- END BREADCRUMB  ->getBody()-->
        <div class="row">
            <div class="col-md-12">
                <?php if (!empty(session()->getFlashdata('success'))) : ?>
                    <?= view('layout/templateAlert', ['msg' => ['success', session()->getFlashdata('success')]]); ?>
                <?php endif; ?>
                <?php if ($validation->hasError('tahunAjar')) : ?>
                    <?= view('layout/templateAlert', ['msg' => ['danger', "<strong>Failed ! </strong>" . $validation->getError('tahunAjar')]]); ?>
                <?php endif; ?>
                <?php if ($validation->hasError('tahunAngkatan')) : ?>
                    <?= view('layout/templateAlert', ['msg' => ['danger', "<strong>Failed ! </strong>" . $validation->getError('tahunAngkatan')]]); ?>
                <?php endif; ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <form name="proses" autocomplete="off" class="form-horizontal" action="/detailMhs/proses" method="POST" id="proses">
                            <div class="col-md-2">
                                <label>Pilih Fakultas</label>
                                <select class="form-control select" name="fakultas">
                                    <option value="">Semua Fakultas</option>
                                    <?php foreach ($listFakultas as $rows) : ?>
                                        <option value="<?= $rows->Faculty_Acronym ?>" <?php if ($rows->Faculty_Acronym == $filter) echo " selected" ?>><?= $rows->Faculty_Name ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label>Tahun Ajar</label>
                                <select class="form-control select" name="tahunAjar">
                                    <option value="">-- Select --</option>
                                    <?php foreach ($listTermYear as $rows) : ?>
                                        <option value="<?= $rows->Term_Year_Id ?>" <?php if ($rows->Term_Year_Id == $termYear) echo " selected" ?>><?= $rows->Term_Year_Name ?></option> -->
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Tahun Angkatan</label>
                                <select class="form-control select" name="tahunAngkatan">
                                    <option value="">--Select--</option>
                                    <?php for ($i = date("Y"); $i >= 2016; $i--) : ?>
                                        <option value="<?= $i ?>" <?php if ($i == $entryYear) echo " selected" ?>><?= $i ?></option>
                                    <?php endfor ?>
                                </select>
                            </div>
                            <ul class="panel-controls">
                                <?php if ($termYear != null  && $entryYear != null) : ?>

                                    <button style="display: inline-block; margin-top: 11px;;margin-right: 5px;" type="submit" form="cetak" class="btn btn-info"><span class="glyphicon glyphicon-print"></span>
                                        Export</button>
                                <?php endif ?>
                                <button style="display: inline-block; margin-top: 11px" type="submit" class="btn btn-success"><span class=" fa fa-arrow-circle-right"></span>
                                    Proses</button>
                            </ul>
                        </form>
                    </div>
                    <div class="panel-body col-md-12">
                        <?php if (count($detailMhs) < 1) : ?>
                            <center>
                                <lottie-player src="https://assets10.lottiefiles.com/packages/lf20_s6bvy00o.json" background="transparent" speed="1" style="width: 100%; height: 500px;" loop autoplay></lottie-player>
                            </center>
                        <?php else : ?>
                            <?php if ($termYear != null  && $entryYear != null) : ?>
                                <form name="cetak" action="/detailMhs/cetak" method="POST" id="cetak">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="fakultas" value="<?= $filter; ?>">
                                    <input type="hidden" name="tahunAjar" value="<?= $termYear; ?>">
                                    <input type="hidden" name="tahunAngkatan" value="<?= $entryYear; ?>">
                                </form>
                            <?php endif ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Detail Mahasiswa Aktif</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-actions table datatable">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>NPM</th>
                                                    <th>Nama Lengkap</th>
                                                    <th>Fakultas</th>
                                                    <th>Prodi</th>
                                                    <th>Stambuk</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $no = 1;
                                                foreach ($detailMhs as $row) : ?>
                                                    <tr>
                                                        <td><?= $no++; ?></td>
                                                        <td><?= $row->NPM; ?></td>
                                                        <td><?= $row->NAMA_LENGKAP; ?></td>
                                                        <td><?= $row->FAKULTAS; ?></td>
                                                        <td><?= $row->NAMA_PRODI; ?></td>
                                                        <td><?= $row->ANGKATAN; ?></td>
                                                        <td><?= $row->STATUS; ?></td>
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