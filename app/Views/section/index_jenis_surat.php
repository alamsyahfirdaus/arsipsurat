<?= $this->extend('layout/fixed-sidebar'); ?>
<?= $this->section('content'); ?>

<?php

$jenis_surat 		= session()->get('language') == 2 ? 'Letter Type' : 'Jenis Surat';
$nama_jenis_surat 	= session()->get('language') == 2 ? 'Letter Type' : 'Jenis Surat';
$cari 				= session()->get('language') == 2 ? 'Search' : 'Cari';
$aksi 				= session()->get('language') == 2 ? 'Action' : 'Aksi';
$tambah 			= session()->get('language') == 2 ? 'Add Mail Type' : 'Tambah Jenis Surat';
$ubah 				= session()->get('language') == 2 ? 'Change Mail Type' : 'Ubah Jenis Surat';
$hapus				= session()->get('language') == 2 ? 'Are you sure?' : 'Apa anda yakin?';
$batal 				= session()->get('language') == 2 ? 'Cancel' : 'Batal';
$simpan 			= session()->get('language') == 2 ? 'Save' : 'Simpan';

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
										'<th>' . $jenis_surat . '</th>',
										'<th style="width: 5%; text-align: center;">' . $aksi . '</th>',
									);

									$targets = array();
									for ($i = 0; $i < count($thead); $i++) {
										if ($i == count($thead) - 1) {
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
					<input type="text" name="id_jenis_surat" value="" style="display: none;">
					<?php
					foreach ([
						'nama_jenis_surat' 	 => $nama_jenis_surat,
					] as $key => $value) : ?>
						<div class="form-group">
							<label for="<?= $key ?>"><?= $value ?></label>
							<input type="text" name="<?= $key ?>" id="<?= $key ?>" class="form-control" placeholder="<?= $value ?>" autocomplete="off">
							<span id="error-<?= $key ?>" class="error invalid-feedback"></span>
						</div>
					<?php endforeach ?>
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
			url: "<?= base_url('save/type') ?>",
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
				"url": "<?= site_url('list/type') ?>/" + $('[name="<?= csrf_token() ?>"]').val(),
				"type": "POST",
				"data": function(data) {
					data.<?= csrf_token() ?> = $('[name="<?= csrf_token() ?>"]').val();
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
		$('.modal-title').text('<?= $tambah ?>');
		$('#modal-form').modal('show');
	}

	function edit_data(id) {
		var nama_jenis_surat = $('[name="nama_jenis_surat_' + id + '"]').val();


		$('#form-data .form-control').val('').change();
		$('#form-data .form-control').removeClass('is-invalid');
		$('[name="id_jenis_surat"]').val(id);
		$('[name="nama_jenis_surat"]').val(nama_jenis_surat);
		$('.modal-title').text('<?= $ubah ?>');
		$('#modal-form').modal('show');
	}

	function delete_data(id) {
		if (confirm('<?= $hapus ?>')) {
			$.ajax({
				type: "POST",
				url: "<?= site_url('delete/type') ?>/" + $('[name="<?= csrf_token() ?>"]').val(),
				data: {
					<?= csrf_token() ?>: $('[name="<?= csrf_token() ?>"]').val(),
					id_jenis_surat: id,
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