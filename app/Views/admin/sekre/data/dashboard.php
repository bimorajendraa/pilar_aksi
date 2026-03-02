<?= $this->extend("layout/master-admin") ?>
<?php if(isset($data, $breadcrumbs)): ?>

<?= $this->section("title") ?>
Admin HMSI | Sekre | Data | Dashboard
<?= $this->endSection() ?>

<?= $this->section("breadcrumbs") ?>
<?= $breadcrumbs ?>
<?= $this->endSection() ?>

<?= $this->section("halaman") ?>
Dashboard Data Mahasiswa
<?= $this->endSection() ?>

<?= $this->section("konten") ?>

<!-- Filter Angkatan -->
<div class="d-flex align-items-center mg-b-15">
    <label for="filter-angkatan" class="tx-bold mg-r-10 mg-b-0">Filter Angkatan:</label>
    <select id="filter-angkatan" class="form-control wd-150">
        <option value="">Semua</option>
        <?php if(isset($angkatan_list)): ?>
        <?php foreach ($angkatan_list as $a): ?>
            <option value="<?= $a->angkatan ?>"><?= $a->angkatan ?></option>
        <?php endforeach; ?>
        <?php endif; ?>
    </select>
</div>

<table id="daftar-nrp" class="table table-hover">
    <thead>
    <tr class="tx-center tx-bold">
        <th class="wd-5p">No.</th>
        <th class="wd-35p">Nama</th>
        <th class="wd-15p">NRP</th>
        <th class="wd-10p">Angkatan</th>
        <th class="wd-35p">Program Studi</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($data as $i=>$d): ?>
        <tr>
            <td class="align-middle tx-center tx-bold"><?= $i+1 ?></td>
            <td class="align-middle"><?= $d->nama ?></td>
            <td class="align-middle"><?= $d->nrp ?></td>
            <td class="align-middle tx-center"><?= $d->angkatan ?></td>
            <td class="align-middle"><?= $d->prodi ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection() ?>

<?= $this->section("js") ?>

<script>
    var table = $('#daftar-nrp').DataTable({
        <?= $this->include("layout/datatable.txt") ?>
        dom: '<"d-flex justify-content-between align-items-center mb-3"lfB>rt<"d-flex justify-content-between align-items-center mt-3"ip>',
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i data-feather="file-text"></i> Export Excel',
                className: 'btn btn-sm btn-success',
                title: 'Data Mahasiswa HMSI',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4],
                    modifier: { search: 'applied' } // Respect filter
                },
                customize: function(xlsx) {
                    // Add angkatan info to filename
                    var filter = $('#filter-angkatan').val();
                    if (filter) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    }
                }
            },
            {
                extend: 'pdfHtml5',
                text: '<i data-feather="file"></i> Export PDF',
                className: 'btn btn-sm btn-danger',
                title: 'Data Mahasiswa HMSI',
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4],
                    modifier: { search: 'applied' }
                }
            },
            {
                extend: 'print',
                text: '<i data-feather="printer"></i> Print',
                className: 'btn btn-sm btn-primary',
                title: 'Data Mahasiswa HMSI',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4],
                    modifier: { search: 'applied' }
                }
            }
        ]
    });

    // Filter by Angkatan
    $('#filter-angkatan').on('change', function() {
        var val = $(this).val();
        // Column index 3 = Angkatan, exact match
        table.column(3).search(val ? '^' + val + '$' : '', true, false).draw();
    });

    // Re-init feather icons after DataTable buttons render
    feather.replace();
</script>

<?= $this->endSection() ?>

<?php endif; ?>