var jabvfcr_inspectorEnabled = true;
var jabvfcr_selector;
var jabvfcr_html;
var jabvfcr_parent;

function jabvfcr_resetSelector() {
    jabvfcr_inspectorEnabled = true;
    jQuery('#jabvfcr_selector').show();
    jabvfcr_parent.html(jabvfcr_html);
    jQuery('html, body').animate({
        scrollTop: jQuery(jabvfcr_selector).offset().top
    });
    jQuery('body').css({
        cursor: 'pointer'
    });
}

function jabvfcr_updateSelector(selector) {
    if (selector) {
        jabvfcr_parent = jQuery(selector).parent();
        jabvfcr_html = jabvfcr_parent.html();
        parent.jabvfcr_selected(selector);
    }
    jabvfcr_parent.html(jabvfcr_html);
    var data = parent.jabvfcr_getOptions();
    selector = selector || data.selector;
    jabvfcr_selector = selector;

    if (!selector) {
        return;
    }
    
    jQuery('html, body').animate({
        scrollTop: jQuery(selector).offset().top
    });
    jQuery(selector)[data.manipulation](data.content);
    jabvfcr_inspectorEnabled = false;
    jQuery('#jabvfcr_selector').hide();
    jQuery('body').css({
        cursor: 'auto'
    });
}

function jabvfcr_isSelectorValid(selector) {
    if (selector === '') {
        return true;
    }
     try {
         if (jabvfcr_parent) {
            var currentHtml = jabvfcr_parent.html();
            jabvfcr_parent.html(jabvfcr_html);
         }
        var result = document.querySelector(selector);
        if (jabvfcr_parent) {
            jabvfcr_parent.html(currentHtml);
        }
        if (!result) {
            return false;
        }
   } catch (e) {
        return false;
   }

   return true;
}

(function () {
    var mySimmer = window.Simmer.configure({
        depth: 10,
        selectorMaxLength: 1000
    });

    jQuery('body').append('<div id="jabvfcr_selector"><div id="jabvfcr_selector-top"></div><div id="jabvfcr_selector-left"></div><div id="jabvfcr_selector-right"></div><div id="jabvfcr_selector-bottom"></div></div>');

    
    var elements = {
        top: jQuery('#jabvfcr_selector-top'),
        left: jQuery('#jabvfcr_selector-left'),
        right: jQuery('#jabvfcr_selector-right'),
        bottom: jQuery('#jabvfcr_selector-bottom')
    };

    jQuery(document).mousemove(function(event) {
        if (!jabvfcr_inspectorEnabled) {
            return;
        }

        if(event.target.id.indexOf('jabvfcr_selector') !== -1 || event.target.tagName === 'BODY' || event.target.tagName === 'HTML') return;
        
        var $target = jQuery(event.target);
            targetOffset = $target[0].getBoundingClientRect(),
            targetHeight = targetOffset.height,
            targetWidth  = targetOffset.width;
        
        elements.top.css({
            left:  (targetOffset.left - 4),
            top:   (targetOffset.top - 4),
            width: (targetWidth + 5)
        });
        elements.bottom.css({
            top:   (targetOffset.top + targetHeight + 1),
            left:  (targetOffset.left  - 3),
            width: (targetWidth + 4)
        });
        elements.left.css({
            left:   (targetOffset.left  - 5),
            top:    (targetOffset.top  - 4),
            height: (targetHeight + 8)
        });
        elements.right.css({
            left:   (targetOffset.left + targetWidth + 1),
            top:    (targetOffset.top  - 4),
            height: (targetHeight + 8)
        });

        var selector = mySimmer(event.target);
        
        if (selector) {
            parent.jabvfcr_updateCurrentSelector(selector)
        }

    });

    jQuery('a').click(function(e){
        e.preventDefault();
    });

    document.addEventListener('click', function(e) {
        if (!jabvfcr_inspectorEnabled) {
            return;
        }
        e = e || window.event;
        var target = e.target || e.srcElement;

        if (jQuery(target).is("a")) {
            e.preventDefault();
        }

        var selector = mySimmer(target);
        
        if (selector) {
            jabvfcr_updateSelector(selector);
        }
    }, false);
})();