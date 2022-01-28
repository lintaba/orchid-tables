$(function () {
    const tableAdvanced = function () {
        const $els = $('tr[data-table-advanced-rowlink]');
        $els.click(function (e) {
            if (!$(e.target).is('a,button,label,input')) {
                var target = $(this).data('table-advanced-rowlink');
                Turbo.visit(target);
            }
        });
    }

    document.addEventListener("turbo:load", tableAdvanced);
    tableAdvanced();
})
