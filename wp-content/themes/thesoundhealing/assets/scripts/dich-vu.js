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
        dateInput.type = 'hidden';

        var availDates = (window.dvSchedule && Array.isArray(window.dvSchedule.availDates) && window.dvSchedule.availDates.length)
            ? window.dvSchedule.availDates : [];
        var firstDate = availDates.length ? availDates[0] : null;

        var quickOpts;
        if (availDates.length) {
            quickOpts = availDates.slice(0, 3).map(function (ds) {
                var d = new Date(ds + 'T00:00:00');
                return {
                    label: d.toLocaleDateString('vi-VN', { day: 'numeric', month: 'long' }),
                    sub:   d.toLocaleDateString('vi-VN', { weekday: 'long' }),
                    date:  d,
                };
            });
        } else {
            var _today    = new Date();
            var _tomorrow = new Date(_today); _tomorrow.setDate(_today.getDate() + 1);
            var _dow      = _today.getDay();
            var _dToSat   = _dow === 0 ? 6 : (6 - _dow);
            var _sat      = new Date(_today); _sat.setDate(_today.getDate() + _dToSat);
            var _sun      = new Date(_sat);   _sun.setDate(_sat.getDate() + 1);
            function fmtDate(d) { return d.getDate() + ' thg ' + (d.getMonth() + 1); }
            quickOpts = [
                { label: 'Hôm nay',       sub: fmtDate(_today),    date: _today },
                { label: 'Ngày mai',      sub: fmtDate(_tomorrow), date: _tomorrow },
                { label: 'Cuối tuần này', sub: _sat.getDate() + ' – ' + _sun.getDate() + ' thg ' + (_sat.getMonth() + 1), date: _sat },
            ];
        }

        var field = document.createElement('div');
        field.className = 'cf7-date-field';

        var triggerBtn = document.createElement('button');
        triggerBtn.type = 'button';
        triggerBtn.className = 'cf7-date-trigger';
        triggerBtn.innerHTML =
            '<span class="cf7-date-trigger__val">Chọn ngày</span>' +
            '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="17" height="17" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>';

        var panel   = document.createElement('div');  panel.className = 'cf7-date-panel'; panel.setAttribute('aria-hidden', 'true');
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
        fpTrigger.type = 'text'; fpTrigger.className = 'cf7-date-fp-trigger'; fpTrigger.readOnly = true;
        rightEl.appendChild(fpTrigger);

        panel.appendChild(leftEl); panel.appendChild(sepEl); panel.appendChild(rightEl);
        field.appendChild(triggerBtn); field.appendChild(panel);

        var cfWrap = dateInput.closest('.wpcf7-form-control-wrap');
        if (cfWrap) { cfWrap.classList.add('cf7-date-replaced'); cfWrap.parentNode.insertBefore(field, cfWrap); }
        else { dateInput.style.display = 'none'; dateInput.parentNode.insertBefore(field, dateInput); }

        var fpConfig = {
            inline: true,
            locale: viLocale,
            dateFormat: 'Y-m-d',
            minDate: 'today',
            onChange: function (selectedDates, dateStr) {
                dateInput.value = dateStr;
                var valEl = triggerBtn.querySelector('.cf7-date-trigger__val');
                if (valEl && selectedDates[0]) {
                    valEl.textContent = selectedDates[0].toLocaleDateString('vi-VN', { day: 'numeric', month: 'short', year: 'numeric' });
                    valEl.classList.add('has-value');
                }
                pillRefs.forEach(function (p) { p.btn.classList.remove('is-active'); });
                if (selectedDates[0]) {
                    var sel = selectedDates[0];
                    pillRefs.forEach(function (p) {
                        if (p.date.getFullYear() === sel.getFullYear() &&
                            p.date.getMonth()    === sel.getMonth()    &&
                            p.date.getDate()     === sel.getDate()) { p.btn.classList.add('is-active'); }
                    });
                }
                setTimeout(closePanel, 200);
            },
        };
        if (availDates.length) {
            fpConfig.enable = availDates.slice();
            delete fpConfig.minDate;
        }

        var fp = flatpickr(fpTrigger, fpConfig);

        function syncDate() {
            if (fp.selectedDates.length > 0) {
                var d = fp.selectedDates[0];
                dateInput.value = d.getFullYear() + '-' +
                    String(d.getMonth() + 1).padStart(2, '0') + '-' +
                    String(d.getDate()).padStart(2, '0');
            }
        }
        var formEl = dateInput.closest('form');
        if (formEl) {
            formEl.addEventListener('submit', syncDate, true);
            var submitBtn = formEl.querySelector('.wpcf7-submit');
            if (submitBtn) submitBtn.addEventListener('click', syncDate, true);
        }

        function applyDate(dateStr) {
            fp.setDate(dateStr, false);
            fp.jumpToDate(new Date(dateStr + 'T00:00:00'));
            var d = fp.selectedDates[0];
            if (!d) return;
            dateInput.value = dateStr;
            var valEl = triggerBtn.querySelector('.cf7-date-trigger__val');
            if (valEl) {
                valEl.textContent = d.toLocaleDateString('vi-VN', { day: 'numeric', month: 'short', year: 'numeric' });
                valEl.classList.add('has-value');
            }
            pillRefs.forEach(function (p) {
                p.btn.classList.remove('is-active');
                if (p.date.getFullYear() === d.getFullYear() &&
                    p.date.getMonth()    === d.getMonth()    &&
                    p.date.getDate()     === d.getDate()) { p.btn.classList.add('is-active'); }
            });
        }

        if (firstDate) {
            applyDate(firstDate);
        } else if (dateInput.value) {
            applyDate(dateInput.value);
        }

        function closePanel() { field.classList.remove('is-open'); panel.setAttribute('aria-hidden', 'true'); }

        pillRefs.forEach(function (item) {
            item.btn.addEventListener('click', function () { fp.setDate(item.date, true); closePanel(); });
        });

        triggerBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            if (field.classList.contains('is-open')) {
                closePanel();
            } else {
                field.classList.add('is-open');
                panel.setAttribute('aria-hidden', 'false');
                var jumpTo = fp.selectedDates.length > 0 ? fp.selectedDates[0] : (firstDate ? new Date(firstDate + 'T00:00:00') : null);
                if (jumpTo) { fp.setDate(jumpTo, false); fp.jumpToDate(jumpTo); }
            }
        });
        document.addEventListener('click', function (e) { if (!field.contains(e.target)) closePanel(); });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closePanel(); });
        return fp;
    }

    function prefillForm(fp) {
        var p = window.tshPrefill;
        if (!p) return;
        var $form = $('.cf7-dich-vu');
        if (!$form.length) return;
        ['fullname', 'email', 'phone'].forEach(function (name) {
            var $el = $form.find('input[name="' + name + '"]');
            if ($el.length && p[name]) $el.val(p[name]);
        });
        if (p.children) {
            $form.find('input[type="radio"][name="kh-children"][value="' + p.children + '"]').prop('checked', true);
        }
        if (fp && p.date) fp.setDate(p.date, true);
        var selMap = { 'dv-time': p.time, 'dv-branch': p.location, 'dv-instructor': p.instructor, 'ws-guests': p.guests };
        Object.keys(selMap).forEach(function (name) {
            if (!selMap[name]) return;
            var $el = $form.find('select[name="' + name + '"]');
            if ($el.length) $el.val(selMap[name]).trigger('change');
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

    function disableIfExpired() {
        if (!(window.dvSchedule && window.dvSchedule.isPast)) return;
        var $btn = $('.cf7-dich-vu .wpcf7-submit');
        $btn.prop('disabled', true).css({ opacity: '0.5', cursor: 'not-allowed', 'pointer-events': 'none' });
        $btn.val('Hết hạn');
    }

    var fp;

    $(document).ready(function () {
        fp = initDatePicker();
        initSubmitLoading();
        initSelect2();
        prefillForm(fp);
        disableIfExpired();
    });

    // Khi bfcache khôi phục trang (history.back/forward), JS không chạy lại.
    // CF7 đã reset form trước khi redirect nên select rỗng — prefill lại ở đây.
    window.addEventListener('pageshow', function (e) {
        if (!e.persisted) return;
        initSelect2();
        prefillForm(fp);
    });

    $(document).on('wpcf7:invalid wpcf7:spam wpcf7:mailfailed wpcf7:mailsent', function () {
        setTimeout(initSelect2, 50);
    });
})(jQuery);
