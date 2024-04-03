<?= $this->extend('layout/top-nav'); ?>
<?= $this->section('content'); ?>

<div class="content">
  <div class="container">

    <div class="row">
      <div class="col-md-4 col-12">
        <div class="info-box">
          <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-list"></i></span>
          <div class="info-box-content">
            <span class="info-box-text"><?= session()->get('language') == 2 ? 'Item' : 'Barang'; ?></span>
            <span class="info-box-number"><?= $barang ?></span>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-12">
        <div class="info-box mb-3">
          <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-users"></i></span>
          <div class="info-box-content">
            <span class="info-box-text"><?= session()->get('language') == 2 ? 'Customer' : 'Pelanggan'; ?></span>
            <span class="info-box-number"><?= $pelanggan ?></span>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-12">
        <div class="info-box mb-3">
          <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-exchange-alt"></i></span>
          <div class="info-box-content">
            <span class="info-box-text"><?= session()->get('language') == 2 ? 'Transaction' : 'Transaksi'; ?></span>
            <span class="info-box-number"><?= $transaksi ?></span>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <div class="card card-primary card-outline">
          <div class="card-header">
            <h5 class="card-title"><?= session()->get('language') == 2 ? 'Transaction Graph' : 'Grafik Transaksi'; ?> <?= date('Y') ?></h5>
          </div>
          <div class="card-body">
            <div id="bar-chart" style="height: 300px;"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

  $(function () {

    var bar_data = {
      data : <?= json_encode($chart['count']) ?>,
      bars: { show: true }
    }
    $.plot('#bar-chart', [bar_data], {
      grid  : {
        borderWidth: 1,
        borderColor: '#f3f3f3',
        tickColor  : '#f3f3f3'
      },
      series: {
         bars: {
          show: true, barWidth: 0.5, align: 'center',
        },
      },
      colors: ['#007bff'],
      xaxis : {
        ticks: <?= json_encode($chart['month']) ?>
      }
    });

  });
</script>

<?= $this->endSection(); ?>