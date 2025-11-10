<script type="text/javascript">
	$(function() {
		HELPER.fields = [
			'kategori_barang_id',
			'kategori_barang_kode',
			'kategori_barang_nama',
			'kategori_barang_tipe',
			'kategori_barang_parent'
		];

		HELPER.setRequired([
			'kategori_barang_nama',
			'kategori_barang_tipe',
		]);

		HELPER.api = {
			table: BASE_URL + 'kategori/',
			read: BASE_URL + 'kategori/read',
			store: BASE_URL + 'kategori/store',
			update: BASE_URL + 'kategori/update',
			destroy: BASE_URL + 'kategori/destroy',
			get_parent: BASE_URL + 'kategori/go_tree',
		}
		// init_table();	
		kategori_barang_tree = $('#tree1').jstree({
			'plugins': ['html_data', 'themes', 'ui', 'types'],
			'core': {
				'data': {
					'url': BASE_URL + 'kategori/select_tree',
					'data': function(node) {
						return {
							'id': node.id
						};
					}
				}
			},
			"types": {
				"default": {
					"icon": "fa fa-arrow-right"
				},
				"file": {
					"icon": "fa fa-arrow-right"
				}
			}
		});
		$("#tree1").on('changed.jstree', function(e, data) {
			var node_selected = data.instance.get_node(data.selected[0]);
			$('#kategori_barang_id').val(node_selected.id);
		});

		HELPER.create_combo_akun({
			el: 'kategori_barang_parent',
			valueField: 'id',
			displayField: 'text',
			parentField: 'parent',
			childField: 'child',
			url: HELPER.api.get_parent,
			withNull: true,
			nesting: true,
			chosen: true,
			callback: function() {}
		});
		$('#child').hide();

		$('#kategori_barang_parent').select2()
		$('#kategori_barang_tipe').select2()
	});

	function genCode(regex) {
		return RegExp(regex);

	}

	function reInitChild() {
		$('#child').empty();
		let html = `
			<label for="kategori_barang_parent" class="col-4 col-form-label">Induk</label>
			<div class="col-8">
				<select name="kategori_barang_parent" id="kategori_barang_parent" class="form-control kategori_barang_parent" style="width: 100%;">
				</select>
			</div>
		`;
		$('#child').html(html);
		HELPER.create_combo_akun({
			el: 'kategori_barang_parent',
			valueField: 'id',
			displayField: 'text',
			parentField: 'parent',
			childField: 'child',
			url: HELPER.api.get_parent,
			withNull: true,
			nesting: true,
			chosen: true,
			callback: function() {}
		});
	}

	function reInitParent() {
		$('#parent').empty();
		let html = `
				<select name="kategori_barang_tipe" class="form-control kategori_barang_tipe" id="kategori_barang_tipe" onchange="handleHide()" style="width: 100%;">
					<option value="">-Pilih Kategori Tipe-</option>
					<option value="parent">Induk</option>
					<option value="detail">Detail</option>
				</select>
								
		`;
		$('#parent').html(html);
		$('#child').hide();
	}

	function onEdit(el) {
		var id = $('#kategori_barang_id').val();
		// console.log(id);
		// return;
		handleHide();

		$.ajax({
			url: BASE_URL + 'kategori/read',
			type: 'post',
			data: {
				kategori_barang_id: id
			},
			success: function(resp_object) {
				if (resp_object.kategori_barang_id) {
					$.each(HELPER.fields, function(i, v) {
						$('#' + v).val(resp_object[v]).trigger('change');
					});

				}
			}
		})
	}


	function onBack() {
		HELPER.back();
	}

	function onRefresh() {
		var tree = $.jstree.reference("#tree1");
		tree.refresh();
		$(":reset").trigger("click");
		$('#kategori_barang_parent').val('');
		reInitChild();
		reInitParent();
	}

	function save() {
		HELPER.save({
			form: 'form-kategori',
			confirm: true,
			callback: function(success, id, record, message) {
				onRefresh();
				if (success === true) {
					HELPER.back();
				}
			}
		})
	}

	function onDestroy(el) {
		var id = $('#kategori_barang_id').val();

		HELPER.confirm({
			message: 'Are you sure you want to delete?',
			callback: function(suc) {
				if (suc) {
					$.ajax({
						url: BASE_URL + 'kategori/destroy',
						type: 'post',
						data: {
							kategori_barang_id: id
						},
						success: function(resp_object) {
							HELPER.showMessage({
								success: true,
								title: 'Success',
								message: 'You have successfully deleted data.'
							})
							onRefresh();
						}
					})
				}
			}
		})
	}

	function handleHide() {
		let parent = $('#kategori_barang_tipe').val();
		let child = $('#child');

		if (parent == 'parent') {
			child.hide();
		} else if (parent == 'detail') {
			child.show();
		} else {
			child.hide();
		}
		console.log(parent);
	}
</script>