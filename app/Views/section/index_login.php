<?= $this->extend('layout/auth-page1'); ?>
<?= $this->section('content'); ?>

<div class="card-body">
    <form action="" method="post" id="form-login">
      <?= csrf_field(); ?>
      <div class="form-group form-floating mb-3">
          <input type="text" class="form-control" id="email" name="email" placeholder="Email" autocomplete="off" required="">
          <label for="email">Email</label>
          <span id="error-email" class="error invalid-feedback"></span>
      </div>
      <div class="form-group form-floating mb-3">
          <input type="password" class="form-control" id="password" name="password" placeholder="Password" autocomplete="off" required="">
          <label for="password">Password</label>
          <span id="error-password" class="error invalid-feedback"></span>
      </div>
      <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
          <button type="submit" class="btn btn-primary btn-block">Login</button>
      </div>
    </form>
</div>
<div class="card-footer text-center py-3">
    <div class="small"><a href="javascript:void(0)">Lupa Password?</a></div>
</div>

<script type="text/javascript">
    $(function() {
      $.validator.setDefaults({
        submitHandler: function () {
          logged_in();
        }
      });
      $('#form-login').validate({
        rules: {
          email: {
            required: true,
          },
          password: {
            required: true,
          },
        },
        messages: {
          email: {
            required: "Email harus diisi.",
          },
          password: {
            required: "Password harus diisi.",
          },
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
          error.addClass('invalid-feedback');
          element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
          $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
          $(element).removeClass('is-invalid');
        }
      });
    });

    function logged_in() {
        $.ajax({
            url: '<?= base_url('login') ?>',
            type: 'POST',
            dataType: 'json',
            data: new FormData($('#form-login')[0]),
            contentType: false,
            processData: false,
            success: function(response) {
              $('[name="<?= csrf_token() ?>"]').val(response.<?= csrf_token() ?>).change();
            	if (response.status) {
            		window.location.href = '<?= base_url('home') ?>';
            	} else {
            		$.each(response.errors, function(key, val) {
            			$('[name="' + key + '"]').addClass('is-invalid');
            			$('#error-'+ key +'').text(val).show();
            			$('[name="' + key + '"]').keyup(function() {
            				$('[name="' + key + '"]').removeClass('is-invalid');
            				$('#error-'+ key +'').text('').hide();
            			});
            		});
            	}
            }
        });
    }
</script>

<?= $this->endSection(); ?>