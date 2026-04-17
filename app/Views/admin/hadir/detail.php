<?= $this->extend("layout/master-admin") ?>
<?php if(isset($data, $data2, $data3, $breadcrumbs)): ?>

<?= $this->section("title") ?>
Admin HMSI | Hadir | Acara Detail
<?= $this->endSection() ?>

<?= $this->section("breadcrumbs") ?>
<?= $breadcrumbs ?>
<?= $this->endSection() ?>

<?= $this->section("halaman") ?>
Detail Tautan Kehadiran Acara
<?= $this->endSection() ?>

<?= $this->section("tambah") ?>
<a href="<?= base_url("admin/hadir/dashboard") ?>" class="btn btn-secondary btn-sm"><i data-feather="arrow-left"></i> Kembali</a>
<?= $this->endSection() ?>

<?= $this->section("konten") ?>

<div class="row">
    <div class="col-12 col-md-7">
        <div class="card bg-white hasil">
            <div class="card-body mg-t-30 mg-b-30">
                <div class="tx-center tx-28 tx-bold tx-primary">
                    <?= $data->nama_acara ?>
                </div>
                <div class="tx-center tx-16 mg-t-1">
                    <?= (new IntlDateFormatter("id_ID",IntlDateFormatter::FULL,IntlDateFormatter::SHORT,"Asia/Jakarta",IntlDateFormatter::GREGORIAN,"eeee, dd MMMM yyyy 'pukul' HH.mm z'"))->format(new DateTime($data->tanggal)) ?>
                </div>

                <div class="mg-t-30"></div>
                <div class="tx-center tx-14 tx-gray-600">Lokasi:</div>
                <div class="tx-center tx-20 tx-bold">
                    <?= $data->lokasi ?>
                </div>

                <div class="mg-t-30"></div>
                <div class="tx-center tx-14 tx-gray-600">Narahubung:</div>
                <div class="tx-center tx-16 tx-bold">
                    <?= $data2->nama . " - " . $data2->nrp ?>
                </div>

                <div class="mg-t-50"></div>
                <div class="row">
                    <div class="col-auto">
                        <a href="<?= base_url("admin/hadir/dashboard") ?>" class="btn btn-primary">
                            <i data-feather="arrow-left"></i> Kembali ke Daftar Acara</a>
                    </div>
                    <?php if(!filter_var($data->lokasi, FILTER_VALIDATE_URL) === true): ?>
                        <div class="col-auto mg-t-10 mg-md-t-0">
                            <a onclick="panitiaConfirm('<?= base_url("/p/$data->kode_acara") ?>')" href="#"
                               class="btn btn-danger">
                                <span class="tx-white"><i data-feather="alert-triangle"></i> Akses Presensi Panitia</span>
                            </a>
                        </div>
                    <?php endif; ?>
                    <?php if($data->jumlah === null || session()->get("id_pengurus") <= 3000): ?>
                        <div class="col-auto mg-t-10 mg-md-t-0">
                            <a href="<?= base_url("admin/hadir/ubah/$data->kode_acara") ?>" class="btn btn-warning">
                                <i data-feather="edit-2"></i> Ubah Acara</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-5 mg-t-30 mg-md-t-0">
        <div class="card bg-white hasil">
            <div class="card-body mg-t-30 mg-b-30">
                <div class="tx-center">
                    <?php if($data3 !== ""): ?>
                        <img src="<?= $data3 ?>" alt="qr-code" style="max-width: 250px;">
                    <?php else: ?>
                        <span class="tx-danger tx-bold">QR belum dapat dibuat.</span>
                    <?php endif; ?>
                </div>

                <div class="mg-t-20"></div>
                <div class="input-group">
                    <label for="tautan"></label>
                    <input type="text" class="form-control tx-center tx-bold tx-20"
                        name="tautan" id="tautan" value="<?= base_url("/$data->kode_acara") ?>" readonly>
                    <div class="input-group-append">
                    <button type="button" onclick="copyLink()" class="btn btn-primary"
                                data-container="body" data-toggle="popover" data-placement="bottom"
                                title="Tautan berhasil disalin ke clipboard!" data-trigger="focus">
                            <i data-feather="clipboard" style="width: 16px; height: 16px;"></i> salin
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_panitia" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog wd-sm-400" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold">Konfirmasi</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="font-weight-bold tx-14">Apakah kamu yakin ingin mengakses halaman
                    <span class="text-danger animated flash infinite slower" >HAK AKSES PRESENSI KHUSUS PANITIA?</span>
                </p>
                <span>Klik tombol <b>BUKA</b> untuk melanjutkan.</span>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary btn-xs" type="button" data-dismiss="modal">Batal</button>
                <a class="btn btn-danger btn-xs" id="btn-panitia" href="#">Buka</a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section("js") ?>

<script>
    $('[data-toggle="popover"]').popover({
        template: '<div class="popover popover-success" role="tooltip">' +
            '<div class="arrow"></div><p class="popover-header"></p></div>'
    });

    function panitiaConfirm(url)
    {
        $("#btn-panitia").attr("href", url);
        $("#modal_panitia").modal();
    }

    function copyLink()
    {
        var copyText = document.getElementById("tautan");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);
    }
</script>

<?= $this->endSection() ?>

<?php endif; ?>