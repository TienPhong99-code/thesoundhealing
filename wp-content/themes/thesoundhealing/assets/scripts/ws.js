(function ($) {
    function injectWorkshopData() {
        if (typeof ws_data === 'undefined') return;
        $('input[name="workshop_id"]').val(ws_data.id);
        $('input[name="workshop_name"]').val(ws_data.name);
    }

    function initSubmitLoading() {
        var form = document.querySelector('.cf7-workshop .wpcf7-form');
        if (!form) return;

        var btn = form.querySelector('.wpcf7-submit');
        if (btn && !btn.closest('.cf7-submit-wrap')) {
            var wrap = document.createElement('div');
            wrap.className = 'cf7-submit-wrap';
            btn.parentNode.insertBefore(wrap, btn);
            wrap.appendChild(btn);
        }

        form.addEventListener('submit', function () {
            var w = form.querySelector('.cf7-submit-wrap');
            if (w) w.classList.add('is-loading');
        });

        new MutationObserver(function () {
            if (!form.classList.contains('submitting')) {
                var w = form.querySelector('.cf7-submit-wrap');
                if (w) w.classList.remove('is-loading');
            }
        }).observe(form, { attributes: true, attributeFilter: ['class'] });
    }

    $(document).ready(function () {
        injectWorkshopData();
        initSubmitLoading();
    });

    // Re-inject sau khi CF7 reset (validation fail, spam)
    $(document).on('wpcf7:invalid wpcf7:spam', injectWorkshopData);
})(jQuery);
