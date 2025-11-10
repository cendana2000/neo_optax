<script type='text/javascript'>
  $(function() {
    HELPER.fields = [
      'wajibpajak_id',
      'wajibpajak_nama_penanggungjawab',
      'wajibpajak_npwpd',
      'wajibpajak_sektor_nama',
      'wajibpajak_nama',
      'wajibpajak_telp',
      'wajibpajak_email',
      'wajibpajak_alamat',
    ];
    HELPER.setRequired([]);
    HELPER.api = {
      table: BASE_URL + 'profil/',
      read: BASE_URL + 'profil/read',
      update: BASE_URL + 'profil/update',
      removeImage: BASE_URL + 'profil/removeImage',
    }
    /*HELPER.initTable({
      el : 'table-satuan',
      url: HELPER.api.table,
    })*/
    loadForm();
  });

  function loadForm() {
    HELPER.ajax({
      url: HELPER.api.read,
      complete: function(res) {
        HELPER.fields.map(item => {
          $('#' + item).val(res[item]).trigger('change');
        });
        $('#toko_kode').val(res.toko_kode);
        var urlImage = BASE_URL.replace('/index.php/', '') + res['wajibpajak_berkas'];
        $('.show-wajibpajak-image').css('background-image', 'url(' + urlImage + ')');
        $('.show-wajibpajak-image').data('imagedb', res['wajibpajak_berkas']);
      }
    });
  }

  function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function(e) {
        // $('#blah').attr('src', e.target.result);
        $('.show-wajibpajak-image').css('background-image', 'url(' + e.target.result + ')');
      }

      reader.readAsDataURL(input.files[0]);
    }
  }

  function onChangeImage(el) {
    readURL($('#wajibpajak_image')[0]);
  }

  function onRemoveImage(el) {
    if(!$('#wajibpajak_image').attr('disabled')){
      if($('#wajibpajak_image')[0].files.length === 0 && $('.show-wajibpajak-image').data('imagedb') !== null){
        HELPER.confirm({
        message: 'Apakah anda yakin ingin menghapus berkas NPWP?',
        callback: function(suc) {
          if (suc) {
            $.ajax({
              data: {
                id: $('#wajibpajak_id').val()
              },
              url: HELPER.api.removeImage,
              confirm: true,
              type: 'post',
              complete: function(res) {
                var result = res.responseJSON
                if (result.success) {
                  HELPER.showMessage({
                    success: true,
                    title: 'Success',
                    message: 'Berhasil menghapus berkas NPWP'
                  });
                  onCancel($('#btnCancel'));
                } else {
                  HELPER.showMessage({
                    success: 'info',
                    title: 'Stop',
                    message: res.message
                  });
                }
                if ($('#wajibpajak_image').val()) {
                  $($('#wajibpajak_image').val(''));
                }
                HELPER.unblock(100);
              },
              finally: function () {
                loadForm();
              }
            })
          }
        }
      })
      } else {
        $($('#wajibpajak_image').val(''));
        loadForm();
      }
    }
  }

  function onUpdate(el) {
    HELPER.fields
      .filter(f => f !== 'wajibpajak_nama_penanggungjawab' && f !== 'wajibpajak_npwpd' && f !== 'wajibpajak_sektor_nama' && f !== 'wajibpajak_nama')
      .map(item => {
        $('#' + item).removeAttr('readonly').removeClass('form-control-solid');
      });
    HELPER.fields.filter(f => f == 'wajibpajak_telp' || f == 'wajibpajak_email').map(item => {
      $('#' + item).parent().removeClass('input-group-solid');
    })
    $('#wajibpajak_image').removeAttr('disabled');
    $('#btnCancel').removeAttr('disabled');
    $('#btnSaveChanges').removeAttr('disabled');
  }

  function onCancel(el) {
    HELPER.fields.map(item => {
      $('#' + item).attr('readonly', true).addClass('form-control-solid');
    });
    HELPER.fields.filter(f => f == 'wajibpajak_telp' || f == 'wajibpajak_email').map(item => {
      $('#' + item).parent().addClass('input-group-solid');
    })
    $('#wajibpajak_image').attr('disabled', true);
    $(el).attr('disabled', true);
    $('#btnSaveChanges').attr('disabled', true);
    loadForm();
  }

  function updateDataMitra(el) {
    var formData = new FormData();

    HELPER.fields.map(item => {
      formData.append(item, $('#' + item).val());
    });

    if ($('#wajibpajak_image').val()) {
      formData.append('wajibpajak_image', $('#wajibpajak_image').prop('files')[0]);
    }

    // console.log($('#wajibpajak_image').val())

    HELPER.confirm({
      message: 'Apakah anda yakin ingin mengubah informasi wajib pajak?',
      callback: function(suc) {
        if (suc) {
          $.ajax({
            data: formData,
            url: HELPER.api.update,
            confirm: true,
            contentType: false,
            processData: false,
            cache: false,
            type: 'post',
            complete: function(res) {
              var result = res.responseJSON
              if (result.success) {
                HELPER.showMessage({
                  success: true,
                  title: 'Success',
                  message: 'Berhasil mengubah info wajib pajak'
                });
                onCancel($('#btnCancel'));
              } else {
                HELPER.showMessage({
                  success: 'info',
                  title: 'Stop',
                  message: res.message
                });
              }
              if ($('#wajibpajak_image').val()) {
                $($('#wajibpajak_image').val(''));
              }
              HELPER.unblock(100);
            }
          })
        }
      }
    })
  }
</script>