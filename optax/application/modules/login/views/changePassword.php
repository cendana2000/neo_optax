<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* url(https://fonts.googleapis.com/css?family=Roboto:300); */

        .login-page {
            width: 360px;
            padding: 8% 0 0;
            margin: auto;
        }

        .form {
            position: relative;
            z-index: 1;
            background: #FFFFFF;
            max-width: 360px;
            margin: 0 auto 100px;
            padding: 45px;
            text-align: center;
            box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
        }

        .form input {
            font-family: "Roboto", sans-serif;
            outline: 0;
            background: #f2f2f2;
            width: 100%;
            border: 0;
            margin: 0 0 15px;
            padding: 15px;
            box-sizing: border-box;
            font-size: 14px;
        }

        .form button {
            font-family: "Roboto", sans-serif;
            text-transform: uppercase;
            outline: 0;
            background: #4CAF50;
            width: 100%;
            border: 0;
            padding: 15px;
            color: #FFFFFF;
            font-size: 14px;
            -webkit-transition: all 0.3 ease;
            transition: all 0.3 ease;
            cursor: pointer;
        }

        .form button:hover,
        .form button:active,
        .form button:focus {
            background: #43A047;
        }

        .form .message {
            margin: 15px 0 0;
            color: #b3b3b3;
            font-size: 12px;
        }

        .form .message a {
            color: #4CAF50;
            text-decoration: none;
        }

        .form .register-form {
            display: none;
        }

        .container {
            position: relative;
            z-index: 1;
            max-width: 300px;
            margin: 0 auto;
        }

        .container:before,
        .container:after {
            content: "";
            display: block;
            clear: both;
        }

        .container .info {
            margin: 50px auto;
            text-align: center;
        }

        .container .info h1 {
            margin: 0 0 15px;
            padding: 0;
            font-size: 36px;
            font-weight: 300;
            color: #1a1a1a;
        }

        .container .info span {
            color: #4d4d4d;
            font-size: 12px;
        }

        .container .info span a {
            color: #000000;
            text-decoration: none;
        }

        .container .info span .fa {
            color: #EF3B3A;
        }

        body {
            background: #76b852;
            /* fallback for old browsers */
            background: -webkit-linear-gradient(right, #76b852, #8DC26F);
            background: -moz-linear-gradient(right, #76b852, #8DC26F);
            background: -o-linear-gradient(right, #76b852, #8DC26F);
            background: linear-gradient(to left, #76b852, #8DC26F);
            font-family: "Roboto", sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
    </style>
    <link href="<?php echo base_url(); ?>assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/css/custom.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.css.map" rel="stylesheet" type="text/css" />
    <title>Form Ubah Password</title>
</head>

<body>
    <div class="login-page">
        <div class="form">
            <form action="javascript:save('form-change-password')" method="post" id="form-change-password" autocomplete="off" class="login-form">
                <input type="hidden" name="user_id" id="user_id" value="<?= $data['user_id'] ?>">
                <div class="form-group my-1 mb-3 row">
                    <input type="password" class="form-control" id="user_password" name="user_password" aria-describedby="password" placeholder="Masukkan Password Baru" autocomplete="off" required minlength="6" maxlength="12">
                </div>
                <div class="form-group my-1 mb-3 row">
                    <input type="Password" class="form-control m-input" id="user_password_repeat" name="password_repeat" aria-describedby="password_repeat" placeholder="Ulangi password Baru" autocomplete="off" required>
                </div>
                <div class="g-recaptcha mb-3" id="chaptcha" data-sitekey="6LfAoKoaAAAAAAdy-U45kpSosIrWRZOXD34pZoxX"></div>
                <button type="submit">login</button>
            </form>
        </div>
    </div>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/global/plugins.bundle.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/custom/blockui/jquery.blockui.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/custom/aos/aos.js"></script>
    <script src="<?php echo base_url(); ?>assets/helper/helper.js?v=1.0.0"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/pages/id_ID_FormValidation.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/helper/jquery.cookie.js"></script>
    <script src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.min.js"></script>

    <script type="text/javascript">
        var fv
        BASE_URL = "<?php echo base_url() ?>index.php/";
        $(function() {
            fv = HELPER.newHandleValidation({
                el: 'form-change-password',
                useRegex: true,
                declarative: true,
                setting: [{
                    name: "Konfirmasi Password",
                    selector: "#user_password_repeat",
                    rule: {
                        identical: {
                            compare: function() {
                                return $('#user_password').val()
                            }
                        }
                    }
                }]
            });
        })

        function save(table) {
            var form = $('#' + table)[0];
            var formData = new FormData(form);
            HELPER.confirm({
                message: 'Ubah Password',
                callback: function(suc) {
                    if (suc) {
                        $.ajax({
						type: "POST",
						url: BASE_URL + "Login/ForgetPasswordChange",
						contentType: false,
						processData: false,
						data: formData,
						success: function(res) {
							if (res.success) {
								HELPER.showMessage({
									success: true,
									title: "Sukses",
									message: "Berhasil menyimpan data"
								});
							} else if(res='recaptcha_kosong') {
								HELPER.showMessage({
									success: false,
                                    title: "Gagal",
									message: "Anda Robot"
								})
							}
							HELPER.unblock(100);
						}
					});
                    }
                }
            })
        }
    </script>

</body>

</html>