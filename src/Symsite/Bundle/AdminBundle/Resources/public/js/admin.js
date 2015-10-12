$(function(){
    /**
     * prevent twice click
     */
    $(document).on('submit', 'form', function(e) {
        var form = $(this);
        // slight timeout so that the submit button gets properly serialized
        setTimeout(function(){ disableFormElements(form); }, 13);
    });

    function disableFormElements(form) {
        var disableSelector = 'input[data-disable-with]:enabled, button[data-disable-with]:enabled, textarea[data-disable-with]:enabled, input[data-disable]:enabled, button[data-disable]:enabled, textarea[data-disable]:enabled';

        formElements(form, disableSelector).each(function() {
            //console.log(this);
            disableFormElement($(this));
        });
    };

    function disableFormElement(element) {
        var method, replacement;

        method = element.is('button') ? 'html' : 'val';
        replacement = element.data('disable-with');

        if (replacement !== undefined) {
            element[method](replacement);
        }

        element.prop('disabled', true);
    };

    // Helper function that returns form elements that match the specified CSS selector
    // If form is actually a "form" element this will return associated elements outside the from that have
    // the html form attribute set
    function formElements(form, selector) {
        return form.is('form') ? $(form[0].elements).filter(selector) : form.find(selector);
    };

    /**
     * toggle delete checkbox for index page.
     */
    $('#checkAllDelete').on('click', function () {
        var checked = $(this).is(':checked');
        $('.checkDelete').prop('checked', checked);

        var disabled = $('.checkDelete:checked').length > 0 ? false : true;
        $('.deleteButton').prop('disabled', disabled);
    });

    $('.checkDelete').on('click', function () {
        var disabled = $('.checkDelete:checked').length > 0 ? false : true;
        $('.deleteButton').prop('disabled', disabled);

        if (disabled) {
            $('#checkAllDelete').prop('checked', false);
        }
    });

    /**
     * confirm button
     */
    var confirmButton = {
        initialize: function () {
            this.buttons = $('button[data-confirm]');
            this.registerEvents();
        },

        registerEvents: function () {
            this.buttons.on('click', this.handleMethod);
        },

        handleMethod: function (e) {
            var button = $(this);

            return confirm(button.data('confirm'));
        }
    };
    confirmButton.initialize();

    /**
     * submit form link
     */
    var submitFromLink = {
        initialize: function() {
            this.formLinks = $('a[data-form]');
            this.registerEvents();
        },

        registerEvents: function() {
            this.formLinks.on('click', this.handleMethod);
        },

        handleMethod: function(e) {
            e.preventDefault();

            var link = $(this);
            var form;

            // Allow user to optionally provide data-confirm="Are you sure?"
            if ( link.data('confirm') ) {
                if ( ! submitFromLink.verifyConfirm(link) ) {
                    return false;
                }
            }

            form = $("#"+link.data('form'));
            form.attr('action', link.attr('href'));
            form.submit();
        },

        verifyConfirm: function(link) {
            return confirm(link.data('confirm'));
        }
    };
    submitFromLink.initialize();
});

