<style>
    .image-zoom-container {
        position: relative;
        width: 100%;
        /* Ganti dengan lebar yang sesuai */
        /* height: 350px; */
        /* Ganti dengan tinggi yang sesuai */
        border: solid 5px #fff;
        overflow: hidden;
        background: #f4f4f4;
    }

    .zoomable-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .zoomed-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-size: 100%;
        /* Besarkan latar belakang sebesar tiga kali ukuran gambar */
        background-repeat: no-repeat;
        background-position: 50% 50%;
        transition: transform 0.3s ease;
        /* Efek zoom animasi */
        cursor: zoom-in;
        /* Agar lapisan zoom tidak mengganggu mouse events */
    }

    .stick-image {
        height: 100%;
        background-size: 100%;
        background-repeat: no-repeat;
        background-position: center;
    }

    .select2-container {
        z-index: 1090;
    }

    .nav-align-top .nav {
        flex-wrap: nowrap !important;
    }

    .nav-align-top .nav button.nav-link {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .nav .nav-item,
    .nav .nav-link,
    .tab-pane,
    .tab-pane .card-body {
        width: 100%;
        display: flex;
    }

    .nav-pills .nav-link {
        height: 100%;
        justify-content: center;
    }

    @media (max-width: 767.98px) {
        .tab-content {
            padding: 0;
        }

        .modal .modal-dialog:not(.modal-fullscreen) {
            margin: auto;
        }
    }
</style>

<!-- Content -->
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4 fs-sm-6"><span class="text-muted fw-light">Daftar Undian</span></h4>

    <div class="row">
        <div class="col-xl-12">
            <!-- HTML5 Inputs -->
            <div class="card mb-4">
                <div class="d-flex justify-content-between p-3 align-items-center">
                    <h5 class="card-header p-0">Daftar Permintaan Nomor Undian</h5>
                    <div class="btn-group mt-lg-0 mt-3" role="group" aria-label="Basic example">
                        <a class="btn btn-warning btn-refresh" href="#"><i class="fa-solid fa-repeat"></i> REFRESH</a>
                        <!-- <a class="btn btn-primary btn-tambah" href="<?= base_url('pajak_hiburan/form') ?>"><i class="fa-solid fa-plus"></i> TAMBAH</a> -->
                    </div>

                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table" id="dataTable">
                        <thead>
                            <tr>
                                <th data="no">#</th>
                                <th data="nama">Nama</th>
                                <th data="alamat">Alamat</th>
                                <th data="no_handphone">No. Handphone</th>
                                <th data="tanggal_request">Tanggal Request</th>
                                <th data="no_undian">No. Undian</th>
                                <th data="status">Status</th>
                                <th data="aksi">Aksi</th>
                                <th data="responsive">Responsive</th>
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

<div class="modal fade" id="exLargeModal" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel4">VALIDASI BONBIL</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body container-fluid">
                <div class="row g-2">
                    <div class="col-lg-4 col-md-12 col-sm-12 mb-0">
                        <div class="image-zoom-container shadow-sm">
                            <div id="openseadragon1" style="width: 100%; height: 300px;"></div>
                        </div>
                        <div class="detail-bonbil my-3">
                            <div class="card">
                                <div class="card-header fw-bold">
                                    Info Pengirim
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-4">Nama</div>
                                        <div class="col detail-nama"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">Alamat</div>
                                        <div class="col detail-alamat"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">No. Telp</div>
                                        <div class="col detail-no_telp"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">Tanggal</div>
                                        <div class="col detail-tanggal-kirim"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-12 col-sm-12 mb-0">
                        <div class="card">
                            <div class="card-body pb-0">
                                <div class="nav-align-top mb-3 pb-3 pt-0">
                                    <ul class="nav nav-pills m-0 nav-fill bg-label-secondary rounded" role="tablist">
                                        <li class="nav-item">
                                            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-validasi-resi" aria-controls="navs-pills-justified-home" aria-selected="true">
                                                <i class="tf-icons bx bx-receipt"></i> Nomor Resi
                                            </button>
                                        </li>
                                        <li class="nav-item">
                                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-validasi-tanggal-jam" aria-controls="navs-pills-justified-profile" aria-selected="false">
                                                <i class="tf-icons bx bx-time"></i> Tanggal Dan Jam
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                                <div class="tab-content pb-0 px-0">

                                    <div class="tab-pane validasi-resi active" id="navs-pills-validasi-resi" role="tabpanel">
                                        <h6 class="text-muted text-bold mb-4">Form Validasi Menggunakan Resi Bonbil</h6>
                                        <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label" for="daftar_etax_resi">Lokasi Etax</label>
                                            <div class="col-sm-9" id="daftar_etax_resi_parent">
                                                <select class="form-control select2" id="daftar_etax_resi" disabled>
                                                    <option value="">Wait..</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label" for="no_resi">No. Resi</label>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" placeholder="No. Resi" aria-label="Recipient's username" aria-describedby="button-addon2" id="no_resi">
                                                    <button class="btn btn-primary" type="button" id="btn-check-resi">Cek Validasi</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label">Status</label>
                                            <div class="col-sm-9">
                                                <span class="badge bg-label-secondary">Waiting..</span>
                                                <span class="btn btn-sm bg-label-secondary">
                                                    <i class="fa-solid fa-rotate-right"></i> Reset Status
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane validasi-tanggal-jam" id="navs-pills-validasi-tanggal-jam" role="tabpanel">
                                        <h6 class="text-muted text-bold mb-4">Form Validasi Menggunakan Tanggal Dan Jam</h6>
                                        <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label" for="daftar_etax_tanggal">Lokasi Etax</label>
                                            <div class="col-sm-9" id="daftar_etax_tanggal_parent">
                                                <select class="form-control select2" id="daftar_etax_tanggal" disabled>
                                                    <option value="">Wait..</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label" for="tanggal">Tanggal</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control datepicker" id="tanggal">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label" for="jam">Jam</label>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <input type="text" class="form-control timepicker" aria-label="Recipient's username" aria-describedby="button-addon2" id="jam">
                                                    <button class="btn btn-primary" type="button" id="btn-check-resi">Cek Validasi</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label">Status</label>
                                            <div class="col-sm-9 status_field">
                                                <span class="badge bg-label-secondary">Waiting..</span>
                                                <span class="btn btn-sm bg-label-secondary"><i class="fa-solid fa-rotate-right"></i> Reset Status</span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer pt-0">
                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label">Status Verifikasi</label>
                                    <div class="col-sm-9 status_field">
                                        <div class="form-check form-check-inline">
                                            <input name="default-radio-1" class="form-check-input" type="radio" value="" id="defaultRadio1" checked>
                                            <label class="form-check-label" for="defaultRadio1"> Approve </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input name="default-radio-1" class="form-check-input" type="radio" value="" id="defaultRadio2">
                                            <label class="form-check-label" for="defaultRadio2"> Tolak </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3 alasan-tolak">
                                    <label class="col-sm-3 col-form-label">Alasan Tolak</label>
                                    <div class="col-sm-9 status_field">
                                        <select class="form-control" id="daftar_alasan_tolak" disabled>
                                            <option value="">Wait..</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label"></label>
                                    <div class="col-sm-9 status_field">
                                        <button type="button" class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan</button>
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                            Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>
<!-- / Content -->