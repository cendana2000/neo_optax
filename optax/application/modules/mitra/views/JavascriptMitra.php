<script type="text/javascript">
  $(function() {
		moment.locale('id');
		HELPER.set_role_access(<?= $role ?>)
		// console.log(<?= $role ?>)
		$('#cari-menu-sidebar').donetyping(function() {
			searchMenu($(this).val())
		})
    
		$('[data-menu=dashboardWp-Table]').trigger('click') 
	})

  function searchMenu(val) {
		$('li.sidebar').removeClass('dapet menu-item-open')
		setTimeout(function() {
			var value = val.toUpperCase();
			if (value) {
				$('#kt_aside_menu').scrollTop(0)
				$('.menu-section').hide()
				$.each($('li.sidebar'), function(i, v) {
					if ($(v).text().toUpperCase().indexOf(value) > -1) {
						$(v).addClass('dapet').show()
						$(v).find('li').show()
						$(v).parents('li').show()
						if ($(v).hasClass('menu-item-submenu')) {
							$(v).addClass('menu-item-open')
						} else {
							$(v).parents('li').addClass('menu-item-open')
						}
					} else {
						if (!$(v).find('li').hasClass('dapet') && !$(v).parents('li').hasClass('dapet')) {
							$(v).hide()
						}
					}
				});
			} else {
				$('.menu-section').show()
				$('li.sidebar').show().removeClass('dapet menu-item-open')
			}
		}, 400)
	}
</script>