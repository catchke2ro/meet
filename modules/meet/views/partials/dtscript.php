<link type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

<script type="text/javascript">
	$(function () {

		$('table.dataTable').each(function(index, e) {
			const $table = $(e);

			let obj = {
				language: {
					url: '//cdn.datatables.net/plug-ins/1.10.21/i18n/Hungarian.json'
				},
				ordering: true,
				paging: true,
				responsive: true,
				serverSide: true,
				lengthChange: false,
				ajax: {}
			};

			if ($table.data('extradata') && $table.data('extradata') instanceof Object) {
				obj.ajax.data = function (d) {
					return $.extend({}, d, $table.data('extradata'));
				}
			}

			/*if ($table.data('order')) {
				const order = $table.data('order').split(',');
				if (order.length === 2) {
					obj.order = [[parseInt(order[0]), order[1]]]
				}
			}*/
		  console.log(obj);

			$table.DataTable(obj);
		});
	});
</script>