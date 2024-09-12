(function ($, doc, win) {
    'use strict';

    window.wp_admin_ajax = function (cls, is_form, cb) {
        const self = this;
        let element = null;
        let handler = {};
        let prefix = "wfco_";
        this.action = null;

        this.data = function (form_data, formEl = null) {

            return form_data;
        };
        this.before_send = function (formEl) {
        };
        this.async = function (bool) {
            return bool
        };
        this.method = function (method) {
            return method;
        };
        this.success = function (rsp, fieldset, loader, jqxhr, status) {
        };
        this.complete = function (rsp, fieldset, loader, jqxhr, status) {
        };
        this.error = function (rsp, fieldset, loader, jqxhr, status) {
        };
        this.action = function (action) {
            return action
        };

        function reset_form(action, fieldset, loader, rsp, jqxhr, status) {
            fieldset.length > 0 ? fieldset.prop('disabled', false) : null;
            loader.remove();
            let loader2;
            loader2 = $("#offer_settings_btn_bottom .wfco_save_funnel_offer_products_ajax_loader ");
            loader2.removeClass('ajax_loader_show');

            if (self.hasOwnProperty(action) === true && typeof self[action] === 'function') {
                self[action](rsp, fieldset, loader, jqxhr, status);
            }
        }

        function form_post(action) {
            let formEl = element;
            let ajax_loader = null;
            let ajax_loader2 = null;
            let form_data = new FormData(formEl);

            form_data.append('action', action);

            let form_method = $(formEl).attr('method');

            let method = (form_method !== "undefined" && form_method !== "") ? form_method : 'POST';
            if ($(formEl).find("." + action + "_ajax_loader").length === 0) {
                $(formEl).find(".wfco_form_submit").prepend("<span class='" + action + "_ajax_loader spinner" + "'></span>");
                ajax_loader = $(formEl).find("." + action + "_ajax_loader");
            } else {
                ajax_loader = $(formEl).find("." + action + "_ajax_loader");
            }

            ajax_loader2 = $("#offer_settings_btn_bottom .wfco_save_funnel_offer_products_ajax_loader ");
            ajax_loader.addClass('ajax_loader_show');
            ajax_loader2.addClass('ajax_loader_show');

            let fieldset = $(formEl).find("fieldset");
            fieldset.length > 0 ? fieldset.prop('disabled', true) : null;

            let input_submit = $(formEl).find("input[type=submit]");
            input_submit.length > 0 ? input_submit.prop('disabled', true) : null;

            self.before_send(formEl, action);

            let data = self.data(form_data, formEl);

            let request = {
                url: ajaxurl,
                async: self.async(true),
                method: self.method('POST'),
                data: data,
                processData: false,
                contentType: false,
                //       contentType: self.content_type(false),
                success: function (rsp, jqxhr, status) {
                    reset_form(action + "_ajax_success", fieldset, ajax_loader, rsp, jqxhr, status);
                    self.success(rsp, jqxhr, status, fieldset, ajax_loader);
                },
                complete: function (rsp, jqxhr, status) {
                    if ('wfco_save_funnel_offer_products' === action) {
                        $("#modal-section_product_success").iziModal('open');
                    }

                    reset_form(action + "_ajax_complete", fieldset, ajax_loader, rsp, jqxhr, status);
                    self.complete(rsp, jqxhr, status, fieldset, ajax_loader);
                },
                error: function (rsp, jqxhr, status) {
                    reset_form(action + "_ajax_error", fieldset, ajax_loader, rsp, jqxhr, status);
                    self.error(rsp, jqxhr, status, fieldset, ajax_loader);
                }
            };
            handler.hasOwnProperty(action) ? clearTimeout(handler[action]) : handler[action] = null;
            handler[action] = setTimeout(
                function (request) {
                    $.ajax(request);
                }, 200, request
            );
        }

        function send_json(action) {
            let formEl = element;
            let data = self.data({}, formEl);
            typeof data === 'object' ? (data.action = action) : (data = {'action': action});

            self.before_send(formEl, action);

            let request = {
                url: ajaxurl,
                async: self.async(true),
                method: self.method('POST'),
                data: data,
                success: function (rsp, jqxhr, status) {
                    self.success(rsp, jqxhr, status, element);
                },
                complete: function (rsp, jqxhr, status) {
                    self.complete(rsp, jqxhr, status, element);
                },
                error: function (rsp, jqxhr, status) {
                    self.error(rsp, jqxhr, status, element);
                }
            };
            handler.hasOwnProperty(action) ? clearTimeout(handler[action]) : handler[action] = null;
            handler[action] = setTimeout(
                function (request) {
                    $.ajax(request);
                }, 200, request
            );
        }

        this.ajax = function (action, data) {
            typeof data === 'object' ? (data.action = action) : (data = {'action': action});

            data.action = prefix + action;
            self.before_send(document.body, action);

            let request = {
                url: ajaxurl,
                async: self.async(true),
                method: self.method('POST'),
                data: data,
                success: function (rsp, jqxhr, status) {
                    self.success(rsp, jqxhr, status, action);
                },
                complete: function (rsp, jqxhr, status) {
                    self.complete(rsp, jqxhr, status, action);
                },
                error: function (rsp, jqxhr, status) {
                    self.error(rsp, jqxhr, status, action);
                }
            };
            handler.hasOwnProperty(action) ? clearTimeout(handler[action]) : handler[action] = null;
            handler[action] = setTimeout(
                function (request) {
                    $.ajax(request);
                }, 200, request
            );
        };

        function form_init(cls) {
            if ($(cls).length > 0) {

                $(cls).on(
                    "submit", function (e) {
                        e.preventDefault();
                        let action = $(this).data('wfoaction');

                        if ('update_funnel' === action || 'add_offer' === action || 'update_offer' === action) {
                            let formelem = $('form[data-wfoaction="' + action + '"]');
                            if (formelem.find('div.errors').length > 0) {
                                return false;
                            }
                        }
                        if ('save_funnel_offer_products' === action && false === window.wfcoBuilder.offer_product_settings.offer_state) {
                            swal(
                                $.extend(
                                    {
                                        title: "",
                                        text: "",
                                        type: 'warning',

                                        confirmButtonText: '',
                                    }, wfcoParams.alerts.offer_edit
                                )
                            ).then(
                                (result) => {
                                    if (result.value) {


                                        if ('save_funnel_offer_products' === action && (false === window.wfcoBuilder.offer_product_settings.isEmpty(window.wfcoBuilder.offer_product_settings.variations) && true === window.wfcoBuilder.offer_product_settings.isEmpty(window.wfcoBuilder.offer_product_settings.selected_variations))) {
                                            swal(
                                                $.extend(
                                                    {
                                                        title: "",
                                                        text: "",
                                                        type: 'warning',

                                                        confirmButtonText: '',
                                                    }, wfcoParams.alerts.no_variations_chosen
                                                )
                                            ).then(
                                                (result) => {
                                                    if (result.value) {
                                                        if (action !== 'undefined') {
                                                            action = prefix + action;
                                                            action = action.trim();
                                                            element = this;
                                                            self.action = action;
                                                            form_post(action);
                                                        }
                                                    }
                                                }
                                            ).catch(
                                                (e) => {
                                                    console.log("Remove offer from list error", e);
                                                }
                                            );
                                        } else {
                                            if (action !== 'undefined') {
                                                action = prefix + action;
                                                action = action.trim();
                                                element = this;
                                                self.action = action;
                                                form_post(action);
                                            }
                                        }
                                    }
                                }
                            ).catch(
                                (e) => {
                                    console.log("Remove offer from list error", e);
                                }
                            );
                        }
                        else if ('save_funnel_offer_products' === action && (false === window.wfcoBuilder.offer_product_settings.isEmpty(window.wfcoBuilder.offer_product_settings.variations) && true === window.wfcoBuilder.offer_product_settings.isEmpty(window.wfcoBuilder.offer_product_settings.selected_variations))) {
                            swal(
                                $.extend(
                                    {
                                        title: "",
                                        text: "",
                                        type: 'warning',

                                        confirmButtonText: '',
                                    }, wfcoParams.alerts.no_variations_chosen
                                )
                            ).then(
                                (result) => {

                                }
                            ).catch(
                                (e) => {
                                    console.log("Remove offer from list error", e);
                                }
                            );
                        }
                        else {
                            if (action !== 'undefined') {
                                action = prefix + action;
                                action = action.trim();
                                element = this;
                                self.action = action;
                                form_post(action);
                            }
                        }


                    }
                );

                if (typeof cb === 'function') {

                    cb(self);
                }
            }
        }

        function click_init(cls) {
            if ($(cls).length > 0) {
                $(cls).on(
                    "click", function (e) {
                        console.log(e);
                        e.preventDefault();
                        let action = $(this).data('wfoaction');
                        if (action !== 'undefined') {
                            action = prefix + action;
                            action = action.trim();
                            element = this;
                            self.action = action;
                            send_json(action);
                        }
                    }
                );

                if (typeof cb === 'function') {
                    cb(self);
                }
            }
        }

        if (is_form === true) {
            form_init(cls, cb);
            return this;
        }

        if (is_form === false) {
            click_init(cls, cb);
            return this;
        }

        return this;
    };

    $(win).load(

    );

})(jQuery, document, window);
