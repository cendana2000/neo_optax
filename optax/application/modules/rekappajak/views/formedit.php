<div class="row form_data_edit" style="display: none;">
	<div class="col">
		<div class="card card-custom">
			<div class="card-header">
			<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span> 
					<h3 class="card-label">EDIT PERIODE</h3>
				</div>
				<div class="card-toolbar">
					<div class="btn-group">
						<button type="reset" class="btn btn-sm btn-secondary" onclick="onBackCard(6)"><i class="fa fa-arrow-left"></i> Kembali</button>
					</div>
				</div>
			</div>
			<form action="javascript:editSubPeriode('form-edit-sub-periode')" method="post" id="form-edit-sub-periode" autocomplete="off">
        <input type="hidden" value="modal-realisasi_id" id="modal-realisasi_id" name="modal-realisasi_id"/>
        <input type="hidden" value="modal-wajibpajak_npwpd" id="modal-wajibpajak_npwpd" name="modal-wajibpajak_npwpd"/>
        <div class="card-body">
          <div class="form-group row">
            <label for="modal-tanggal" class="col-2 col-form-label">Tanggal</label>
            <div class="col-10">
              <input class="form-control form-control-solid" type="text" placeholder="Tanggal" id="modal-tanggal" name="modal-tanggal" readonly/>
            </div>
          </div>
          <div class="form-group">
              <label class="form-label">Rekap Transaksi</label>
              <div class="table-responsive">
                <table class="table table-bordered" id="table-rekap-form">
                  <thead>
                    <tr class="d-flex d-md-table-row">
                      <th class="col-1 col-md-auto">No</th>
                      <th class="col-4 col-md-auto">Time</th>
                      <th class="col-3 col-md-auto">Receipt No</th>
                      <th class="col-3 col-md-auto">Sub Total</th>
                      <th class="col-3 col-md-auto">Jasa</th>
                      <th class="col-3 col-md-auto">Pajak</th>
                      <th class="col-3 col-md-auto">Total</th>
                      <th class="col-2 col-md-auto">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="d-flex d-md-table-row">
                      <td class="col-1 col-md-auto">1</td>
                      <td class="col-4 col-md-auto"><input type="time" class="form-control" name="time[]" required/></td>
                      <td class="col-3 col-md-auto"><input type="text" class="form-control" name="receiptno[]" required/></td>
                      <td class="col-3 col-md-auto"><input type="text" class="form-control" name="subtotal[]" required/></td>
                      <td class="col-3 col-md-auto"><input type="text" class="form-control" name="service[]" required/></td>
                      <td class="col-3 col-md-auto"><input type="text" class="form-control" name="tax[]" required/></td>
                      <td class="col-3 col-md-auto"><input type="text" class="form-control" name="total[]" style="background-color: #eaeaea;" readonly/></td>
                      <td class="col-2 col-md-auto">
                        <button type="button" onclick="deleteRow(this)" class="btn btn-danger btn-icon mr-2"><i class="fa fa-trash"></i></button>
                      </td>
                    </tr>
                  </tbody>
                  <tfoot>
                    <tr class="d-flex d-md-table-row">
                      <th class="col-8 col-md-auto" style="vertical-align: middle;" colspan="3">Total</th>
                      <th class="col-3 col-md-auto"><input type="text" class="form-control" name="sum_subtotal" style="background-color: #eaeaea;" readonly/></th>
                      <th class="col-3 col-md-auto"><input type="text" class="form-control" name="sum_service" style="background-color: #eaeaea;" readonly/></th>
                      <th class="col-3 col-md-auto"><input type="text" class="form-control" name="sum_tax" style="background-color: #eaeaea;" readonly/></th>
                      <th class="col-3 col-md-auto"><input type="text" class="form-control" name="sum_total" style="background-color: #eaeaea;" readonly/></th>
                      <th class="col-2 col-md-auto">
                        <button type="button" onclick="addRow()" class="btn btn-primary btn-icon"><i class="fa fa-plus"></i></button>
                      </th>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end">
          <button type="submit" class="btn btn-primary font-weight-bold">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
