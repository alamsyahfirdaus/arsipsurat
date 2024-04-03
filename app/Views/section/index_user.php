<?= $this->extend('layout/fixed-sidebar'); ?>
<?= $this->section('content'); ?>

<?php

$profile_pengguna = session()->get('language') == 2 ? 'User Profile' : 'Profile Pengguna';
$daftar_pengguna = session()->get('language') == 2 ? 'User List' : 'Daftar Pengguna';
$nama_pengguna = session()->get('language') == 2 ? 'Full Name' : 'Nama Lengkap';
$tanggal_pengguna = session()->get('language') == 2 ? 'Register Date' : 'Tanggal Register';
$cari = session()->get('language') == 2 ? 'Search' : 'Cari';
$aksi = session()->get('language') == 2 ? 'Action' : 'Aksi';
$tambah = session()->get('language') == 2 ? 'Add User' : 'Tambah Pengguna';
$ubah = session()->get('language') == 2 ? 'Change User' : 'Ubah Pengguna';
$hapus = session()->get('language') == 2 ? 'Are you sure?' : 'Apa anda yakin?';
$batal = session()->get('language') == 2 ? 'Cancel' : 'Batal';
$simpan = session()->get('language') == 2 ? 'Save' : 'Simpan';
$password = session()->get('language') == 2 ? 'Current Password' : 'Password Saat Ini';
$passconf = session()->get('language') == 2 ? 'Confirm Password' : 'Konfirmasi Password';
$newpass = session()->get('language') == 2 ? 'New Password' : 'Password Baru';

?>

<div class="container-fluid">
	<div class="row">
		<div class="col-12">
			<?php if (session()->getFlashData('success')) : ?>
				<div class="alert alert-success alert-dismissible" id="alert" style="font-weight: bold;">
					<?= session()->getFlashData('success') ?>
				</div>
			<?php endif ?>
			<div id="flashdata"></div>
			<div class="card">
				<div class="card-header">
					<h3 class="card-title">
						<?= $heading ?>
					</h3>
					<div class="card-tools">
						<button type="button" onclick="add_data();" class="btn btn-primary btn-sm"><i class="fas fa-user-plus"></i></button>
					</div>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table id="table" class="table table-bordered" style="width: 100%;">
							<thead>
								<tr>
									<?php

									if (session()->get('language') == 2) {
										$thead = array(
											'<th style="width: 5%; text-align: center;">No</th>',
											'<th>Full<span style="color: white;">_</span>Name</th>',
											'<th>Email</th>',
											'<th style="width: 5%; text-align: center;">' . $aksi . '</th>',
										);
									} else {
										$thead = array(
											'<th style="width: 5%; text-align: center;">No</th>',
											'<th>Nama<span style="color: white;">_</span>Lengkap</th>',
											'<th>Email</th>',
											'<th style="width: 5%; text-align: center;">' . $aksi . '</th>',
										);
									}

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
					<input type="text" name="id_pengguna" value="" style="display: none;">
					<?php foreach (['nama_pengguna' => $nama_pengguna, 'email' => 'Email',] as $key => $value) : ?>
						<div class="form-group">
							<label for="<?= $key ?>">
								<?= $value ?>
							</label>
							<input type="text" name="<?= $key ?>" id="<?= $key ?>" class="form-control" placeholder="<?= $value ?>" autocomplete="off">
							<span id="error-<?= $key ?>" class="error invalid-feedback"></span>
						</div>
					<?php endforeach ?>
					<div class="form-group" id="currentpass" style="display: none;">
						<label for="password">
							<?= $password ?>
						</label>
						<input type="password" name="password" id="password" class="form-control" placeholder="<?= $password ?>" autocomplete="off">
						<span id="error-password" class="error invalid-feedback"></span>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="password1" id="newpass"></label>
								<input type="password" name="password1" id="password1" class="form-control" placeholder="" autocomplete="off">
								<span id="error-password1" class="error invalid-feedback"></span>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="password2">
									<?= $passconf ?>
								</label>
								<input type="password" name="password2" id="password2" class="form-control" placeholder="<?= $passconf ?>" autocomplete="off">
								<span id="error-password2" class="error invalid-feedback"></span>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-secondary btn-sm" style="font-weight: bold;" data-dismiss="modal"><i class="fas fa-angle-double-left"></i>
					<?= $batal ?>
				</button>
				<button type="button" class="btn btn-success btn-sm" onclick="save_data();" style="font-weight: bold;"><i class="fas fa-save"></i>
					<?= $simpan ?>
				</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
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
				"url": "<?= site_url('list/user') ?>/" + $('[name="<?= csrf_token() ?>"]').val(),
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
		$('#currentpass').hide();
		$('#newpass').text('Password');
		$('#password1').attr('placeholder', 'Password');
		$('[name="id_pengguna"]').val('').change();
		$('#form-data .form-control').val('').change();
		$('#form-data .form-control').removeClass('is-invalid');
		$('.modal-title').text('<?= $tambah ?>');
		$('#modal-form').modal('show');
	}

	function edit_data(id) {
		var nama_pengguna = $('[name="nama_pengguna_' + id + '"]').val();
		var email = $('[name="email_' + id + '"]').val();

		if (id == <?= $id_pengguna ?>) {
			$('#newpass').text('<?= $newpass ?>');
			$('#password1').attr('placeholder', '<?= $newpass ?>');
		} else {
			$('#currentpass').hide();
			$('#newpass').text('Password');
			$('#password1').attr('placeholder', 'Password');
		}

		$('#form-data .form-control').val('').change();
		$('#form-data .form-control').removeClass('is-invalid');
		$('[name="id_pengguna"]').val(id);
		$('[name="nama_pengguna"]').val(nama_pengguna);
		$('[name="email"]').val(email);
		$('.modal-title').text('<?= $ubah ?>');
		$('#modal-form').modal('show');
	}

	function save_data() {
		var form_data = new FormData($('#form-data')[0]);
		var list_data = {
			<?= csrf_token() ?>: $('[name="<?= csrf_token() ?>"]').val(),
		};
		$.each(list_data, function(key, val) {
			form_data.append(key, val);
		});

		$.ajax({
			type: "POST",
			url: "<?= base_url('save/user') ?>",
			data: form_data,
			dataType: "JSON",
			contentType: false,
			processData: false,
			success: function(response) {
				$('[name="<?= csrf_token() ?>"]').val(response.<?= csrf_token() ?>).change();
				if (response.status) {
					if (response.id_pengguna) {
						window.location.reload();
					} else {
						table.ajax.reload();
						$('#modal-form').modal('hide');
						$('#flashdata').html('');
						$(window).scrollTop(0);
						$('<div class="alert alert-success alert-dismissible" id="alert" style="font-weight: bold;">' + response.message + '</div>').show().appendTo('#flashdata');
						$('#alert').delay(2750).slideUp('slow', function() {
							$(this).remove();
						});
					}
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
	}

	function delete_data(id) {
		if (confirm('<?= $hapus ?>')) {
			$.ajax({
				type: "POST",
				url: "<?= site_url('delete/user') ?>/" + $('[name="<?= csrf_token() ?>"]').val(),
				data: {
					<?= csrf_token() ?>: $('[name="<?= csrf_token() ?>"]').val(),
					id_pengguna: id,
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