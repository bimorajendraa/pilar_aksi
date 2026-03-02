<?= $this->extend("layout/master-presensi") ?>
<?php if(isset($data)): ?>

<?= $this->section("title") ?>
Kehadiran Acara HMSI
<?= $this->endSection() ?>

<?= $this->section("konten") ?>

<div class="card card-body shadow-none bd-primary">
    <div class="marker marker-ribbon marker-danger pos-absolute t-10 l-0">Hak Akses Panitia: <?= $data->kode_acara ?><br></div>
    <p class="mg-t-30">
        <span class="tx-gray-700">Kehadiran Acara oleh Panitia:</span><br><b><?= $data->nama_acara ?></b><br>
        <span class=""><?= (new IntlDateFormatter("id_ID",IntlDateFormatter::FULL,IntlDateFormatter::SHORT,"Asia/Jakarta",IntlDateFormatter::GREGORIAN,"eeee, dd MMMM yyyy 'pukul' HH.mm z'"))->format(new DateTime($data->tanggal)) ?></span>
    </p>
    <p>
        <span class="tx-gray-700">Penyelenggara:</span><br><b><?= $data->nama_departemen ?></b>
    </p>
</div>

<?php if(session()->has("error")): ?>
    <div class="alert alert-danger alert-dismissible fade show mt-3 mb-3" role="alert">
        <?= session()->getFlashdata("error") ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
<?php endif; ?>

<?php if(session()->has("berhasil")): ?>
    <div class="alert alert-success alert-dismissible fade show mt-3 mb-3" role="alert">
        <?= session()->getFlashdata("berhasil") ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
<?php endif; ?>

<div class="form-group mt-3">
    <label for="nrp" class="tx-bold">NRP <span class="tx-danger">*</span></label>
    <div class="input-group mg-b-10">
        <input id="nrp" name="nrp" type="text" class="form-control wd-200" placeholder="Masukkan NRP peserta">
        <div class="input-group-append">
            <button type="submit" id="cek" name="cek" class="btn btn-primary"><i data-feather="search"></i> Cari</button>
        </div>
    </div>
</div>

<div class="mt-3 hasil_cek" style="display: none">
    <div class="card card-body tx-white bg-success ht-100p overflow-hidden">
        <div class="marker pos-absolute t-10 l-10">Hasil Pencarian: <span class="tx-primary" id="cek_nrp"></span></div>
        <form action="<?= base_url("/hadir_panitia") ?>" method="post">
            <?= csrf_field() ?>
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
            <br>
            <span><label for="keterangan">Keterangan:</label></span>
            <textarea id="keterangan" name="keterangan" class="form-control" rows="1" type="text" placeholder="tidak wajib diisi"></textarea>
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
            Pastikan NRP sudah benar.</span>
    </div>
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