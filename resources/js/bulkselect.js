$(function () {
    const bulkselect = function () {
        const $els = $('.cb-checker:not(.cb-checker-loaded)');
        $els.each(function () {
            const id = $(this).data('bulkselect-id');
            const $groupCheckbox = $(this).find('.cb-bulk');
            const $statHolder = $('.cb-counter-' + id);

            $groupCheckbox.addClass('cb-checker-loaded');

            let lastChecked = null;

            const update = function () {
                const $checkboxes = $('.cb-check-' + id).not('[disabled]');

                const $checked = $checkboxes.filter(':checked');

                $statHolder.text($checked.length + '/' + $checkboxes.length);
            }

            const updateMain = function updateMain() {
                const $checkboxes = $('.cb-check-' + id).not('[disabled]');

                const checkednum = $checkboxes.filter(':checked').length
                const cbnum = $checkboxes.length;

                if (cbnum === 0 && checkednum === 0) {
                    $groupCheckbox.prop('checked', false);
                    $groupCheckbox.prop('indeterminate', true);
                } else if (cbnum === checkednum) {
                    $groupCheckbox.prop('checked', true);
                    $groupCheckbox.prop('indeterminate', false);
                } else if (0 === checkednum) {
                    $groupCheckbox.prop('checked', false);
                    $groupCheckbox.prop('indeterminate', false);
                } else {
                    $groupCheckbox.prop('indeterminate', true);
                }

                update();
            };


            $('.cb-check-' + id).change(updateMain).on('lintaba:checked', updateMain).click(function (e) {
                const $checkboxes = $('.cb-check-' + id).not('[disabled]');

                const clickTarget = this;
                if (!lastChecked) {
                    lastChecked = clickTarget;
                    return;
                }

                if (e.shiftKey) {
                    const start = $checkboxes.index(clickTarget);
                    const end = $checkboxes.index(lastChecked);
                    $checkboxes.slice(Math.min(start, end), Math.max(start, end) + 1).prop('checked', lastChecked.checked).trigger('lintaba:checked');
                }

                update();

                lastChecked = clickTarget;
            });

            $groupCheckbox.on('click', function () {
                const $checkboxes = $('.cb-check-' + id).not('[disabled]');

                $checkboxes.prop('checked', $groupCheckbox.prop('checked'));
                update();
            })
            updateMain();
        });
    }

    document.addEventListener("turbo:load", bulkselect);
    bulkselect();
});


