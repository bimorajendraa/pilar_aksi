<?= $this->extend("layout/master-admin") ?>
<?php if(isset($data, $data1, $data2, $breadcrumbs)): ?>

<?= $this->section("title") ?>
Admin HMSI | Sekretariat | Piket | Dashboard
<?= $this->endSection() ?>

<?= $this->section("breadcrumbs") ?>
<?= $breadcrumbs ?>
<?= $this->endSection() ?>

<?= $this->section("halaman") ?>
Kehadiran Piket Ruang Kesekretariatan
<?= $this->endSection() ?>

<?= $this->section("konten") ?>
<?php
    $jadwalWajib = $data2->jadwal_wajib ?? null;
    $statusJadwal = (string) ($data2->status ?? '0');
?>

<div class="row">
    <form action="<?= base_url("admin/sekre/piket/hadir") ?>" method="post" data-parsley-validate class="col-md-6 col-lg-4 mg-b-10">
    <?= csrf_field() ?>
        <div class="card card-body">
            <div class="marker marker-ribbon marker-primary pos-absolute t-10 l-0">Kedatangan piket<br></div>
            <p class="mg-t-30">
                <span class="tx-gray-700">Status Piket:</span><br><b>
                    <?= ($jadwalWajib === date("Y-m-d")) ? "Jadwal piket wajib" : "BUKAN jadwal piket wajib" ?></b>
            </p>
            <p>
                <span class="tx-gray-700">Waktu Mulai:</span><br><b>
                    <?= $data3->waktu_datang ?? "-" ?></b>
            </p>
            <?php if(
                (strtotime("22:00:01") <= time()) ||
                (strtotime("07:00:00") >= time()) ||
                (date("w") == 6) ||
                (date("w") == 0)):
            ?>
                <button type="button" class="btn btn-danger btn-icon btn-sm mg-l-auto">
                    <span><i data-feather="x-octagon"></i> Bukan Sesi Piket</span>
                </button>
            <?php elseif($data3 !== null): ?>
                <button type="button" class="btn btn-primary btn-icon btn-sm mg-l-auto">
                    <span><i data-feather="check-circle"></i> Sudah Hadir</span>
                </button>
            <?php else: ?>
                <button type="submit" class="btn btn-primary btn-icon btn-sm mg-l-auto">
                    <span><i data-feather="check-circle"></i> Tekan untuk Hadir</span>
                </button>
            <?php endif; ?>
        </div>
    </form>

    <div class="col-md-6 col-lg-4">
        <div class="card shadow-none bg-light">
            <div class="card-body tx-center">
                <span class="tx-20 tx-bold tx-primary">Statistik</span><br><br>
                <span class="tx-bold">Jadwal Piket Wajib</span><br>
                <span class="">
                    <?php
                    if ($jadwalWajib === null || $jadwalWajib === '') {
                        echo 'Belum terjadwal';
                    } elseif ($statusJadwal === "0") {
                        echo (new IntlDateFormatter("id_ID",IntlDateFormatter::FULL,IntlDateFormatter::SHORT,"Asia/Jakarta",IntlDateFormatter::GREGORIAN,"eeee, dd MMMM yyyy'"))->format(new DateTime($jadwalWajib));
                    } else {
                        echo "Sudah Melaksanakan";
                    }
                    ?>
                </span><br><br>
                <span class="tx-bold">Jumlah Piket Tidak Wajib</span><br>
                <span class=""><?= $data1 ?> kali</span><br><br>
            </div>
        </div>
    </div>

    <form action="<?= base_url("admin/sekre/piket/pulang") ?>" method="post" data-parsley-validate class="col-md-6 col-lg-4 mb-b-10">
    <?= csrf_field() ?>
        <div class="card card-body">
            <div class="marker marker-ribbon marker-primary pos-absolute t-10 l-0">Kepulangan piket<br></div>
            <p class="mg-t-30">
                <span class="tx-gray-700">Status Piket:</span><br><b>
                    <?= ($jadwalWajib == date("Y-m-d")) ? "Jadwal piket wajib" : "BUKAN jadwal piket wajib" ?></b>
            </p>
            <p>
                <span class="tx-gray-700">Waktu Selesai:</span><br><b>
                    <?= $data3->waktu_keluar ?? "-" ?></b>
            </p>
            <?php if(
                (strtotime("22:00:01") <= time()) ||
                (strtotime("07:00:00") >= time()) ||
                (date("w") == 6) ||
                (date("w") == 0)):
            ?>
                <button type="button" class="btn btn-danger btn-icon btn-sm mg-l-auto">
                    <span><i data-feather="x-octagon"></i> Bukan Sesi Piket</span>
                </button>
            <?php elseif($data3 === null): ?>
                <button type="button" class="btn btn-danger btn-icon btn-sm mg-l-auto">
                    <span><i data-feather="x-octagon"></i> Belum Hadir</span>
                </button>
            <?php elseif($data3->waktu_keluar !== null): ?>
                <button type="button" class="btn btn-primary btn-icon btn-sm mg-l-auto">
                    <span><i data-feather="check-circle"></i> Sudah Selesai</span>
                </button>
            <?php else: ?>
                <button type="submit" class="btn btn-primary btn-icon btn-sm mg-l-auto">
                    <i data-feather="check-circle"></i> <span>Tekan untuk Selesai</span>
                </button>
            <?php endif; ?>
        </div>
    </form>
</div>

<div class="row">
    <div class="col-12">
        <span class="tx-danger">
            <b>Catatan:</b><br>
            Bagian <b>KEPULANGAN PIKET</b> wajib diisi untuk yang statusnya <b>jadwal piket wajib</b>.
        </span>
    </div>
</div>

<?= $this->endSection() ?>

<?php endif; ?>