<?= $this->extend("layout/master-admin") ?>
<?php if(isset($data, $breadcrumbs)): ?>

<?= $this->section("title") ?>
Admin HMSI | Rapor | Dashboard
<?= $this->endSection() ?>

<?= $this->section("breadcrumbs") ?>
<?= $breadcrumbs ?>
<?= $this->endSection() ?>

<?= $this->section("halaman") ?>
Daftar Nilai Rapor Fungsionaris
<?= $this->endSection() ?>

<?= $this->section("konten") ?>

<?php $total = array_key_last($data); ?>
<!-- <php dd($total)?> -->
<?php for($i = 0 ; $i <= $total ; $i+=6): ?>
<div class="row mt-3">
    <?php for($j = $i ; $j < $i+6 ; $j+=2): ?>
        <div class="col-12 col-md-6 col-lg-4">
            <form action="<?= base_url("admin/rapor/hasil") ?>" method="post" id="hasil<?= $j ?>">
            <?= csrf_field() ?>
                <input type="hidden" id="id_pengurus" name="id_pengurus" value="<?= $data[$j]->id_pengurus ?>">
                <input type="hidden" id="id_bulan" name="id_bulan" value="<?= $data[$j]->id_bulan ?>">
                <a href="#" onclick="document.getElementById('hasil<?= $j ?>').submit();">
                <div class="card card-body btn-outline-<?= ($data[$j]->nilai === "0" || $data[$j+1]->nilai === "0") ? "danger" : "primary" ?>" style="min-height: 100px">
                    <span class="tx-10"><b><?php switch($data[$j]->id_bulan){
                                case(1): echo "APRIL"; break;
                                case(2): echo "MEI"; break;
                                case(3): echo "JUNI"; break;
                                case(4): echo "JULI"; break;
                                case(5): echo "AGUSTUS"; break;}
                            ?></b>
                    </span>
                    <div class="marker pos-absolute t-20 r-20 tx-10" style="text-transform: capitalize">Kekeluargaan: <?= round($data[$j]->nilai,2) ?></div>
                    <div class="marker pos-absolute b-20 r-20 tx-10" style="text-transform: capitalize">Keprofesian: <?= round($data[$j+1]->nilai,2) ?></div>
                    <span class="tx-bold mg-r-100 tx-14"><?= $data[$j]->nama ?></span>
                    <span class="mg-b-0 mg-t-0 mg-r-100 tx-10"><?= $data[$j]->jabatan . " " . $data[$j]->nama_departemen ?></span>
                </div>
                </a>
            </form>
        </div>
    <?php endfor; ?>
</div>
<?php endfor; ?>

<?= $this->endSection() ?>

<?php endif; ?>