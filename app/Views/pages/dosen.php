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
                <?php if ($validation->hasError('fakultas')) : ?>
                    <?= view('layout/templateAlert', ['msg' => ['danger', "<strong>Failed ! </strong>" . $validation->getError('fakultas')]]); ?>
                <?php endif; ?>
                <?php if ($validation->hasError('tahunAjar')) : ?>
                    <?= view('layout/templateAlert', ['msg' => ['danger', "<strong>Failed ! </strong>" . $validation->getError('tahunAjar')]]); ?>
                <?php endif; ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <form autocomplete="off" class="form-horizontal" action="/dosen/proses" method="POST">
                            <div class="col-md-2">
                                <label>Pilih Fakultas</label>
                                <select class="form-control select" name="fakultas">
                                    <option value="">--Select--</option>
                                    <?php foreach ($listFakultas as $rows) : ?>
                                        <option value="<?= $rows->fakNamaSingkat ?>" <?php if ($rows->fakNamaSingkat == $filter) echo " selected" ?>><?= $rows->fakNamaResmi ?></option>
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
                            <ul class="panel-controls">
                                <?php if ($termYear != null  && $filter != null) : ?>
                                    <button style="display: inline-block; margin-top: 11px;;margin-right: 5px;" type="submit" form="cetak" class="btn btn-info"><span class="glyphicon glyphicon-print"></span>
                                        Export</button>
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
                            <?php if ($filter != null  && $termYear != null) : ?>
                                <form name="cetak" action="/dosen/cetak" method="POST" id="cetak">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="fakultas" value="<?= $filter; ?>">
                                    <input type="hidden" name="tahunAjar" value="<?= $termYear; ?>">
                                </form>
                            <?php endif ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Penugasan Dosen</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-actions table datatable">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Username</th>
                                                    <th>Password</th>
                                                    <th>Firstname</th>
                                                    <th>Lastname</th>
                                                    <th>Email</th>
                                                    <th>Course</th>
                                                    <th>Role</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $no = 1;
                                                foreach ($dataResult as $row) : ?>
                                                    <tr>
                                                        <td><?= $no++; ?></td>
                                                        <td><?= $row->username; ?></td>
                                                        <td><?= $row->password; ?></td>
                                                        <td><?= $row->firstname; ?></td>
                                                        <td><?= $row->lastname; ?></td>
                                                        <td><?= $row->email; ?></td>
                                                        <td><?= $row->course1; ?></td>
                                                        <td><?= $row->role1; ?></td>
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