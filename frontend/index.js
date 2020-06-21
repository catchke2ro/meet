import "./global.sass";

import $ from "jquery"
import "bootstrap/dist/js/bootstrap"
import "admin-lte/dist/js/adminlte"


$(function () {
	$('[data-toggle="tooltip"]').tooltip({});

	$(document).on('click', 'a.logoutLink', function() {
		$('#logoutForm').submit();
	});


	$(document).on('click', '.treeAccordion > .card > .card-header', function(event) {
		if ($(event.target).is('input') || $(event.target).is('.card-title')) {
			return;
		}
		$(event.target).closest('.card').find('.card-title').trigger('click');
	});

	const $conditionedCategories = $('div.questionCategory').filter((index, e) => parseInt($(e).data('condition-option')) > 0);
	$conditionedCategories.each(function(index, e) {
		const $category = $(e);
		const optionId = parseInt($category.data('condition-option'));
		const $option = $('.questionOption[data-id='+optionId+']');
		if ($option.length) {
			$(document).on('change', '.questionOption[data-qid="'+$option.data('qid')+'"]', function() {
				$category.toggleClass('d-none', !$option.prop('checked'));
			});
		}
	});
	$('.questionOption').trigger('change');

	$('div.customInput').each(function() {
		const $customInput = $(this),
			optionId = $customInput.data('optionid'),
			$option = $('input[data-id='+optionId+']');
		if ($option.length) {
			$(document).on('change', '.questionOption[data-qid="'+$option.data('qid')+'"]', function() {
				$customInput.toggleClass('d-none', !$option.prop('checked'));
			});
		}
	});

	const $instanceNumberFields = $('.instanceNumber');
	$instanceNumberFields.each(function(index, e) {
		const $numberField = $(e);
		$numberField.on('change', function() {
			const $questionCategory = $numberField.closest('.questionCategory'),
				instanceCount = parseInt($numberField.val());
			let $instances = $questionCategory.find('.categoryInstance');
			while ($instances.length !== instanceCount) {
				if ($instances.length > instanceCount) {
					$instances.last().remove();
				} else if ($instances.length < instanceCount) {
					const $cloned = $instances.last().clone();

					$cloned.html($cloned.html().replace(/(name="options\[\d+\]\[)(\d+)(\]")/g, function(match, p1, p2, p3) {
						return p1 + (parseInt(p2) + 1) + p3;
					}));
					$cloned.html($cloned.html().replace(/(name="instanceNames\[\d+\]\[)(\d+)(\]")/g, function(match, p1, p2, p3) {
						return p1 + (parseInt(p2) + 1) + p3;
					}));
					$cloned.html($cloned.html().replace(/(id\="opt\d+_\d+_)(\d+)(")/g, function(match, p1, p2, p3) {
						return p1 + (parseInt(p2) + 1) + p3;
					}));
					$cloned.html($cloned.html().replace(/(for\="opt\d+_\d+_)(\d+)(")/g, function(match, p1, p2, p3) {
						return p1 + (parseInt(p2) + 1) + p3;
					}));

					$instances.last().parent().append($cloned);
				}
				$instances = $questionCategory.find('.categoryInstance');
			}

		});
	});

});