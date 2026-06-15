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
        var dateInput = document.querySelector('.cf7-dich-vu input[type="date"]');
        if (!dateInput || typeof flatpickr === 'undefined') return;

        var today    = new Date();
        var tomorrow = new Date(today); tomorrow.setDate(today.getDate() + 1);
        var dow      = today.getDay();
        var daysToSat = dow === 0 ? 6 : (6 - dow);
        var sat      = new Date(today); sat.setDate(today.getDate() + daysToSat);
        var sun      = new Date(sat);   sun.setDate(sat.getDate() + 1);

        function fmtDate(d) { return d.getDate() + ' thg ' + (d.getMonth() + 1); }

        var quickOpts = [
            { label: 'Hôm nay',       sub: fmtDate(today),    date: today },
            { label: 'Ngày mai',      sub: fmtDate(tomorrow), date: tomorrow },
            { label: 'Cuối tuần này', sub: sat.getDate() + ' – ' + sun.getDate() + ' thg ' + (sat.getMonth() + 1), date: sat },
        ];

        var panel   = document.createElement('div');  panel.className   = 'cf7-date-panel';
        var leftEl  = document.createElement('div');  leftEl.className  = 'cf7-date-left';
        var sepEl   = document.createElement('div');  sepEl.className   = 'cf7-date-sep';
        var rightEl = document.createElement('div');  rightEl.className = 'cf7-date-right';

        var pillRefs = [];
        quickOpts.forEach(function (opt) {
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'cf7-date-pill';
            btn.innerHTML = '<span class="cf7-date-pill__label">' + opt.label + '</span><span class="cf7-date-pill__sub">' + opt.sub + '</span>';
            leftEl.appendChild(btn);
            pillRefs.push({ btn: btn, date: opt.date });
        });

        var fpTrigger = document.createElement('input');
        fpTrigger.type = 'text';
        fpTrigger.className = 'cf7-date-fp-trigger';
        fpTrigger.readOnly = true;
        rightEl.appendChild(fpTrigger);

        panel.appendChild(leftEl);
        panel.appendChild(sepEl);
        panel.appendChild(rightEl);

        var cfWrap = dateInput.closest('.wpcf7-form-control-wrap');
        if (cfWrap) {
            cfWrap.style.display = 'none';
            cfWrap.parentNode.insertBefore(panel, cfWrap);
        } else {
            dateInput.style.display = 'none';
            dateInput.parentNode.insertBefore(panel, dateInput);
        }

        var fp = flatpickr(fpTrigger, {
            inline: true,
            locale: viLocale,
            dateFormat: 'Y-m-d',
            minDate: 'today',
            onChange: function (selectedDates, dateStr) {
                dateInput.value = dateStr;
                pillRefs.forEach(function (p) { p.btn.classList.remove('is-active'); });
                if (selectedDates[0]) {
                    var sel = selectedDates[0];
                    pillRefs.forEach(function (p) {
                        if (p.date.getFullYear() === sel.getFullYear() &&
                            p.date.getMonth()    === sel.getMonth()    &&
                            p.date.getDate()     === sel.getDate()) {
                            p.btn.classList.add('is-active');
                        }
                    });
                }
            },
        });

        pillRefs.forEach(function (item) {
            item.btn.addEventListener('click', function () { fp.setDate(item.date, true); });
        });
    }

    function initSubmitLoading() {
        var form = document.querySelector('.cf7-dich-vu .wpcf7-form');
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

    function initSelect2() {
        if (typeof $.fn.select2 === 'undefined') return;
        $('.cf7-dich-vu .wpcf7-select').each(function () {
            if ($(this).hasClass('select2-hidden-accessible')) {
                $(this).select2('destroy');
            }
            var placeholder = $(this).find('option[value=""]').text().trim() || 'Vui lòng chọn';
            $(this).select2({
                minimumResultsForSearch: -1,
                placeholder: placeholder,
                dropdownParent: $('body'),
                selectionCssClass: 'select2-tsh',
                dropdownCssClass: 'select2-tsh-dropdown',
            });
        });
    }

    $(document).ready(function () {
        initDatePicker();
        initSubmitLoading();
        initSelect2();
    });

    $(document).on('wpcf7:invalid wpcf7:spam wpcf7:mailfailed wpcf7:mailsent', function () {
        setTimeout(initSelect2, 50);
    });
})(jQuery);
