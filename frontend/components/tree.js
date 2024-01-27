import Cookies from 'js-cookie'
import $ from 'jquery';

$(function () {
	$('table.tree').each(function (index, e) {
		const $tree = $(e);
		$tree.find('*[data-widget="expandable-table"]').on('expanded.lte.expandableTable collapsed.lte.expandableTable', () => {
			const state = new Array();
			$tree.find('*[data-widget="expandable-table"]').each((index, e) => {
				$(e).attr('aria-expanded');
				if ($(e).attr('aria-expanded') === 'true') {
					state.push($(e).data('id'));
				}
			});
			Cookies.set($tree.data('cookie-name'), JSON.stringify(state), { expires: 365, path: '/' });
		});

		$tree.find('a.editLink, a.addLink').on('click', (e) => {
			e.preventDefault();
			e.stopPropagation();

			loadModal($(e.target).attr('href'), $(e.target).attr('title'));
			$modal.data('jumpto', $(e.target).closest('.treeRow[id]').attr('id'));

			return false;
		});

		$tree.find('a.moveUpLink, a.moveDownLink').on('click', (e) => {
			e.preventDefault();
			e.stopPropagation();

			$.ajax({
				url: $(e.target).attr('href'),
			}).always((data, textStatus, jqXHR) => {
				window.location.reload();
			});

			return false;
		});

		$tree.find('a.deleteLink').on('click', (e) => {
			e.preventDefault();
			e.stopPropagation();

			if ($(e.target).hasClass('disabled') || confirm('Biztosan törlőd?') === false) {
				return false;
			}

			$.ajax({
				url: $(e.target).attr('href'),
			}).always((data, textStatus, jqXHR) => {
				window.location.reload();
			});

			return false;
		});

		const $modal = $tree.siblings('.treeModal');
		$modal.modal({
			show: false,
		});
		const basePath = window.location.pathname;
		$modal.on('hide.bs.modal', () => {
			if ($modal.data('jumpto')) {
				window.location = window.location.pathname + '#' + $modal.data('jumpto');
				window.location.reload();
			} else {
				window.location = window.location.pathname;
				window.location.reload();
			}
		});

		const fillModal = (data) => {
			$modal.find('.modal-body').html(data);
			$modal.modal('show');
		};

		const loadModal = (href, title) => {
			$modal.find('.spinner-border').removeClass('d-none');
			if (typeof title !== 'undefined') {
				$modal.find('.modal-title').html(title);
			}
			$.ajax({
				url: href,
			}).done((data, textStatus, jqXHR) => {
				fillModal(data);
			}).fail((jqXHR, textStatus, errorThrown) => {
				fillModal(jqXHR.responseText);
			}).always((data, textStatus, jqXHR) => {
				$modal.find('.spinner-border').addClass('d-none');
			});
		}

		const saveForm = ($form, close) => {
			$modal.find('.spinner-border').removeClass('d-none');
			$.ajax({
				url: $form.attr('action'),
				method: $form.attr('method'),
				data: $form.serialize(),
			}).done((data, textStatus, jqXHR) => {
				if (close) {
					fillModal('');
					$modal.modal('hide');
				} else {
					fillModal(data);
				}
			}).fail((jqXHR, textStatus, errorThrown) => {
				fillModal(jqXHR.responseText);
			}).always((jqXHR) => {
				if (jqXHR.status > 300 && jqXHR.status < 400) {
					loadModal(jqXHR.getResponseHeader('Location'));
				} else {
					$modal.find('.spinner-border').addClass('d-none');
				}
			});
			return false;
		}

		$modal.on('click', '.modal-footer .submit', (e) => {
			e.preventDefault();
			const $form = $modal.find('form');
			const close = $(e.target).hasClass('withClose');
			saveForm($form, close);
		});
		$modal.on('submit', 'form', (e) => {
			e.preventDefault();
			const $form = $(e.target);
			saveForm($form, false);
		});
	});


});



