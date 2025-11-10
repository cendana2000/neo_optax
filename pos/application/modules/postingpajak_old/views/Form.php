<div class="form_data" style="display: none;">
  <div class="row">
    <div class="col-12 mb-8">
      <div class="card card-custom">
        <div class="card-header">
          <div class="card-title">
            <span class="card-icon">
              <i class="fas fa-table text-primary"></i>
            </span>
            <h3 class="card-label">DATA LAPOR PAJAK</h3>
          </div>
          <div class="card-toolbar">
            <button class="btn btn-secondary" onclick="onBack()"><i class="fa fa-arrow-left"></i> Kembali</button>
          </div>
        </div>
        <form action="javascript:getlaporan('form-realisasi-laporan')" method="post" id="form-realisasi-laporan" name="form-realisasi-laporan" autocomplete="off" enctype="multipart/form-data">
          <div class="card-body">
            <div class="row">
              <div class="col-12">
                <div class="form-group row">
                  <label class="col-2 col-form-label" for="periode_tanggal">Periode</label>
                  <div class="col-5">
                    <!-- <input class="form-control datepicker" type="text" value="<?= date_format((new DateTime(date('Y-m-d')))->modify('-1 day'), 'd/m/Y'); ?>" id="periode_tanggal" name="periode_tanggal" /> -->
                    <input class="form-control datepicker" type="text" value="<?= date_format((new DateTime(date('Y-m-d'))), 'Y-m'); ?>" id="periode_tanggal" name="periode_tanggal" />
                  </div>
                  <div class="col-5">
                    <button type="submit" class="btn btn-success" id="btn_save"><span class="fas fa-paper-plane"></span> Proses</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="row hasil-laporan mt-3" style="display: none;">
    <div class="col-12">
      <div class="card card-custom">
        <div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
          <div class="ribbon-target" style="top: 12px;">
            <span class="ribbon-inner bg-primary"></span>RINCIAN LAPOR PAJAK
          </div>
          <div class="card-toolbar">
            <form action="javascript:save('form-realisasi')" method="post" id="form-realisasi" name="form-realisasi" autocomplete="off" enctype="multipart/form-data">
              <input type="hidden" id="tanggal" name="tanggal" />
              <button class="btn btn-success"><i class="fas fa-paper-plane"></i> Kirim</button>
            </form>
          </div>
        </div>
        <div class="card-body table-responsive">
          <div class="kt-form">
            <div class="kt-portlet__body form" id="pdf-laporan">
              <object data="" type="application/pdf" width="100%" height="500px"></object>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>