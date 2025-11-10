<script>
  $(() => {
    HELPER.fields = [
      'wajibpajak_npwpd',
      'wajibpajak_sektor_nama',
      'jenis_kode',
      'wajibpajak_nama',
      'wajibpajak_alamat',
      'wajibpajak_nama_penanggungjawab',
      'wajibpajak_telp',
      'wajibpajak_email',
      'wajibpajak_password',
    ];
    HELPER.setRequired([
      'wajibpajak_npwpd',
      'wajibpajak_sektor_nama',
      'wajibpajak_nama',
      'wajibpajak_alamat',
      'wajibpajak_nama_penanggungjawab',
      'wajibpajak_telp',
      'wajibpajak_email',
      'wajibpajak_password',
		]);
  })

  function doRevisi(){
    HELPER.block();
    HELPER.ajax({
        type: "POST",
        url: BASE_URL + "mitra/doRevisi",
        data: $('[name=kt_login_signup_form]').serializeObject(),
        success: function(response) {
            $('#password').val('')
            if (response.success) {
                swal.fire({
                    title: "Success",
                    text: "Selamat, akun Anda berhasil disimpan!. Harap login setelah menerima email verifikasi.",
                    icon: "success"
                }).then((result) => {
                    HELPER.unblock();
                    window.location.href = BASE_URL_NO_INDEX;
                });
            } else {
                let res = response;
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
        complete: function(res){
            HELPER.unblock();
        }
    });
  }
  
  function cancelSignup(){
    window.location.reload();
  }
</script>