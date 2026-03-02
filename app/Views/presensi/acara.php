<?= $this->extend("layout/master-presensi") ?>
<?php if(isset($data)): ?>

<?= $this->section("title") ?>
Kehadiran Acara HMSI
<?= $this->endSection() ?>

<?= $this->section("konten") ?>

<div class="card card-body shadow-none bd-primary animated fadeInDown">
    <div class="marker marker-ribbon marker-primary pos-absolute t-10 l-0">Kode Acara: <?= $data->kode_acara ?><br></div>
    <p class="mg-t-30">
        <span class="tx-gray-700">Kamu sedang menghadiri acara:</span><br><b><?= $data->nama_acara ?></b><br>
        <span class=""><?= (new IntlDateFormatter("id_ID",IntlDateFormatter::FULL,IntlDateFormatter::SHORT,"Asia/Jakarta",IntlDateFormatter::GREGORIAN,"eeee, dd MMMM yyyy 'pukul' HH.mm z'"))->format(new DateTime($data->tanggal)) ?></span>
    </p>
    <p>
        <span class="tx-gray-700">Penyelenggara:</span><br><b><?= $data->nama_departemen ?></b>
    </p>
</div>

<?php if(session()->has("error")): ?>
    <div class="alert alert-danger alert-dismissible fade show mt-3 animated zoomIn fast delay-1s" role="alert">
        <?= session()->getFlashdata("error") ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
<?php endif; ?>

<div class="form-group mt-3 animated zoomIn fast delay-1s">
    <label for="nrp" class="tx-bold">NRP <span class="tx-danger">*</span></label>
    <div class="input-group mg-b-10">
        <input id="nrp" name="nrp" type="text" class="form-control wd-200" placeholder="Masukkan NRP kamu">
        <div class="input-group-append">
            <button type="submit" id="cek" name="cek" class="btn btn-primary"><i data-feather="search"></i> Cari</button>
        </div>
    </div>
</div>

<div class="mt-3 hasil_cek" style="display: none">
    <div class="card card-body tx-white bg-success ht-100p overflow-hidden">
        <div class="marker pos-absolute t-10 l-10">Hasil Pencarian: <span class="tx-primary" id="cek_nrp"></span></div>
        <table class="mg-t-25">
            <tr>
                <td style="min-width: 50px">Nama</td>
                <td>:</td>
                <td class="tx-bold" id="cek_nama"></td>
            </tr>
            <tr>
                <td>Prodi</td>
                <td>:</td>
                <td class="tx-bold" id="cek_prodi"></td>
            </tr>
            <tr>
                <td>Angkatan</td>
                <td>:</td>
                <td class="tx-bold" id="cek_angkatan"></td>
            </tr>
        </table>
        <form action="<?= base_url("/hadir") ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" id="form_kode" name="form_kode" value="<?= $data->kode_acara ?>">
            <input type="hidden" id="form_nrp" name="form_nrp">
            <button type="submit" class="mt-2 btn btn-primary btn-block btn-xs"><i data-feather="check-circle"></i> Klik untuk Hadir</button>
        </form>
    </div>
</div>

<div class="mt-3 hasil_error" style="display: none">
    <div class="card card-body tx-white bg-danger ht-100p overflow-hidden">
        <div class="marker pos-absolute  t-10 l-10">Hasil Pencarian: <span class="tx-primary" id="nrp_salah"></span></div>
        <span class="mg-t-25">Maaf, data yang kamu masukkan <b>tidak terdaftar</b> di sistem kami.
            Silakan melanjutkan registrasi manual menggunakan formulir di bawah ini.</span>
        <form action="<?= base_url("/hadir_manual") ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" id="form2_kode" name="form2_kode" value="<?= $data->kode_acara ?>">
            <input type="hidden" id="form2_nrp" name="form2_nrp">
            <div class="form-group mt-3">
                <label for="form2_nama" class="tx-bold">Nama Lengkap<span class="tx-danger">*</span></label>
                <div class="input-group">
                    <input id="form2_nama" name="form2_nama" type="text" class="form-control wd-200" placeholder="Masukkan nama lengkap kamu" required>
                </div>
            </div>
            <div class="custom-control custom-checkbox mt-2">
                <input type="checkbox" class="custom-control-input" id="data_benar_2" required>
                <label class="custom-control-label" for="data_benar_2">Saya menyatakan bahwa data di atas sudah benar</label>
            </div>
            <button type="submit" class="mt-2 btn btn-primary btn-block btn-xs"><i data-feather="check-circle"></i> Klik untuk Hadir</button>
        </form>
    </div>
</div>

<div class="mg-t-20 card card-body animated fadeInUp delay-2s">
    <span class="tx-gray-700">Narahubung: <span class="tx-bold"><?= $data->nama_panggilan ?></span></span>
    <table class="tx-black tx-bold">
        <tr>
            <td style="min-width: 50px">LINE</td>
            <td>:</td>
            <td>
                <a href="https://line.me/ti/p/~<?= $data->id_line ?>" target="_blank"><u><?= $data->id_line ?></u>
                    <i data-feather="external-link" class="tx-gray-600" style="height: 10px"></i></a>
            </td>
        </tr>
        <tr>
            <td>WhatsApp</td>
            <td>:</td>
            <td>
                <a href="https://wa.me/62<?= substr($data->no_wa,1) ?>" target="_blank"><u><?= $data->no_wa ?></u>
                    <i data-feather="external-link" class="tx-gray-600 tx-primary" style="height: 10px"></i></a>
            </td>
        </tr>
    </table>
</div>

<?= $this->endSection() ?>

<?= $this->section("js") ?>

<script>
    <?= $this->include("layout/mahasiswa.js") ?>
</script>

<?= $this->endSection() ?>

<?php else: ?>

<?= $this->section("konten") ?>
Mohon maaf, halaman ini sedang error. Hubungi Pengembang via LINE: hendry.naufal atau WhatsApp: 0853-3130-3015 (hendry). Terima kasih.
<?= $this->endSection() ?>

<?php endif; ?>