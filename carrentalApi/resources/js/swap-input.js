$(document).ready(function () {
    $('.swap-tab').on('show.bs.tab', function(e) {
        const $this = $(this);
        const swapInput = $($($this.closest('.swap-container')).find('.swap-input'));
        const swaps = swapInput.find('.tab-pane:not('+$this.attr('href')+')');

        const currentTab = $($this.attr('href'));
        const currentInputs = $(currentTab).find(':input[name]');
        for (let input of currentInputs) {
            input = $(input);
            if (input.is('select')) {
                input.val(input.find('[selected]').val());
            } else {
                input.val(input.attr('value'));
            }
            input.attr('required', true);
        }

        for (const swap of swaps) {
            const inputs = $(swap).find(':input');
            for (let input of inputs) {
                input = $(input);
                const text = input.val();
                input.val('');
                if (input.is('input')) {
                    input.attr('value', text);
                }
                input.attr('required', false);
            }
        }
    });

    const swapContainers = $('.swap-container');
    for (let container of swapContainers) {
        container = $(container);
        const activeTab = $(container.find('.tab-pane.active'));
        $(container.find('.swap-tab[href="#'+activeTab.attr('id')+'"]')).tab('show');
    }
});
