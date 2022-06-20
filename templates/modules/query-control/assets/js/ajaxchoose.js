jQuery(window).on("elementor:init", function() {
    "use strict";
    var e = elementor.modules.controls.BaseData.extend({
        cache: null,
        isTitlesReceived: false,
        onReady: function() {
            if (!this.isTitlesReceived) {
                this.getValueTitles();
            }
            var el = this.ui.select;
            el.select2( this.getSelect2Options() );
        },
        getValueTitles: function getValueTitles() {
            var self = this,
                data = {},
                ids = this.getControlValue(),
                action = 'query_control_value_titles',
                filterTypeName = 'autocomplete',
                filterType = {};

            filterType = this.model.get(filterTypeName).object;
            data.get_titles = self.getQueryData().autocomplete;
            data.unique_id = '' + self.cid + filterType;

            if (!ids || !filterType) {
                return;
            }

            if (!this.isArray(ids)) {
                ids = [ids];
            }

            elementorCommon.ajax.loadObjects({
                action: action,
                ids: ids,
                data: data,
                before: function before() {
                    self.addControlSpinner();
                },
                success: function success(ajaxData) {
                    self.isTitlesReceived = true;
                    self.model.set('options', ajaxData);
                    self.render();
                }
            });
        },
        getSelect2Options: function getSelect2Options() {
            var self = this;
            return {
                ajax: {
                    transport: function transport(params, success, failure) {
                        var data = {},
                        action = 'pro_panel_posts_control_filter_autocomplete';
                        data = self.getQueryData();
                        data.q = params.data.q;
                        return elementor.ajax.addRequest(action, {
                            data: data,
                            success: success,
                            error: failure
                        });
                    },
                    data: function data(params) {
                        return {
                            q: params.term,
                            page: params.page
                        };
                    },
                    cache: true
                },
                escapeMarkup: function escapeMarkup(markup) {
                    return markup;
                },
                minimumInputLength: 1
            }
        },

        getQueryData: function getQueryData() {
            // Use a clone to keep model data unchanged:
            var autocomplete = elementorCommon.helpers.cloneObject(this.model.get('autocomplete'));

            if (this.isEmpty(autocomplete.query)) {
                autocomplete.query = {};
            } // Specific for Group_Control_Query


            if ('cpt_tax' === autocomplete.object) {
                autocomplete.object = 'tax';

                if (this.isEmpty(autocomplete.query) || this.isEmpty(autocomplete.query.post_type)) {
                    autocomplete.query.post_type = this.getControlValueByName('post_type');
                }
            }

            return {
                autocomplete: autocomplete
            };
        },

        getControlValueByName: function getControlValueByName(controlName) {
            var name = this.model.get('group_prefix') + controlName;
            return this.elementSettingsModel.attributes[name];
        },

        addControlSpinner: function addControlSpinner() {
            this.ui.select.prop('disabled', true);
            this.$el.find('.elementor-control-title').after('<span class="elementor-control-spinner">&nbsp;<i class="eicon-spinner eicon-animation-spin"></i>&nbsp;</span>');
        },

        isEmpty: function isEmpty(object) {
            return ( !object || 0 === object.length );
        },

        isArray: function isArray(arr) {
            if (typeof Array.isArray === 'function') {
                return Array.isArray(arr);
            }

            return toStr.call(arr) === '[object Array]';
        },

        onBeforeDestroy: function() {
            this.ui.select.data("select2") && this.ui.select.select2("destroy"), this.el.remove()
        }
    });
    elementor.addControlView("query", e)
});