import $ from 'jquery';

$(function () {
	const tableAdvanced = function () {
		const $els = $('tr[data-table-advanced-rowlink]');
		$els.click(function (e) {
			if (!$(e.target).is('a,button,label,input')) {
				let target = $(this).data('table-advanced-rowlink');
				if (target) {
					Turbo.visit(target);
				}
			}
		});
	}

	document.addEventListener("turbo:load", tableAdvanced);
	tableAdvanced();
})
