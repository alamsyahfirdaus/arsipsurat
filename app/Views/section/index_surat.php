<?= $this->extend('layout/fixed-sidebar'); ?>
<?= $this->section('content'); ?>

<?php

$no_surat 			= 'No Surat';
$nama_surat 		= 'Nama Surat';
$titimangsa_surat 	= 'Tanggal Surat';
$lihat_surat 		= 'Lihat Surat';
$file_surat 		= 'File Surat';
$cari 				= 'Cari';
$aksi 				= 'Aksi';
$tambah 			= 'Tambah ' . $jenis_surat;
$ubah 				= 'Ubah ' . $jenis_surat;
$hapus				= 'Apa anda yakin?';
$batal 				= 'Batal';
$simpan 			= 'Simpan';

?>

<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<div id="flashdata"></div>
			<div class="card">
				<div class="card-header">
					<h5 class="card-title"><?= $heading ?></h5>
					<div class="card-tools">
						<button type="button" onclick="add_data();" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i></button>
					</div>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table id="table" class="table table-bordered" style="width: 100%;">
							<thead>
								<tr>
									<?php

									$thead = array(
										'<th style="width: 5%; text-align: center;">No</th>',
										'<th>' . $no_surat . '</th>',
										'<th>' . $nama_surat . '</th>',
										'<th>' . $titimangsa_surat . '</th>',
										'<th>' . $lihat_surat . '</th>',
										'<th style="width: 5%; text-align: center;">' . $aksi . '</th>',
									);

									$targets = array();
									for ($i = 0; $i < count($thead); $i++) {
										if ($i > 2) {
											$targets[] = $i;
										}
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

<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="" method="post" id="form-data">
					<input type="text" name="id_surat" value="" style="display: none;">
					<input type="text" name="jenis_surat_id" value="<?= $id_jenis_surat ?>" style="display: none;">
					<?php
					foreach ([
						'no_surat' 	 	 => $no_surat,
						'nama_surat' 	 => $nama_surat,
					] as $key => $value) : ?>
						<div class="form-group">
							<label for="<?= $key ?>"><?= $value ?></label>
							<input type="text" name="<?= $key ?>" id="<?= $key ?>" class="form-control" placeholder="<?= $value ?>" autocomplete="off">
							<span id="error-<?= $key ?>" class="error invalid-feedback"></span>
						</div>
					<?php endforeach ?>
					<div class="form-group">
						<label for="titimangsa_surat" class=""><?= $titimangsa_surat ?></label>
						<div class="input-group date" id="reservationdate" data-target-input="nearest">
							<input type="text" name="titimangsa_surat" id="titimangsa_surat" value="" class="form-control datetimepicker-input" data-target="#reservationdate" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/dd/yyyy" placeholder="<?= $titimangsa_surat ?>" autocomplete="off">
							<div class="input-group-append" style="width: 50px;">
								<button type="button" class="btn btn-default btn-block" data-target="#reservationdate" data-toggle="datetimepicker"><i class="fas fa-calendar-plus"></i></button>
							</div>
						</div>
						<span id="error-titimangsa_surat" class="error invalid-feedback"></span>
					</div>
					<div class="form-group">
						<label for="file_surat">File Surat</label>
						<div class="input-group">
							<div class="custom-file">
								<input type="file" class="custom-file-input" id="file_surat" name="file_surat">
								<label class="custom-file-label" for="file_surat">Choose File</label>
							</div>
						</div>
						<span id="error-file_surat" class="error invalid-feedback"></span>
					</div>
				</form>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-secondary btn-sm" style="font-weight: bold;" data-dismiss="modal"><i class="fas fa-angle-double-left"></i> <?= $batal ?></button>
				<button type="button" id="btn-save" class="btn btn-success btn-sm" style="font-weight: bold;"><i class="fas fa-save"></i> <?= $simpan ?></button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$('#reservationdate').datetimepicker({
		format: 'L'
	});

	$('#titimangsa_surat').inputmask('mm/dd/yyyy', {
		'placeholder': 'mm/dd/yyyy'
	});

	$('#reservationdate').click(function() {
		$('[name="titimangsa_surat"]').change();
	});

	$('#file_surat').change(function() {
		var fileName = $(this).val().split('\\').pop();
		var fileLabel = $(this).next('.custom-file-label');
		fileLabel.html(fileName);
	});

	$('#btn-save').click(function() {

		var form_data = new FormData($('#form-data')[0]);
		var list_data = {
			<?= csrf_token() ?>: $('[name="<?= csrf_token() ?>"]').val(),
		};
		$.each(list_data, function(key, val) {
			form_data.append(key, val);
		});

		$.ajax({
			type: "POST",
			url: "<?= base_url('save/letter') ?>",
			data: form_data,
			dataType: "JSON",
			contentType: false,
			processData: false,
			success: function(response) {
				$('[name="<?= csrf_token() ?>"]').val(response.<?= csrf_token() ?>).change();
				if (response.status) {
					table.ajax.reload();
					$('#modal-form').modal('hide');
					$('#flashdata').html('');
					$(window).scrollTop(0);
					$('<div class="alert alert-success alert-dismissible" id="alert" style="font-weight: bold;">' + response.message + '</div>').show().appendTo('#flashdata');
					$('#alert').delay(2750).slideUp('slow', function() {
						$(this).remove();
					});
				} else {
					$.each(response.errors, function(key, val) {
						$('[name="' + key + '"]').addClass('is-invalid');
						$('#error-' + key + '').text(val).show();
						$('[name="' + key + '"]').on('change keyup', function() {
							$('[name="' + key + '"]').removeClass('is-invalid');
							$('#error-' + key + '').text('').hide();
						});
					});
				}
			}
		});

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
				"url": "<?= site_url('list/letter') ?>/" + $('[name="<?= csrf_token() ?>"]').val(),
				"type": "POST",
				"data": function(data) {
					data.<?= csrf_token() ?> = $('[name="<?= csrf_token() ?>"]').val();
					data.jenis_surat_id = $('[name="jenis_surat_id"]').val();
				},
			},
			"columnDefs": [{
				"targets": <?= count($targets) > 0 ? json_encode($targets) : [] ?>,
				"orderable": false,
			}],
			"drawCallback": function(settings) {
				$('[name="<?= csrf_token() ?>"]').val(settings.json.<?= csrf_token() ?>).change();
			},
		});

	});

	function add_data() {
		$('#form-data .form-control').val('').change();
		$('#form-data .form-control').removeClass('is-invalid');
		table.ajax.reload();
		$('[name="titimangsa_surat"]').val('<?= date('m/d/Y') ?>');
		$('.modal-title').text('<?= $tambah ?>');
		$('#modal-form').modal('show');
	}

	function edit_data(id) {
		var no_surat = $('[name="no_surat_' + id + '"]').val();
		var nama_surat = $('[name="nama_surat_' + id + '"]').val();
		var titimangsa_surat = $('[name="titimangsa_surat_' + id + '"]').val();

		$('#form-data .form-control').val('').change();
		$('#form-data .form-control').removeClass('is-invalid');
		$('[name="id_surat"]').val(id);
		$('[name="no_surat"]').val(no_surat);
		$('[name="nama_surat"]').val(nama_surat);
		$('[name="titimangsa_surat"]').val(titimangsa_surat);
		$('[name="file_surat"]').val('').change();
		$('.modal-title').text('<?= $ubah ?>');
		$('#modal-form').modal('show');
	}

	function delete_data(id) {
		if (confirm('<?= $hapus ?>')) {
			$.ajax({
				type: "POST",
				url: "<?= site_url('delete/letter') ?>/" + $('[name="<?= csrf_token() ?>"]').val(),
				data: {
					<?= csrf_token() ?>: $('[name="<?= csrf_token() ?>"]').val(),
					id_surat: id,
				},
				dataType: "JSON",
				success: function(response) {
					$('[name="<?= csrf_token() ?>"]').val(response.<?= csrf_token() ?>).change();
					table.ajax.reload();
					$('#flashdata').html('');
					$(window).scrollTop(0);
					$('<div class="alert alert-success alert-dismissible" id="alert" style="font-weight: bold;">' + response.message + '</div>').show().appendTo('#flashdata');
					$('#alert').delay(2750).slideUp('slow', function() {
						$(this).remove();
					});
				}
			});
		}
	}
</script>

<?= $this->endSection(); ?>