<?= $this->extend("layout/master-admin") ?>
<?php if(isset($data5, $breadcrumbs)): ?>

<?= $this->section("title") ?>
    Admin HMSI | Sekretariat | Piket | Kontrol
<?= $this->endSection() ?>

<?= $this->section("breadcrumbs") ?>
<?= $breadcrumbs ?>
<?= $this->endSection() ?>

<?= $this->section("halaman") ?>
    Kontrol Kehadiran Piket Ruang Kesekretariatan
<?= $this->endSection() ?>

<?= $this->section("konten") ?>

<div class="row mg-t-30">
    <div class="col-12">
        <table id="rekap-piket" class="table table-hover">
            <thead>
            <tr class="tx-center">
                <th class="wd-5p">No</th>
                <th class="wd-30p">Nama</th>
                <th class="wd-30p">Departemen</th>
                <th class="wd-10p">Jadwal</th>
                <th class="wd-10p">Status</th>
                <th class="wd-10p">Aksi</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data5 as $i=>$d): ?>
                <tr>
                    <td class="align-middle tx-center"><?= $i+1 ?></td>
                    <td class="align-middle"><?= $d->nama ?></td>
                    <td class="align-middle"><?= $d->nama_departemen ?></td>
                    <td class="align-middle tx-center"><?= $d->jadwal_wajib ?></td>
                    <td class="align-middle tx-center <?= ($d->status  === "Belum") ? "tx-danger tx-bold" : "" ?>"><?= $d->status ?></td>
                    <td class="align-middle tx-center">
                        <?php if($d->status  === "Belum"): ?>
                            <a onclick="pindahConfirm(<?= $d->id_pengurus ?>)" href="#" class="btn btn-primary btn-xs btn-block"><i data-feather="calendar"></i> Pindah</a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modal_piket" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog wd-sm-400" role="document">
        <div class="modal-content bg-white">
            <form action="<?= base_url("admin/sekre/piket/ubah") ?>" method="post">
            <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">Pindah Jadwal Piket</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_pengurus" id="id_pengurus" value="">
                    <div class="form-group">
                        <label for="tanggal" class="tx-bold">Tanggal Piket <span class="tx-danger">*</span></label>
                        <input id="tanggal" name="tanggal" type="date" class="form-control" placeholder="Masukkan tanggal acara" required data-parsley-required-message="Tanggal piket wajib diisi!">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary btn-xs" type="button" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-xs btn-danger">
                        <i data-feather="save"></i> <span>Pindah Jadwal</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section("js") ?>
<script>
    function pindahConfirm(id_pengurus)
    {
        $('#id_pengurus').attr("value",id_pengurus);
        $("#modal_piket").modal();
    }

    $('#rekap-piket').DataTable({
        <?= $this->include("layout/datatable.txt") ?>
    });
</script>

<?= $this->endSection() ?>

<?php endif; ?>