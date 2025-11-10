<script type="text/javascript">
  $(function (){
    HELPER.api = {
      changePassword: BASE_URL + 'wajibpajak/changepassword',
    }

    $('#kt_reset_submit').click(function(){
      console.log("here")
      changePassword();
    })
  });

  function changePassword(){
    if(!$('#password').val() || !$('#confirm-password').val()){
      HELPER.showMessage({
        info: 'warning',
        success: 'warning',
        title: 'Peringatan',
        message: 'Password dan Konfirmasi password tidak boleh kosong!'
      })
      return;
    }
    if($('#password').val() != $('#confirm-password').val()){
      HELPER.showMessage({
        info: 'warning',
        success: 'warning',
        title: 'Peringatan',
        message: 'Konfirmasi password tidak cocok!'
      })
      return;
    }
    HELPER.block();
    $.ajax({
      url: HELPER.api.changePassword,
      data: $('#kt_reset_form').serializeObject(),
      type: 'post',
      success: function(res){
        if(res.success){
          HELPER.showMessage({
            success: true,
            text: 'Berhasil',
            message: 'Berhasil mengubah password!',
            callback: function () {
              window.location.replace(BASE_URL_NO_INDEX);
            }
          });
          return;
        }
        HELPER.showMessage();
      },
      error: function(e){
        HELPER.showMessage()
      },
      complete: function(){
        HELPER.unblock();
      },
    })
  }
</script>