<script type="text/javascript">
  $(function(){
    HELPER.fields = [
      'exec_id',
      'exec_jenis',
      'exec_query',
    ];
    HELPER.setRequired([
      'exec_jenis',
      'exec_query',
    ]);
    HELPER.api = {
      store: BASE_URL+'dbupdater/store',
    }
    $(".select2").select2();
  });

  function save(){
    var form = $('#form-sync_dbpos')[0]; // You need to use standard javascript object here
		var formData = new FormData(form);
    HELPER.save({
			form: 'form-sync_dbpos',
      data: formData,
			confirm: true,
			contentType: false,
			processData: false,
			callback: function(success,id,record,message)
			{
				if (success===true) {
					// HELPER.back({});
				}
			}	
		})
  }
</script>