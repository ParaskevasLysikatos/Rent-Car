var search =  function (text, url, depends, query_fields = {}, except = "") {
    postData = {
        search: text
    }
    if (except) {
        postData.except = except;
    }
    for (const depend of Object.keys(depends)) {
        const el = document.getElementById(depends[depend]);
        if (el.nodeName == 'SELECT') {
            const selectr = el.selectr;
            postData[depend] = selectr.getValue();
        } else {
            postData[depend] = el.value;
        }
    }
    for (const query_field of Object.keys(query_fields)) {
        postData[query_field] = query_fields[query_field];
    }
    return $.post(url, postData);
}

var selectorSearch = function(searchText, selectr, callFromDepend = false, strict = false) {
    const select = $(selectr.el);
    let depends = select.data('depends');
    if (!depends) {
        depends = {};
    }
    let query_fields = select.data('query_fields');
    if (!query_fields) {
        query_fields = {};
    }
    const url = eval(select.data('search'));
    const value = select.data('value');
    const text = select.data('text');
    let selectedData = null;
    let except = '';
    if (select.data('except')) {
        except = select.data('except')
    }
    return search(searchText, url, depends, query_fields, except).done(function (response) {
        try {
            selectedData = selectr.getSelected();
        }catch (e) {
            selectedData = null;
        }
        const values = {};
        if (selectedData != null) {
            for (const option of selectedData) {
                const selctrOption = $(selectr.options[option.idx]);
                let key = option.value;
                if (selctrOption.data('model')) {
                    key = option.value + '-' + selctrOption.data('model');
                }
                values[key] = selctrOption;
            };
        }

        const response_vals = {};
        let options = [];
        if (selectr.options) {
            options = [...selectr.options];
        }
        // console.log([...selectr.options]);
        for (const option of options) {
            if (!option.selected) {
                const index = option.idx;
                // selectr.el.remove(index);
                selectr.options.splice(index, 1);

                // Remove reference from the items array
                selectr.items.splice(index, 1);
                if (selectr.data) {
                    selectr.data.splice(index, 1);
                }

                selectr.options.forEach(function(opt, i) {
                    opt.idx = i;
                    selectr.items[i].idx = i;
                });
            }
        }

        $(response).each(function (index, element) {
            const texts = text.split('.');
            const el_text = texts.reduce((prev, curr) => prev && prev[curr], element);
            const data = {
                value: element[value],
                text: el_text
            };
            for (const depend of Object.keys(depends)) {
                if (element[depend]) {
                    let elem_val = element[depend].id;
                    if (!elem_val && element[depend][0]) {
                        elem_val = element[depend][0].id;
                    }
                    if (elem_val) {
                        data['data-'+depend] = elem_val;
                    }
                }
            }
            const extraDeps = {};
            const event = new CustomEvent('ajax_search_extra_args', {detail: {depends: extraDeps}});
            selectr.el.dispatchEvent(event);
            for (const depend of Object.keys(extraDeps)) {
                if (element[extraDeps[depend]]) {
                    let elem_val = element[extraDeps[depend]];
                    if (!elem_val) {
                        elem_val = element[extraDeps[depend]][0];
                    }
                    if (elem_val) {
                        data['data-'+depend] = elem_val;
                    }
                }
            }
            let extra_fields = select.data('extra_fields');
            if (!extra_fields) {
                extra_fields = [];
            }
            if (extra_fields == '"all"') {
                extra_fields = Object.keys(element);
            }
            for (const field of extra_fields) {
                if (element[field]) {
                    let elem_val = element[field];
                    if (elem_val) {
                        data['data-'+field] = elem_val;
                    }
                }
            }
            let key = element[value];
            if (data['data-model']) {
                key += '-'+data['data-model'];
            }
            const el = values[key];
            if (el === undefined) {
                selectr.add(data, false);
            }
            response_vals[element[value]] = data;
        });

        if (selectedData != null) {
            for (key in values) {
                const el = values[key];
                let belongsToDep = true;
                if (strict) {
                    const el_val = el.val();
                    if (!response_vals[el_val]) {
                        belongsToDep = false;
                    }
                } else if (callFromDepend) {
                    for (const depend of Object.keys(select.data('depends'))) {
                        const depend_el = document.getElementById(depends[depend]);
                        let depend_val;
                        if (depend_el.nodeName == 'SELECT') {
                            const selectr = depend_el.selectr;
                            depend_val = selectr.getValue();
                        } else {
                            depend_val = depend_el.value;
                        }
                        if (el.data(depend) != depend_val) {
                            belongsToDep = false;
                        }
                    }
                }
                if (!belongsToDep) {
                    const index = el[0].idx;
                    selectr.deselect(index, true);
                    // selectr.remove(parseInt(el.idx));

                    // selectr.el.remove(index);
                    selectr.options.splice(index, 1);

                    // Remove reference from the items array
                    selectr.items.splice(index, 1);
                    if (selectr.data) {
                        selectr.data.splice(index, 1);
                    }

                    selectr.options.forEach(function(opt, i) {
                        opt.idx = i;
                        selectr.items[i].idx = i;
                    });
                }
            }
        }
        selectr.search();
    });
}

window.search = search;
window.selectorSearch = selectorSearch;
