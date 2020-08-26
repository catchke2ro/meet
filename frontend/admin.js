import "./admin.sass";

import $ from "jquery"
import "bootstrap/dist/js/bootstrap"
import "admin-lte/dist/js/adminlte"
import "datatables.net"
import "datatables.net-bs4"

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

		$table.DataTable(obj);
	});


});