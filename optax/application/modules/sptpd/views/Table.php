<div class="row table_data">
    <div class="col-12 mb-3 " data-roleable="false" data-role="sptpd-Table" data-action="hide">
        <div class="card card-custom">
            <div class="card-header">
                <div class="card-title">
                    <span class="card-icon">
                        <i class="fas fa-table text-primary"></i>
                    </span>
                    <h3 class="card-label">SPTPD</h3>
                </div>
                <div class="card-toolbar">
                    <button type="button" id="btnKirimSPTPD" onclick="createSptpdForm()" class="btn btn-info mr-2 form_sptpd_view">Kirim SPTPD</button>
                </div>
            </div>
            <div class="card-body table-responsive">
                <ul class="nav nav-tabs nav-tabs-line" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="verifikasi-tab" data-toggle="tab" data-target="#verifikasi-tab-content" type="button" onclick="loadTable('table-verifikasi')" role="tab" aria-controls="verifikasi-tab" aria-selected="true">Proses Verifikasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="verifikasi-disetujui-tab" data-toggle="tab" data-target="#verifikasi-disetujui-tab-content" onclick="javascript:loadTable('table-verifikasi-disetujui')" type="button" role="tab" aria-controls="verifikasi-disetujui-tab" aria-selected="false">Verifikasi Disetujui</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="verifikasi-ditolak-tab" data-toggle="tab" data-target="#verifikasi-ditolak-tab-content" onclick="javascript:loadTable('table-verifikasi-ditolak')" type="button" role="tab" aria-controls="verifikasi-ditolak-tab" aria-selected="false">Verifikasi Ditolak</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="verifikasi-pembayaran-tab" data-toggle="tab" data-target="#verifikasi-pembayaran-tab-content" onclick="javascript:loadTable('table-verifikasi-pembayaran')" type="button" role="tab" aria-controls="verifikasi-pembayaran-tab" aria-selected="false">Pembayaran</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="verifikasi-lihat-semua-tab" data-toggle="tab" data-target="#verifikasi-lihat-semua-tab-content" onclick="javascript:loadTable('table-verifikasi-lihat-semua')" type="button" role="tab" aria-controls="verifikasi-lihat-semua-tab" aria-selected="false">Lihat Semua</a>
                    </li>
                </ul>
                <div class="tab-content mt-5" id="myTabContent">
                    <div class="tab-pane fade show active" id="verifikasi-tab-content" role="tabpanel" aria-labelledby="verifikasi-tab">
                        <table class="table table-striped table-checkable table-condensed" id="table-verifikasi">
                            <thead>
                                <tr>
                                    <th style="width:5%;">No.</th>
                                    <th>Bulan & Tahun Pajak</th>
                                    <th>Nominal Omzet</th>
                                    <th>Nominal Pajak</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th style="width:5%;">No.</th>
                                    <th>Bulan & Tahun Pajak</th>
                                    <th>Nominal Omzet</th>
                                    <th>Nominal Pajak</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="verifikasi-disetujui-tab-content" role="tabpanel" aria-labelledby="verifikasi-disetujui-tab">
                        <table class="table table-striped table-checkable table-condensed" id="table-verifikasi-disetujui">
                            <thead>
                                <tr>
                                    <th style="width:5%;">No.</th>
                                    <th>Bulan & Tahun Pajak</th>
                                    <th>Nominal Omzet</th>
                                    <th>Nominal Pajak</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th style="width:5%;">No.</th>
                                    <th>Bulan & Tahun Pajak</th>
                                    <th>Nominal Omzet</th>
                                    <th>Nominal Pajak</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="verifikasi-ditolak-tab-content" role="tabpanel" aria-labelledby="verifikasi-ditolak-tab">
                        <table class="table table-striped table-checkable table-condensed" id="table-verifikasi-ditolak">
                            <thead>
                                <tr>
                                    <th style="width:5%;">No.</th>
                                    <th>Bulan & Tahun Pajak</th>
                                    <th>Nominal Omzet</th>
                                    <th>Nominal Pajak</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th style="width:5%;">No.</th>
                                    <th>Bulan & Tahun Pajak</th>
                                    <th>Nominal Omzet</th>
                                    <th>Nominal Pajak</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="verifikasi-pembayaran-tab-content" role="tabpanel" aria-labelledby="verifikasi-pembayaran-tab">
                        <table class="table table-striped table-checkable table-condensed" id="table-verifikasi-pembayaran">
                            <thead>
                                <tr>
                                    <th style="width:5%;">No.</th>
                                    <th>Bulan & Tahun Pajak</th>
                                    <th>Nominal Omzet</th>
                                    <th>Nominal Pajak</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th style="width:5%;">No.</th>
                                    <th>Bulan & Tahun Pajak</th>
                                    <th>Nominal Omzet</th>
                                    <th>Nominal Pajak</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="verifikasi-lihat-semua-tab-content" role="tabpanel" aria-labelledby="verifikasi-lihat-semua-tab">
                        <table class="table table-striped table-checkable table-condensed" id="table-verifikasi-lihat-semua">
                            <thead>
                                <tr>
                                    <th style="width:5%;">No.</th>
                                    <th>Bulan & Tahun Pajak</th>
                                    <th>Nominal Omzet</th>
                                    <th>Nominal Pajak</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th style="width:5%;">No.</th>
                                    <th>Bulan & Tahun Pajak</th>
                                    <th>Nominal Omzet</th>
                                    <th>Nominal Pajak</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="row form_create" style="display: none">
    <div class="col-12 mb-3 " data-roleable="false" data-role="sptpd-Create" data-action="hide">
        <div class="card card-custom">
            <div class="card-header">
                <div class="card-title">
                    <span class="card-icon">
                        <i class="fas fa-table text-primary"></i>
                    </span>
                    <h3 class="card-label">Kirim SPTPD</h3>
                </div>
                <div class="card-toolbar">
                    <button type="button" id="" onclick="tableData('form_create')" class="btn btn-info mr-2 form_sptpd_view">Kembali</button>
                </div>
            </div>
            <div class="card-body table-responsive">
                <form method="POST" action="javascript:save('form-create-sptpd')" id="form-create-sptpd" name="form-create-sptpd" autocomplete="off">
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="sptpd_npwpd" class="col-2 col-form-label"><strong>NPWPD</strong></label>
                            <div class="col-10">
                                <input name="sptpd_npwpd" class="form-control" readonly type="text" value="<?php echo $this->session->userdata()['wajibpajak_npwpd'] ?>" id="sptpd_npwpd" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="sptpd_bulan_tahun_pajak" class="col-2 col-form-label"><strong>Tahun & Bulan</strong></label>
                            <div class="col-10">
                                <input name="sptpd_bulan_tahun_pajak" class="form-control" type="month" value="" id="sptpd_bulan_tahun_pajak" onchange="javascript:get_omzet_ajax()"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="sptpd_nominal_omzet" class="col-2 col-form-label"><strong>Nominal Omzet</strong></label>
                            <div class="col-10">
                                <input name="sptpd_nominal_omzet" class="form-control" type="number" value="" id="sptpd_nominal_omzet" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="sptpd_nominal_pajak" class="col-2 col-form-label"><strong>Nominal Pajak</strong></label>
                            <div class="col-10">
                                <div class="input-group input-group-sm">
                                    <input name="sptpd_nominal_pajak" class="form-control" type="number" value="" id="sptpd_nominal_pajak" readonly="true"/>
                                    <div class="input-group-append"><span class="input-group-text"><?= round($this->session->userdata('jenis_tarif')) ?? 0; ?>%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12 text-right">
                                <button type="submit" class="btn btn-info mr-2">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row detail_sptpd" style="display: none">
    <div class="col-12 mb-3 " data-roleable="false" data-role="sptpd-Detail" data-action="hide">
        <div class="card card-custom">
            <div class="card-header">
                <div class="card-title">
                    <span class="card-icon">
                        <i class="fas fa-table text-primary"></i>
                    </span>
                    <h3 class="card-label">Detail SPTPD</h3>
                </div>
                <div class="card-toolbar">
                    <button type="button" id="" onclick="tableData('detail_sptpd')" class="btn btn-info mr-2 form_sptpd_view">Kembali</button>
                </div>
            </div>
            <div class="card-body table-responsive">
                <!--begin::Form-->
                <input type="hidden" name="detail_sptpd_id" value="" id="detail_sptpd_id">
                <input type="hidden" name="detail_sptpd_id" value="" id="detail_jenis_sptpd">
                <div class="form-group row">
                    <label class="col-2 col-form-label">NPWPD</label>
                    <div class="col-10">
                        <input readonly class="form-control" type="text" value="" id="detail_sptpd_npwpd" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 col-form-label">Tahun & Bulan</label>
                    <div class="col-10">
                        <input readonly class="form-control" type="text" value="" id="detail_sptpd_tahun_bulan" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 col-form-label">Nominal Omzet</label>
                    <div class="col-10">
                        <input readonly class="form-control" type="text" value="" id="detail_sptpd_nominal_omzet" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 col-form-label">Nominal Pajak</label>
                    <div class="col-10">
                        <input readonly class="form-control" type="text" value="" id="detail_sptpd_nominal_pajak" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 col-form-label">Tanggal Permohonan</label>
                    <div class="col-10">
                        <input readonly class="form-control" type="text" value="" id="detail_sptpd_tanggal_permohonan" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 col-form-label">Status SPTPD</label>
                    <div class="col-10">
                        <button disabled class="btn font-weight-bold btn-lg" id="detail_sptpd_status_button"><span id="detail_sptpd_status"></span></button>
                    </div>
                </div>

                <hr>
                
                <div class="form-group row">
                    <label class="col-2 col-form-label">Nomor VA</label>
                    <div class="col-10">
                        <input readonly class="form-control" type="text" value="" id="detail_sptpd_nomor_va" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 col-form-label">Kode Billing</label>
                    <div class="col-10">
                        <input readonly class="form-control" type="text" value="" id="detail_sptpd_kode_billing" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 col-form-label">Tanggal Pembayaran</label>
                    <div class="col-10">
                        <input readonly class="form-control" type="text" value="" id="detail_sptpd_tanggal_bayar" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 col-form-label">Status Pembayaran</label>
                    <div class="col-10">
                        <button disabled class="btn font-weight-bold btn-lg" id="detail_sptpd_status_pembayaran_button"><span id="detail_sptpd_status_pembayaran"></span></button>
                    </div>
                </div>

                <hr>

                <div class="form-group row">
                    <label class="col-2 col-form-label">Unduh SPTPD</label>
                    <div class="col-10">
                        <button class="btn font-weight-bold btn-lg btn-primary" id="detail_sptpd_unduh_button" onclick="javascript:print_sptpd()">Unduh</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mt-3 kt-laporan" style="display: none;">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">DATA WAJIB PAJAK</h3>
				</div>
			</div>
			<div class="card-body table-responsive">
				<div class="kt-portlet kt-portlet--mobile ">
					<div class="kt-portlet__head">
						<div class="kt-portlet__head-label">
							<h3 class="kt-portlet__head-title">

							</h3>
						</div>
					</div>
					<div class="kt-form">
						<div class="kt-portlet__body form" id="pdf-laporan">
							<object data="" type="application/pdf" width="100%" height="500px"></object>
						</div>
					</div>
				</div>
				<div class="kt-portlet kt-portlet--mobile"></div>
			</div>
		</div>
	</div>
</div>
<?php load_view('javascript') ?>