<script type="text/javascript">
  var tsublog = null;
  $(function() {
    HELPER.api = {
      datatable: BASE_URL + 'logwajibpajak/datatable',
      detaildatatable: BASE_URL + 'logwajibpajak/detaildatatable',
    }

    tsublog = $('#table-sublog').DataTable();

    init_table();
  })

  function init_table() {
    HELPER.initTable({
      el: "table-log",
      url: HELPER.api.datatable,
      searchAble: true,
      destroyAble: true,
      responsive: false,
      order: [
        [1, "desc"]
      ],
      columnDefs: [{
        defaultContent: "-",
        targets: "_all"
      }, {
        targets: 0,
        orderable: false
      }, {
        targets: 2,
        render: function(data, type, full, meta) {
          return full.wajibpajak_nama;
        },
      }, {
        targets: 1,
        render: function(data, type, full, meta) {
          return moment(full.log_tanggal, 'YYYY-MM-DD').format('DD/MM/YYYY');
        },
      }, {
        targets: 3,
        width: '10px',
        orderable: false,
        visible: true,
        render: function(data, type, full, meta) {
          return `<button onclick="onDetail('${full['log_id']}')" class="btn btn-primary btn-sm">Detail</button>`;
        },
      }]
    });
  }

  function init_subtable(log_id = null) {
    tsublog.clear().draw();
    HELPER.ajax({
      url: HELPER.api.detaildatatable,
      data: {
        log_id
      },
      success: function(res) {
        if (res.success) {
          if (res.data) {
            if (res.data.log) {
              $('#sublog_namawp').text(res.data.log.log_wajibpajak_nama);
              $('#sublog_tanggal').text(res.data.log.log_tanggal);
            }
            if (res.data.activity) {
              res.data.activity.forEach((item, index) => {
                tsublog.row.add([index + 1, item.log_message, item.log_at]).draw(false);
              })
            }
          }
        }
      }
    })
  }

  function onDetail(log_id) {
    init_subtable(log_id);
    HELPER.toggleForm({
      toshow: 'table_subdata',
      tohide: 'table_data'
    });
  }

  function onBack() {
    HELPER.toggleForm({
      toshow: 'table_data',
      tohide: 'table_subdata'
    });
  }

  function onRefresh() {
    HELPER.refresh({
      table: 'table-log'
    });
  }
</script>