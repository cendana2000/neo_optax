<fieldset class="header-pendataan" >
  <legend id="bc" class="judul-pendataan" data-bind="text: title"></legend>
  	<div class="label-dependensi" >	
		<span class="label label-info" data-bind="visible: id_penetapan != 0" >PENETAPAN</span>
		<a href="" data-bind="attr: {'href': '<?php echo base_url(); ?>tbp/form/' + id_tbp }" ><span class="label label-info" data-bind="visible: id_tbp != 0" >PEMBAYARAN</span></a>
		<a href="" data-bind="attr: {'href': '<?php echo base_url(); ?>sts/form/' + id_sts }" ><span class="label label-info" data-bind="visible: id_sts != 0" >PENYETORAN</span></a>
		</div>
</fieldset>

<form id="frm" method="post" action="<?php echo base_url(); ?>pajak_hotel/proses">
  <fieldset>
    <div class="control-group" >
      <div class="control-group pull-left" data-bind="validationElement: npwpd" >
        <label class="control-label" for="npwpd">NPWPD</label>
        <div class="controls input-append">
          <input type="text" class="span8" id="npwpd" readonly="1" data-bind="value: npwpd, executeOnEnter: pilih_npwpd" required />
          <span class="add-on" data-bind="visible: !isEdit() && (canSave() || canSaveEntri()),  click: pilih_npwpd" ><i class="icon-folder-open"></i></span>
        </div>
      </div>
      <div class="control-group pull-left" style="margin-left:20px" data-bind="validationElement: tgl" >
        <label class="control-label" for="tgl">Tanggal</label>
        <input type="text" class="datepicker span2" id="tgl" data-bind="value: tgl" required />
      </div>
    </div>
	    <div class="controls-row">
      <div class="control-group pull-left" data-bind="validationElement: kd_rek" >
        <label class="control-label" for="idrek">Jenis Pajak/Retribusi</label>
        <input type="text" class="span2" id="kd_rek" readonly="1" data-bind="value: kd_rek" required />
        <!--<div class="controls span8 input-append">-->
          <input type="text" class="span8" id="nm_rek" readonly="1" data-bind="value: nm_rek" required />
        <!--  <span class="add-on" data-bind="visible: !isEdit() && canSave(),  click: pilih_rekening" ><i class="icon-folder-open"></i></span>
        </div>-->
      </div>
    </div>
  </fieldset>

  <fieldset>
    <legend>Wajib Pajak    </legend>
    <div class="controls-row">
      <div class="control-group" data-bind="validationElement: nama" >
        <label class="control-label" for="nama">Nama</label>
        <input type="text" class="span10" id="nama" readonly="1" data-bind="value: nama" required />
      </div>
    </div>

    <div class="controls-row">
      <div class="control-group" data-bind="validationElement: alamat" >
        <label class="control-label" for="alamat">Alamat</label>
        <input type="text" class="span10" id="alamat" readonly="1" data-bind="value: alamat" required />
      </div>
    </div>

    <div class="controls-row" >
      <div class="control-group pull-left" data-bind="validationElement: kecamatan" >
        <label class="control-label" for="kecamatan">Kecamatan</label>
        <input type="text" class="span5" id="kecamatan" readonly="1" data-bind="value: kecamatan" required />
      </div>
      <div class="control-group pull-left" style="margin-left:20px" data-bind="validationElement: kelurahan" >
        <label class="control-label" for="kelurahan">Kelurahan</label>
        <input type="text" class="span5" id="kelurahan" readonly="1" data-bind="value: kelurahan" required />
      </div>
    </div>
		
		<table id="gridWP"></table>
		<div id="pagerWP"></div>
		
		<div class="controls-row" style="margin-top:20px;margin-bottom:20px;">
			<div class="control-group pull-left" data-bind="validationElement: bt" >
				<label class="control-label" for="bt">Petugas Penerima</label>
				<input type="text" id="bt" class="span5" data-bind="attr : {'data-init': nm_bt}, value: bt, select2: { minimumInputLength: 0, containerCss: {'margin-left':'0px'}, containerCssClass: 'span5', initSelection: init_select, query: query_pejabat_penerima, formatResult: formatPejabat }" />
			</div>
		</div>
  </fieldset>

  <fieldset>
    <legend>Estimasi Perhitungan Pajak</legend>
    <div class="controls-row">
      <div class="control-group pull-left" data-bind="validationElement: nospt" >
        <label class="control-label" for="nospt">Nomor SPT</label>
        <input type="text" class="span3" id="nospt" data-bind="value: nospt, attr: {readonly: auto()}" required />
      </div>
      <div class="control-group pull-left" style="margin-left:20px;display:none;" data-bind="validationElement: status" >
        <label class="control-label" for="status">Status SPT</label>
        <select id="status" class="span3" data-bind="options: opsiStatus, optionsValue:'kode', optionsText:'uraian', value: status" /></select>
      </div>
    </div>

    <div class="controls-row">
      <div class="control-group pull-left" data-bind="validationElement: awal" >
        <label class="control-label" for="awal">Periode Awal</label>
        <input type="text" class="span2 datepicker" id="awal" data-bind="value: awal" required />
      </div>
      <div class="control-group pull-left" style="margin-left:20px" data-bind="validationElement: akhir" >
        <label class="control-label" for="akhir">Periode Akhir</label>
        <input type="text" class="span2 datepicker" id="akhir" data-bind="value: akhir" required />
      </div>
    </div>

    <div class="controls-row" >
      <div class="control-group pull-left" >
        <label class="control-label" for="omset">Potensi</label>
        <input type="text" class="span3 currency" id="omset" data-bind="numeralvalue: omset" required />
      </div>
      <!--<div class="control-group pull-left" style="margin-left:20px" >-->
        <!--<label class="control-label" for="tarif1">Tarif Pajak (Rp)</label>-->
        <input type="hidden" class="span3" id="tarif1" readonly="1" data-bind="numeralvalue: tarif1, hidden:true"  />
      <!--</div>-->
      <div class="control-group pull-left" style="margin-left:20px" >
        <label class="control-label" for="tarif2">Tarif Pajak (%)</label>
        <input type="text" class="span3" id="tarif2" readonly="1" data-bind="numeralvalue: tarif2" hidden="true" />
      </div>
	  <div class="control-group pull-left" style="margin-left:20px" data-bind="">
        <label class="control-label" for="jatuh_tempo">Jatuh Tempo</label>
        <input type="text" class="datepicker span2" id="jatuh_tempo" data-bind="value: jatuh_tempo"  required/>
      </div>
    </div>

    <div class="controls-row">
      <div class="control-group">
        <label class="control-label" for="jml">Jumlah Pajak</label>
        <input type="text" class="span2 currency" id="jml" readonly="1" data-bind="numeralvalue: jml" />
      </div>
    </div>
    <div class="controls-row">
      <div class="control-group" data-bind="validationElement: lokasi" >
        <!--<label class="control-label" for="lokasi">Lokasi</label>-->
        <input type="hidden" class="span10" id="lokasi" data-bind="value: lokasi" required />
      </div>
    </div>
    <div class="controls-row">
      <div class="control-group" data-bind="validationElement: uraian" >
        <label class="control-label" for="uraian">Keterangan</label>
        <!--<input type="text" class="span10" id="uraian" data-bind="value: uraian" required />-->
        <textarea rows="4" class="span10" id="uraian" data-bind="value: uraian" required></textarea>
      </div>
    </div>
  </fieldset>
</form>

<div class="controls-row pull-right">
  <input type="button" value="Sebelumnya" class="btn btn-primary" data-bind="click: prev" />
  <input type="button" value="Berikutnya" class="btn btn-primary" data-bind="click: next" />
  <div class="btn-group dropup">
    <button type="button" class="btn btn-primary" data-bind="enable: (canSave() || canSaveEntri())  && !processing(), click: function(data, event){save(false, data, event) }" />Simpan</button>
    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" data-bind="enable: (canSave() || canSaveEntri()) && !processing()">
      <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
      <li><a href="#" data-bind="enable: (canSave() || canSaveEntri()) && !processing(), click: function(data, event){save(true, data, event) }" >Simpan & Buat Baru</a></li>
    </ul>
  </div>
	<button type="button" class="btn btn-primary" data-bind="enable: canPrint, click: print" >Cetak</button>
  <input type="button" id="penetapan" value="Penetapan <?php echo strtoupper($tipe)?>" class="btn btn-primary" data-bind="enable: (canSave() || canSaveEntri()) && id_penetapan == 0 <?php if ($tipe == 'SA'){ ?>&& isJatuhTempo()<?php } ?>, click: penetapan" />
  <input type="button" id="back" value="Kembali" class="btn btn-primary" data-bind="click: back" />
</div>

<script>
var id = last = 0;
var purge_wp = new Array();

$(document).ready(function() {
    $('.currency')
    .blur(function(){ $(this).formatCurrency(fmtCurrency); })
    .focus(function(){ $(this).toNumber(fmtCurrency); });
    
    $.datepicker.setDefaults($.datepicker.regional['id']);
 
	$('.datepicker#awal').datepicker({
		onSelect:function(selectedDate) {
			App.awal(selectedDate);
			$('.datepicker#akhir').datepicker("option", "minDate", selectedDate);
		}
	});
  
	$('.datepicker#akhir').datepicker({
		onSelect:function(selectedDate) {
			App.akhir(selectedDate);
			//$('.datepicker#tgl').datepicker("option", "minDate", selectedDate);
		},
		minDate:$('.datepicker#awal').val(),
	});
	
	$('.datepicker#tgl').datepicker({
		onSelect:function(selectedDate) {
			App.tgl(selectedDate);
			var aDate = selectedDate.split('/'),
			bDate = new Date(aDate[2], aDate[1], 0);
			if(aDate[1] === 4 || aDate[1] === 6 || aDate[1] === 9 || aDate[1] === 11){
				console.log('30 ---');
				var cDate = new Date(bDate.getTime() - 30*24*60*60*1000);
			}
			else if(aDate[1] === 2){
				if (aDate[2]%4 === 0)
				{
					if (aDate[2]%100 === 0) {
								   if (aDate[2]%400 === 0){
														console.log('29 ---');
														var cDate = new Date(bDate.getTime() - 29*24*60*60*1000);}//tahun kabisat
								   else{
									   				console.log('28 ---');
													var cDate = new Date(bDate.getTime() - 28*24*60*60*1000);}
						}
						   else {				console.log('29 ---');
						   var cDate = new Date(bDate.getTime() - 29*24*60*60*1000);}//tahun kabisat
				}
				 else {
				 				console.log('28 ---');
				 var cDate = new Date(bDate.getTime() - 28*24*60*60*1000);}
				console.log(aDate[2]%4,'modulo');
			}
			else{
				console.log('31 ---');
				var cDate = new Date(bDate.getTime() - 31*24*60*60*1000);
			}

			console.log(bDate,'bDate');
			console.log(cDate,'cDate');

			/* if(App.tipe() === 'SA'){ 
			var dd = cDate.getDate(), mm = cDate.getMonth()+1, yy = cDate.getFullYear();
			var lastDay = new Date(cDate.getFullYear(), cDate.getMonth() + 1, 0);
			var dd2 = lastDay.getDate(), mm2 = lastDay.getMonth()+1, yy2 = lastDay.getFullYear();
			var dd = dd < 10 ? "0"+dd : dd, mm = mm < 10 ? "0"+mm : mm;
			var dd2 = dd2 < 10 ? "0"+dd2 : dd2, mm2 = mm2 < 10 ? "0"+mm2 : mm2;
			var dDate = '01' + '/' + mm + '/' + yy;
			var dDatelast = dd2 + '/' + mm2 + '/' + yy2;
				App.awal(dDate);
				App.akhir(dDatelast);
			}
			else{
			var dd = bDate.getDate(), mm = bDate.getMonth()+1, yy = bDate.getFullYear();
			var lastDay = new Date(bDate.getFullYear(), bDate.getMonth() + 1, 0);
			var dd2 = lastDay.getDate(), mm2 = lastDay.getMonth()+1, yy2 = lastDay.getFullYear();
			var dd = dd < 10 ? "0"+dd : dd, mm = mm < 10 ? "0"+mm : mm;
			var dd2 = dd2 < 10 ? "0"+dd2 : dd2, mm2 = mm2 < 10 ? "0"+mm2 : mm2;
			var dDate = '01' + '/' + mm + '/' + yy;
			var dDatelast = dd2 + '/' + mm2 + '/' + yy2;
				App.awal(dDate);
				App.akhir(dDatelast);
			} */
			
			var dd = cDate.getDate(), mm = cDate.getMonth()+1, yy = cDate.getFullYear();
			var lastDay = new Date(cDate.getFullYear(), cDate.getMonth() + 1, 0);
			var dd2 = lastDay.getDate(), mm2 = lastDay.getMonth()+1, yy2 = lastDay.getFullYear();
			var dd = dd < 10 ? "0"+dd : dd, mm = mm < 10 ? "0"+mm : mm;
			var dd2 = dd2 < 10 ? "0"+dd2 : dd2, mm2 = mm2 < 10 ? "0"+mm2 : mm2;
			var dDate = '01' + '/' + mm + '/' + yy;
			var dDatelast = dd2 + '/' + mm2 + '/' + yy2;
			App.awal(dDate);
			App.akhir(dDatelast);
			
			var bbDate = new Date(aDate[2], aDate[1]-1, aDate[0]),
			ccDate = new Date(bbDate.getTime() + 30*24*60*60*1000);
			var dd3 = ccDate.getDate(), mm3 = ccDate.getMonth()+1, yy3 = ccDate.getFullYear();
			var dd3 = dd3 < 10 ? "0"+dd3 : dd3, mm3 = mm3 < 10 ? "0"+mm3 : mm3;
			var ddDate = dd3 + '/' + mm3 + '/' + yy3;
			App.jatuh_tempo(ddDate);
		},
		//minDate:$('.datepicker#akhir').val(),
	});
	
	$('.datepicker#jatuh_tempo').datepicker({
		onSelect:function(selectedDate) {
			App.jatuh_tempo(selectedDate);
		},
	});
	
	$("#gridWP").jqGrid({
      url:'',
      datatype: 'local',
      mtype: 'POST',
			colNames:['', 'Golongan Kamar', 'Tarif', 'Jumlah Kamar', 'Jumlah Kamar yang Laku', ''],
			colModel:[
        {name:'id',hidden:true,editable:true},
				{name:'gol',width:400,editable:true,edittype:'text',editoptions:{class:'span7'},sortable:false},
				{name:'tarif',width:300,editable:true,edittype:'text',formatter:'currency', align:'right',editoptions:{class:'span7'},sortable:false},       
				{name:'jmlkamar',width:150,editable:true,edittype:'text',editoptions:{class:'span7'},sortable:false},
				{name:'jmlkamarlaku',width:150,editable:true,edittype:'text',editoptions:{class:'span7'},sortable:false},
				{name:'total', hidden:true, width:100, formatter:'currency'}
      ],
      pager:'#pagerWP',
      rowNum:1000000,
      scroll:true,
      rownumbers:true,
      viewrecords:true,
      gridview:true,
      shrinkToFit:true,
      autowidth:true,
      height:100,
      recordtext:'{2} baris',
      ondblClickRow: edit_row,
	  loadComplete:function(){
      // HitungTotal();
    }
  });
  $("#gridWP").jqGrid('bindKeys', {"onEnter": edit_row});
  $("#gridWP").jqGrid('navGrid', '#pagerWP', {
      add: true,
      addtext: 'Tambah',
      addfunc:add_row,		
      edit: true,
      edittext: 'Ubah',
      editfunc:edit_row,
			del:true,
			deltext: 'Hapus',
			delfunc:del_row,      
      search:false,
      refresh: false,
  },{},{},{},{});
  
  	function HitungTotal(){
		var jmltarif = $("#gridWP").jqGrid('getCol', 'tarif', false, 'sum');
		var jmlkamarlaku = $("#gridWP").jqGrid('getCol', 'jmlkamarlaku', false, 'sum');
		var total = parseFloat(jmlkamarlaku) * parseFloat(jmltarif);
		
		var xtotal = $("#gridWP").jqGrid('getCol', 'total', false, 'sum');
		
		App.omset(xtotal);
	}
	
	
	function add_row(){
    var dataWP = $('#gridWP').getRowData(),
				defdata = {};
		/*if (dataWP.length > 0) {
			var	id = dataWP[0].id,
					gol = dataWP[0].gol,
					tarif = dataWP[0].tarif,
					jmlkamar = dataWP[0].jmlkamar,
					jmlkamarlaku = dataWP[0].jmlkamarlaku,
					defdata = {'id': id, 'gol': gol, 'tarif': tarif, 'jmlkamar': jmlkamar, 'jmlkamarlaku': jmlkamarlaku};
		}*/

		newid = --id;
    editparam = {
      keys : true,
			url: 'clientArray',
      aftersavefunc: after_save,
      beforeSaveRow: beforesavefunc,
      afterrestorefunc: null,
      oneditfunc: oneditfunc,
      successfunc: null,
      errorfunc: null,
      restoreAfterError: false,
    }

    $(this).jqGrid('addRowData', newid, defdata, 'last');
    $("#"+newid).addClass("jqgrid-new-row");
    $(this).jqGrid('setSelection', newid);
    $(this).jqGrid('editRow', newid, editparam);

	}

	function edit_row(id){		
    editparam = {
      keys : true,
			url: 'clientArray',
      aftersavefunc: after_save,
      beforeSaveRow: beforesavefunc,
      afterrestorefunc: null,
      oneditfunc: oneditfunc,
      successfunc: null,
      errorfunc: null,
      restoreAfterError: false,
    }
    $(this).jqGrid('editRow', id, editparam);
	//HitungTotal();
    last = id;
	}
	
	function del_row(id){
    //if (!App.canEdit()) return;

    var grid = $(this),
         question = 'Hapus Golongan Kamar dari daftar ?';

    confirmation(question).then(function(answer){
      if (!answer) return;

      purge_wp.push(id);
      grid.jqGrid('delRowData', id);   
	  HitungTotal();	  
    });
  };
	
  function beforesavefunc(opt, id){    
  }

  function oneditfunc(id){		
		$('#'+id+'_gol').focus();		
  }
  
  function after_save(id){		
		var rowData = $("#gridWP").jqGrid ('getRowData', id);
		var tarif = rowData.tarif;
		var jmlkamarlaku = rowData.jmlkamarlaku;
		var total = tarif * jmlkamarlaku;
		
		$('#gridWP').jqGrid('setRowData', id, {total: total});
    $(this).focus();
    HitungTotal();
  }
});

  ko.validation.init({
    insertMessages: false,
    decorateElement: true,
    errorElementClass: 'error',
  });

  var Status = function(kode, uraian){
    this.kode = kode;
    this.uraian = uraian;
  }

  var ModelPendaftaran = function (){
    var self = this;
    self.opsiStatus = ko.observableArray([
        new Status('BARU', 'Baru'),
        new Status('KB', 'Kurang Bayar'),
        new Status('KBT', 'Kurang Bayar Tambahan'),
        new Status('LB', 'Lebih Bayar'),
        new Status('NIHIL', 'Nihil'),
    ]);
    self.modul = 'pendataan';
    self.akses_level = ko.observable(<?php echo isset($akses) ? $akses : 0 ?>);
    self.processing = ko.observable(false);
    self.link_back = ko.observable('<?php echo isset($link_back) ? $link_back : '' ?>');
    self.form = ko.observable('<?php echo isset($form) ? $form : '' ?>');
    self.id = ko.observable('<?php echo isset($data['ID_SPT']) ? $data['ID_SPT'] : 0 ?>');
	self.id_penetapan = <?php echo isset($data['ID_PENETAPAN']) ? $data['ID_PENETAPAN'] : 0 ?>;
    self.id_tbp = <?php echo isset($data['ID_TBP']) ? $data['ID_TBP'] : 0 ?>;
	self.id_sts = <?php echo isset($data['ID_STS']) ? $data['ID_STS'] : 0 ?>;
	self.nospt = ko.observable('<?php echo isset($data['NOMOR_SPT']) ? $data['NOMOR_SPT'] : '' ?>')
      .extend({
        required: {params: true, message: 'Nomor SPT tidak boleh kosong'},
        maxLength: {params: 20, message: 'Nomor SPT tidak boleh melebihi 20 karakter'},
      });
	self.auto = ko.observable(<?php echo isset($data['ID_SPT']) ? 'true' : 'true' ?>);
	console.log(self.auto(),'auto');
    self.auto.subscribe(function(new_value){
		self.nospt('(Auto)');
	});
    self.tgl = ko.observable('<?php echo isset($data['TANGGAL_SPT']) ? format_date($data['TANGGAL_SPT']) : date('d/m/Y') ?>')
      .extend({
        required: {params: true, message: 'Tanggal SPT tidak boleh kosong'},
      });
    self.tipe = ko.observable('<?php echo isset($tipe) ? $tipe : '' ?>');
    self.idrek = ko.observable('<?php echo isset($data['ID_REKENING']) ? $data['ID_REKENING'] : 0 ?>')
    self.kd_rek = ko.observable('<?php echo isset($data['KODE_REKENING']) ? $data['KODE_REKENING'] : '' ?>')
      .extend({
        required: {params: true, message: 'Kode Rekening tidak boleh kosong'},
      });
    self.nm_rek = ko.observable('<?php echo isset($data['NAMA_REKENING']) ? $data['NAMA_REKENING'] : '' ?>')
      .extend({
        required: {params: true, message: 'Nama Rekening tidak boleh kosong'},
      });
    self.id_wp = ko.observable('<?php echo isset($data['ID_WAJIB_PAJAK']) ? $data['ID_WAJIB_PAJAK'] : 0 ?>');
	self.id_ju = ko.observable('<?php echo isset($data['ID_JENIS_USAHA']) ? $data['ID_JENIS_USAHA'] : 0 ?>');
    self.npwpd = ko.observable('<?php echo isset($data['NPWPD']) ? $data['NPWPD'] : '' ?>')
      .extend({
        required: {params: true, message: 'NPWPD tidak boleh kosong'},
      });
    self.nama = ko.observable('<?php echo isset($data['NAMA_WP']) ? $data['NAMA_WP'] : '' ?>')
      .extend({
        required: {params: true, message: 'Nama WP/WR tidak boleh kosong'},
      });
    self.alamat = ko.observable('<?php echo isset($data['ALAMAT_WP']) ? $data['ALAMAT_WP'] : '' ?>')
      .extend({
        required: {params: true, message: 'Alamat WP/WR tidak boleh kosong'},
      });
    self.id_kecamatan = ko.observable('<?php echo isset($data['ID_KECAMATAN']) ? $data['ID_KECAMATAN'] : 0 ?>');
    self.id_kelurahan = ko.observable('<?php echo isset($data['ID_KELURAHAN']) ? $data['ID_KELURAHAN'] : 0 ?>');
    self.kecamatan = ko.observable('<?php echo isset($data['NAMA_KECAMATAN']) ? $data['NAMA_KECAMATAN'] : '' ?>')
      .extend({
        required: {params: true, message: 'Kecamatan tidak boleh kosong'},
      });
    self.kelurahan = ko.observable('<?php echo isset($data['NAMA_KELURAHAN']) ? $data['NAMA_KELURAHAN'] : '' ?>')
      .extend({
        required: {params: true, message: 'Kelurahan tidak boleh kosong'},
      });
    self.status = ko.observable('<?php echo isset($data['STATUS_SPT']) ? $data['STATUS_SPT'] : '' ?>');
    self.omset = ko.observable('<?php echo isset($data['JUMLAH']) ? $data['JUMLAH'] : 0 ?>');
	self.awal = ko.observable('<?php 
		$format = isset($data['TANGGAL_SPT']) ? $data['TANGGAL_SPT'] : date('Y/m/01');
		$newdate = strtotime ('-1 month',strtotime($format));
		$newdate = date ( 'd/m/Y',$newdate );
		echo isset($data['PERIODE_AWAL']) ? format_date($data['PERIODE_AWAL']) : $newdate
	?>')
		  .extend({
			 required: {params: true, message: 'Periode Awal tidak boleh kosong'},
		  });
	self.akhir = ko.observable('<?php 
		$format = isset($data['TANGGAL_SPT']) ? $data['TANGGAL_SPT'] : date('Y/m/t');
		$newdate = strtotime ('-1 month',strtotime($format));
		$newdate = date ( 't/m/Y',$newdate );
		echo isset($data['PERIODE_AKHIR']) ? format_date($data['PERIODE_AKHIR']) : $newdate
	?>')
		  .extend({
				required: {params: true, message: 'Periode Akhir tidak boleh kosong'},
		  });
	self.jatuh_tempo = ko.observable('<?php 
		$format = isset($data['TANGGAL_SPT']) ? $data['TANGGAL_SPT'] : date('Y/m/d');
		$newdate = strtotime ('+30 day',strtotime($format));
		//echo $newdate = date ( 'd/m/Y',$newdate );
		echo isset($data['TANGGAL_JATUH_TEMPO']) ? format_date($data['TANGGAL_JATUH_TEMPO']) : date ( 'd/m/Y',$newdate );
	?>')
		  .extend({
			required: {params: true, message: 'Tanggal batas bayar tidak boleh kosong'}
		  });
	 /*self.jatuh_tempo = ko.observable('<?php echo isset($data['TANGGAL_JATUH_TEMPO']) ? format_date($data['TANGGAL_JATUH_TEMPO']) : date('d/m/Y') ?>')
      .extend({
        required: {params: true, message: 'Tanggal Jatuh Tempo tidak boleh kosong'},
      });*/
	  
    self.lokasi = ko.observable('<?php echo isset($data['LOKASI']) ? $data['LOKASI'] : '-' ?>')
      .extend({
        required: {params: true, message: 'Lokasi tidak boleh kosong'},
      });
    self.uraian = ko.observable('<?php echo isset($data['URAIAN']) ? $data['URAIAN'] : '' ?>')
      .extend({
        required: {params: true, message: 'Uraian tidak boleh kosong'},
      });
    self.tarif1 = ko.observable('<?php echo isset($data['TARIF_RP']) ? $data['TARIF_RP'] : 0 ?>');
    self.tarif2 = ko.observable('<?php echo isset($data['TARIF_PERSEN']) ? $data['TARIF_PERSEN'] : 0 ?>');
    
    self.jml = ko.observable('<?php echo isset($data['JUMLAH_PAJAK']) ? $data['JUMLAH_PAJAK'] : 0 ?>');
		
		self.id_skpd = ko.observable(<?php echo isset($skpd_pemda['ID_SKPD'])? $skpd_pemda['ID_SKPD'] : '' ?>);
	self.bt = ko.observable('<?php echo isset($data['ID_BT']) ? $data['ID_BT'] : $this->session->userdata('id_user') ?>')
      .extend({
        required: {params: true, message: 'Bendahara Penerimaan belum dipilih'}
      });
    self.nm_bt = ko.observable('<?php echo isset($data['NAMA_PEJABAT']) ? json_encode($data['NAMA_PEJABAT']) : $this->session->userdata('nama_operator')?>');

    self.mode = ko.computed(function(){
      return self.id() > 0 ? 'edit' : 'new';
    });

    self.title = ko.computed(function(){
      return (self.mode() === 'edit' ? 'Edit ' : 'Entri ') + '<?php echo $breadcrumbs;?>';
    });

   self.isEdit = ko.computed(function(){
      return self.mode() === 'edit';
    });

    self.canPrint = ko.computed(function(){
      return self.akses_level() !== 2 && self.mode() === 'edit';
    });

    self.canSave = ko.computed(function(){
      return self.akses_level() >= 4 && self.id_penetapan === 0 && self.id_tbp === 0 && self.id_sts === 0;
    });
	
	self.canSaveEntri = ko.computed(function(){
		return self.akses_level() == 1 && self.mode() == 'new' && self.id_penetapan === 0 && self.id_tbp === 0 && self.id_sts === 0;
    });
		
		self.isJatuhTempo = ko.computed(function(){
			var jatuh_tempo = self.jatuh_tempo();
			return new Date().getTime() >= new Date(jatuh_tempo.split("/").reverse().join("-")).getTime();
		});

    self.errors = ko.validation.group(self);

    self.omset.subscribe(function(newValue){
      hitung = self.omset() * self.tarif1() * (self.tarif2() / 100);
      self.jml(hitung);
    })
	
  }

  var App = new ModelPendaftaran();

  App.prev = function(){
    show_prev(modul, App.id());
  }

  App.next = function(){
    show_next(modul, App.id());
  }

	App.print = function(){
		preview({"tipe":"form", "id": App.id()});
	}

  App.back = function(){
    if(App.tipe() === 'SA')
      location.href = root+modul+'/daftar_sa';
    else if(App.tipe() === 'OA')
      location.href = root+modul+'/daftar_oa';
  }
  
	App.penetapan = function(){
    if(App.tipe() === 'SA')
      location.href = root+'penetapan_sa/form?id_spt='+App.id();
    else if(App.tipe() === 'OA')
      location.href = root+'penetapan_oa/form?id_spt='+App.id();
	}
	
  App.formValidation = function(){
    var errmsg = [];  
    if (!App.isValid()){
      errmsg.push('Ada kolom yang belum diisi dengan benar. Silakan diperbaiki.');
      App.errors.showAllMessages();
    }

    if (errmsg.length > 0) {
      $.pnotify({
        title: 'Perhatian',
        text: errmsg.join('</br>'),
        type: 'warning'
      });
      return false;
    }
    return true;
  }
	
  App.save = function(createNew){
    if (!App.formValidation()){ return }
	
		var $frm = $('#frm'),
        data = JSON.parse(ko.toJSON(App));
				data['wp'] = JSON.stringify($('#gridWP').jqGrid('getRowData'));
				data['purge_wp'] = purge_wp;

    App.processing(true);
    $.ajax({
      url: $frm.attr('action'),
      type: 'post',
      dataType: 'json',
      data: data,
      success: function(res, xhr){
        if (res.isSuccess){
          if (res.id) App.id(res.id);
					if (res.nomor) App.nospt(res.nomor);
					
						App.init_grid();
        }

        $.pnotify({
          title: res.isSuccess ? 'Sukses' : 'Gagal',
          text: res.message,
          type: res.isSuccess ? 'info' : 'error'
        });

        if (createNew) location.href = root+modul+App.form();
      },
      complete: function(){
        App.processing(false);
      }
    });
  }

  /*App.pilih_rekening = function(){
    if (!App.canSave() || App.isEdit()) { return; }
    var option = {multi:0, mode:'pendataan_hotel'};
    Dialog.pilihRekening(option, function(obj, select){
      var rs = $(obj).jqGrid('getRowData', select[0].id);
      App.idrek(rs.idrek);
      App.kd_rek(rs.kdrek);
      App.nm_rek(rs.nmrek);
      if (rs.tarif_rp === '') rs.tarif_rp = 0;
      App.tarif1(rs.tarif_rp);
      if (rs.tarif_persen === '') rs.tarif_persen = 0;
      App.tarif2(rs.tarif_persen);

      var hitung = App.omset() * App.tarif1() * (App.tarif2() / 100);
      App.jml(hitung);
    });
  }*/

  App.pilih_npwpd = function(){
    if (!(App.canSave() || App.canSaveEntri())) { return; }
    var option = {multi:0,  mode:'pendataan_hotel_npwpd'}; //update nana
    Dialog.pilihNPWPD(option, function(obj, select){
      var rs = $(obj).jqGrid('getRowData', select[0].id);
      App.id_wp(rs.id_wp);
      App.npwpd(rs.npwpd);
      App.nama(rs.nama_wp);
      App.alamat(rs.alamat_wp);
      App.id_kecamatan(rs.id_kecamatan);
      App.id_kelurahan(rs.id_kelurahan);
      App.kecamatan(rs.kecamatan);
      App.kelurahan(rs.kelurahan);
	  App.id_ju(rs.id_ju);
	  
	  	// update jenis pajak
		$.ajax({
			url: root+modul+'/jenis_pajak/'+ rs.id_ju,
			type: 'post',
			dataType: 'json',
			data: {mode:'pendataan_hotel'},
			success: function(res, xhr){
			console.log(res,'res');
			  App.idrek(res.id_rek);
			  App.kd_rek(res.kd_rek);
			  App.nm_rek(res.nm_rek);
			  if (res.trf_rp === '') res.trf_rp = 0;
			  App.tarif1(res.trf_rp);
			  if (res.trf_prsn === '') res.trf_prsn = 0;
			  App.tarif2(res.trf_prsn);
			   
				var hitung = App.omset() * App.tarif1() * (App.tarif2() / 100);
				App.jml(hitung);
			}
		});	

		$.ajax({
			url: root+modul+'/cek_periode/'+rs.npwpd,
			type: 'post',
			dataType: 'json',
			data: {awal:awal.value, akhir:akhir.value, tipe:App.tipe(), jns_pajak:'A'}, 
			success: function(res, xhr){
			console.log(res,'res to dialog confirm');
			 if (res.id_spt !== '') {
				var question = 'NPWPD '+rs.npwpd+' a.n '+res.nama_wp;
					question+= ' sudah pernah melakukan pendataan pada periode yang sama';
					question+= ' pada tanggal '+res.tgl_spt+', tetap lanjutkan entri pendataan?';
				
					confirmation(question).then(function(answer){
						if (!answer) App.pilih_npwpd(); 
					});
			  }
			}  
		});
		
    });
  }
	
	App.query_pejabat_penerima = function(option){
    //var id_skpd = App.id_skpd();
    $.ajax({
      url: "<?php echo base_url()?>pilih/pejabat_penerima",
      type: 'POST',
      dataType: 'json',
      data: {
        'q': option.term,
        //'skpd': id_skpd,
      },
      success: function (data) {
        option.callback({
          results: data.results
        });
      }
    });
  };
	
	App.init_grid = function(){
		if (App.id() > 0){
			$('#gridWP').jqGrid('setGridParam', {'url': '<?php echo base_url($modul) ?>/get_wp/' + App.id(), 'datatype': 'json'});
			$('#gridWP').trigger('reloadGrid');
		}
		else{
			$('#gridWP').jqGrid('setGridParam', {'url': '', 'datatype': 'local'});			
		}
	}
	
	App.formatPejabat = function(res){
    return '<div><strong>' + res.text + '</strong></div>';
  }
	
	App.init_select = function(element, callback){
    var data = {'text': $(element).attr('data-init')};
    callback(data);
  }

	
	
  ko.applyBindings(App);
	
	setTimeout(function(){
		App.init_grid();
	}, 500);
<?php
if (!isset($data['ID_SPT'])){
	echo "App.auto.valueHasMutated();";
}
?>  
</script>