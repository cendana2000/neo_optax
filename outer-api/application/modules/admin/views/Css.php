<link rel="stylesheet" href="<?= base_url('assets/plugin/datatables/dataTables.bootstrap4.min.js') ?>">
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

    div:where(.swal2-container) {
        z-index: 1100;
    }

    #dataTable_wrapper * {
        font-size: 12px !important;
    }

    .nav-align-top * {
        font-size: 14px !important;
    }

    #daftar_etax_tanggal+.select2-container,
    #daftar_etax_resi+.select2-container {
        width: 1% !important;
        flex: 1 1 auto;
    }

    .select2-container--default .select2-selection--single,
    .select2-container--default .select2-selection--multiple {
        border-radius: 0.42rem 0 0 0.42rem;
    }

    .modal-header {
        cursor: move;
        user-select: none;
    }

    .image-new-tab {
        position: absolute;
        top: 2px;
        right: 5px;
    }

    .text-wrap {
        white-space: normal;
    }

    .width-200 {
        width: 200px;
    }

    .was-validated :invalid~.invalid-feedback,
    .was-validated :invalid~.invalid-tooltip,
    .is-invalid~.invalid-feedback,
    .is-invalid~.invalid-tooltip {
        display: block;
    }
</style>