<script>
    BASE_URL = '<?= base_url('index.php/') ?>';
    $(() =>{
        $('#kt_login_signup').click(()=>{
            $('.header-login, .login-signin-wp').hide();
            $('.login-signup-wp').show(500);

        })
    })

    function getLogin(el) {
        id = $(el).data('id');
        $('#wizard-body').hide('500').addClass('animated slideOutLeft');
        $('#wizard-'+id).show('500').addClass('animated slideInRight');
        console.log(id);
        $('#text-login').text(id.replace("_", " ").toUpperCase());
    }

    function getNPWPD() {
        HELPER.block();
        npwpd = $('[name=wajibpajak_npwpd]').val();
        $.ajax({
            url : BASE_URL+'mitra/check_wp',
            data : { 'NPWPD': npwpd},
            type : 'post',
            success : (res)=>{
                if(res.NPWPD){
                    $('[name=wajibpajak_usaha_nama]').val(res.JENIS_USAHA);
                    $('[name=wajibpajak_nama]').val(res.NAMA_WP);
                    $('[name=wajibpajak_alamat]').val(res.ALAMAT_WP);
                }else{
                    $('[name=wajibpajak_usaha_nama]').val('');
                    $('[name=wajibpajak_nama]').val('');
                    $('[name=wajibpajak_alamat]').val('');
                    swal.fire('Informasi', 'NPWPD tidak ditemukan', 'warning');
                }
                HELPER.unblock();
            }
        })
    }
    
    function cancelSignup() {
        $('.header-login, .login-signin-wp').show(500);
        $('.login-signup-wp').hide(500);
    }
</script>