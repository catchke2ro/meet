import "./admin.sass";

import "@fortawesome/fontawesome-free/webfonts/fa-brands-400.ttf"
import "@fortawesome/fontawesome-free/webfonts/fa-brands-400.woff2"
import "@fortawesome/fontawesome-free/webfonts/fa-regular-400.ttf"
import "@fortawesome/fontawesome-free/webfonts/fa-regular-400.woff2"
import "@fortawesome/fontawesome-free/webfonts/fa-solid-900.ttf"
import "@fortawesome/fontawesome-free/webfonts/fa-solid-900.woff2"

import $ from "jquery"
import "bootstrap/dist/js/bootstrap"
import "admin-lte/dist/js/adminlte"
import "datatables.net"
import "datatables.net-bs4"
import ClassicEditor from './ckeditor/ckeditor'

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

	const csrfToken = $('meta[name=csrf-token]').attr('content');

	$('textarea.ckeditor').each(function() {
		const textarea = $(this);
		const uploadType = textarea.data('upload-type');
		console.log(uploadType);
		ClassicEditor
			.create(textarea[0], {
				removePlugins: ['ImageCaption'],
				toolbar: {
					items: [
						'heading',
						'fontSize',
						'|',
						'bold',
						'italic',
						'underline',
						'strikethrough',
						'subscript',
						'superscript',
						'|',
						'link',
						'imageUpload',
						'mediaEmbed',
						'insertTable',
						'|',
						'bulletedList',
						'numberedList',
						'horizontalLine',
						'|',
						'blockQuote',
						'indent',
						'outdent',
						'|',
						'undo',
						'redo',
						'|',
						'removeFormat'
					]
				},
				language: 'hu',
				image: {
					toolbar: [
						'imageTextAlternative',
						'imageStyle:full',
						'imageStyle:side',
						'linkImage'
					]
				},
				table: {
					contentToolbar: [
						'tableColumn',
						'tableRow',
						'mergeTableCells',
						'tableCellProperties',
						'tableProperties'
					]
				},
				licenseKey: '',
				simpleUpload: {
					uploadUrl: '/meet/upload',
					headers: {
						'X-CSRF-Token': csrfToken,
						'X-Upload-Type': uploadType
					}
				}
			})
			.then(editor => {})
			.catch(error => {});
	});


});