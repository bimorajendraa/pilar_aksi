<?= $this->extend("layout/master-admin") ?>
<?php if(isset($data, $breadcrumbs)): ?>

<?= $this->section("title") ?>
Admin HMSI | Akun | Ubah
<?= $this->endSection() ?>

<?= $this->section("breadcrumbs") ?>
<?= $breadcrumbs ?>
<?= $this->endSection() ?>

<?= $this->section("halaman") ?>
Ubah Data Akun
<?= $this->endSection() ?>

<?= $this->section("konten") ?>

<fieldset class="form-fieldset">
    <legend>Ubah Informasi Narahubung</legend>
    <form action="<?= base_url("admin/akun/ubah") ?>" method="post" data-parsley-validate>
    <?= csrf_field() ?>
        <div class="row">
            <div class="col-lg-3">
                <div class="form-group">
                    <label for="nama_panggilan" class="tx-bold">Nama Panggilan <span class="tx-danger">*</span></label>
                    <input id="nama_panggilan" name="nama_panggilan" type="text" class="form-control" placeholder="Masukkan nama panggilan" required value="<?= $data->nama_panggilan ?>" data-parsley-required-message="Nama Panggilan wajib diisi!">
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-group">
                    <label for="no_wa" class="tx-bold">Nomor WhatsApp <span class="tx-danger">*</span></label>
                    <input id="no_wa" name="no_wa" type="text" class="form-control" placeholder="Masukkan nomor WhatsApp" required value="<?= $data->no_wa ?>" data-parsley-required-message="Nomor WhatsApp wajib diisi!">
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-group">
                    <label for="id_line" class="tx-bold">ID LINE <span class="tx-danger">*</span></label>
                    <input id="id_line" name="id_line" type="text" class="form-control" placeholder="Masukkan bagian harahubung" required value="<?= $data->id_line ?>" data-parsley-required-message="ID LINE wajib diisi!">
                </div>
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
</fieldset>

<fieldset class="form-fieldset mt-5">
    <legend>Ubah Kata Sandi</legend>
    <form action="<?= base_url("admin/akun/ubah_pass") ?>" method="post" data-parsley-validate>
    <?= csrf_field() ?>
        <div class="row">
            <div class="col-lg-3">
                <div class="form-group">
                    <label for="pass_lama" class="tx-bold">Kata Sandi Lama <span class="tx-danger">*</span></label>
                    <input id="pass_lama" name="pass_lama" type="password" class="form-control" placeholder="Masukkan kata sandi lama" required  data-parsley-required-message="Kata sandi Lama wajib diisi!">
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-group">
                    <label for="pass_baru1" class="tx-bold">Kata Sandi Baru <span class="tx-danger">*</span></label>
                    <input id="pass_baru1" name="pass_baru1" type="password" class="form-control" placeholder="Masukkan kata sandi baru" required  data-parsley-required-message="Kata sandi Baru wajib diisi!">
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-group">
                    <label for="pass_baru2" class="tx-bold">Ketik Ulang Kata Sandi Baru <span class="tx-danger">*</span></label>
                    <input id="pass_baru2" name="pass_baru2" type="password" class="form-control" placeholder="Masukkan kata sandi baru" required  data-parsley-required-message="Kata sandi Baru wajib diisi!">
                </div>
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
</fieldset>

<?= $this->endSection() ?>

<?php endif; ?>