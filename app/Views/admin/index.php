<?= $this->extend("layout/master-admin") ?>
<?php if(isset($data, $data1, $data2, $data3, $data4, $data5)): ?>
<?= $this->section("title") ?>
Admin HMSI | Beranda
<?= $this->endSection() ?>

<?= $this->section("use_chart") ?>1<?= $this->endSection() ?>
<?= $this->section("use_parsley") ?>1<?= $this->endSection() ?>

<?= $this->section("halaman") ?>
Selamat Datang <span class="tx-primary">Arek Pilar Aksi ✨</span>
<?= $this->endSection() ?>

<?= $this->section("konten") ?>

<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header bd-b-0 pd-t-20 pd-lg-t-25 pd-l-20 pd-lg-l-25 d-flex flex-column flex-sm-row align-items-sm-start justify-content-sm-between">
                <div>
                    <h6 class="mg-b-5">Jumlah Penyelenggaraan Acara per Departemen</h6>
                    <p class="tx-12 tx-color-03 mg-b-0">Dihitung berdasarkan jumlah total pranala yang ada di web Admin HMSI</p>
                </div>
            </div>
            <div class="card-body pd-y-25">
                <div class="row">
                    <div class="col-sm-12 col-lg-5">
                        <div class="chart-thirteen" style="height:250px"><canvas id="chartDonut"></canvas></div>
                    </div>
                    <div class="col-sm-12 col-lg-7 tx-10 animated fadeInLeft delay-2s">
                        <table class="table table-striped table-hover table-borderless">
                            <thead>
                            <tr class="tx-center">
                                <th>#</th>
                                <th class="wd-90p">Nama Departemen</th>
                                <th class="wd-10p">Jumlah</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($data1 as $i=>$d): ?>
                                <tr>
                                    <td><span style="background-color:<?= $data2[$i] ?>; color:<?= $data2[$i] ?>;font-size:10px;">⠀⠀</span></td>
                                    <td class="align-middle"><?= $d->nama_departemen ?></td>
                                    <td class="align-middle tx-center"><?= $d->jumlah ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12 col-lg-4 mg-t-30 mg-lg-t-0">
        <div class="card card-widget card-events">
            <div class="card-header">
                <span class="tx-bold">Acara yang Akan Datang</span>
            </div>
            <div class="card-body">
                <ul class="list-unstyled media-list mg-b-0">
                    <?php if(array_key_last($data) === null): ?>
                        <li class="media animated fadeInDown delay-3s">
                            <span class="tx-bold tx-danger">Belum ada kegiatan yang tercatat</span>
                        </li>
                    <?php else: ?>
                        <?php foreach($data as $i=>$d): ?>
                            <li class="media animated fadeInDown delay-<?= $i+3 ?>s">
                                <div class="media-left">
                                    <label><?= substr((new IntlDateFormatter("id_ID", IntlDateFormatter::FULL, IntlDateFormatter::SHORT, "Asia/Jakarta", IntlDateFormatter::GREGORIAN, "eeee, dd MMMM yyyy 'pukul' HH.mm z'"))->format(new DateTime($d->tanggal)),0,3) ?></label>
                                    <p><?= (new IntlDateFormatter("id_ID",IntlDateFormatter::FULL,IntlDateFormatter::SHORT,"Asia/Jakarta",IntlDateFormatter::GREGORIAN,"dd"))->format(new DateTime($d->tanggal)) ?></p>
                                </div>
                                <div class="media-body event-panel-<?php switch($d->id_departemen){
                                    case(1):case(2):case(3):case(4): echo "pink"; break;
                                    case(5):case(7):case(9):case(13): echo "primary"; break;
                                    default: echo "green";break;
                                }?>">
                                    <span class="event-desc"><?= date_format(date_create($d->tanggal),"H.i") . " WIB" ?></span><br>
                                    <span class="event-title tx-bold"><?= $d->nama_acara ?></span><br>
                                    <span class="event-desc tx-medium"><?= $d->nama_departemen ?></span>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row mg-t-30">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header bd-b-0 pd-t-20 pd-lg-t-25 pd-l-20 pd-lg-l-25 d-flex flex-column flex-sm-row align-items-sm-start justify-content-sm-between">
                <div>
                    <h6 class="mg-b-5">Rerata Nilai Fungsionaris per Departemen</h6>
                    <p class="tx-12 tx-color-03 mg-b-0">Dihitung berdasarkan rata-rata nilai akhir setiap staf masing-masing departemen</p>
                </div>
            </div>
            <div class="card-body pd-lg-25">
                <div class="row align-items-sm-end">
                    <div class="col-lg-7 col-xl-8">
                        <div class="chart-six" style="height:250px"><canvas id="chartBar1"></canvas></div>
                    </div>
                    <div class="col-lg-5 col-xl-4 mg-t-30 mg-lg-t-0">
                        <div class="row col-12">
                            <p class="tx-center"></p>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex align-items-center justify-content-between mg-b-5">
                                    <h6 class="tx-10 tx-color-02 tx-semibold mg-b-0">Capaian Penilaian</h6>
                                    <span class="tx-10 tx-color-03"><?= ceil((array_key_last($data5) + 1) / 5 / 67 * 100) ?>% selesai</span>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mg-b-5">
                                    <h5 class="tx-normal tx-rubik lh-2 mg-b-0"><?= round((array_key_last($data5) + 1) / 5) ?> Orang</h5>
                                    <h6 class="tx-normal tx-rubik tx-color-03 lh-2 mg-b-0">67</h6>
                                </div>
                                <div class="progress ht-4 mg-b-0 op-5">
                                    <div class="progress-bar bg-danger" role="progressbar"
                                         style="width: <?= ceil((array_key_last($data5) + 1) / 5 / 68 * 100) ?>%; !important"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row mg-t-30">
                            <div class="col-6">
                                <div class="d-flex align-items-center justify-content-between mg-b-5">
                                    <h6 class="tx-10 tx-color-02 tx-semibold mg-b-0">Rerata Kekeluargaan</h6>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mg-b-5">
                                    <h5 class="tx-normal tx-rubik lh-2 mg-b-0 tx-20" id="rerata_inisiatif" style="color: #5C86F2;">
                                        <?php
                                        $hitung1 = 0;
                                        foreach($data3 as $d3) { $hitung1 += $d3->rerata; }
                                        echo round($hitung1 / 9, 2);
                                        ?>
                                        <span class="tx-10"> / 100</span>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center justify-content-between mg-b-5">
                                    <h6 class="tx-10 tx-color-02 tx-semibold mg-b-0">Rerata Keprofesian</h6>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mg-b-5">
                                    <h5 class="tx-normal tx-rubik lh-2 mg-b-0 tx-20" id="rerata_integritas" style="color: #45BCA8;">
                                        <?php
                                        $hitung2 = 0;
                                        foreach($data4 as $d4) { $hitung2 += $d4->rerata; }
                                        echo round($hitung2 / 9,2);
                                        ?>
                                        <span class="tx-10"> / 100</span>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ganti_pass" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog wd-sm-400" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold">Ubah Kata Sandi Default</h5>
            </div>
            <form action="<?= base_url("admin/akun/ubah_pass") ?>" method="post" id="form_pass" data-parsley-validate>
            <?= csrf_field() ?>
                <div class="modal-body">
                    <p class="font-weight-bold tx-12 tx-danger">Ups! kamu harus mengganti password default demi keamanan akunmu!</p>
                    <div id="alert_pass" style="display: none">
                        <div class="alert alert-danger alert-dismissible fade show mt-3 mb-3" role="alert">
                            Kata Sandi yang dimasukkan tidak cocok atau sama dengan password default
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="pass_lama" value="1234">
                    <div class="form-group">
                        <label for="pass_baru1" class="tx-bold">Kata Sandi Baru <span class="tx-danger">*</span></label>
                        <input id="pass_baru1" name="pass_baru1" type="password" class="form-control" placeholder="Masukkan kata sandi baru" required  data-parsley-required-message="Kata sandi Baru wajib diisi!">
                    </div>
                    <div class="form-group">
                        <label for="pass_baru2" class="tx-bold">Ketik Ulang Kata Sandi Baru <span class="tx-danger">*</span></label>
                        <input id="pass_baru2" name="pass_baru2" type="password" class="form-control" placeholder="Masukkan kata sandi baru" required  data-parsley-required-message="Kata sandi Baru wajib diisi!">
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-primary btn-xs" href="#" id="tombol_pass">Ubah Kata Sandi</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section("js") ?>

<script type="text/javascript">
    $(function() {
        const donutLabels = [
            <?php foreach ($data1 as $dept): ?>
            <?= json_encode($dept->nama_departemen, JSON_UNESCAPED_UNICODE) ?>,
            <?php endforeach; ?>
        ];

        // For a pie chart
        let ctx2 = document.getElementById('chartDonut');
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: donutLabels,
                datasets: [{
                    data: [
                        <?php
                        foreach ($data1 as $i) {
                            echo "'" . $i->jumlah . "',";
                        }
                        ?>
                    ],
                    backgroundColor: [
                        <?php
                        foreach ($data2 as $d2) {
                            echo "'" . $d2 . "',";
                        }
                        ?>
                    ]
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                legend: {
                    display: false,
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });

        let ctx1 = document.getElementById('chartBar1').getContext('2d');
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ["ES","EA","HRD","IM","IA","RND","SocDev","SWF"],
                datasets: [{
                    data: [
                        <?php
                        foreach ($data3 as $j) {
                            echo "'" . $j->rerata . "',";
                        }
                        ?>
                    ],
                    backgroundColor: '#87A2E8'
                }, {
                    data: [
                        <?php
                        foreach ($data4 as $k) {
                            echo "'" . $k->rerata . "',";
                        }
                        ?>
                    ],
                    backgroundColor: '#75E0CF'
                }]
            },
            options: {
                maintainAspectRatio: false,
                legend: {
                    display: false,
                },
                scales: {
                    xAxes: [{
                        display: true,
                        barPercentage: 0.75,
                        ticks: {
                            fontColor: '#8392a5',
                            fontSize: 9,
                        }
                    }],
                    yAxes: [{
                        gridLines: {
                            color: '#ebeef3'
                        },
                        ticks: {
                            fontColor: '#8392a5',
                            fontSize: 9,
                            min: 0,
                            max: 100
                        }
                    }]
                }
            }
        });
    });

    $("#tombol_pass").on("click", function()
    {
        let pass1 = $("#pass_baru1").val();
        let pass2 = $("#pass_baru2").val();
        return ((pass1 === pass2) && (pass1 !== "1234") && (pass1 !== "") && (pass2 !== "")) ? document.getElementById('form_pass').submit() : $("#alert_pass").show();
    });

    $.ajax({
        type: "GET",
        url: "<?= base_url('ajax/cek_password') ?>/" + <?= session()->get("id_pengurus") ?>,
        dataType: "json",

        success: function (data)
        {
            console.log(data);
            if(data === "ganti")
            {
                $('#ganti_pass').modal();
            }
        }
    });
</script>

<?= $this->endSection() ?>

<?php endif; ?>