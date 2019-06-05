define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'mage/translate',
    'mage/backend/notification',
    'mage/validation'
], function ($) {
    'use strict';

    $.widget('credova.uploadNewFederalLicense', {
        formWrapper: null,
        form: null,

        _getModalOptions: function () {
            return {
                'title': $.mage.__('Upload New Federal License'),
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
            this.formWrapper = $('.credova-upload-new-license-form-wrapper');
            this.form = this.formWrapper.find('form');
            this.wireModal();
        },

        wireModal: function () {
            let that = this;

            that.formWrapper
                .modal(this._getModalOptions())
                .addClass('modal');

            $('#credova-upload-new-federal-license').click(function () {

                that.formWrapper.modal('openModal');
            });
        },

        handleSubmit: function () {
            if (!this.form.valid()) {
                return;
            }
            this.doLFileUpload();
        },

        displayCreateFields: function () {
            this.formWrapper.addClass('create');
            this.formWrapper.find('.create-field input').removeProp('disabled');
        },

        doLFileUpload: function () {
            let that = this;
            let formData = new FormData();

            $.each(that.form.serializeArray(), function (data) {
                formData.append(this.name, this.value)
            });

            formData.append('file', $('#file_upload').prop('files')[0])
            formData.append('order_id', that.options.orderId,)


            $.ajax({
                url: this.form.prop('action'),
                showLoader: true,
                data: formData,
                contentType: false,
                processData: false,
                method: "POST",
            }).done(function (data) {
                switch (data.status) {
                    case 'error':
                        that.displayMessage(data.message, true);
                        break;
                    case 'success':
                        that.displayMessage('Federal license File Uploaded.', false);
                        that.formWrapper.modal('closeModal');
                        break;
                }
            })
        },

        displayMessage: function (message, error) {
            $('body').notification('clear')
                .notification('add', {
                    error: error,
                    message: $.mage.__(message),

                    insertMethod: function (message) {
                        var $wrapper = $('<div/>').html(message);

                        $('.page-main-actions').after($wrapper);
                    }
                });
        },
    });

    return $.credova.uploadNewFederalLicense;
});
