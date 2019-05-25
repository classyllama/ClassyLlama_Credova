define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'mage/translate'
], function ($) {
    'use strict';

    $.widget('credova.createFederalLicense', {
        formWrapper: null,

        _getModalOptions: function() {
            return {
                'title': $.mage.__('Create Federal License'),
                'type': 'slide',
                'buttons': [
                    {
                        'class': 'action-primary',
                        'text': $.mage.__('Submit'),
                        'click': this.handleSubmit.bind(this)
                    }
                ]
            };
        },

        _create: function () {
            let that = this;

            that.formWrapper = $('.credova-license-creation-form-wrapper');

            that.formWrapper
                .modal(this._getModalOptions())
                .addClass('modal');

            $('#credova-create-federal-license').click(function() {
                that.formWrapper.modal('openModal');
            });
        },

        handleSubmit: function() {
            let form = this.formWrapper.find('.credova-license-get-form');
            let url = form.prop('action');

            // TODO: start spinner
            $.ajax({
                url: url,
                data: form.serialize()
            })  .done(function() {
                // TODO: found license
            })
            .fail(function() {
                // TODO: no such license
            })
            .always(function() {
                // TODO: stop spinner
            });
        }
    });

    return $.credova.createFederalLicense;
});
