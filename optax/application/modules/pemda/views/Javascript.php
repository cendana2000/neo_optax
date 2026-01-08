<script>
    var avatar5 = new KTImageInput('pemda_logo');
    var maps = null;
    var marker = null;
    $(function() {
        HELPER.api = {
            table: BASE_URL + 'pemda',
            read: BASE_URL + 'pemda/read',
            save: BASE_URL + 'pemda/save',
            update: BASE_URL + 'pemda/update'
        }

        HELPER.createCombo({
            el: ['select_provinsi'],
            valueField: 'provinsi_id',
            displayField: 'provinsi_nama',
            url: BASE_URL + 'pemda/combobox_provinsi',
            withNull: true,
            grouped: false,
            select2: true,
            select2Parent: '#modalData',
            callback: function() {}
        });

        HELPER.createCombo({
            el: ['select_kabkota'],
            valueField: 'kabkota_id',
            displayField: 'kabkota_nama',
            url: BASE_URL + 'pemda/combobox_kabkota',
            withNull: true,
            grouped: false,
            select2: true,
            select2Parent: '#modalData',
            callback: function() {}
        });

        $(".sort-by-status .nav-link").click(function(e) {
            e.preventDefault();
            $(".sort-by-status .nav-link").removeClass("active");
            $(this).addClass("active");
            loadTable();
        });

        $('#provinsi_id').on('change', function() {
            loadKabkotaId($(this).val());
        });
        loadTable()
    })

    function loadTable() {
        const activeStatus = $(".sort-by-status .nav-link.active").attr("data");
        HELPER.initTable({
            el: 'table-pemda',
            url: HELPER.api.table,
            searchAble: true,
            destroyAble: true,
            responsive: false,
            data: {
                filter_status: activeStatus
            },
            columnDefs: [{
                    targets: 1,
                    render: function(data, type, full, meta) {
                        return full['pemda_nama'];
                    }
                },
                {
                    targets: 2,
                    render: function(data, type, full, meta) {
                        return full['pemda_kode'];
                    }
                },
                {
                    targets: 3,
                    render: function(data, type, full, meta) {
                        return full['provinsi_nama'];
                    }
                },
                {
                    targets: 4,
                    render: function(data, type, full, meta) {
                        return full['kabkota_nama'];
                    }
                },
                {
                    targets: 5,
                    render: function(data, type, full, meta) {
                        const status = {
                            'null': '<span class="label label-inline label-success">Aktif</span>',
                            '': '<span class="label label-inline label-success">Aktif</span>',
                            'not_null': '<span class="label label-inline label-warning">Tidak Aktif</span>'
                        };

                        let statusKey = 'not_null';

                        if (full['pemda_deleted_at'] === null ||
                            full['pemda_deleted_at'] === '' ||
                            full['pemda_deleted_at'] === 'null') {
                            statusKey = 'null';
                        }

                        return status[statusKey];
                    }
                },
                {
                    targets: 6,
                    orderable: false,
                    width: '100px',
                    render: function(data, type, full, meta) {
                        return `
                            <button type="button" 
                                    class="btn btn-sm btn-primary btn-detail" 
                                    onclick="onDetail('${full['pemda_id']}')">
                                Detail
                            </button>
                        `;
                    }
                }
            ],
        });
    }

    var isProvinsiEventBound = false;

    function onCreate() {
        $.each(HELPER.fields || [], function(i, v) {
            $('[name="' + v + '"]').val('').trigger('change');
        });
        $('#select_provinsi').val(null).trigger('change');
        $('#select_kabkota').empty().trigger('change');
        $('#select_kabkota').html('<option value="">Pilih Provinsi terlebih dahulu</option>');
        $('#select_kabkota').prop('disabled', true);
        $('#select_kabkota').select2({
            dropdownParent: $('#modalData'),
            placeholder: "Pilih Provinsi terlebih dahulu",
            width: '100%'
        });

        loadProvinsi();

        if (!isProvinsiEventBound) {
            $('#select_provinsi').off('change').on('change', function() {
                const provinsiId = $(this).val();
                loadKabkota(provinsiId);
            });
            isProvinsiEventBound = true;
        }

        $('#pemda_id').val('');
        $("#modal-title").html('Add Pemda');
        $("[data-action='cancel']").click();
        $("#modalData").modal('show');

        let lat = -7.9770;
        let lng = 112.6234;

        $('#pemda_coord').val(`${lat},${lng}`);
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
        if (marker && maps.removeLayer) {
            maps.removeLayer(marker);
        }
        marker = new L.Marker([lat, lng]);
        maps.addLayer(marker);
    }

    function onMapClick(e) {
        const latlng = e.latlng;
        if (marker) {
            maps.removeLayer(marker);
        }
        marker = new L.Marker(latlng);
        maps.addLayer(marker);
        $('#pemda_coord').val(`${latlng.lat},${latlng.lng}`);
    }

    function loadProvinsi() {
        HELPER.createCombo({
            el: 'select_provinsi',
            valueField: 'provinsi_id',
            displayField: 'provinsi_nama',
            url: BASE_URL + 'pemda/combobox_provinsi',
            withNull: true,
            grouped: false,
            select2: true,
            select2Parent: '#modalData',
            callback: function(response) {
                if (response.success && response.data) {
                    $('#select_provinsi').select2({
                        dropdownParent: $('#modalData'),
                        placeholder: "Pilih Provinsi",
                        width: '100%',
                        allowClear: true
                    });
                    $('#select_kabkota').empty().trigger('change');
                    $('#select_kabkota').html('<option value="">Pilih Provinsi terlebih dahulu</option>');
                    $('#select_kabkota').prop('disabled', true);
                    $('#select_kabkota').select2({
                        dropdownParent: $('#modalData'),
                        placeholder: "Pilih Provinsi terlebih dahulu",
                        width: '100%'
                    });
                }
            }
        });
    }

    function loadKabkota(provinsiId = null) {
        if (!provinsiId || provinsiId === '') {
            $('#select_kabkota').empty().trigger('change');
            $('#select_kabkota').html('<option value="">Pilih Provinsi terlebih dahulu</option>');
            $('#select_kabkota').prop('disabled', true);
            $('#select_kabkota').select2({
                dropdownParent: $('#modalData'),
                placeholder: "Pilih Provinsi terlebih dahulu",
                width: '100%'
            });
            return;
        }
        $('#select_kabkota').empty().trigger('change');
        $('#select_kabkota').html('<option value="">Memuat data...</option>');
        $('#select_kabkota').prop('disabled', false);

        HELPER.createCombo({
            el: 'select_kabkota',
            valueField: 'kabkota_id',
            displayField: 'kabkota_nama',
            url: BASE_URL + 'pemda/combobox_kabkota',
            data: {
                provinsi_id: provinsiId
            },
            withNull: true,
            grouped: false,
            select2: true,
            select2Parent: '#modalData',
            callback: function(response) {
                if (response.success && response.data && response.data.length > 0) {
                    $('#select_kabkota').select2({
                        dropdownParent: $('#modalData'),
                        placeholder: "Pilih Kabupaten/Kota",
                        width: '100%',
                        allowClear: true
                    });
                } else {
                    $('#select_kabkota').html('<option value="">Tidak ada kabupaten/kota</option>');
                    $('#select_kabkota').select2({
                        dropdownParent: $('#modalData'),
                        placeholder: "Tidak ada data",
                        width: '100%'
                    });
                }
            },
            errorCallback: function(xhr, status, error) {
                $('#select_kabkota').html('<option value="">Gagal memuat data</option>');
                $('#select_kabkota').select2({
                    dropdownParent: $('#modalData'),
                    placeholder: "Error loading data",
                    width: '100%'
                });
            }
        });
    }

    function save(id) {
        var form = $('#' + id)[0];
        var formData = new FormData(form);

        HELPER.save({
            cache: false,
            data: formData,
            contentType: false,
            processData: false,
            form: 'pemda_app-form',
            confirm: true,
            url: HELPER.api.save,
            callback: function(success, id, record, message) {
                if (success) {
                    HELPER.showMessage({
                        success: true,
                        title: "Success",
                        message: "Successfully saved data"
                    });

                    $("#modalData").modal('hide')
                    loadTable()
                } else {
                    HELPER.showMessage({
                        success: false
                    })
                }
                HELPER.unblock(100);
            },
            oncancel: function(result) {
                HELPER.unblock(100);
            }
        });
    }

    function loadProvinsiId(selectedId = null) {
        $.ajax({
            url: BASE_URL + 'pemda/combobox_provinsi',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                const $provinsi = $('#provinsi_id');
                $provinsi.empty().append('<option value="">-- Pilih Provinsi --</option>');

                res.data.forEach(item => {
                    const selected = selectedId == item.provinsi_id ? 'selected' : '';
                    $provinsi.append(
                        `<option value="${item.provinsi_id}" ${selected}>${item.provinsi_nama}</option>`
                    );
                });

                $provinsi.trigger('change.select2');
            }
        });
    }

    function loadKabkotaId(provinsiId, selectedId = null) {
        if (!provinsiId) return;

        $.ajax({
            url: BASE_URL + 'pemda/combobox_kabkota',
            type: 'POST',
            dataType: 'json',
            data: {
                provinsi_id: provinsiId
            },
            success: function(res) {
                const $kabkota = $('#kabkota_id');
                $kabkota.empty().append('<option value="">-- Pilih Kab/Kota --</option>');

                res.data.forEach(item => {
                    const selected = selectedId == item.kabkota_id ? 'selected' : '';
                    $kabkota.append(
                        `<option value="${item.kabkota_id}" ${selected}>${item.kabkota_nama}</option>`
                    );
                });

                $kabkota.trigger('change.select2');
            }
        });
    }

    function onDetail(id) {
        HELPER.loadData({
            url: HELPER.api.read,
            server: true,
            data: {
                pemda_id: id
            },
            callback: function(res) {
                const imagePath = res.pemda_logo ?
                    BASE_URL_NO_INDEX + 'dokumen/pemda/' + res.pemda_logo :
                    'assets/media/users/blank.png';
                $('#provinsi_id').select2();
                $('#kabkota_id').select2();
                loadProvinsiId(res.provinsi_id);
                loadKabkotaId(res.provinsi_id, res.kabkota_id);
                testImageLoad(imagePath, function(success) {
                    $('.show-pemda-logo')
                        .css('background-image',
                            success ? `url(${imagePath})` : 'url(assets/media/users/blank.png)')
                        .data('imagedb', res.pemda_logo);
                });

                if (res.pemda_deleted_at === null) {
                    $('#pemda_status').val('active');
                } else {
                    $('#pemda_status').val('inactive');
                }

                let lat = -6.9175;
                let lng = 107.6191;
                const coord = parseLatLng(res.pemda_coord);
                if (coord) {
                    lat = coord.lat;
                    lng = coord.lng;
                }

                $('#pemda_coord').val(`${lat},${lng}`);
                if (maps && maps.remove) {
                    maps.off();
                    maps.remove();
                }
                maps = new L.Map('map_1').setView([lat, lng], 13);
                new L.TileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(maps);
                maps.on('click', onMapClickId);
                if (marker && maps.removeLayer) {
                    maps.removeLayer(marker);
                }
                marker = new L.Marker([lat, lng]);
                maps.addLayer(marker);

                HELPER.toggleForm({
                    tohide: 'table_data',
                    toshow: 'form_data'
                });
            }
        });
    }

    function onMapClickId(e) {
        const {
            lat,
            lng
        } = e.latlng;

        if (marker) {
            maps.removeLayer(marker);
        }
        marker = L.marker([lat, lng]).addTo(maps);
        $('#pemda_coord').val(`${lat},${lng}`);
    }

    function parseLatLng(coord) {
        if (!coord) return null;
        coord = coord.replace(/[()]/g, '').trim();

        const parts = coord.split(',');
        if (parts.length !== 2) return null;

        const lat = parseFloat(parts[0]);
        const lng = parseFloat(parts[1]);

        if (isNaN(lat) || isNaN(lng)) return null;

        return {
            lat,
            lng
        };
    }

    function testImageLoad(url, callback) {
        const img = new Image();
        img.onload = function() {
            console.log('Gambar berhasil dimuat:', url);
            callback(true);
        };
        img.onerror = function() {
            console.error('GAGAL memuat gambar:', url);
            callback(false);
        };
        img.src = url;
    }

    function onChangeLogoPemda() {
        const input = $('#pemda_logo_file')[0];

        if (!input.files || !input.files[0]) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            $('.show-pemda-logo').css(
                'background-image',
                `url(${e.target.result})`
            );
        };
        reader.readAsDataURL(input.files[0]);
    }

    function onRemoveLogoPemda() {
        const imageDb = $('.show-pemda-logo').data('imagedb');
        if (!imageDb) {
            $('#pemda_logo_file').val('');
            $('.show-pemda-logo').css(
                'background-image',
                'url(assets/media/users/blank.png)'
            );
            return;
        }
        HELPER.confirm({
            message: 'Apakah anda yakin ingin menghapus logo pemda?',
            callback: function(yes) {
                if (!yes) return;
                $.ajax({
                    url: HELPER.api.removeLogoPemda,
                    type: 'POST',
                    data: {
                        pemda_id: $('input[name=pemda_id]').val()
                    },
                    success: function(res) {
                        if (res.success) {
                            HELPER.showMessage({
                                success: true,
                                title: 'Berhasil',
                                message: 'Logo pemda berhasil dihapus'
                            });

                            $('.show-pemda-logo')
                                .css('background-image', 'url(assets/media/users/blank.png)')
                                .data('imagedb', null);

                            $('#pemda_logo_file').val('');
                        }
                    }
                });
            }
        });
    }

    function onRemoveLogoPemda() {
        $('#remove_logo').val(1);
        $('.show-pemda-logo')
            .css('background-image', 'url(assets/media/users/blank.png)')
            .data('imagedb', null);
        $('#pemda_logo_file').val('');
    }

    function update() {
        var form = $('#form-pemda')[0];
        var formData = new FormData(form);
        HELPER.save({
            cache: false,
            data: formData,
            contentType: false,
            processData: false,
            form: 'pemda_app-form',
            confirm: true,
            url: HELPER.api.update,
            callback: function(success, id, record, message) {
                if (success) {
                    HELPER.showMessage({
                        success: true,
                        title: "Success",
                        message: "Berhasil update data"
                    });
                    HELPER.toggleForm({
                        tohide: 'form_data',
                        toshow: 'table_data'
                    });
                } else {
                    HELPER.showMessage({
                        success: false,
                        title: "Gagal",
                        message: message || "Gagal update data"
                    })
                }
                HELPER.unblock(100);
            },
            oncancel: function(result) {
                HELPER.unblock(100);
            }
        });
    }
</script>