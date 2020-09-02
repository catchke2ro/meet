import "./app.sass";

import $ from "jquery"
import "bootstrap/dist/js/bootstrap"
import "admin-lte/dist/js/adminlte"
import "admin-lte/dist/js/adminlte"
import "admin-lte/plugins/select2/js/select2.full.min"
import Sticky from "sticky-js"
import imagesLoaded from "imagesloaded"
import "./map"


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

	$(document).on('click', '.instanceNumberWrapper', function (ev) {
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


	const updateScore = function ($form) {
		$.ajax({
			url: '/vallalasok/pontok',
			method: 'POST',
			data: $form.serialize(),
			success: function (data) {
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
						$progressBar.find('.progress-bar').width(percentage + '%');
					} else {
						$progressBar.addClass('d-none');
						$progressBar.find('.progress-bar').width(0);
					}
				}
			},
			error: function (data) {
				console.log('An error occured.', data);
			},
		});
	}




	const $treeModules = $('.card.modules');
	if ($treeModules.length) {
		const sticky = new Sticky('.card.modules', {
			wrap: true,
			stickyClass: 'stuck',
		});

		const $treeModuleList = $treeModules.find('ul.moduleList');

		$treeModuleList.on('click', 'a.selectModule', function () {
			const $a = $(this),
				$li = $a.closest('li'),
				$allLis = $li.closest('ul').find('li'),
				$actives = $li.prevAll().add($li);

			$allLis.removeClass('active');
			$actives.addClass('active');

			$('input#selectedModule').val($a.data('moduleid'));

			updateScore($('form.commitmentsForm'));
		});

		const initialModule = $('input#selectedModule').val();
		if (initialModule !== '') {
			$treeModuleList.find('a.selectModule[data-moduleid="' + initialModule + '"]').click();
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
					commitmentId: commitmentId,
				},
				success: function (data) {
					modal.find('.modal-body').html(data);
				},
			});
		});
		$historyModal.on('hidden.bs.modal', function (event) {
			const modal = $(this);
			modal.find('.modal-header h3').html('');
			modal.find('.modal-body').html('');
		});
	}


	const $orgSelector = $('.form-group.orgSelector select');
	if ($orgSelector.length) {
		$orgSelector.select2({
			ajax: {
				delay: 500,
				url: '/_org-list',
				dataType: 'json',
				minimumInputLength: 2,
				// Additional AJAX parameters go here; see the end of this chapter for the full code of this example
			},
		});

		$orgSelector.on('select2:open', function (e) {
			$('.select2-search__field').attr('placeholder', 'Keress a szervezet nevére');
		});
		$orgSelector.on('change', function () {

		});
	}

	const $regForm = $('form#form-signup');
	if ($regForm.length) {
		$regForm.find('.orgSelector select').on('change', function () {
			$regForm.find('fieldset.orgData').toggleClass('d-none', $(this).val() !== '');
		});
	}


	const $postsIndex = $('.postsIndex');
	if ($postsIndex.length) {

		function resizeGridItem ($item) {
			const rowHeight = parseInt($postsIndex.css('grid-auto-rows'));
			let rowGap = $postsIndex.css('grid-row-gap');
			if (rowGap === 'normal') {
				rowGap = 0;
			} else {
				rowGap = parseInt(rowGap);
			}
			const rowSpan = Math.ceil(($item.find('.postInner')[0].getBoundingClientRect().height + rowGap) / (rowHeight + rowGap));

			$item.css('grid-row-end', 'span ' + rowSpan);
		}

		function resizeAllGridItems () {
			$postsIndex.find('.postItem').each(function () {
				resizeGridItem($(this));
			})
		}

		function resizeInstance (instance) {
			item = instance.elements[0];
			resizeGridItem(item);
		}

		resizeAllGridItems();
		$(window).on('resize', resizeAllGridItems());

		imagesLoaded.makeJQueryPlugin($);
		$postsIndex.imagesLoaded(function () {
			resizeAllGridItems();
		});

		$(document).on('click', '.postsIndex .moreLink a, .postsIndex .lessLink a', function () {
			const $item = $(this).closest('.postItem');
			$postsIndex.find('.postItem').removeClass('opened');
			if ($(this).closest('.moreLink').length > 0) {
				$item.addClass('opened');
			}
			resizeAllGridItems();
			$([document.documentElement, document.body]).animate({
				scrollTop: $item.offset().top,
			}, 1000);
		});
	}

	$('input[name*="[recaptcha_response]"]').each(function (index, e) {
		const $input = $(this),
			$form = $input.closest('form');

		$form.on('submit', function(event) {
			event.preventDefault();
			grecaptcha.execute($form.data('sitekey'), { action: 'registration' }).then(function (token) {
				$input.val(token);
				$form[0].submit();
			}, function (reason) {
				console.log(reason);
			});
		});
	});

});
