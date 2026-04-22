<?= $this->extend("layout/master-admin") ?>
<?php if(isset($data, $breadcrumbs)): ?>

<?= $this->section("title") ?>
    Admin HMSI | Hadir | Rekap
<?= $this->endSection() ?>

<?= $this->section("use_datatables") ?>1<?= $this->endSection() ?>
<?= $this->section("use_datatables_buttons") ?>1<?= $this->endSection() ?>
<?= $this->section("use_datatables_export") ?>1<?= $this->endSection() ?>

<?= $this->section("page_css") ?>
<style>
    #rekap-acara_wrapper table.dataTable.no-footer {
        border-bottom: 0 !important;
    }

    #rekap-acara thead th {
        color: #001737;
        font-weight: 700;
        border-bottom: 1px solid #d9dee8;
        padding-top: 14px;
        padding-bottom: 14px;
    }

    #rekap-acara.dataTable thead > tr > th.sorting,
    #rekap-acara.dataTable thead > tr > th.sorting_asc,
    #rekap-acara.dataTable thead > tr > th.sorting_desc {
        padding-right: 26px;
        background-position: right 8px center;
    }

    #rekap-acara.dataTable thead > tr > th.sorting_asc,
    #rekap-acara.dataTable thead > tr > th.sorting_desc,
    #rekap-acara.dataTable thead > tr > th.sorting {
        background-image: none !important;
    }

    #rekap-acara.dataTable thead > tr > th.sorting::before,
    #rekap-acara.dataTable thead > tr > th.sorting::after,
    #rekap-acara.dataTable thead > tr > th.sorting_asc::before,
    #rekap-acara.dataTable thead > tr > th.sorting_desc::before {
        display: none !important;
        content: none !important;
    }

    #rekap-acara.dataTable thead > tr > th.sorting_asc::after,
    #rekap-acara.dataTable thead > tr > th.sorting_desc::after {
        display: inline-block !important;
        right: 8px;
    }

    #rekap-acara tbody td {
        border-top: 1px solid #ebedf2;
        line-height: 1.55;
        padding-top: 12px;
        padding-bottom: 12px;
    }

    #rekap-acara tbody tr:hover {
        background-color: #fbfcff;
    }

    #rekap-acara .btn {
        min-width: 118px;
    }

    #rekap-acara_wrapper .dataTables_paginate .paginate_button {
        min-width: 36px;
        height: 36px;
        padding: 0 12px !important;
        margin: 0 3px;
        border: 1px solid #d9dee8 !important;
        border-radius: 8px;
        color: #344563 !important;
        background: #fff !important;
        line-height: 34px;
        transition: all .15s ease;
    }

    #rekap-acara_wrapper .dataTables_paginate .paginate_button:hover {
        border-color: #c5d0e3 !important;
        background: #f5f8ff !important;
        color: #1f2e4d !important;
    }

    #rekap-acara_wrapper .dataTables_paginate .paginate_button.current,
    #rekap-acara_wrapper .dataTables_paginate .paginate_button.current:hover {
        border-color: #3f51c6 !important;
        background: #3f51c6 !important;
        color: #fff !important;
        box-shadow: 0 6px 16px rgba(63, 81, 198, .2);
    }

    #rekap-acara_wrapper .dataTables_paginate .paginate_button.disabled,
    #rekap-acara_wrapper .dataTables_paginate .paginate_button.disabled:hover {
        opacity: .5;
        cursor: not-allowed;
        background: #fff !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section("breadcrumbs") ?>
<?= $breadcrumbs ?>
<?= $this->endSection() ?>

<?= $this->section("halaman") ?>
    Rekap Kehadiran Acara
<?= $this->endSection() ?>

<?= $this->section("konten") ?>

<table id="rekap-acara" class="table table-hover">
    <thead>
    <tr class="tx-center">
        <th>No.</th>
        <th class="wd-10p">Kode</th>
        <th class="wd-20p">Nama Acara (Peserta)</th>
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
            <td class="align-middle"><?= $d->nama_acara ?><br><span class="tx-info tx-bold"><?= $d->peserta ?> orang</span></td>
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
                <form action="<?= base_url("admin/hadir/rekap/detail") ?>" method="post">
                <?= csrf_field() ?>
                    <input type="hidden" name="kode_acara" id="kode_acara" value="<?= $d->kode_acara ?>">
                    <button type="submit" class="btn btn-primary"><i data-feather="file-text"></i> Tampilkan</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection() ?>

<?= $this->section("js") ?>

<script>
    const rekapAcaraConfig = {
        <?= $this->include("layout/datatable.txt") ?>
        columnDefs: [
            { targets: [0, 5], orderable: false }
        ],
        language: {
            emptyTable: 'Belum terdapat rekap acara.',
            zeroRecords: 'Belum terdapat rekap acara yang sesuai.'
        }
    };

    if ($.fn.dataTable && $.fn.dataTable.Buttons) {
        rekapAcaraConfig.dom = 'Bfrtip';
        rekapAcaraConfig.buttons = [
            {
                extend: 'print',
                text: "<i data-feather='file-text'></i> Ekspor PDF",
                title: 'Rekap Kehadiran Acara',
                autoPrint: false,
                className: 'btn btn-danger bg-danger'
            },
            {
                extend: 'excel',
                text: "<i data-feather='table'></i> Ekspor Excel",
                className: 'btn btn-success bg-success',
                filename: 'Rekap Kehadiran Acara',
                title: 'Rekap Kehadiran Acara'
            },
            {
                extend: 'pageLength',
                className: 'btn btn-outline-primary bg-white'
            }
        ];
    }

    $('#rekap-acara').DataTable(rekapAcaraConfig);
</script>

<?= $this->endSection() ?>
<?php endif; ?>