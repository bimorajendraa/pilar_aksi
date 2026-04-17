<?= $this->extend("layout/master-admin") ?>
<?php if(isset($data, $breadcrumbs)): ?>

<?= $this->section("title") ?>
    Admin HMSI | Hadir | Dashboard
<?= $this->endSection() ?>

<?= $this->section("use_datatables") ?>1<?= $this->endSection() ?>
<?= $this->section("use_datatables_buttons") ?>1<?= $this->endSection() ?>
<?= $this->section("use_select2") ?>1<?= $this->endSection() ?>

<?= $this->section("breadcrumbs") ?>
    <?= $breadcrumbs ?>
<?= $this->endSection() ?>

<?= $this->section("halaman") ?>
    Daftar Tautan Kehadiran Acara
<?= $this->endSection() ?>

<?= $this->section("tambah") ?>
<a href="<?= base_url("admin/hadir/tambah") ?>" class="btn btn-primary btn-sm"><i data-feather="plus"></i> Buat Acara Baru</a>
<?= $this->endSection() ?>

<?= $this->section("konten") ?>
<div id="copy_link"></div>

<table id="daftar-link-acara" class="table table-hover">
    <thead>
    <tr class="tx-center">
        <th>No.</th>
        <th class="wd-10p">Kode</th>
        <th class="wd-20p">Nama Acara</th>
        <th class="wd-20p">Waktu dan Tempat</th>
        <th class="wd-30p">Pembuat / Pengubah</th>
        <th class="wd-15p">Aksi</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($data as $i=>$d): ?>
    <tr>
        <td class="align-middle tx-center"><?= $i+1 ?></td>
        <td class="align-middle tx-center tx-bold"><?= $d->kode_acara ?></td>
        <td class="align-middle"><?= $d->nama_acara ?></td>
        <td class="align-middle">
            <?= (new IntlDateFormatter("id_ID",IntlDateFormatter::FULL,IntlDateFormatter::SHORT,"Asia/Jakarta",IntlDateFormatter::GREGORIAN,"eeee, dd MMMM yyyy"))->format(new DateTime($d->tanggal)) ?><br>
            Pukul <?= date_format(date_create($d->tanggal),"H.i") ?> WIB<br>
            <?= (!filter_var($d->lokasi, FILTER_VALIDATE_URL) === false) ?
                "<span class='tx-success tx-bold'>Daring (online)</span>" :
                "<span class='tx-gray-600 tx-bold'>Luring (offline)</span>"
            ?>
        </td>
        <td class="align-middle">
            <?= "<b>" . $d->nama . "</b><br>" . $d->jabatan . "<br>" . $d->nama_departemen ?><br>
        </td>
        <td class="align-middle tx-center">
            <a href="<?= base_url("admin/hadir/detail/$d->kode_acara") ?>" class="btn btn-primary btn-xs btn-block">
                <i data-feather="settings"></i> Detail Acara</a>
            <?php if($d->status === '0'): ?>
                <a onclick="tutupConfirm('<?= base_url('admin/hadir/tutup/'.$d->kode_acara) ?>')" href="#"
                   class="btn btn-danger btn-xs btn-block"><i data-feather="x-octagon"></i> Tutup Akses</a>
            <?php else: ?>
                <a href="<?= base_url("admin/hadir/buka/$d->kode_acara") ?>" class="btn btn-outline-secondary btn-xs btn-block">
                    <i data-feather="eye"></i> Buka Akses</a>
            <?php endif; ?>
            <?php if($d->jumlah === null): ?>
                <a onclick="deleteConfirm('<?= base_url('admin/hadir/hapus/'.$d->kode_acara) ?>')" href="#" class="btn btn-dark btn-xs btn-block"><i data-feather="trash-2"></i> Hapus</a>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<div class="modal fade" id="modal_tutup" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog wd-sm-400" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold">Konfirmasi</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="font-weight-bold tx-14">Apakah kamu yakin ingin MENUTUP AKSES presensi?</p>
                <p class="tx-danger" id="konfirm_tutup">Peserta <b>TIDAK BISA </b>melakukan presensi lagi setelah akses ditutup</p>
                <span>Klik tombol <b>TUTUP</b> untuk melanjutkan.</span>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary btn-xs" type="button" data-dismiss="modal">Batal</button>
                <a class="btn btn-danger btn-xs" id="btn-tutup" href="#">Tutup</a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section("js") ?>

<script>
    function tutupConfirm(url)
    {
        $("#btn-tutup").attr("href", url);
        $("#modal_tutup").modal();
    }

    $('#daftar-link-acara').DataTable({
        <?= $this->include("layout/datatable.txt") ?>
        language: {
            emptyTable: 'Belum terdapat acara.',
            zeroRecords: 'Belum terdapat acara yang sesuai.'
        }
    });
</script>

<?= $this->endSection() ?>

<?php endif; ?>