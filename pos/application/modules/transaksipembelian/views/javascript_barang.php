<script type="text/javascript">
    $(document).ready(function() {
        $('.tnumber').number(true);
        $('#barang_stok').number(true);
        $('.disc').number(true, 2);

        HELPER.create_combo_akun({
            el: 'barang_kategori_barang_2',
            valueField: 'id',
            displayField: 'text',
            parentField: 'parent',
            childField: 'child',
            url: BASE_URL + 'kategori/go_tree',
            withNull: true,
            nesting: true,
            chosen: false,
            callback: function(res) {
                console.log(res);
                $('#barang_kategori_barang_2').select2();
            }
        });

        let jenis = [];

        HELPER.createCombo({
            el: 'barang_jenis_barang',
            valueField: 'jenis_id',
            displayField: 'jenis_nama',
            url: BASE_URL + 'jenis/select',
            callback: function(res) {
                $('#barang_jenis_barang').select2();
                jenis = res.data;
            }
        })

        HELPER.createCombo({
            el: 'barang_satuan_satuan_id_1',
            valueField: 'satuan_id',
            displayField: 'satuan_kode',
            url: BASE_URL + 'satuan/select',
            callback: function(res) {
                $('#barang_satuan_satuan_id_1').select2();
                jenis = res.data;
            }
        })

        HELPER.ajaxCombo({
            el: '#barang_bc',
            url: BASE_URL + 'barang/select_ajax',
        });

    });

    function simpanBarang() {
        swal.fire({
            title: 'Informasi',
            text: "Simpan sebagai barang baru ?",
            type: 'info',
            confirmButtonText: '<i class="fa fa-check"></i> Yes',
            confirmButtonClass: 'btn btn-focus btn-success m-btn m-btn--pill m-btn--air',
            showCancelButton: true,
            cancelButtonText: '<i class="fa fa-times"></i> No',
            cancelButtonClass: 'btn btn-focus btn-danger m-btn m-btn--pill m-btn--air',
            reverseButton: true
        }).then(function(result) {
            if (result.value) {
                kategori = (($('#barang_kategori_barang option:selected').text()).trim()).split(" - ");
                kode = kategori[0] || '';
                barang = $('#form-barang2').serializeObject();
                $.ajax({
                    url: BASE_URL + 'barang/store_sc',
                    data: barang,
                    type: 'post',
                    success: function(res) {
                        if (res.success == true) {
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                }
                            })

                            Toast.fire({
                                icon: 'success',
                                title: 'Barang berhasil ditambah'
                            })

                            backToForm();
                        }
                        $('#daftar_barang').modal('hide');
                        $('#form-barang').trigger("reset");
                        $('#form-barang select').val("").trigger('change');
                    }
                })
            }
        });
    }

    function setUntung2(row) {
        untung_persen = parseFloat($('#barang_satuan_keuntungan_' + row).val()) || 0;
        hb = parseFloat($('#barang_satuan_harga_beli_' + row).val()) || 0;
        untung = untung_persen * hb / 100;
        hj = untung + hb;
        $('#barang_satuan_harga_jual_' + row).val(hj);
    }

    function setUntungRp2(row) {
        hj = parseFloat($('#barang_satuan_harga_jual_' + row).val()) || 0;
        hb = parseFloat($('#barang_satuan_harga_beli_' + row).val()) || 0;
        untung = ((hj - hb) * 100) / hj;
        $('#barang_satuan_keuntungan_' + row).val(untung);
    }

    function backToForm() {
        HELPER.toggleForm({
            tohide: 'form_barang',
            toshow: 'form_data'
        });
    }
</script>