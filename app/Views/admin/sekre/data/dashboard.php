<?= $this->extend("layout/master-admin") ?>
<?php if(isset($data, $breadcrumbs)): ?>

<?= $this->section("title") ?>
Admin HMSI | Sekre | Data | Dashboard
<?= $this->endSection() ?>

<?= $this->section("use_datatables") ?>1<?= $this->endSection() ?>
<?= $this->section("use_select2") ?>1<?= $this->endSection() ?>

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
    <div class="mg-l-10">
        <button type="button" id="btn-export-csv" class="btn btn-sm btn-success">Download CSV</button>
        <button type="button" id="btn-export-xls" class="btn btn-sm btn-primary">Download Excel</button>
        <button type="button" id="btn-print" class="btn btn-sm btn-secondary">Print</button>
    </div>
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
    <?php if(empty($data)): ?>
        <tr class="row-empty-fallback">
            <td class="align-middle tx-center" colspan="5">Belum terdapat data mahasiswa.</td>
        </tr>
    <?php endif; ?>
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
    var table = null;

    if ($.fn && $.fn.DataTable) {
        table = $('#daftar-nrp').DataTable({
            <?= $this->include("layout/datatable.txt") ?>
            language: {
                emptyTable: 'Belum terdapat data mahasiswa.',
                zeroRecords: 'Belum terdapat data mahasiswa yang sesuai.'
            }
        });
    }

    function getFilteredRows() {
        if (table) {
            return table.rows({ search: 'applied' }).data().toArray();
        }

        var rows = [];
        $('#daftar-nrp tbody tr:visible').each(function() {
            var cols = $(this).find('td');
            if (cols.length === 5) {
                rows.push([
                    $(cols[0]).text().trim(),
                    $(cols[1]).text().trim(),
                    $(cols[2]).text().trim(),
                    $(cols[3]).text().trim(),
                    $(cols[4]).text().trim()
                ]);
            }
        });
        return rows;
    }

    function applyAngkatanFilter(val) {
        if (table) {
            var regex = val ? '^' + $.fn.dataTable.util.escapeRegex(val) + '$' : '';
            table.column(3).search(regex, true, false).draw();
            return;
        }

        $('#daftar-nrp tbody tr').each(function() {
            var $row = $(this);
            var cells = $row.find('td');
            if (cells.length !== 5) {
                return;
            }
            var angkatan = $(cells[3]).text().trim();
            var visible = (val === '' || angkatan === val);
            $row.toggle(visible);
        });
    }

    function downloadFile(content, filename, mimeType) {
        var blob = new Blob([content], { type: mimeType });
        var url = URL.createObjectURL(blob);
        var a = document.createElement('a');
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }

    function currentFilename(ext) {
        var filter = $('#filter-angkatan').val();
        var suffix = filter ? ('_angkatan_' + filter) : '_semua';
        return 'data_mahasiswa_hmsi' + suffix + '.' + ext;
    }

    $('#filter-angkatan').on('change', function() {
        applyAngkatanFilter($(this).val());
    });

    $('#btn-export-csv').on('click', function() {
        var rows = getFilteredRows();
        var header = ['No.', 'Nama', 'NRP', 'Angkatan', 'Program Studi'];
        var csv = [header.join(',')];

        rows.forEach(function(row) {
            var escaped = row.map(function(value) {
                var clean = String(value).replace(/"/g, '""');
                return '"' + clean + '"';
            });
            csv.push(escaped.join(','));
        });

        downloadFile(csv.join('\n'), currentFilename('csv'), 'text/csv;charset=utf-8;');
    });

    $('#btn-export-xls').on('click', function() {
        var rows = getFilteredRows();
        var header = ['No.', 'Nama', 'NRP', 'Angkatan', 'Program Studi'];
        var allRows = [header].concat(rows);
        var tsv = allRows.map(function(row) {
            return row.map(function(value) {
                return String(value).replace(/\t/g, ' ').replace(/\n/g, ' ');
            }).join('\t');
        }).join('\n');

        downloadFile(tsv, currentFilename('xls'), 'application/vnd.ms-excel;charset=utf-8;');
    });

    $('#btn-print').on('click', function() {
        var rows = getFilteredRows();
        var html = '<html><head><title>Data Mahasiswa HMSI</title></head><body>' +
            '<h3>Data Mahasiswa HMSI</h3>' +
            '<table border="1" cellspacing="0" cellpadding="6">' +
            '<thead><tr><th>No.</th><th>Nama</th><th>NRP</th><th>Angkatan</th><th>Program Studi</th></tr></thead><tbody>';

        rows.forEach(function(row) {
            html += '<tr>' +
                '<td>' + row[0] + '</td>' +
                '<td>' + row[1] + '</td>' +
                '<td>' + row[2] + '</td>' +
                '<td>' + row[3] + '</td>' +
                '<td>' + row[4] + '</td>' +
                '</tr>';
        });

        html += '</tbody></table></body></html>';

        var printWindow = window.open('', '_blank');
        printWindow.document.open();
        printWindow.document.write(html);
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
    });

    if (typeof feather !== 'undefined') {
        feather.replace();
    }
</script>

<?= $this->endSection() ?>

<?php endif; ?>