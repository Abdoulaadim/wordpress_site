var jabvfcr_selector;
var jabvfcr_iframe = jQuery('.jabvfcr .js-inspector .js-site-preview-iframe')[0].contentWindow;

jabvfcr_iframe.addEventListener("load", function() {
    jabvfcr_openInspector();
});

function jabvfcr_isValidSelector() {
    jQuery('.jabvfcr .invalid').addClass('dn');

    var selector = jQuery('.jabvfcr .js-selector').val();
    var isValid = jabvfcr_iframe.jabvfcr_isSelectorValid(selector);

    if (!isValid) {
        jQuery('.jabvfcr .invalid').removeClass('dn');
    }

    return isValid;
}

function jabvfcr_selected(selector) {
    jQuery('.jabvfcr .js-selector').val(selector);
    jabvfcr_toggleOptions();
    jQuery('.jabvfcr .invalid').addClass('dn');
}

function jabvfcr_toggleOptions(hide) {
    jQuery('.jabvfcr .js-inspector .js-clear').toggleClass('dn', hide);
}

function jabvfcr_toggleOverlay(show) {
    jQuery('.jabvfcr .js-inspector')[show ? 'show' : 'hide']();
        jQuery('body').css({
            overflow: show ? 'hidden' : ''
        });
}

function jabvfcr_updateCurrentSelector(selector) {
    jQuery('.jabvfcr .js-current-selector').text(selector);
}

function jabvfcr_getOptions() {
    var selector = jQuery('.jabvfcr .js-selector').val();
    var manipulation = jQuery('.jabvfcr .js-manipulation:checked').val();
    var content = tinyMCE.activeEditor && !tinyMCE.activeEditor.hidden ? tinyMCE.activeEditor.getContent() : jQuery('#content').val();

    return {
        selector:selector,
        manipulation: manipulation,
        content: content
    };
}

function jabvfcr_resetSelector() {
    jabvfcr_iframe.jabvfcr_resetSelector();
    jQuery('.jabvfcr .js-selector').val('');
}

function jabvfcr_openInspector() {
    jQuery('.jabvfcr .js-loading-screen').remove();
    var selector = jQuery('.jabvfcr .js-selector').val();
    if (selector && jabvfcr_iframe.jabvfcr_isSelectorValid(selector)) {
        jabvfcr_iframe.jabvfcr_updateSelector(selector);
        jabvfcr_toggleOptions(false);
    }
    jabvfcr_toggleOverlay(true);
}

function jabvfcr_saveSelector() {
    if (!jabvfcr_isValidSelector()) {
        return;
    }

    var data = {
        _ajax_nonce: jabvfcr_ajax.nonce,
        action: 'jabvfcr_save_selector',
        selector: jQuery('.jabvfcr .js-selector').val(),
        manipulation: jQuery('.jabvfcr .js-manipulation:checked').val(),
        content: tinyMCE.activeEditor && !tinyMCE.activeEditor.hidden ? tinyMCE.activeEditor.getContent() : jQuery('#content').val()
    };

    jQuery.post(jabvfcr_ajax.url, data).then(function() {
        alert('Settings updated.');
    }).fail(function() {
        alert('There was an unexpected error. Please try again.');
    });
}

(function ($) {
   
    jQuery( document ).on( 'tinymce-editor-init', function( event, editor ) {
        tinyMCE.activeEditor.onKeyUp.add(function(){
            jabvfcr_iframe.jabvfcr_updateSelector();
        });
        tinyMCE.activeEditor.onChange.add(function(){
            jabvfcr_iframe.jabvfcr_updateSelector();
        });
    });
    
    jQuery('#content').keyup(function(){
        jabvfcr_iframe.jabvfcr_updateSelector();
    });
    jQuery('#content').change(function(){
        jabvfcr_iframe.jabvfcr_updateSelector();
    });

    $('.jabvfcr .js-inspector .js-clear').click(function(e) {
        jabvfcr_resetSelector();
        jabvfcr_toggleOptions();
    });
    $('.jabvfcr .js-close-inspector').click(function(e) {
        e.preventDefault();
        jabvfcr_toggleOverlay(false);
    });
    $('.jabvfcr .js-toggle-advanced-options').click(function(e) {
        e.preventDefault();
        $('.js-advanced-options').toggleClass('dn');
    });
    $('.jabvfcr form .js-submit').click(function(e) {
        e.preventDefault();
        
        jabvfcr_saveSelector();
    });
    $('.jabvfcr form .js-selector').blur(function() {
        if (jabvfcr_isValidSelector()) {
            var selector = jQuery('.jabvfcr .js-selector').val();
            jabvfcr_iframe.jabvfcr_updateSelector(selector);
        }
    });
    $('.jabvfcr form .js-manipulation').change(function() {
        jabvfcr_iframe.jabvfcr_updateSelector();
    });
    $('.jabvfcr .js-open-inspector').click(function() {
        jabvfcr_openInspector();
    });
})(jQuery);
