<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Admin HMSI | Login</title>

    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url("pic/pilar-aksi-favicon.png") ?>">

    <link href="<?= base_url("main/lib/@fortawesome/fontawesome-free/css/all.min.css") ?>" rel="stylesheet">

    <link href="<?= base_url("main/assets/css/dashforge.min.css") ?>" rel="stylesheet">
    <link href="<?= base_url("main/lib/animate.css/animate.min.css") ?>" rel="stylesheet">
</head>
<body>

<div class="container mt-3 col-md-6 col-lg-4 col-xl-3">
    <h3>Selamat Datang</h3>
    <p class="tx-gray-700 tx-12">Silakan lakukan login terlebih dahulu</p>

    <form action="<?= base_url("admin/login") ?>" method="post" data-parsley-validate class="animated fadeInUp">
    <?= csrf_field() ?>
        <div class="form-group">
            <label for="username" class="tx-bold">NRP <span class="tx-danger">*</span></label>
            <input id="username" name="username" type="text" class="form-control" placeholder="Masukkan NRP" required data-parsley-required-message="NRP wajib diisi!">
        </div>
        <div class="form-group">
            <label for="password" class="tx-bold">Kata Sandi <span class="tx-danger">*</span></label>
            <input id="password" name="password" type="password" class="form-control" placeholder="Masukkan password" required data-parsley-required-message="Password wajib diisi!">
        </div>
        <button type="submit" class="btn btn-block btn-primary btn-icon">
            <i data-feather="log-in"></i> <span>Masuk</span>
        </button>
    </form>

    <?php if(session()->has("error")): ?>
        <div class="alert alert-danger alert-dismissible fade show mt-3 animated fadeInDown delay-1s" role="alert">
            <?= session()->getFlashdata("error") ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
    <?php endif; ?>

    <footer class="content-footer tx-9 mb-3">
        <div>
            <span>Copyright &copy; 2023 - <?= date("Y") ?> by <a href="https://arek.its.ac.id/hmsi" target="_blank"><b>HMSI ITS</b></a></span>
        </div>        
        <div>
            <span>Support by <a href="https://www.tekan.id" target="_blank"><b>Tekan.ID</b></a></span><br>
        </div>
    </footer>
</div>

<script src="<?= base_url("main/lib/jquery/jquery.min.js") ?>"></script>
<script src="<?= base_url("main/lib/bootstrap/js/bootstrap.bundle.min.js") ?>"></script>
<script src="<?= base_url("main/lib/feather-icons/feather.min.js") ?>"></script>
<script src="<?= base_url("main/lib/parsleyjs/parsley.min.js") ?>"></script>
<script src="<?= base_url("main/assets/js/dashforge.js") ?>"></script>

</body>
</html>