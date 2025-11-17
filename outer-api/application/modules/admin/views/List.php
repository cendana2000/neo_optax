<?= $this->load->view('admin/css'); ?>

<!-- Content -->
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4 fs-sm-6"><span class="text-muted fw-light">Setting API SYNC</span></h4>
    <div class="row">
        <form class="form-setting" method="post" id="form_setting">
            <div class="col-xl-12">
                <div class="card mb-4">
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="d-flex justify-content-between p-3 align-items-center">
                                <h5 class="card-header p-0">Form Setting API SYNC</h5>
                                <div class="btn-group mt-lg-0 mt-3" role="group" aria-label="Basic example">
                                </div>
                            </div>
                            <div class="card-body pt-0">

                                <div class="mb-3">
                                    <input type="hidden" id="id_setting">
                                    <label class="form-label" for="nama_setting">Nama Setting</label>
                                    <input type="text" class="form-control" id="nama_setting" placeholder="Nama WP" required>
                                    <div class="invalid-feedback"> Isikan nama setting! </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="npwpd">NPWPD</label>
                                    <input type="text" class="form-control" id="npwpd" placeholder="xxxx.xxx.xxx" required>
                                    <div class="invalid-feedback"> Isikan NPWPD</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="nama_wp">Nama WP</label>
                                    <input type="text" id="nama_wp" class="form-control" placeholder="Nama WP" required>
                                    <div class="invalid-feedback"> Nama WP Belum Terisi</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="method">Method</label>
                                    <select id="method" class="form-control" required>
                                        <option value="GET">GET</option>
                                        <option value="POST">POST</option>
                                    </select>
                                    <div class="invalid-feedback"> Pilih Method!</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="link_curl">Link CURL</label>
                                    <textarea id="link_curl" class="form-control" rows="5" placeholder="https://mayang.majoo.id/0_0_11/laporan/sales?limit=100000000&page=1&is_payment=0&start_date={{start_date}}&end_date={{end_date}}" required></textarea>
                                    <div class="invalid-feedback"> Isikan Link URL Enpoint Yang Akan Diambil Datanya!</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="curlopt_header">CurlOpt Header</label>
                                    <textarea id="curlopt_header" class="form-control" rows="5" placeholder="Token:{{token}}"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="preset_curl">Preset Curl</label>
                                    <select id="preset_curl" class="form-control" required>
                                        <option value="MOKA">MOKA</option>
                                        <option value="MAJOO">MAJOO</option>
                                        <option value="OLSERA">OLSERA</option>
                                        <option value="OLSERAV2">OLSERA V2</option>
                                        <option value="PARAGON">PARAGON</option>
                                        <option value="PAWOON">PAWOON</option>
                                        <option value="ESBPOS">ESBPOS</option>
                                        <option value="ESBPOSV2">ESBPOS V2</option>
                                        <option value="NUTAPOS">NUTAPOS</option>
                                        <option value="LARISPOS">LARISPOS</option>
                                        <option value="LARISPOSV2">LARISPOS V2</option>
                                        <option value="LOYVERSE">LOYVERSE</option>
                                        <option value="KASIRPINTAR">KASIRPINTAR</option>
                                        <option value="GOBIZ">GOBIZ</option>
                                        <option value="LUNAPOS">LUNA POS</option>
                                        <option value="WOOGIGS">WOOGIGS</option>
                                        <option value="OMEGAPOS">OMEGAPOS</option>
                                    </select>
                                </div>
                                <button type="button" class="btn btn-secondary" onclick="clearForm()">Reset</button>
                                <button type="button" class="btn btn-primary btn-simpan-setting">Simpan</button>
                            </div>

                        </div>
                        <div class="col-xl-6">
                            <div class="d-flex justify-content-between p-3 align-items-center">
                                <h5 class="card-header p-0">&nbsp;</h5>
                                <div class="btn-group mt-lg-0 mt-3" role="group" aria-label="Basic example">
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="mb-3">
                                    <label class="form-label" for="need_token">Butuh Refresh Token?</label>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="need_token" checked="">
                                        <label class="form-check-label" for="need_token">Ya</label>
                                    </div>
                                </div>
                                <div class="form-tambahan">
                                    <div class="mb-3">
                                        <label class="form-label" for="token_req_url">Token Req URL</label>
                                        <textarea id="token_req_url" class="form-control" rows="5" placeholder="https://mayang.majoo.id/0_0_11/user/login"></textarea>

                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="token_req_payload">Token Req Payload</label>
                                        <textarea id="token_req_payload" class="form-control" rows="5" placeholder='{" email":"user.majoo@gmail.com", "password" :"xxxxxx", "is_cms" :1, "is_retina" :1 }'></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="token_req_method">Token Req Method</label>
                                        <select id="token_req_method" class="form-control" required>
                                            <option value="GET">GET</option>
                                            <option value="POST">POST</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <!-- HTML5 Inputs -->
            <div class="card mb-4">
                <div class="d-flex justify-content-between p-3 align-items-center">
                    <h5 class="card-header p-0">Data Pengaturan OAPI e-TAX</h5>
                    <div class="btn-group mt-lg-0 mt-3" role="group" aria-label="Basic example">
                        <a class="btn btn-warning btn-refresh" href="#"><i class="fa-solid fa-repeat"></i> REFRESH</a>
                    </div>
                </div>

                <div class="table-responsive text-nowrap">
                    <table class="table" id="dataTable">
                        <thead>
                            <tr>
                                <th data="no">#</th>
                                <th data="nama">Nama Pengaturan</th>
                                <th data="alamat">NPWPD</th>
                                <th data="no_handphone">Nama WP</th>
                                <th data="preset_curl">Preset Curl</th>
                                <th data="aksi">Aksi</th>
                                <th data="responsive">Data</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- / Content -->