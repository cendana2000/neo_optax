<style type="text/css">
	.txt-total{
		font-weight: bold!important;
	    text-align: right;
	    padding-right: 20px;
	}
</style>
<div class="row form_saldo" style="display: none">	
	<div class="col-md-12">
		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">
						Set Saldo Awal
					</h3>
				</div>
			</div>
			<form class="kt-form" action="javascript:saveSaldo('form-saldo')" name="form-saldo" id="form-saldo">
				<input type="hidden" id="akun_id_saldo" name="akun_id_saldo">
				<div class="kt-portlet__body">
					<div class="form-group row">
						<label for="saldo_periode" class="col-2 col-form-label">Periode Saldo</label>
						<div class="col-3">
							<input class="form-control" type="date" id="saldo_periode" name="saldo_periode" value="" required>
						</div>
					</div>
					<div class="form-group row" style="height: 400px;padding: 10px;overflow-y: scroll;background: #bdd0dc;">
						<table class="table table-bordered table-hover" id="table-saldo" style="background: #fff">
							<thead style="background: #cddce2;">
								<tr>
									<th style="width: 15%">Kode Akun</th>
									<th style="width: 55%">Nama Akun</th>
									<th>Debit</th>
									<th>Kredit</th>
								</tr>
							</thead>
							<tbody></tbody>
							<tfoot style="display: none">
								<tr>
									<th colspan="2">Total</th>
									<th><input type="text" class="form-control number" name="total_saldo_debit" id="total_saldo_debit"></th>
									<th><input type="text" class="form-control number" name="total_saldo_kredit" id="total_saldo_kredit"></th>
								</tr>
							</tfoot>
						</table>
					</div>
					<div class="form-group row" style="padding: 10px;background: #bdd0dc;">
						<label class="col-8 col-form-label" style="font-weight: bold;text-align: center;">Total</label>
						<label for="total_saldo_debit" class="col-2 col-form-label txt-total" style="border-right: 1px solid #a6acbb"><i>0</i></label>
						<label for="total_saldo_kredit" class="col-2 col-form-label txt-total" style="padding-right: 40px;"><i>0</i></label>
					</div>
				</div>
				<div class="kt-portlet__foot">
					<div class="kt-form__actions">
						<div class="row">
							<div class="col-12">
								<button type="submit" class="btn btn-success"><i class="flaticon-paper-plane-1"></i> Simpan</button>
								<button type="reset" class="btn btn-secondary" onclick="onBack()"><i class="flaticon2-cancel-music"></i> Batal</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
