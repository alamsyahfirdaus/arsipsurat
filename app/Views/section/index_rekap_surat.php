<?= $this->extend('layout/fixed-sidebar'); ?>
<?= $this->section('content'); ?>

<?php

$no_surat 			= 'No Surat';
$nama_surat 		= 'Nama Surat';
$tanggal_surat 		= 'Tanggal Surat';
$jenis_surat 		= 'Jenis Surat';
$cari 				= 'Cari';

?>

<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<div id="flashdata"></div>
			<div class="card">
				<div class="card-header">
					<h5 class="card-title"><?= $heading ?></h5>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-lg-4">
							<div class="form-group">
								<label for="" style="padding-top: 8px;">Filter Tanggal</label>
							</div>
						</div>
						<div class="col-lg-4">
							<div class="form-group">
								<select class="form-control select2" name="dari_tanggal" id="dari_tanggal">
									<option value="">-- Dari Tanggal --</option>
									<?php foreach ($list_date as $key => $value) {
										echo '<option value="' . $key . '">' . $value . '</option>';
									} ?>
								</select>
							</div>
						</div>
						<div class="col-lg-4">
							<div class="form-group">
								<select class="form-control select2" name="sampai_tanggal" id="sampai_tanggal">
									<option value="">-- Sampai Tanggal --</option>
								</select>
							</div>
						</div>
					</div>
					<hr style="margin-top: 0px;">
					<div class="table-responsive">
						<table id="table" class="table table-bordered" style="width: 100%;">
							<thead>
								<tr>
									<?php

									$thead = array(
										'<th style="width: 5%; text-align: center;">No</th>',
										'<th>' . $no_surat . '</th>',
										'<th>' . $nama_surat . '</th>',
										'<th>' . $tanggal_surat . '</th>',
										'<th>' . $jenis_surat . '</th>'
									);

									$targets = array();
									for ($i = 0; $i < count($thead); $i++) {
										$targets[] = 0;
										echo $thead[$i];
									}

									?>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

	$('[name="dari_tanggal"]').change(function() {
		$('[name="sampai_tanggal"]').find('option').not(':first').remove();
		table.ajax.reload();
	});

	$('[name="sampai_tanggal"]').change(function() {
		table.ajax.reload();
	});

	$(function() {

		table = $('#table').DataTable({
			"processing": false,
			"serverSide": true,
			"paging": true,
			"lengthChange": true,
			"searching": true,
			"ordering": <?= count($targets) > 0 ? true : false ?>,
			"order": [],
			"info": true,
			"autoWidth": false,
			"responsive": false,
			"language": {
				"infoFiltered": "",
				"sZeroRecords": "",
				"sEmptyTable": "",
				"sSearch": "<?= $cari ?>:"
			},
			"ajax": {
				"url": "<?= site_url('list/recap') ?>/" + $('[name="<?= csrf_token() ?>"]').val(),
				"type": "POST",
				"data": function(data) {
					data.<?= csrf_token() ?> = $('[name="<?= csrf_token() ?>"]').val();
					data.dari_tanggal = $('[name="dari_tanggal"]').val();
					data.sampai_tanggal = $('[name="sampai_tanggal"]').val();
				},
			},
			"columnDefs": [{
				"targets": <?= count($targets) > 0 ? json_encode($targets) : [] ?>,
				"orderable": false,
			}],
			"drawCallback": function(settings) {
				$('[name="<?= csrf_token() ?>"]').val(settings.json.<?= csrf_token() ?>).change();
				var option = [];
				$.each(settings.json.tanggal, function (key, val) { 
					option.push({
						id: key,
						text: val
					});
				});
				$('[name="sampai_tanggal"]').select2({data: option});
			},
		});

	});
</script>

<?= $this->endSection(); ?>