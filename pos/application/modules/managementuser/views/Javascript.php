<script type="text/javascript">
	var isMobile = false;

	$(function() {
		HELPER.fields = [
			'role_access_id',
			'role_access_nama',
		];

		HELPER.setRequired([
			'role_access_nama',
		]);

		HELPER.api = {
			table: BASE_URL + 'managementuser/loadtable',
			store: BASE_URL + 'managementuser/store',
			update: BASE_URL + 'managementuser/update',
			read: BASE_URL + 'managementuser/read',
			destroy: BASE_URL + 'managementuser/destroy',
			getmenu: BASE_URL + 'managementuser/get_menu',
			getmenuv2: BASE_URL + 'managementuser/get_menu_v2',
			getmenuMobile: BASE_URL + 'managementuser/get_menu_mobile',
		}

		init_table();
	});

	function init_table() {
		HELPER.initTable({
			el: "table-hak-akses",
			url: HELPER.api.table,
			searchAble: true,
			destroyAble: true,
			responsive: false,
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						return full['role_access_nama'];
					},
				},
				{
					targets: 2,
					width: '10px',
					orderable: false,
					visible: true,
					render: function(data, type, full, meta) {
						let btn_aksi = "";
						btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-primary btn-icon mx-1" title="Edit" onclick="onEdit(this)">
							<span class="svg-icon svg-icon-md">
								<i class="fa fa-pen"></i>
							</span>
						</a>`;
						btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-danger btn-icon mx-1" onclick="onDelete('${full['role_access_id']}')"">
							<span class="svg-icon svg-icon-md">
								<i class="fa fa-trash"></i>
							</span>
						</a>`;
						btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-success btn-icon mx-1" onclick="showConfig('${full['role_access_id']}')"">
							<span class="svg-icon svg-icon-md">
								<i class="fas fa-desktop"></i>
							</span>
						</a>`;
						// btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-info btn-icon mx-1" onclick="showConfigMobile('${full['role_access_id']}')"">
						// 	<span class="svg-icon svg-icon-md">
						// 		<i class="fas fa-mobile"></i>
						// 	</span>
						// </a>`;
						return btn_aksi;
					},
				},
			],
		});
	}

	function showFormInsert() {
		$("#role_access_id").val("");
		$("#role_access_nama").val("");
		$("#modal-form-hakakses").modal("show");
	}

	function save(form) {
		HELPER.save({
			form,
			confirm: true,
			callback: function(success, id, record, message) {
				if (success === true) {
					onRefresh();
					$("#modal-form-hakakses").modal("hide");
				}
			}
		});
	}

	function onRefresh() {
		HELPER.refresh({
			table: 'table-hak-akses'
		})
	}

	function onEdit(el) {
		HELPER.loadData({
			table: 'table-hak-akses',
			url: HELPER.api.read,
			server: true,
			inline: $(el),
			callback: function(res) {
				$('#role_access_id').val(res.role_access_id);
				$('#role_access_nama').val(res.role_access_nama);
				$("#modal-form-hakakses").modal('show');
			}
		})
	}

	function onDelete(id) {
		HELPER.confirm({
			message: 'Are you sure you want to delete?',
			callback: function(suc) {
				if (suc) {
					HELPER.ajax({
						url: HELPER.api.destroy,
						// url: BASE_URL + 'managementuser/softDelete',
						data: {
							id
						},
						complete: function(res) {
							// console.log(res);
							if (res.success) {
								HELPER.showMessage({
									success: true,
									title: 'Success',
									message: 'You have successfully deleted data.'
								})

								HELPER.refresh({
									table: 'table-hak-akses'
								});
							} else {
								HELPER.showMessage({
									success: 'info',
									title: 'Stop',
									message: res.message
								})
							}
							HELPER.unblock(100)
						}
					})
				}
			}
		})
	}

	function showConfig(role_id = "") {
		isMobile = false;
		$('#webHandler').show();
		$('#mobileHandler').hide();
		if (role_id) {
			$("#tree_role_id").val(role_id);
			$('#tree1').data('jstree', false).empty();
			$("#btn-save-config").addClass("d-none");

			HELPER.ajax({
				url: HELPER.api.getmenuv2,
				data: {
					role_id
				},
				complete: function(res) {
					$('#tree1').jstree('destroy');
					$('#tree1').jstree({
						'plugins': ['checkbox', 'types', 'wholerow'],
						'core': {
							"themes": {
								"responsive": false
							},
							'data': res['menu']
						},
						'types': {
							'default': {
								'icon': 'jstree-icon jstree-themeicon fa fa-folder kt-font-warning jstree-themeicon-custom'
							},
							'file': {
								'icon': 'jstree-icon jstree-themeicon fa fa-file kt-font-warning jstree-themeicon-custom'
							}
						}
					});

					// $('#tree1').on('changed.jstree', function(e, data) {
					// 	if (typeof data.node != 'undefined') {
					// 		$("#btn-save-config").removeClass("d-none");
					// 		// $('#btnSaveHA').css('display', 'block');
					// 		unique = [];
					// 		var itemList = data.selected;
					// 		unique = itemList;
					// 		if (data.node.parent != '#') {
					// 			unique.push(data.node.parent);
					// 		}
					// 	}
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

		HELPER.showMessage({
			success: 'info',
			title: 'Stop',
			message: "Data tidak ditemukan"
		});
	}

	function showConfigMobile(role_id = "") {
		isMobile = true;
		$('#webHandler').hide();
		$('#mobileHandler').show();


		if (role_id) {
			$("#tree_role_id").val(role_id);
			$('#tree2').data('jstree', false).empty();
			$("#btn-save-config").addClass("d-none");

			HELPER.ajax({
				url: HELPER.api.getmenuMobile,
				data: {
					role_id
				},
				complete: function(res) {
					$('#tree2').empty();
					$('#tree2').jstree({
						'plugins': ['checkbox', 'types', 'wholerow'],
						'core': {
							"themes": {
								"responsive": false
							},
							'data': res['menu']
						},
						'types': {
							'default': {
								'icon': 'jstree-icon jstree-themeicon fa fa-folder kt-font-warning jstree-themeicon-custom'
							},
							'file': {
								'icon': 'jstree-icon jstree-themeicon fa fa-file kt-font-warning jstree-themeicon-custom'
							}
						}
					});

					$('#tree2').on('changed.jstree', function(e, data) {
						if (typeof data.node != 'undefined') {
							$("#btn-save-config").removeClass("d-none");
							// $('#btnSaveHA').css('display', 'block');
							unique = [];
							var itemList = data.selected;
							unique = itemList;
							if (data.node.parent != '#') {
								unique.push(data.node.parent);
							}
						}

						// unique = [];
						// var itemList = data.selected;
						// unique = itemList;
						// console.log(data);
						// if(data.node.parent!='#'){
						// 	unique.push(data.node.parent);
						// }
					});
				}
			});

			return;
		}

		HELPER.showMessage({
			success: 'info',
			title: 'Stop',
			message: "Data tidak ditemukan"
		});
	}

	function save_role_menu() {
		let url = BASE_URL + 'managementuser/store_menu_role';
		if (isMobile) {
			url = BASE_URL + 'managementuser/store_menu_role_mobile';
		}

		// /*
		let role_access_id = $("#tree_role_id").val();
		$.ajax({
			url: url,
			data: {
				role_access_id,
				roles: unique,
			},
			type: 'post',
			complete: function(res) {
				if (res.status == 200) {
					var resJson = res.responseJSON;
					// console.log(res);

					HELPER.showMessage({
						success: true,
						title: 'Success',
						message: 'You have successfully save data.'
					})

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