<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title><?= WEBSITE ?></title>
        <link rel="icon" type="image/x-icon" href="<?= base_url('public/assets/img/icon-smkm.png') ?>">
        <link href="<?= base_url('public/assets/css/auth.css') ?>" rel="stylesheet" />
        <style type="text/css">
            body {
                font-family: Arial;
            }
            .card-header h3 {
                font-weight: bold;
            }
            .btn {
                font-weight: bold;
            }
            .btn-block {
                width: 100%;
            }
            .small a {
                text-decoration: none;
            }
        </style>
    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div style="margin-top: 25%;">
                                    <script src="<?= base_url('public/assets') ?>/plugins/jquery/jquery.min.js"></script>
                                    <script src="<?= base_url('public/assets') ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
                                    <script src="<?= base_url('public/assets') ?>/plugins/jquery-validation/jquery.validate.min.js"></script>
                                    <script src="<?= base_url('public/assets') ?>/plugins/jquery-validation/additional-methods.min.js"></script>
                                    <div class="card shadow-lg border-0 rounded-lg mt-5">
                                        <div class="card-header"><h3 class="text-center font-weight-light my-4"><marquee behavior="" direction=""><?= WEBSITE ?></marquee></h3></div>
                                        <?= $this->renderSection('content'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
