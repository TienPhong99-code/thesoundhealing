(function ($) {
    var viLocale = {
        weekdays: {
            shorthand: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
            longhand: ['Chủ Nhật', 'Thứ Hai', 'Thứ Ba', 'Thứ Tư', 'Thứ Năm', 'Thứ Sáu', 'Thứ Bảy'],
        },
        months: {
            shorthand: ['Th.1', 'Th.2', 'Th.3', 'Th.4', 'Th.5', 'Th.6', 'Th.7', 'Th.8', 'Th.9', 'Th.10', 'Th.11', 'Th.12'],
            longhand: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
        },
        firstDayOfWeek: 1,
        ordinal: function () { return ''; },
    };

    function initDatePicker() {
        var dateInput = document.querySelector('.cf7-workshop input[type="date"]');
        if (!dateInput || typeof flatpickr === 'undefined') return;

        flatpickr(dateInput, {
            locale: viLocale,
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'd/m/Y',
            defaultDate: 'today',
            minDate: 'today',
            disableMobile: true,
            onReady: function (_, __, fpInstance) {
                var icon = document.createElement('span');
                icon.className = 'flatpickr-calendar-icon';
                icon.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>';
                icon.addEventListener('click', function () { fpInstance.open(); });
                fpInstance.altInput.parentNode.insertBefore(icon, fpInstance.altInput.nextSibling);
            },
        });
    }

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
        initDatePicker();
        injectWorkshopData();
        initSubmitLoading();
    });

    // Re-inject sau khi CF7 reset (validation fail, spam)
    $(document).on('wpcf7:invalid wpcf7:spam', injectWorkshopData);
})(jQuery);
