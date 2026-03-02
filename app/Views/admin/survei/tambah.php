<?= $this->extend("layout/master-admin") ?>
<?php if(isset($breadcrumbs)): ?>

<?= $this->section("title") ?>
Admin HMSI | Survei | Tambah
<?= $this->endSection() ?>

<?= $this->section("breadcrumbs") ?>
<?= $breadcrumbs ?>
<?= $this->endSection() ?>

<?= $this->section("halaman") ?>
Buat Tautan Survei Baru
<?= $this->endSection() ?>

<?= $this->section("tambah") ?>
<a href="<?= base_url("admin/survei/dashboard") ?>" class="btn btn-secondary btn-sm"><i data-feather="arrow-left"></i> Kembali</a>
<?= $this->endSection() ?>

<?= $this->section("konten") ?>

<form action="<?= base_url("admin/survei/tambah") ?>" method="post" data-parsley-validate>
<?= csrf_field() ?>
    <div class="row">
        <div class="col-lg-7">
            <div class="form-group">
                <label for="nama_survei" class="tx-bold">Nama Survei <span class="tx-danger">*</span></label>
                <input id="nama_survei" name="nama_survei" type="text" class="form-control" placeholder="Masukkan nama survei" required data-parsley-required-message="Nama survei wajib diisi!">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <label for="tautan" class="tx-bold">Tautan Survei <span class="tx-danger">*</span></label>
                <input id="tautan" name="tautan" type="text" class="form-control" placeholder="Masukkan tautan survei (harus mengandung bidang 'id-survei' dan 'nrp')" required data-parsley-required-message="Tautan survei wajib diisi dan mengandung bidang 'id-survei' dan 'nrp'!">
            </div>
        </div>
    </div>
    <div class="row">
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

<?php endif; ?>