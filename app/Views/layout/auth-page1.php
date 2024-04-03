<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?= WEBSITE ?></title>
    <link rel="icon" type="image/x-icon" href="<?= base_url('public/assets/img/icon-smkm.png') ?>">
    <link rel="stylesheet" href="<?= base_url('public/assets/css/auth.css') ?>">
    <link rel="stylesheet" href="<?= base_url('public/assets') ?>/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('public/assets/css/all.min.css') ?>">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="<?= base_url('public/assets/css/sb-admin-2.min.css') ?>" rel="stylesheet">
    <style>
        body {
            font-family: Arial;
            background-color: #1154a3;
        }

        marquee {
            color: #1154a3;
        }

        .card-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            margin: 0 auto;
            width: 75%;
        }

        .centered-img {
            width: 75%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
</head>

<body class="bg-gradient-primary">

    <div class="container">

        <div class="row justify-content-center">

            <div class="card-container col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0">
                    <div class="card-body p-0">
                        <script src="<?= base_url('public/assets/js/jquery.min.js') ?>"></script>
                        <script src="<?= base_url('public/assets/js/bootstrap.bundle.min.js') ?>"></script>
                        <script src="<?= base_url('public/assets') ?>/plugins/jquery/jquery.min.js"></script>
                        <script src="<?= base_url('public/assets') ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
                        <script src="<?= base_url('public/assets') ?>/plugins/jquery-validation/jquery.validate.min.js"></script>
                        <script src="<?= base_url('public/assets') ?>/plugins/jquery-validation/additional-methods.min.js"></script>
                        <?= $this->renderSection('content'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>