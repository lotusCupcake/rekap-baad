<!-- START PAGE SIDEBAR -->
<div class="page-sidebar">
    <!-- START X-NAVIGATION -->
    <ul class="x-navigation">
        <li class="xn-logo">
            <a href="/home">UMSU</a>
            <a href="#!" class="x-navigation-control"></a>
        </li>
        <li class="xn-profile">
            <a href="#!" class="profile-mini">
                <img src="<?= base_url('theme/assets/images/users/no-image.jpg'); ?>" alt="John Doe" />
            </a>
            <div class="profile">
                <div class="profile-image">
                    <img src="<?= base_url('theme/assets/images/users/no-image.jpg'); ?>" alt="John Doe" />
                </div>
                <div class="profile-data">
                    <div class="profile-data-name"><?= user()->username; ?></div>
                    <div class="profile-data-title"><?= user()->email; ?></div>
                </div>
            </div>
        </li>
        <?= $menus ?>
        <!-- <li class="xn-title">Home</li>
            <li>
                <a href="/home"><span class="fa fa-home"></span><span class="xn-text">Dashboard</span></a>
            </li>
        <li class="xn-title">Laporan</li>
        <li class="xn-openable">
            <a href=" #"><span class="fa fa-ban"></span><span class="xn-text">Laporan Tunggakan</span></a>
            <ul>
                <li><a href="/tunggakanPerMahasiswa"><span class="xn-text">Tunggakan Per Mahasiswa</span></a></li>
                <li><a href="/tunggakanDetail"><span class="xn-text">Detail Tunggakan</span></a></li>
                <li><a href="/tunggakanTotal"><span class="xn-text">Total Tunggakan</span></a></li>
            </ul>
        </li>
        <li class="xn-openable">
            <a href="#"><span class="fa fa-money"></span><span class="xn-text">Laporan Pembayaran</span></a>
            <ul>
                <li><a href=" /pembayaranDetail"><span class="xn-text">Detail Pembayaran Pokok</span></a></li>
                <li><a href=" /pembayaranTotal"><span class="xn-text">Total Pembayaran Pokok</span></a></li>
                <li><a href=" /pembayaranLain"><span class="xn-text">Pembayaran Lain-Lain</span></a></li>
            </ul>
        </li>
        <li><a href="/krsAktif"><span class="glyphicon glyphicon-user"></span><span class="xn-text">Jumlah KRS Aktif</span></a></li>
        <li class="xn-title">Setting</li>
        <li class="xn-openable">
            <a href="#"><span class="fa fa-edit"></span><span class="xn-text">Setting Tanggal Tahap</span></a>
            <ul>
                <li><a href="/ubahAngkatan"><span class="xn-text">Per Angkatan</span></a></li>
                <li class="xn-openable">
                    <a href="#"><span class="xn-text">Per Fakultas</span></a>
                    <ul>
                        <li><a href="/ubahFakultasNonKedokteran"><span class="xn-text">Fakultas Non Kedokteran</span></a></li>
                        <li><a href="/ubahFakultasKedokteran"><span class="xn-text">Fakultas Kedokteran</span></a></li>
                        <li><a href="/ubahFakultasPascasarjana"><span class="xn-text">Fakultas Pascasarjana</span></a></li>
                    </ul>
                </li>
                <li class="xn-openable">
                    <a href="#"><span class="xn-text">Per Prodi</span></a>
                    <ul>
                        <li><a href="/ubahProdiNonKedokteran"><span class="xn-text">Prodi Non Kedokteran</span></a></li>
                        <li><a href="/ubahProdiKedokteran"><span class="xn-text">Prodi Kedokteran</span></a></li>
                        <li><a href="/ubahProdiPascasarjana"><span class="xn-text">Prodi Pascasarjana</span></a></li>
                    </ul>
                </li>
            </ul>
        </li> -->
    </ul>
    <!-- END X-NAVIGATION -->
</div>
<!-- END PAGE SIDEBAR -->