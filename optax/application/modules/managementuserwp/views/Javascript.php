<script type="text/javascript">
  $(function (){
		HELPER.fields = [
			'role_access_id',
			'role_access_nama',
		];

		HELPER.setRequired([
			'role_access_nama',
		]);

    HELPER.api = {
			getmenu: BASE_URL + 'managementuserwp/get_menu',
		}

		HELPER.ajaxCombo({
			el: '#wajibpajak',
			url: BASE_URL + 'managementuserwp/select_wajibpajak',
			displayField: 'text'
		});

		$('#switch_semua_wp').on('change', function(){
			let ischecked = $(this).prop('checked')
			if(ischecked){
				$('#wajibpajak').parent().hide();
			}else{
				$('#wajibpajak').parent().show();
			}
		})
		$('#wajibpajak, #switch_semua_wp').on('change', function(){
			showConfig();
		});

		$('#switch_semua_wp').prop('checked', true).trigger('change');

		// showConfig();
  });

	function showConfig(){

		$('#tree1').data('jstree', false).empty();
		$("#btn-save-config").addClass("d-none");

		let wajibpajak_id = $("#wajibpajak").val();
		let switch_semua_wp = $("#switch_semua_wp").prop('checked');

		HELPER.ajax({
			url: HELPER.api.getmenu,
			data:{
				wajibpajak_id,
				switch_semua_wp
			},
			complete: function(res) {
				$('#tree1').jstree({
					'plugins' :['checkbox', 'types', 'wholerow'],
					'core'	  :{
							"themes" : {
												"responsive": false
										},    
						'data': res['menu']
					},
					'types'	  :{
						'default' : {
							'icon': 'jstree-icon jstree-themeicon fa fa-folder kt-font-warning jstree-themeicon-custom'
						},
						'file' 	  : {
							'icon': 'jstree-icon jstree-themeicon fa fa-file kt-font-warning jstree-themeicon-custom'
						}
					}
				});

				// $('#tree1').on('changed.jstree', function(e, data) {
				// 	$("#btn-save-config").removeClass("d-none");

				// 	unique = [];
				// 	var itemList = data.selected;
				// 	unique = itemList;
				// 	// console.log(data);
				// 	// if(data.node.parent!='#'){
				// 	// 	unique.push(data.node.parent);
				// 	// }
				// });

				$('#tree1').on("changed.jstree", function(e, data) {
					if (typeof data.node != 'undefined') {
						$("#btn-save-config").removeClass("d-none");
						if (data.selected.length == 0) {
							$("#btnSaveHA").css('display', 'block');
						} else {
							$("#btnSaveHA").css('display', 'block');
						}
						unique = [];
						var itemList = data.selected;
						$.each(itemList, function(i, el) {
							if (data.instance.is_leaf(el)) {
								$.each(data.instance.get_node(el).parents, function(i2, el2) {
									if ($.inArray(el2, itemList) == -1 && el2 != '#') itemList.push(el2);
								})
							}
						});
						unique = itemList;
					}
				});
			}
		});

		return;
	}

	function save_role_menu(){
		// /*
		let wajibpajak_id = $("#wajibpajak").val();
		let switch_semua_wp = $("#switch_semua_wp").prop('checked');

		console.log(wajibpajak_id, switch_semua_wp);

		if(!switch_semua_wp){
			if(!wajibpajak_id){
				return HELPER.showMessage({
					success: 'warning',
					title: '',
					message: 'Mohon pilih wajib pajak.'
				})
			}
		}

		$.ajax({
			url: BASE_URL + 'managementuserwp/store_menu_role',
			data: {
				wajibpajak_id,
				switch_semua_wp,
				roles: unique,
			},
			type:'post',
			complete: function(res){
				if(res.status == 200){
					var resJson = res.responseJSON;
					HELPER.showMessage({
						success: true,
						title: 'Success',
						message: 'You have successfully save data.'
					});
					showConfig(role_access_id);
					return;
				}
				HELPER.showMessage({
					success: 'error',
					title: 'Stop',
					message: "Konfigurasi hak akses gagal."
				});
			}
		})
		// */
	}

</script>