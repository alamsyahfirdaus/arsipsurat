<?= $this->extend('layout/fixed-sidebar'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid">
  <div class="row">
    <?php foreach ($jenis_surat as $field) : ?>
      <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
          <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-envelope"></i></span>
          <div class="info-box-content">
            <span class="info-box-text"><a href="<?= base_url('letter/' . base64_encode($field->id_jenis_surat)) ?>" style="color: #000000;"><?= $field->nama_jenis_surat ?></a></span>
            <span class="info-box-number">
              <?= db_connect()->table('surat')->where('jenis_surat_id', $field->id_jenis_surat)->countAllResults(); ?>
            </span>
          </div>
        </div>
      </div>
    <?php endforeach ?>
  </div>
</div>

<?= $this->endSection(); ?>