<div class="row">
    <div class="col-md-12">
        <div class="card mb-3">
            <div class="card-header">
                <h4 class="card-title mb-0">Lokasi</h4>
            </div>
            <form id="form-search" method="post">
                <div class="card-body">
                    <div class="form-group row">
                        <label for="kecamatan_id" class="col-2 col-form-label">Kecamatan</label>
                        <div class="col-10">
                            <select class="form-control" name="kecamatan_id" id="kecamatan_id"></select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="kelurahan_id" class="col-2 col-form-label">Kelurahan</label>
                        <div class="col-10">
                            <select class="form-control" name="kelurahan_id" id="kelurahan_id">
                                <option value="">-Semua-</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-search"></i> Cari
                    </button>
                </div>
            </form>
        </div>

        <div id="map"></div>
    </div>
</div>

<style>
    #map {
        height: 512px;
        border-radius: 8px;
    }
</style>

<?php $this->load->view('javascript'); ?>