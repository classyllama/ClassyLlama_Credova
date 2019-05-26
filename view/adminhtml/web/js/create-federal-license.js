define([
    'jquery',
    'mage/storage',
    'Magento_Ui/js/modal/modal',
    'mage/translate',
    'mage/backend/notification'
], function ($, storage) {
    'use strict';

    $.widget('credova.createFederalLicense', {
        formWrapper: null,
        form: null,

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
            this.formWrapper = $('.credova-license-creation-form-wrapper');
            this.form = this.formWrapper.find('form');

            this.wireModal();
        },

        wireModal: function() {
            let that = this;

            that.formWrapper
                .modal(this._getModalOptions())
                .addClass('modal');

            $('#credova-create-federal-license').click(function() {
                that.formWrapper.modal('openModal');
            });
        },

        handleSubmit: function() {
            if (this.formWrapper.hasClass('create')) {
                this.doLicenseCreate();
            } else {
                this.doLicenseLookup(this.formWrapper.find('#license-number').val());
            }
        },

        doLicenseLookup: function(licenseNumber) {
            let that = this;

            // TODO: spinner
            storage.post(
                this.form.prop('action'),
                {
                    license_number: licenseNumber,
                    order_id: this.options.orderId,
                    form_key: FORM_KEY,
                    action: 'get'
                },
                false,
                'application/x-www-form-urlencoded'
            ).done(function(data) {
                switch(data.status) {
                    case 'error':
                        // No such license. Display create fields.
                        that.displayCreateFields();
                        break;
                    case 'success':
                        // License exists and set on order -- close modal.
                        that.displayMessage('Federal license created.', false);
                        that.formWrapper.modal('closeModal');
                        break;
                }
            }).always(function() {
                // TODO: stop spinner
            });
        },

        displayCreateFields: function() {
            this.formWrapper.addClass('create');
        },

        doLicenseCreate: function() {
            let that = this;
            let data = {
                order_id: that.options.orderId,
                action: 'create'
            };

            $.each(this.form.serializeArray(), function() {
                data[this.name] = this.value;
            });

            // TODO: spinner
            storage.post(
                this.form.prop('action'),
                data,
                false,
                'application/x-www-form-urlencoded'
            ).done(function(data) {
                switch(data.status) {
                    case 'error':
                        that.displayMessage(data.message, true);
                        break;
                    case 'success':
                        // License created and set on order -- close modal.
                        that.displayMessage('Federal license created.', false);
                        that.formWrapper.modal('closeModal');
                        break;
                }
            }).always(function() {
                // TODO: stop spinner
            });
        },

        displayMessage: function(message, error) {
            $('body').notification('clear')
                .notification('add', {
                    error: error,
                    message: $.mage.__(message),

                    insertMethod: function (message) {
                        var $wrapper = $('<div/>').html(message);

                        $('.page-main-actions').after($wrapper);
                    }
                });
        }
    });

    return $.credova.createFederalLicense;
});
