<fieldset>
  <legend><?php echo $breadcrumbs;?></legend>
</fieldset>

<div id="grid_container">
  <div id="search" class="row">
  </div>
  <table id="grid"></table>
  <div id="pager"></div>
</div>

<script>

var grid = $("#grid"), id = last = 0;
$(document).ready(function() {
  var params = {
    container : $('#search'),
    grid : grid,
    cols : <?php echo json_encode($grid['fields']); ?>,
  }
  Search.init(params);
	
  grid.jqGrid({
    url:root+modul+'<?php echo $link_daftar;?>',
    datatype:'json',
    mtype:'POST',
    colNames:['Nomor SPTD', 'Tanggal', 'Periode Awal', 'Periode Akhir', 'Jatuh Tempo', 'Pajak/Retribusi', 'NPWPD','Wajib Pajak', 'Potensi/Omset', 'Jumlah Pajak', 'Nama User', 'Status','Tanggal Bayar','Keterangan'],
    colModel:[
        {name:'no', width:75, formatter: daftarOpFmatter},
        {name:'tgl', width:75, formatter:'date',sorttype: 'date', align:'center'},
        {name:'awal', width:75, formatter:'date', align:'center'},
        {name:'akhir', width:75, formatter:'date', align:'center'},
        {name:'jatuh_tempo', width:75, formatter:'date', align:'center'},
        {name:'pajak', width:100},
		{name:'npwpd', width:75},
        {name:'wp', width:150},
        {name:'omset', width:110, formatter:'currency', align:'right'},
        {name:'nom', width:100, formatter:'currency', align:'right'},
		{name:'nama', width:100},
		{name:'status', width:65},
	{name:'tglbyr', width:80, formatter:'date', align:'center'},
	{name:'ket', width:100},
    ],
    pager:'#pager',
    rowNum:10,
    rowList:[10,20,30],
    rownumbers:true,
    viewrecords:true,
	footerrow:true, 
	userDataOnFooter:true,
    gridview:true,
    shrinkToFit:true,
    autowidth:true,
    height:250,
    ondblClickRow:edit_row,
	sortname :'tgl',
    sortorder:'asc',
	loadComplete:function(){
      //tanggal_tempo();
    },
  });
  
	function tanggal_tempo()
	{
		var rowData = grid.jqGrid('getRowData');
		var arr = grid.jqGrid('getDataIDs');
		for (var i=0; i<rowData.length; i++) {
			var aDate = rowData[i].tgl.split('/'),
			bDate = new Date(aDate[2], aDate[1]-1, aDate[0]),
			cDate = new Date(bDate.getTime() + 30*24*60*60*1000);
			var dd = cDate.getDate(), mm = cDate.getMonth()+1, yy = cDate.getFullYear();
			var dd = dd < 10 ? "0"+dd : dd, mm = mm < 10 ? "0"+mm : mm;
			var dDate = dd + '/' + mm + '/' + yy;
			grid.jqGrid('setRowData', arr[i], {jatuh_tempo:dDate});
		}
	}
	
	function daftarOpFmatter(cellvalue, options, rowdata) 
	{		
		var fmt = '';
		fmt +=  canPrint?"<a href='javascript:;' class='print-form' data-id='"+options.rowId+"' ><i class='icon-print'></i></a>":'';
		fmt +=  "<a href='"+root+modul+"<?php echo $link_form;?>/"+options.rowId+"' style='padding: 0px 2px 0px 2px; text-decoration: underline' >";
		fmt += cellvalue;
		fmt +=  "</a>";

		return fmt;
	}
	
	grid.on('click', '.print-form', print_form);
	 
	function print_form(e){
    var id = $(e.currentTarget).attr('data-id');
    preview({"tipe":"form", "id":id}, grid);
  }

  grid.jqGrid('bindKeys', {
    'onEnter':edit_row
  });

  grid.jqGrid('navGrid', '#pager', {
    add:canAdd,
    addtext: 'Tambah',
    addfunc:function(){
      location.href = root+modul+'<?php echo $link_form;?>';
    },
    edit:true,
    edittext: 'Ubah',
    editfunc:edit_row,
    del:canDel,
    deltext: 'Hapus',
    delfunc:del_row,
    search:false,
    refresh:true,
    refreshtext:'Refresh',
  },{},{},{},{});

if (canPrint){
  grid.jqGrid().navSeparatorAdd('#pager')
    .navButtonAdd('#pager',{
      caption:'Cetak',
      onClickButton: function(){ preview({"tipe":"daftar", "kodrek":"<?php echo $kode_pajak;?>", "tipe_pajak":"<?php echo $tipe?>"}, grid)},
      title:'Cetak Daftar',
      buttonicon:'ui-icon-print',
      position:'last'
    });
}


  function edit_row(id){
    location.href = root+modul+'<?php echo $link_form;?>/'+id;
  }

  function del_row(id){
	  if (!canDel) return false;
    var answer = confirm('Hapus dari daftar?');
    if(answer == true){
      $.ajax({
        type: "post",
        dataType: "json",
        url: root+modul+'/hapus',
        data: {id: id, tipe: '<?php echo $tipe;?>'},
        success: function(res) {
          $.pnotify({
            title: res.isSuccess ? 'Sukses' : 'Gagal',
            text: res.message,
            type: res.isSuccess ? 'info' : 'error'
          });
          if (true == res.isSuccess){
            jQuery('#grid').jqGrid('delRowData', id);
          };
        },
      });
    }
  }
 
});
</script>