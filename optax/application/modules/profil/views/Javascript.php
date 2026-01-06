<script type='text/javascript'>
  var is_init = false
  var maps = null;
  var marker = null;
  var is_readonly = true;

  $(function() {
    is_init = false;

    HELPER.fields = [
      'wajibpajak_id',
      'wajibpajak_nama_penanggungjawab',
      'wajibpajak_npwpd',
      'wajibpajak_sektor_nama',
      'wajibpajak_nama',
      'wajibpajak_telp',
      'wajibpajak_email',
      'wajibpajak_alamat',
      'kecamatan_id',
      'kelurahan_id',
      'wajibpajak_coord',
      'wajibpajak_berkas'
    ];
    HELPER.setRequired([]);
    HELPER.api = {
      table: BASE_URL + 'profil/',
      read: BASE_URL + 'profil/read',
      update: BASE_URL + 'profil/update',
      removeImage: BASE_URL + 'profil/removeImage',
    }

    $('#kelurahan_id').select2()

    $('#kecamatan_id').on('change', function() {
      if (is_init) {
        is_init = false;
        return;
      }

      HELPER.createCombo({
        el: 'kelurahan_id',
        url: BASE_URL + 'profil/kelurahan',
        data: {
          kecamatan_id: this.value
        },
        valueField: 'kelurahan_id',
        displayField: 'kelurahan_nama',
        placeholder: '-Pilih-',
        callback: function(resp) {
          $('#kelurahan_id').select2();
        }
      })
    })

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
          if (item == 'kecamatan_id') return
          if (item == 'kelurahan_id') return
          if (item == 'wajibpajak_coord') return

          $('#' + item).val(res[item]).trigger('change');
        });
        $('#toko_kode').val(res.toko_kode);
        if (res.wajibpajak_berkas) {
          const imgUrl = 'assets/media/berkasnpwpd/' + res.wajibpajak_berkas;

          $('#kt_profile_avatar').css('background-image', 'none');
          $('.show-wajibpajak-image')
            .css({
              'background-image': 'url(' + imgUrl + '?t=' + Date.now() + ')',
              'background-size': 'cover',
              'background-repeat': 'no-repeat',
              'background-position': 'center center',
              'display': 'block'
            })
            .data('imagedb', res.wajibpajak_berkas);
        } else {
          console.log('Tidak ada berkas untuk ditampilkan');
        }
        is_init = true

        HELPER.createCombo({
          el: 'kecamatan_id',
          url: BASE_URL + 'profil/kecamatan',
          valueField: 'kecamatan_id',
          displayField: 'kecamatan_nama',
          placeholder: '-Pilih-',
          callback: function() {
            // $('#kecamatan_id').select2()

            $('#kecamatan_id').val(res.kecamatan_id).trigger('change');

            HELPER.createCombo({
              el: 'kelurahan_id',
              url: BASE_URL + 'profil/kelurahan',
              data: {
                kecamatan_id: res.kecamatan_id
              },
              valueField: 'kelurahan_id',
              displayField: 'kelurahan_nama',
              placeholder: '-Pilih-',
              callback: function(resp) {
                $('#kelurahan_id').val(res.kelurahan_id).trigger('change');
                $('#kelurahan_id').select2();
              }
            })
          }
        })

        let lat = -7.9770;
        let lng = 112.6234;

        if (res.wajibpajak_coord) {
          const cleanString = res.wajibpajak_coord.replace("(", "").replace(")", "");
          const coordinatesArray = cleanString.split(",");
          lat = parseFloat(coordinatesArray[0].trim());
          lng = parseFloat(coordinatesArray[1].trim());

          $('#wajibpajak_coord').val(`${lat},${lng}`);
        }

        if (maps && maps.remove) {
          maps.off();
          maps.remove();
        }

        maps = new L.Map('map').setView([lat, lng], 13);
        new L.TileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
          maxZoom: 19,
          attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(maps);

        maps.on('click', onMapClick);

        if (res.wajibpajak_coord) {
          marker = new L.Marker([lat, lng]);
          maps.addLayer(marker);
        }
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
    if (!$('#wajibpajak_image').attr('disabled')) {
      if ($('#wajibpajak_image')[0].files.length === 0 && $('.show-wajibpajak-image').data('imagedb') !== null) {
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
                finally: function() {
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
    $('#kecamatan_id').removeAttr('disabled');
    $('#kelurahan_id').removeAttr('disabled');
    $('#btnCancel').removeAttr('disabled');
    $('#btnSaveChanges').removeAttr('disabled');
    $('#wajibpajak_image').prop('disabled', false);
    is_readonly = false;
  }

  function onCancel(el) {
    is_readonly = true;

    HELPER.fields.map(item => {
      $('#' + item).attr('readonly', true).addClass('form-control-solid');
    });
    HELPER.fields.filter(f => f == 'wajibpajak_telp' || f == 'wajibpajak_email').map(item => {
      $('#' + item).parent().addClass('input-group-solid');
    })
    $('#wajibpajak_image').attr('disabled', true);
    $('#kecamatan_id').attr('disabled', true);
    $('#kelurahan_id').attr('disabled', true);
    $(el).attr('disabled', true);
    $('#btnSaveChanges').attr('disabled', true);
    loadForm();
  }

  function updateDataMitra(el) {
    $('#wajibpajak_image').prop('disabled', false);
    var formData = new FormData();

    HELPER.fields.map(item => {
      formData.append(item, $('#' + item).val());
    });

    if ($('#wajibpajak_image')[0].files.length > 0) {
      formData.append(
        'wajibpajak_image',
        $('#wajibpajak_image')[0].files[0]
      );
    }

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

  function onMapClick(e) {
    if (is_readonly) return

    const latlng = e.latlng;
    if (marker) {
      maps.removeLayer(marker)
    }
    marker = new L.Marker(latlng);
    maps.addLayer(marker);

    $('#wajibpajak_coord').val(`${latlng.lat},${latlng.lng}`);
  }
</script>