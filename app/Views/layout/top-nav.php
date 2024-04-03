  <!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
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
<body class="hold-transition layout-top-nav">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand-md navbar-primary navbar-dark">
    <div class="container">
      <a href="javascript:void(0)" class="navbar-brand">
        <img src="<?= base_url('public/assets/img/logo-smkm.png') ?>" alt="" class="brand-image img-circle" style="background-color: white; width: 33px; height: 33px;">
        <span class="brand-text">MST</span>
      </a>

      <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse order-3" id="navbarCollapse">
        <!-- Left navbar links -->
        <?php
          $request    = \Config\Services::request();
          $uri        = $request->uri->getSegment(2) ? $request->uri->getSegment(2) : $request->uri->getSegment(1);
        ?>
        <ul class="navbar-nav">
          <li class="nav-item">
            <a href="<?= base_url('home') ?>" class="nav-link <?php if ($uri == 'home') echo 'active' ?>"><?= session()->get('language') == 2 ? 'Home' : 'Beranda'; ?></a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url('goods') ?>" class="nav-link <?php if ($uri == 'goods') echo 'active' ?>"><?= session()->get('language') == 2 ? 'Item' : 'Barang'; ?></a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url('customer') ?>" class="nav-link <?php if ($uri == 'customer') echo 'active' ?>"><?= session()->get('language') == 2 ? 'Customer' : 'Pelanggan'; ?></a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url('transaction') ?>" class="nav-link <?php if ($uri == 'transaction') echo 'active' ?>"><?= session()->get('language') == 2 ? 'Transaction' : 'Transaksi'; ?></a>
          </li>
        </ul>

      </div>

      <!-- Right navbar links -->
      <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="javascript:void(0)" style="padding-right: 4px;">
            <i class="fas fa-globe" style="width: 16px;"></i> <small style="font-weight: bold;"><?= session()->get('language') == 2 ? 'EN' : 'ID'; ?> </small>
          </a>
          <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
            <?php 
            $number     = 1;
            $countries  = array('ID' => 'Indonesia', 'EN' => 'English');
            foreach ($countries as $key => $value) : ?>
              <a href="<?= base_url('language/'. base64_encode($number)) ?>" class="dropdown-item">
                <img src="<?= base_url('public/assets/img/icon-'. $key .'.png') ?>" alt="" style="width: 24px;"> <?= $value ?>
              </a>
              <?php if ($number != count($countries)) : ?>
                <div class="dropdown-divider"></div>
              <?php endif ?>
              <?php $number++ ?>
            <?php endforeach ?>
          </div>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="javascript:void(0)">
            <i class="fas fa-user-alt"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
            <a href="<?= base_url('profile') ?>" class="dropdown-item">
              <i class="fas fa-user mr-2"></i> Profile
            </a>
            <div class="dropdown-divider"></div>
            <a href="<?= base_url('logout') ?>" class="dropdown-item">
              <i class="fas fa-sign-in-alt mr-2"></i> Logout
            </a>
          </div>
        </li>
      </ul>
    </div>
  </nav>
  <!-- /.navbar -->

  

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0"><?= $title ?></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right"></ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- REQUIRED SCRIPTS -->

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
        $('#alert').delay(2750).slideUp('slow', function(){
          $(this).remove();
        });
      });
    </script>
    <!-- Main content -->
    <?= csrf_field(); ?>
    <?= $this->renderSection('content'); ?>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer" style="border-top: 2px solid #007bff;">
    <!-- To the right -->
    <!-- <div class="float-right d-none d-sm-inline">Versi 1.0</div> -->
    <!-- Default to the left -->
    <strong>Copyright &copy; 2022- <?= date('Y') ?> <a href="javascript:void(0)">Alamsyah Firdaus</a>.</strong>
  </footer>
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
