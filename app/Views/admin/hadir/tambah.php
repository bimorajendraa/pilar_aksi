<?= $this->extend("layout/master-admin") ?>
<?php if(isset($breadcrumbs)): ?>

<?= $this->section("title") ?>
Admin HMSI | Hadir | Tambah
<?= $this->endSection() ?>

<?= $this->section("breadcrumbs") ?>
<?= $breadcrumbs ?>
<?= $this->endSection() ?>

<?= $this->section("halaman") ?>
Buat Tautan Kehadiran Acara Baru
<?= $this->endSection() ?>

<?= $this->section("tambah") ?>
<a href="<?= base_url("admin/hadir/dashboard") ?>" class="btn btn-secondary btn-sm"><i data-feather="arrow-left"></i> Kembali</a>
<?= $this->endSection() ?>

<?= $this->section("konten") ?>

<form action="<?= base_url("admin/hadir/tambah") ?>" method="post" data-parsley-validate>
<?= csrf_field() ?>
    <div class="row">
        <div class="col-lg-7">
            <div class="form-group">
                <label for="nama_acara" class="tx-bold">Nama Acara <span class="tx-danger">*</span></label>
                <input id="nama_acara" name="nama_acara" type="text" class="form-control" placeholder="Masukkan nama acara" required data-parsley-required-message="Nama Acara wajib diisi!">
            </div>
        </div>
        <div class="col-lg-5">
            <div class="form-group">
                <label for="tanggal" class="tx-bold">Tanggal <span class="tx-danger">*</span></label>
                <input id="tanggal" name="tanggal" type="datetime-local" class="form-control" placeholder="Masukkan tanggal acara" required data-parsley-required-message="Tanggal Acara wajib diisi!">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-7">
            <div class="form-group">
                <label for="lokasi" class="tx-bold">Lokasi <span class="tx-danger">*</span></label>
                <input id="lokasi" name="lokasi" type="text" class="form-control" placeholder="Masukkan lokasi acara (dapat berupa link online meet seperti link zoom atau lokasi offline)" required data-parsley-required-message="Lokasi Acara wajib diisi!">
            </div>
        </div>
        <div class="col-lg-5">
            <div class="form-group">
                <label for="tipe" class="tx-bold">Tipe Acara <span class="tx-danger">*</span></label>
                <select id="tipe" name="tipe" type="text" class="form-control" required data-parsley-required-message="Tipe Acara wajib diisi!">
                    <option value="0">0 - WAJIB diikuti fungsionaris tanpa kecuali</option>
                    <option value="1" selected>1 - TIDAK WAJIB diikuti fungsionaris</option>
                    <option value="2">2 - Hanya diikuti fungsionaris TERTENTU (peserta terbatas)</option>
                    <option value="3">3 - Rapat INTERNAL departemen atau KOORDINASI HMSI</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <div class="form-group">
                <label for="narahubung1" class="tx-bold">Narahubung <span class="tx-danger">*</span></label>
                <select id="narahubung1" name="narahubung1" class="form-control" required data-parsley-required-message="Narahubung wajib diisi!"></select>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group">
                <label for="no_wa1" class="tx-bold">Nomor WhatsApp <span class="tx-danger">*</span></label>
                <input id="no_wa1" name="no_wa1" type="text" class="form-control" placeholder="Masukkan bagian harahubung" readonly>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group">
                <label for="id_line1" class="tx-bold">ID LINE <span class="tx-danger">*</span></label>
                <input id="id_line1" name="id_line1" type="text" class="form-control" placeholder="Masukkan bagian harahubung" readonly>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-7">
            <label class="tx-danger tx-bold">Catatan Penting: </label>
            <label class="tx-danger">PASTIKAN KAMU SUDAH MEMBACA PANDUAN PEMBUATAN ACARA DI
                <a href="https://its.id/m/panduan-web-hmsi-inovasi" target="_blank">https://its.id/m/panduan-web-hmsi-inovasi</a></label>
        </div>
        <div class="col-lg-3 mt-auto">
            <div class="form-group">
                <button type="submit" class="btn btn-block btn-primary btn-icon">
                    <i data-feather="save"></i> <span>Simpan Data</span>
                </button>
            </div>
        </div>
    </div>
</form>

<?= $this->endSection() ?>

<?= $this->section("js") ?>

<script>
    <?= $this->include("layout/narahubung.js") ?>
</script>

<?= $this->endSection() ?>

<?php endif; ?>
