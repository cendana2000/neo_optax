<script>
    $(function() {
        var map = null
        var markerGroup = null;

        $.ajax({
            url: BASE_URL + 'map/get_center_point',
            success: function(response) {
                map = new L.Map('map').setView([response.lat, response.lng], 13);

                var tiles = new L.TileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map);

                markerGroup = new L.FeatureGroup().addTo(map);
            }
        })

        $('#kelurahan_id').select2()

        HELPER.createCombo({
            el: 'kecamatan_id',
            url: BASE_URL + 'map/kecamatan',
            valueField: 'kecamatan_id',
            displayField: 'kecamatan_nama',
            placeholder: '-Semua-',
            callback: function() {
                $('#kecamatan_id').select2()
            }
        })

        $('#kecamatan_id').on('change', function() {
            HELPER.createCombo({
                el: 'kelurahan_id',
                url: BASE_URL + 'map/kelurahan',
                data: {
                    kecamatan_id: this.value
                },
                valueField: 'kelurahan_id',
                displayField: 'kelurahan_nama',
                placeholder: '-Semua-',
                callback: function(resp) {
                    $('#kelurahan_id').select2();
                }
            })
        })

        $('#form-search').on('submit', function() {
            $.ajax({
                url: BASE_URL + 'map/get',
                method: 'POST',
                beforeSend: function() {
                    $('#form-search')
                        .find('button[type="submit"]')
                        .prop('disabled', true)
                        .html('<i class="fa fa-spinner fa-spin"></i> Loading ...')
                },
                data: {
                    kecamatan_id: $('#kecamatan_id').val(),
                    kelurahan_id: $('#kelurahan_id').val(),
                },
                complete: function() {
                    $('#form-search')
                        .find('button[type="submit"]')
                        .prop('disabled', false)
                        .html('<i class="fa fa-search"></i> Cari')
                },
                success: function(response) {
                    if (!response?.data) return;
                    if (!markerGroup || !response) return

                    markerGroup.clearLayers();

                    response.data.forEach(wp => {
                        if (wp.wajibpajak_coord) {
                            const cleanString = wp.wajibpajak_coord.replace("(", "").replace(")", "");
                            const coordinatesArray = cleanString.split(",");
                            const latitude = parseFloat(coordinatesArray[0].trim());
                            const longitude = parseFloat(coordinatesArray[1].trim());

                            const marker = new L.Marker([latitude, longitude])
                                .bindPopup(`
                                    <div>
                                        <b>(${wp.wajibpajak_npwpd})</b>
                                        <br>
                                        ${wp.wajibpajak_nama}
                                        <hr>
                                        <a href="https://www.google.com/maps/dir/?api=1&origin=Current+Location&destination=${latitude},${longitude}" target="_blank">
                                            <div class="text-decoration-none d-flex justify-content-center align-items-center gap-2 small text-muted goto-map" role="button">
                                                <span>Lihat Google Maps</span> <i class="fa fa-arrow-right"></i>
                                            </div>
                                        </a>
                                    </div>`);

                            markerGroup.addLayer(marker);
                        }
                    });

                    const bounds = markerGroup.getBounds();
                    if (bounds.isValid()) {
                        map.fitBounds(bounds, {
                            padding: [50, 50]
                        })
                    }
                }
            })

            return false;
        })
    });
</script>