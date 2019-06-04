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

        _getModalOptions: function() {
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

        wireModal: function() {
            let that = this;

            that.formWrapper
                .modal(this._getModalOptions())
                .addClass('modal');

            $('#credova-upload-new-federal-license').click(function() {

                that.formWrapper.modal('openModal');
            });

        },

        handleSubmit: function() {

            if (!this.form.valid()) {
                return;
            }
            this.doLFileUpload();

        },

        doLicenseLookup: function(licenseNumber) {
            let that = this;

            $.ajax({
                url: this.form.prop('action'),
                showLoader: true,
                data: {
                    license_number: licenseNumber,
                    order_id: this.options.orderId,
                    action: 'get'
                }
            }).done(function(data) {
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
            });
        },

        displayCreateFields: function() {
            this.formWrapper.addClass('create');

            this.formWrapper.find('.create-field input').removeProp('disabled');
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

            $.ajax({
                url: this.form.prop('action'),
                showLoader: true,
                data: data
            }).done(function(data) {
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
            });
        },

        doLFileUpload: function(){
            let that = this;
            let formData = new FormData();
            $.each(that.form.serializeArray(), function(data) {
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
            }).done(function(data) {
                console.log(data);
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
            }).always(function (dat) {
                console.log(dat);
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
        },

        clickHandler: function(){
            $('#credova-upload-new-federal-license').on('click',function () {
                alert('hello');
                console.log('hello')
            })
        }



    });

    return $.credova.uploadNewFederalLicense;
});
