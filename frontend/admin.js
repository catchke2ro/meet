import "./admin.sass";

import $ from "jquery"
import "bootstrap/dist/js/bootstrap"
import "admin-lte/dist/js/adminlte"
import "datatables.net"
import "datatables.net-bs4"

$(function () {

	$('table.dataTable').DataTable({
		language: {
			url: '//cdn.datatables.net/plug-ins/1.10.21/i18n/Hungarian.json'
		},
		paging: true,
		responsive: true,
		serverSide: true,
		lengthChange: false
	});

});