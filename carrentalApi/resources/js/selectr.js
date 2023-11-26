function appendItem(item, parent, custom) {
    if (item.parentNode) {
        if (!item.parentNode.parentNode) {
            parent.appendChild(item.parentNode);
        }
    } else {
        parent.appendChild(item);
    }

    util.removeClass(item, "excluded");
    if (!custom) {
        // remove any <span> highlighting, without xss
        item.textContent = item.textContent;
    }
}

var util = {
    escapeRegExp: function(str) {
      // source from lodash 3.0.0
          var _reRegExpChar = /[\\^$.*+?()[\]{}|]/g;
          var _reHasRegExpChar = new RegExp(_reRegExpChar.source);
          return (str && _reHasRegExpChar.test(str)) ? str.replace(_reRegExpChar, '\\$&') : str;
      },
      extend: function(src, props) {
                  for (var prop in props) {
                          if (props.hasOwnProperty(prop)) {
                                  var val = props[prop];
                                  if (val && Object.prototype.toString.call(val) === "[object Object]") {
                                          src[prop] = src[prop] || {};
                                          util.extend(src[prop], val);
                                  } else {
                                          src[prop] = val;
                                  }
                          }
                  }
                  return src;
      },
      each: function(a, b, c) {
          if ("[object Object]" === Object.prototype.toString.call(a)) {
              for (var d in a) {
                  if (Object.prototype.hasOwnProperty.call(a, d)) {
                      b.call(c, d, a[d], a);
                  }
              }
          } else {
              for (var e = 0, f = a.length; e < f; e++) {
                  b.call(c, e, a[e], a);
              }
          }
      },
      createElement: function(e, a) {
          var d = document,
              el = d.createElement(e);
          if (a && "[object Object]" === Object.prototype.toString.call(a)) {
              var i;
              for (i in a)
                  if (i in el) el[i] = a[i];
                  else if ("html" === i) el.innerHTML = a[i];
                  else el.setAttribute(i, a[i]);
          }
          return el;
      },
      hasClass: function(a, b) {
          if (a)
              return a.classList ? a.classList.contains(b) : !!a.className && !!a.className.match(new RegExp("(\\s|^)" + b + "(\\s|$)"));
      },
      addClass: function(a, b) {
          if (!util.hasClass(a, b)) {
              if (a.classList) {
                  a.classList.add(b);
              } else {
                  a.className = a.className.trim() + " " + b;
              }
          }
      },
      removeClass: function(a, b) {
          if (util.hasClass(a, b)) {
              if (a.classList) {
                  a.classList.remove(b);
              } else {
                  a.className = a.className.replace(new RegExp("(^|\\s)" + b.split(" ").join("|") + "(\\s|$)", "gi"), " ");
              }
          }
      },
      closest: function(el, fn) {
          return el && el !== document.body && (fn(el) ? el : util.closest(el.parentNode, fn));
      },
      isInt: function(val) {
          return typeof val === 'number' && isFinite(val) && Math.floor(val) === val;
      },
      debounce: function(a, b, c) {
          var d;
          return function() {
              var e = this,
                  f = arguments,
                  g = function() {
                      d = null;
                      if (!c) a.apply(e, f);
                  },
                  h = c && !d;
              clearTimeout(d);
              d = setTimeout(g, b);
              if (h) {
                  a.apply(e, f);
              }
          };
      },
      rect: function(el, abs) {
          var w = window;
          var r = el.getBoundingClientRect();
          var x = abs ? w.pageXOffset : 0;
          var y = abs ? w.pageYOffset : 0;

          return {
              bottom: r.bottom + y,
              height: r.height,
              left: r.left + x,
              right: r.right + x,
              top: r.top + y,
              width: r.width
          };
      },
      includes: function(a, b) {
          return a.indexOf(b) > -1;
      },
      startsWith: function(a, b) {
          return a.substr( 0, b.length ) === b;
      },
      truncate: function(el) {
          while (el.firstChild) {
              el.removeChild(el.firstChild);
          }
      }
};

var render = function() {
    if (this.items.length) {
        var f = document.createDocumentFragment();

        if (this.config.pagination) {
            var pages = this.pages.slice(0, this.pageIndex);

            util.each(pages, function(i, items) {
                util.each(items, function(j, item) {
                    appendItem(item, f, this.customOption);
                }, this);
            }, this);
        } else {
            util.each(this.items, function(i, item) {
                appendItem(item, f, this.customOption);
            }, this);
        }

        // highlight first selected option if any; first option otherwise
        if (f.childElementCount) {
            util.removeClass(this.items[this.navIndex], "active");
            this.navIndex = (
                f.querySelector(".selectr-option.selected") ||
                f.querySelector(".selectr-option")
            ).idx;
            util.addClass(this.items[this.navIndex], "active");
        }

        this.tree.appendChild(f);
    }
};

var deselectSelectr = function (option) {
    const template = [
        '<span class="selectr-text">' + option.text + '</span>' + ' <span data-idx="'+ option.idx +'" class="fas fa-times deselect-selectr text-danger"></span>'
    ];
    return template.join('');
}

var editableSelectr = function(option) {
    return ' <span data-id="'+ option.value +'" class="fas fa-pen edit-selectr"></span>'
}

var editableMutliSelectr = function (option) {
    return option.text + editableSelectr(option);
}

var editableSingleSelectr = function (option) {

    const template = [
        deselectSelectr(option),
        editableSelectr(option)
    ];
    return template.join('');
};

var linkSelectr = function (url) {
    return function(option) {
        return option.text + ' <a target="_blank" href="'+ url + option.value +'" class="fas fa-eye"></a>'
    };
}

window.linkSelectr = linkSelectr;
window.deselectSelectr = deselectSelectr;
window.editableMutliSelectr = editableMutliSelectr;
window.editableSingleSelectr = editableSingleSelectr;

function render_ajax_selectors(field = document) {
    const ajax_selectors = $(field).find('.ajax-selector');
    for (let selector of ajax_selectors) {
        const selectr = selector.selectr;
        let input
        if (!selectr.config.taggable) {
            input = selectr.input.cloneNode(true);
            selectr.inputContainer.replaceChild(input, selectr.input);
            selectr.input = input;
        } else {
            input = selectr.input;
        }
        input = $(input);
        selector = $(selector);
        input.on('input', function (e) {
            e.preventDefault();
            clearTimeout(typingTimer);
            typingTimer = setTimeout(function () {
                selectorSearch(input.val(), selectr);
            }, doneTypingInterval);
        });
        // selectr.on('selectr.open', function (option) {
        //     selectorSearch(input.val(), selectr);
        // });
        selectorSearch(input.val(), selectr);


        selectr.on('selectr.select', async function (option) {
            selectr.close();
            const optionData = selectr.data[option.idx];
            optionData.idx = option.idx;
            selectr.data[option.idx] = optionData;
            option = $(option);
            for (const dep of Object.keys(depends)) {
                const depSelectr = document.getElementById(depends[dep]).selectr;
                const depInput = $(depSelectr.input);
                const val = option.data(dep);
                if (!depSelectr.getValue() && val) {
                    await selectorSearch(val.toString(), depSelectr);
                    depSelectr.setValue(val);
                    selectorSearch(depInput.val(), depSelectr);
                }
            }
        })

        let depends = selector.data('depends');
        if (!depends) {
            depends = {};
        }
        for (const dep of Object.keys(depends)) {
            const el = document.getElementById(depends[dep]);
            if (el.nodeName == 'SELECT') {
                const depSelectr = el.selectr;
                depSelectr.on('selectr.select', function (e) {
                    selectorSearch(input.val(), selectr, true);
                });
                depSelectr.on('selectr.deselect', function (e) {
                    selectorSearch('', selectr);
                });
            }
        }
    }
}

$(document).ready(function () {
    Selectr.prototype.search = function( string, anchor ) {
        if ( this.navigating ) {
            return;
        }

        // we're only going to alter the DOM for "live" searches
        var live = false;
        if ( ! string ) {
            string = this.input.value;
            live = true;

            // Remove message and clear dropdown
            this.removeMessage();
            util.truncate(this.tree);
        }
        var results = [];
        var f = document.createDocumentFragment();

        string = string.trim().toLowerCase();

        if ( string.length > 0 ) {
            util.each( this.options, function ( i, option ) {
                var item = this.items[option.idx];

                if ( !option.disabled ) {
                    results.push( { text: option.textContent, value: option.value } );
                    if ( live ) {
                        appendItem( item, f, this.customOption );
                        util.removeClass( item, "excluded" );
                    }
                } else if ( live ) {
                    util.addClass( item, "excluded" );
                }
            }, this);

            if ( live ) {
                // Append results
                if ( !f.childElementCount ) {
                    if ( !this.config.taggable ) {
                        this.noResults = true;
                        this.setMessage( this.config.messages.noResults );
                    }
                } else {
                    // Highlight top result (@binary-koan #26)
                    var prevEl = this.items[this.navIndex];
                    var firstEl = f.querySelector(".selectr-option:not(.excluded)");
                    this.noResults = false;

                    util.removeClass( prevEl, "active" );
                    this.navIndex = firstEl.idx;
                    util.addClass( firstEl, "active" );
                }

                this.tree.appendChild( f );
            }
        } else {
            render.call(this);
        }

        return results;
    }

    Selectr.prototype.open = function() {

        var that = this;

        if (!this.opened) {
            this.emit("selectr.open");
        }

        this.opened = true;

        if (this.mobileDevice || this.config.nativeDropdown) {
            util.addClass(this.container, "native-open");

            if (this.config.data && this.el.options.length === 0) {
                // Dump the options into the select
                // otherwise the native dropdown will be empty
                util.each(this.options, function(i, option) {
                    this.el.add(option);
                }, this);
            }

            return;
        }

        util.addClass(this.container, "open");

        render.call(this);

        this.invert();

        this.tree.scrollTop = 0;

        util.removeClass(this.container, "notice");

        this.selected.setAttribute("aria-expanded", true);

        this.tree.setAttribute("aria-hidden", false);
        this.tree.setAttribute("aria-expanded", true);

        if (this.config.searchable && !this.config.taggable) {
            setTimeout(function() {
                that.input.focus();
                // Allow tab focus
                that.input.tabIndex = 0;
            }, 10);
        }
    };

    render_ajax_selectors();

    const selectr_add_modal = $('#selectr-add-modal');
    const selectr_modal = $('#selectr-modal');
    $(document).on('submit', '.selectr-modal form', function(evt) {
        evt.preventDefault();
        const modal = $(this).closest('.selectr-modal')
        const form = modal.find('form');
        // evt.preventDefault();
        // console.log(form.serializeArray());
        $.ajax({
            url: form.attr('action'),
            data: new FormData(form[0]),
            processData: false,
            contentType: false,
            type: form.attr('method'),
        }).done(async function(data) {
            const select = $('#'+modal.data('referer'));
            const val = modal.data('value');
            await selectorSearch(val, select[0].selectr, true);
            selectorSearch('', select[0].selectr);
            modal.modal('hide');
        }).fail(function(xhr, textStatus) {
            alert(xhr.responseJSON);
        });
    });

    $(document).on('click', '.edit-selectr', function(e) {
        e.preventDefault();
        const $this = $(this);
        const select = $this.closest('.selectr-container').find('select');
        const selectr = select[0].selectr;
        const val = $this.data('id');
        let option = select.find('option[value="'+val+'"]');
        selectr.close();
        const data = {
            modal: select.data('modal'),
            model: {
                class: option.data('model') ? option.data('model') : select.data('class'),
                key: select.data('model'),
                value: val
            }
        };
        $.get({
            url: editModalUrl,
            data: data
        }).done(function (response) {
            const modal = selectr_modal.clone();
            modal.removeAttr('id');
            modal.find('.edit-content').html(response);
            modal.css('z-index', z_index);
            modal.data('referer', select.attr('id'));
            modal.data('value', val);
            $('.page-wrapper').append(modal);
            render_ajax_selectors('.edit-content');
            loadDatepickers('.edit-content');
            modal.modal('show');
        });
    });

    $(document).on('submit', '.selectr-add-modal form', function(evt) {
        evt.preventDefault();
        const $this = $(this);
        // const form = $('#selectr-add-modal form');
        const modal = $(this).closest('.selectr-add-modal')
        const referer_dom = $('#'+modal.data('referer'));
        $.ajax({
            url: $this.attr('action'),
            data: new FormData($this[0]),
            processData: false,
            contentType: false,
            type: $this.attr('method'),
        }).done(function(element) {
            let depends = referer_dom.data('depends');
            if (!depends) {
                depends = {};
            }
            const data = {
                value: element[referer_dom.data('value')],
                text: element[referer_dom.data('text')]
            }
            if (referer_dom[0].selectr) {
                const selectr = referer_dom[0].selectr;
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
                let extra_fields = referer_dom.data('extra_fields');
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
                const option = selectr.add(data);
                try {
                    selectr.select(option.idx);
                } catch {
                    selectr.options.splice(option.idx - 1, 1);
                    selectr.select(option.idx - 1);
                }
            } else if(modal.data('referer_type') == 'typing_selector') {
                referer_dom.find('.option_id').val(data.value);
                referer_dom.find('.option_name').val(data.text);
                referer_dom.find('.option_name').trigger('change');
            }
            modal.modal('hide');
        }).fail(function(xhr, textStatus) {
            alert(xhr.responseJSON);
        });
    });

    $(document).on('click', '.btn-open-modal', function(e) {
        const $this = $(this);
        const referer = $this.data('referer');
        const referer_type = $this.data('referer_type');
        const referer_dom = $('#'+referer);
        const depends = referer_dom.data('depends');
        const data = {
            view: $this.data('modal'),
            add_fields: $this.data('add_fields')
        };
        if (depends) {
            data.depends = {};
            for (const depend of Object.keys(depends)) {
                const selectr = document.getElementById(depends[depend]).selectr;
                data.depends[depend] = selectr.getValue();
            }
        }
        $.get({
            url: addModalUrl,
            data: data
        }).done(function(response) {
            const cloned_modal = selectr_add_modal.clone();
            cloned_modal.removeAttr('id');

            cloned_modal.find('.edit-content').html(response);
            cloned_modal.data('referer', referer);
            cloned_modal.data('referer_type', referer_type);
            $('.page-wrapper').append(cloned_modal);
            render_ajax_selectors('.edit-content');
            loadDatepickers('.edit-content');
            cloned_modal.modal('show');
        });
    });

    $(document).on('click', '.deselect-selectr', function(e) {
        const $this = $(this);
        const select = $this.closest('.selectr-container').find('select');
        const selectr = select[0].selectr;
        selectr.clear();
    });
});

window.render_ajax_selectors = render_ajax_selectors;
