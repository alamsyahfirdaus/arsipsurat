<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= WEBSITE ?></title>
  <link rel="icon" type="image/x-icon" href="<?= base_url('public/assets/img/icon-smkm.png') ?>">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="<?= base_url('public/assets') ?>/plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?= base_url('public/assets') ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= base_url('public/assets') ?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="<?= base_url('public/assets') ?>/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="<?= base_url('public/assets') ?>/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="<?= base_url('public/assets') ?>/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- daterange picker -->
  <link rel="stylesheet" href="<?= base_url('public/assets') ?>/plugins/daterangepicker/daterangepicker.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="<?= base_url('public/assets') ?>/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- Bootstrap4 Duallistbox -->
  <link rel="stylesheet" href="<?= base_url('public/assets') ?>/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
  <!-- BS Stepper -->
  <link rel="stylesheet" href="<?= base_url('public/assets') ?>/plugins/bs-stepper/css/bs-stepper.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url('public/assets') ?>/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <!-- Site wrapper -->
  <div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-primary navbar-dark">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="javascript:void(0)" role="button"><i class="fas fa-bars"></i></a>
        </li>
      </ul>
      <?php
      $request    = \Config\Services::request();
      $uri        = $request->uri->getSegment(2) ? $request->uri->getSegment(2) : $request->uri->getSegment(1);
      $db         = db_connect();
      ?>
      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="javascript:void(0)">
            <i class="fas fa-user-alt"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
            <a href="<?= base_url('logout') ?>" class="dropdown-item">
              <i class="fas fa-sign-in-alt mr-2"></i> Keluar
            </a>
          </div>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary">
      <!-- Brand Logo -->
      <a href="javascript:void(0)" class="brand-link" style="background-color: #007bff; border-bottom: 0px;">
        <img src="<?= base_url('public/assets/img/icon-smkm.png') ?>" alt="" class="brand-image" style="background-color: #fff; border-bottom: 0px; padding: 1px; border-radius: 6px;">
        <span class="brand-text" style="font-weight: bold; color: #fff;">ARSIP SURAT</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="<?= base_url('public/assets/img/avatar.png') ?>" class="img-circle" alt="">
          </div>
          <div class="info">
            <?php
            $model  = new \App\Models\UserModel;
            $query  = $model->find(session()->get('id_pengguna'));
            ?>
            <a href="javascript:void(0)" class="d-block"><?= $query->nama_pengguna ?></a>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
              <a href="<?= base_url('home') ?>" class="nav-link <?php if ($uri == 'home') echo 'active' ?>">
                <i class="nav-icon fas fa-home"></i>
                <p>Beranda</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('user') ?>" class="nav-link <?php if ($uri == 'user') echo 'active' ?>">
                <i class="nav-icon fas fa-users"></i>
                <p>Pengguna</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('type') ?>" class="nav-link <?php if ($uri == 'type') echo 'active' ?>">
                <i class="nav-icon fas fa-envelope"></i>
                <p>Jenis Surat</p>
              </a>
            </li>
            <li class="nav-item <?php if ($request->uri->getSegment(1) == 'letter' && $request->uri->getSegment(2) != NULL) echo 'menu-open' ?>">
              <a href="#" class="nav-link <?php if ($request->uri->getSegment(1) == 'letter' && $request->uri->getSegment(2) != NULL) echo 'active' ?>">
                <i class="nav-icon fas fa-database"></i>
                <p>
                  Data Surat
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <?php foreach ($db->table('jenis_surat')->get()->getResult() as $field) : ?>
                  <li class="nav-item">
                    <a href="<?= base_url('letter/' . base64_encode($field->id_jenis_surat)) ?>" class="nav-link <?php if ($request->uri->getSegment(2) == base64_encode($field->id_jenis_surat)) echo 'active' ?>">
                      <i class="far fa-circle nav-icon"></i>
                      <p><?= $field->nama_jenis_surat ?></p>
                    </a>
                  </li>
                <?php endforeach ?>
              </ul>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('letter') ?>" class="nav-link <?php if ($request->uri->getSegment(1) == 'letter' && $request->uri->getSegment(2) == NULL) echo 'active' ?>">
                <i class="nav-icon fas fa-table"></i>
                <p>Rekap Surat</p>
              </a>
            </li>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1><?= $title ?></h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right"></ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        <!-- jQuery -->
        <script src="<?= base_url('public/assets') ?>/plugins/jquery/jquery.min.js"></script>
        <!-- Bootstrap 4 -->
        <script src="<?= base_url('public/assets') ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- DataTables  & Plugins -->
        <script src="<?= base_url('public/assets') ?>/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="<?= base_url('public/assets') ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="<?= base_url('public/assets') ?>/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
        <script src="<?= base_url('public/assets') ?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
        <!-- Select2 -->
        <script src="<?= base_url('public/assets') ?>/plugins/select2/js/select2.full.min.js"></script>
        <!-- Bootstrap4 Duallistbox -->
        <script src="<?= base_url('public/assets') ?>/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
        <!-- InputMask -->
        <script src="<?= base_url('public/assets') ?>/plugins/moment/moment.min.js"></script>
        <script src="<?= base_url('public/assets') ?>/plugins/inputmask/jquery.inputmask.min.js"></script>
        <!-- date-range-picker -->
        <script src="<?= base_url('public/assets') ?>/plugins/daterangepicker/daterangepicker.js"></script>
        <!-- Tempusdominus Bootstrap 4 -->
        <script src="<?= base_url('public/assets') ?>/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
        <!-- dropzonejs -->
        <script src="<?= base_url('public/assets') ?>/plugins/dropzone/min/dropzone.min.js"></script>
        <!-- jquery-validation -->
        <script src="<?= base_url('public/assets') ?>/plugins/jquery-validation/jquery.validate.min.js"></script>
        <script src="<?= base_url('public/assets') ?>/plugins/jquery-validation/additional-methods.min.js"></script>
        <!-- BS-Stepper -->
        <script src="<?= base_url('public/assets') ?>/plugins/bs-stepper/js/bs-stepper.min.js"></script>
        <!-- FLOT CHARTS -->
        <script src="<?= base_url('public/assets') ?>/plugins/flot/jquery.flot.js"></script>
        <!-- AdminLTE App -->
        <script src="<?= base_url('public/assets') ?>/js/adminlte.min.js"></script>
        <script type="text/javascript">
          var table;
          $(function() {
            $('#alert').delay(2750).slideUp('slow', function() {
              $(this).remove();
            });
          });
        </script>
        <?= csrf_field(); ?>
        <?= $this->renderSection('content'); ?>
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
      <!-- <div class="float-right d-none d-sm-block"><b>Version</b> 3.2.0</div> -->
      <strong>Copyright &copy; 2023- <?= date('Y') ?> <a href="javascript:void(0)">Rifqi Aminulloh</a>.</strong>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->
  <script type="text/javascript">
    $(function() {
      $('.select2').select2();
    });
  </script>

  <style type="text/css">
    body {
      font-family: Arial;
    }

    .navbar-dark {
      border: #007bff;
    }

    .brand-text {
      font-weight: bold;
    }

    .content-wrapper {
      background-color: #fff;
    }

    a {
      color: #007bff;
      text-decoration: none;
      background-color: transparent;
    }

    a:hover {
      color: #007bff;
      text-decoration: none;
    }

    .card {
      border-top: 3px solid #007bff;
    }

    .card-footer {
      background-color: rgba(255, 255, 255, 0.03);
      border-top: 1px solid rgba(0, 0, 0, .125);
    }
  </style>
</body>

</html>