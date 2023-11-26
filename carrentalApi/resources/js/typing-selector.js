var activeFlexListContainer;

function typingSearch(input_val, container) {
    let depends = container.data('depends');
    if (!depends) {
        depends = {};
    }
    const datalist = container.find('.flex-list');
    const value = container.data('value');
    const text = container.data('text');
    search(input_val, eval(container.data('search')), depends).done(function(response) {
        datalist.html('');
        $(response).each(function (index, element) {
            const texts = text.split('.');
            const el_text = texts.reduce((prev, curr) => prev && prev[curr], element);

            let extra_fields = datalist.data('extra_fields');
            const data = {};
            if (!extra_fields) {
                extra_fields = [];
            }
            for (const field of extra_fields) {
                if (element[field]) {
                    let elem_val = element[field];
                    if (elem_val) {
                        data['data-'+field] = elem_val;
                    }
                }
            }

            let dataValue = '';
            for (const field of Object.keys(data)) {
                dataValue += ' ' + field + '="' + data[field] + '"';
            }

            const option = $('<span class="flex-option" '+ dataValue +' data-value="'+element[value]+'">'+el_text+'</span>');
            datalist.append(option);
        });
        datalist.addClass('visible');
        activeFlexListContainer = container;
    });
}

$(document).on('input focus', '.typing-selector input', function (e) {
    const $this = $(this);
    const container = $this.parent();
    container.find('.option_id').val('');
    container.find('.option_id').trigger('change');
    clearTimeout(typingTimer);
    typingTimer = setTimeout(function () {
       typingSearch($this.val(), container);
    }, doneTypingInterval);
});

$(document).on('click', '.typing-selector .flex-list span', function (e) {
    const $this = $(this);
    typingSelect($this);
});

function typingSelect(option) {
    const container = option.closest('.typing-selector');
    const option_id = container.find('.option_id');
    option_id.val(option.data('value'));
    container.find('.option_name').val(option.text());
    container.find('.flex-list').removeClass('visible');
    option_id.trigger('change');
    container.find('.option_name').trigger('change');
}

$(document).on('click', function (e) {
    const $this = $(this);
    const target = $(e.target);
    if (activeFlexListContainer && activeFlexListContainer.has(e.target).length == 0) {
        activeFlexListContainer.find('.flex-list').removeClass('visible');
    }
});

$(document).ready(function () {
    const typingSelectors = $('.typing-selector');
    typingSelectors.each(function() {
        const typingSelector = $(this);
        const value_field = $(this).data('value');
        const text_field = $(this).data('text');
        const value = $(this).find('.option_id').val();
        let depends = $(this).data('depends');
        if (!depends) {
            depends = {};
        }
        for (const key in depends) {
            const selectr = document.getElementById(depends[key]).selectr;
            let first_response;
            let found = false;
            selectr.on('selectr.select', async function (e) {
                await search(value, eval(typingSelector.data('search')), depends).done(function(response) {
                    $(response).each(function (index, element) {
                        if (value == element[value_field]) {
                            found = true;
                        }
                    });
                });
                if (!found) {
                    search('', eval(typingSelector.data('search')), depends).done(function(response) {
                        first_response = response[0] !== undefined ? response[0] : {};
                        typingSelector.find('.option_id').val(first_response[value_field]);
                        typingSelector.find('.option_name').val(first_response[text_field]);
                    });
                }
            });
        }
    });
});

window.activeFlexListContainer = activeFlexListContainer;
