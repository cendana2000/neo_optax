<script>
    BASE_URL = '<?= base_url('/') ?>';
    // $('#mb_wp').hide();
    // $('#mb_bp').hide();

    FCM.setConfig("<?php echo $this->config->item('config_fcm'); ?>");

    $(() => {

        // if (Notification.permission === 'granted') {
        //     reqPermission()
        // } else {
        //     HELPER.showMessage({
        //         success: 'info',
        //         title: 'Information !',
        //         message: 'Silahkan klik allow pada popup browser untuk mengijinkan notifikasi.',
        //         allowOutsideClick: false,
        //         callback: function(res) {
        //             reqPermission()
        //         }
        //     })
        // }

        HELPER.fields = [
            'pemda_id',
            'wajibpajak_nik',
            'wajibpajak_npwpd',
            'wajibpajak_sektor_id',
            'jenis_kode',
            'wajibpajak_nama',
            'wajibpajak_alamat',
            'wajibpajak_nama_penanggungjawab',
            'wajibpajak_telp',
            'wajibpajak_email',
            'wajibpajak_password',
        ];

        HELPER.setRequired([
            'pemda_id',
            'wajibpajak_nik',
            'wajibpajak_pemda_id',
            'wajibpajak_npwpd',
            'wajibpajak_sektor_id',
            'wajibpajak_nama',
            'wajibpajak_alamat',
            'wajibpajak_nama_penanggungjawab',
            'wajibpajak_telp',
            'wajibpajak_email',
            'wajibpajak_password',
        ]);

        oauth = '<?= $this->session->userdata('login_status'); ?>';
        <?php $this->session->sess_destroy(); ?>
        if (oauth == 'false') $('.alert').addClass('show');

        $('#wizard_wp').click(() => {
            console.log('wp clicked ');
        });

        $('#kt_login_signup').click(() => {
            $('.header-login, .login-signin-wp').hide();
            $('.login-signup-wp').show(500);
            $('.alert').removeClass('show');
        })

        $('#kt_login_forgot').click(() => {
            let email = $("#email").val();
            $("#email_forgot").val(email);
            $('.header-login, .login-signin-wp').hide();
            $('.login-forgot').show(500);
        })

        $('#kt_login_forgot_cancel').click(() => {
            $('.header-login, .login-signin-wp').show(500);
            $('.login-forgot').hide(500);
        })

        $('#kt_login_forgot_submit').click(() => {
            doForgotPassword();
        })
    });

    $("#email, #password").keyup(function(e) {
        if (e.keyCode == 13) {
            doLogin();
        }
    });

    $('#wizard-pemerintah_daerah .user_email, #wizard-pemerintah_daerah .user_password')
        .on('keyup', function(e) {
            if (e.key !== 'Enter') return;
            submitLoginAuto();
        });



    // $("#user_email, #user_password").keyup(function(e) {
    //     if (e.keyCode == 13) {
    //         doLoginPemda();
    //     }
    // });


    passwordShow = () => {
        if ($('#user_password').attr('type') == 'Password') {
            $('#user_password').attr('type', 'text');
            $('#btn-show-user-password i').removeClass('fa-eye');
            $('#btn-show-user-password i').addClass('fa-eye-slash');
        } else {
            $('#user_password').attr('type', 'Password');
            $('#btn-show-user-password i').removeClass('fa-eye-slash');
            $('#btn-show-user-password i').addClass('fa-eye');
        }
    };

    passwordShowUser = () => {
        if ($('#password').attr('type') == 'Password') {
            $('#password').attr('type', 'text');
            $('#btn-show-password i').removeClass('fa-eye');
            $('#btn-show-password i').addClass('fa-eye-slash');
        } else {
            $('#password').attr('type', 'Password');
            $('#btn-show-password i').removeClass('fa-eye-slash');
            $('#btn-show-password i').addClass('fa-eye');
        }
    };


    function backLogin() {
        $('#text-login').text('');
        $('#wizard-body').show('500').addClass('animated slideInRight');
        $('.wizard-opt').hide('500').addClass('animated slideOutLeft');
        $('#mb_bp').hide('500').addClass('animated slideOutLeft');
        $('#mb_wp').hide('500').addClass('animated slideOutLeft');
        $('#mb_download').hide('500').addClass('animated slideOutLeft');
    }

    function getLogin(el) {
        $('.close').click();
        id = $(el).data('id');
        $('#wizard-body').hide('500').addClass('animated slideOutLeft');
        $('#wizard-' + id).show('500').addClass('animated slideInRight');

        if (id == 'wajib_pajak') {
            $('#mb_download').show('300').addClass('animated slideInRight');
            $('#mb_bp').hide('500').addClass('animated slideOutLeft');
            $('#mb_wp').show('500').addClass('animated slideInRight');
        }
        if (id == 'pemerintah_daerah') {
            $('#mb_download').show('300').addClass('animated slideInRight');
            $('#mb_wp').hide('500').addClass('animated slideOutLeft');
            $('#mb_bp').show('500').addClass('animated slideInRight');
        }
        if (id == 'bank_jatim') {
            $('#mb_download').show('300').addClass('animated slideInRight');
            $('#mb_wp').hide('500').addClass('animated slideOutLeft');
            $('#mb_bp').show('500').addClass('animated slideInRight');
        }
        if (id == 'kpk') {
            $('#mb_download').show('300').addClass('animated slideInRight');
            $('#mb_wp').hide('500').addClass('animated slideOutLeft');
            $('#mb_bp').show('500').addClass('animated slideInRight');
        }

        $('#text-login').text(id.replace("_", " ").toUpperCase());
    }

    function getNPWPD() {
        HELPER.block();
        npwpd = $('[name=wajibpajak_npwpd]').val();
        HELPER.ajax({
            url: BASE_URL + 'mitra/check_wp',
            data: {
                'NPWPD': npwpd
            },
            type: 'post',
            success: (res) => {
                // $('#wajibpajak_npwpd').val('');
                if (res.succes == false) {
                    console.log('wp ada');
                    $('#wajibpajak_npwpd').val('');
                    Swal.fire('Warning', 'NPWPD Sudah', 'warning');
                    return;
                }
                if (res.NPWPD) {
                    $('[name=wajibpajak_sektor_id]').val(res.JENIS_USAHA);
                    $('[name=wajibpajak_nama]').val(res.NAMA_WP);
                    $('[name=wajibpajak_alamat]').val(res.ALAMAT_WP);
                    $('[name=jenis_kode]').val(res.ID_JENIS);
                } else {
                    $('#wajibpajak_npwpd').val('');
                    $('[name=wajibpajak_sektor_id]').val('');
                    $('[name=wajibpajak_nama]').val('');
                    $('[name=wajibpajak_alamat]').val('');
                    swal.fire('Informasi', res.message || 'NPWPD tidak ditemukan', 'warning');
                }
            },
            complete: function() {
                HELPER.unblock();
            },
            error: function() {
                $('#wajibpajak_npwpd').val('');
                $('[name=wajibpajak_sektor_id]').val('');
                $('[name=wajibpajak_nama]').val('');
                $('[name=wajibpajak_alamat]').val('');
                HELPER.showMessage({
                    success: false,
                    message: 'Terjadi kesalahan, mohon ulangi lagi'
                })
                HELPER.unblock();
            }
        })
    }

    function checkEmail() {
        let email = $('#wajibpajak_email').val();

        $.post(BASE_URL + 'mitra/check_email', {
            email: email
        }, function(res) {
            if (res.success == false) {
                $('#wajibpajak_email').val('');
                Swal.fire('Warning', 'Email sudah terdaftar', 'warning');
            }
        });
    }

    function cancelSignup() {
        $('.header-login, .login-signin-wp').show(500);
        $('.login-signup-wp').hide(500);
    }

    function doLogin() {
        HELPER.block()
        var email = $('#email').val();
        var password = $('#password').val();
        var token = $('#token2').val();
        $.ajax({
            type: "POST",
            url: BASE_URL + "login/doLogin",
            data: {
                email: email,
                password: password,
                token: token,
                // recaptcha: grecaptcha.getResponse()
            },
            success: function(response) {
                $('#password').val('')
                if (response.success) {
                    window.location.reload();
                } else {
                    HELPER.unblock()
                    HELPER.showMessage({
                        success: false,
                        message: response.message
                    })
                }
            }
        });
    }

    function doLoginPemda(role) {
        HELPER.block();
        var wizard = '#wizard-pemerintah_daerah';
        var email = $(wizard + ' .user_email').val()?.trim();
        var password = $(wizard + ' .user_password').val();

        console.log('DEBUG LOGIN:', {
            role,
            email,
            password
        });

        $.ajax({
            type: "POST",
            url: BASE_URL + "login/doLoginPemda",
            dataType: "json",
            data: {
                email: email,
                password: password,
                role: role
            },
            success: function(response) {
                if (response.success) {
                    window.location.reload();
                } else {
                    HELPER.unblock();
                    HELPER.showMessage({
                        success: false,
                        message: response.message
                    });
                }
            },
            error: function() {
                HELPER.unblock();
                HELPER.showMessage({
                    success: false,
                    message: 'Terjadi kesalahan server'
                });
            }
        });
    }



    function doSignup(e) {
        event.preventDefault();
        grecaptcha.ready(function() {
            grecaptcha.execute('<?= $_ENV['GAPI_CAPTCHA_SITE_KEY'] ?>', {
                action: 'submit'
            }).then(function(token) {
                event.preventDefault();
                HELPER.block();
                HELPER.ajax({
                    type: "POST",
                    url: BASE_URL + "wajibpajak/doSignup",
                    data: $.extend($('[name=kt_login_signup_form]').serializeObject(), {
                        token: token
                    }),
                    success: function(response) {
                        $('#password').val('')
                        let res = JSON.parse(response);
                        if (res.success) {
                            swal.fire({
                                title: "Success",
                                text: "Selamat, akun Anda berhasil disimpan!. Harap login setelah akun Anda diverifikasi oleh Petugas.",
                                icon: "success"
                            }).then((result) => {
                                HELPER.unblock();
                                window.location.reload();
                            });
                        } else {
                            swal.fire({
                                title: "Gagal",
                                text: res.message,
                                icon: "warning"
                            }).then((result) => {
                                HELPER.unblock();
                                // window.location.reload();
                            });
                        }
                    },
                    complete: function(res) {
                        HELPER.unblock();
                    }
                });
            });
        });
    }

    function doForgotPassword() {
        if ($('#email_forgot').val()) {
            var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            if (!$('#email_forgot').val().match(mailformat)) {
                HELPER.showMessage({
                    message: 'Email tidak valid! contoh: example@domain.com'
                });
                return false;
            }
        }

        HELPER.block();

        $.ajax({
            url: BASE_URL + "wajibpajak/forgotpassword",
            data: $("#kt_login_forgot_form").serializeObject(),
            type: 'post',
            success: function(res) {
                console.log(res);
                if (res.success) {
                    HELPER.showMessage({
                        success: 'success',
                        title: 'Berhasil',
                        message: 'Link Reset Password telah kami kirimkan, silahkan periksa email Anda',
                    });
                    return;
                }
                HELPER.showMessage({
                    message: 'Email anda tidak terdaftar.'
                });
            },
            error: function(e) {
                HELPER.showMessage({
                    message: `${e.statusText}`,
                })
            },
            complete: function() {
                HELPER.unblock();
            }
        })
    }

    function downloadMB(file) {
        event.preventDefault();
        window.open(
            BASE_ASSETS + 'manualbook/' + file,
            '_blank'
        );
    }

    function detectRoleByEmail(email, callback) {
        $.ajax({
            type: "POST",
            url: BASE_URL + "login/getUser",
            dataType: "json",
            data: {
                email: email
            },
            success: function(res) {
                if (!res.success || !res.data) {
                    return callback(null);
                }
                callback(res.data.role_access_kode);
            },
            error: function() {
                callback(null);
            }
        });
    }


    function submitLoginAuto() {
        const email = $('#wizard-pemerintah_daerah .user_email').val().trim();
        const password = $('#wizard-pemerintah_daerah .user_password').val();

        if (!email || !password) {
            HELPER.showMessage({
                success: false,
                message: 'Email dan password wajib diisi'
            });
            return;
        }

        HELPER.block();

        detectRoleByEmail(email, function(role) {
            if (!role) {
                HELPER.unblock();
                HELPER.showMessage({
                    success: false,
                    message: 'Email tidak terdaftar'
                });
                return;
            }

            doLoginPemda(role);
        });
    }
</script>