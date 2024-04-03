<?= $this->extend('layout/auth-page1'); ?>
<?= $this->section('content'); ?>

<div class="row">
  <div class="col-lg-6 d-none d-lg-block">
    <img class="centered-img" src="<?= base_url('public/assets/img/logo-smkm.png') ?>" alt="">
  </div>
  <div class="col-lg-6">
    <div class="py-5 px-3">
      <div class="text-center">
        <h1 class="h3 text-gray-900 mb-4 fw-bold">
          <marquee behavior="" direction=""><?= WEBSITE ?></marquee>
        </h1>
      </div>
      <form action="" method="post" id="form-login">
        <?= csrf_field(); ?>
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
          <input type="text" class="form-control" id="email" name="email" placeholder="Email" autocomplete="off" required="">
          <span id="error-email" class="error invalid-feedback"></span>
        </div>
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
          <input type="password" class="form-control" id="password" name="password" placeholder="Password" autocomplete="off" required="">
          <span id="error-password" class="error invalid-feedback"></span>
        </div>
        <div class="mt-4">
          <button type="submit" class="btn btn-primary btn-block fw-bold">Login</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(function() {
    $.validator.setDefaults({
      submitHandler: function() {
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
      errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight: function(element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function(element, errorClass, validClass) {
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
            $('#error-' + key + '').text(val).show();
            $('[name="' + key + '"]').keyup(function() {
              $('[name="' + key + '"]').removeClass('is-invalid');
              $('#error-' + key + '').text('').hide();
            });
          });
        }
      }
    });
  }
</script>

<?= $this->endSection(); ?>