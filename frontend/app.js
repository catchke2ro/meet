import "./app.sass";

import $ from "jquery"
import "bootstrap/dist/js/bootstrap"
import "admin-lte/dist/js/adminlte"
import Sticky from "sticky-js"


$(function () {

	$('#heroSlider').carousel();

	$('[data-toggle="tooltip"]').tooltip({});

	$(document).on('click', 'a.logoutLink', function () {
		$('#logoutForm').submit();
	});

	$(document).on('click', '.treeAccordion > .card > .card-header', function (event) {
		if ($(event.target).is('input') || $(event.target).is('.titles')) {
			return;
		}
		$(event.target).closest('.card').find('.titles').trigger('click');
	});

	$(document).on('click', '.instanceNumberWrapper', function(ev) {
		ev.stopPropagation();
	})
	/*$(document).on('show.bs.collapse hide.bs.collapse', function(ev) {
		if ($(ev.target).is('input')) {
			return false;
		}
	});*/



	const $conditionedCategories = $('div.qcCategory').filter((index, e) => parseInt($(e).data('condition-option')) > 0);
	$conditionedCategories.each(function (index, e) {
		const $category = $(e);
		const optionId = parseInt($category.data('condition-option'));
		const $option = $('.qcOption[data-id=' + optionId + ']');
		if ($option.length) {
			$(document).on('change', '.qcOption[data-qid="' + $option.data('qid') + '"]', function () {
				$category.toggleClass('d-none', !$option.prop('checked'));
			});
		}
	});
	$('.qcOption').trigger('change');

	$('div.customInput').each(function () {
		const $customInput = $(this),
			optionId = $customInput.data('optionid'),
			$option = $('input[data-id=' + optionId + ']');
		if ($option.length) {
			$(document).on('change', '.qcOption[data-qid="' + $option.data('qid') + '"]', function () {
				$customInput.toggleClass('d-none', !$option.prop('checked'));
			});
		}
	});

	const $instanceNumberFields = $('.instanceNumber');
	$instanceNumberFields.each(function (index, e) {
		const $numberField = $(e);
		$numberField.on('change', function () {
			const $category = $numberField.closest('.qcCategory'),
				instanceCount = parseInt($numberField.val());
			let $instances = $category.find('.categoryInstance');
			while ($instances.length !== instanceCount) {
				if ($instances.length > instanceCount) {
					$instances.last().remove();
				} else if ($instances.length < instanceCount) {
					const $cloned = $instances.last().clone();

					$cloned.html($cloned.html().replace(/(name="options\[\d+\]\[)(\d+)(\]")/g, function (match, p1, p2, p3) {
						return p1 + (parseInt(p2) + 1) + p3;
					}));
					$cloned.html($cloned.html().replace(/(name="instanceNames\[\d+\]\[)(\d+)(\]")/g, function (match, p1, p2, p3) {
						return p1 + (parseInt(p2) + 1) + p3;
					}));
					$cloned.html($cloned.html().replace(/(id\="opt\d+_\d+_)(\d+)(")/g, function (match, p1, p2, p3) {
						return p1 + (parseInt(p2) + 1) + p3;
					}));
					$cloned.html($cloned.html().replace(/(for\="opt\d+_\d+_)(\d+)(")/g, function (match, p1, p2, p3) {
						return p1 + (parseInt(p2) + 1) + p3;
					}));

					$instances.last().parent().append($cloned);
				}
				$instances = $category.find('.categoryInstance');
			}

		});
	});


	const updateScore = function($form) {
		$.ajax({
			url: '/vallalasok/pontok',
			method: 'POST',
			data: $form.serialize(),
			success: function(data) {
				if (typeof data.score !== 'undefined') {
					$('.commitmentScore span.score').text(data.score);
				}
				if (typeof data.currentModule !== 'undefined') {
					$('.commitmentScore span.currentModule').text(data.currentModule);
				}
				if (typeof data.nextModule !== 'undefined') {
					$('.commitmentScore span.nextModule').text(data.nextModule);
				}
				if (typeof data.targetModulePercentage !== 'undefined') {
					$('.commitmentScore span.targetModulePercentage').text(data.targetModulePercentage);
				}
				if (typeof data.nextModulePercentage !== 'undefined') {
					$('.commitmentScore span.nextModulePercentage').text(data.nextModulePercentage);
				}

				if (typeof data.targetModulePercentage !== 'undefined') {
					const percentage = parseInt(data.targetModulePercentage);
					const $progressBar = $('div.moduleProgress');
					if (percentage > 0) {
						$progressBar.removeClass('d-none');
						$progressBar.find('.progress-bar').width(percentage+'%');
					} else {
						$progressBar.addClass('d-none');
						$progressBar.find('.progress-bar').width(0);
					}
				}
			},
			error: function(data) {
				console.log('An error occured.', data);
			}
		});
	}

	let changeTimeout;
	$(document).on('change', 'form.commitmentsForm *', function () {
		const $form = $(this).closest('form');
		clearTimeout(changeTimeout);
		changeTimeout = setTimeout(function () {
			updateScore($form)
		}, 500)
	});
	updateScore($('form.commitmentsForm'));


	const $historyModal = $('#historyModal');
	$historyModal.on('show.bs.modal', function (event) {
		const $a = $(event.relatedTarget);
		const commitmentId = $a.data('commitmentid');
		const commitmentName = $a.data('commitment');
		const modal = $(this);

		modal.find('.modal-header p').html(commitmentName);
		modal.find('.modal-body').html(modal.find('.modal-loader').html());

		$.ajax({
			url: '/vallalasok/history',
			method: 'GET',
			data: {
				commitmentId: commitmentId
			},
			success: function (data) {
				modal.find('.modal-body').html(data);
			}
		});
	});
	$historyModal.on('hidden.bs.modal', function (event) {
		const modal = $(this);
		modal.find('.modal-header h3').html('');
		modal.find('.modal-body').html('');
	});


	const $treeModules = $('.card.modules');
	if ($treeModules.length) {

		const sticky = new Sticky('.card.modules', {
			wrap: true,
			stickyClass: 'stuck'
		});

		const $treeModuleList = $treeModules.find('ul.moduleList');

		$treeModuleList.on('click', 'a.selectModule', function() {
			const	$a = $(this),
				$li = $a.closest('li'),
				$allLis = $li.closest('ul').find('li'),
				$actives = $li.prevAll().add($li);

			$allLis.removeClass('active');
			$actives.addClass('active');

			$('input#selectedModule').val($a.data('moduleid'));

			updateScore($('form.commitmentsForm'));
		});
	}

});