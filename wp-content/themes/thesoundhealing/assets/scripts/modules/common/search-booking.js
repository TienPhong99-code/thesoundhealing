(function () {
    'use strict';

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

    var sb = document.getElementById('search-booking');
    if (!sb) return;

    // Mức giá — chọn 1 (radio)
    var priceValue = ((document.getElementById('sb-input-price') || {}).value) || '';

    // ── Helpers ───────────────────────────────────────────────────────────
    function closeAllPanels() {
        sb.querySelectorAll('.sb-field.is-open').forEach(function (f) {
            f.classList.remove('is-open');
            var btn = f.querySelector('[data-sb-toggle]');
            if (btn) btn.setAttribute('aria-expanded', 'false');
            var panel = f.querySelector('.sb-panel');
            if (panel) panel.setAttribute('aria-hidden', 'true');
        });
        // Keep body locked if mobile popup is still open
        if (!sb.classList.contains('is-popup-open')) {
            document.body.classList.remove('no-scroll');
        }
    }

    function openPanel(name) {
        closeAllPanels();
        var field = document.getElementById('sb-field-' + name);
        var panel = document.getElementById('sb-panel-' + name);
        if (!field || !panel) return;
        var btn = field.querySelector('[data-sb-toggle]');
        field.classList.add('is-open');
        if (btn) btn.setAttribute('aria-expanded', 'true');
        panel.setAttribute('aria-hidden', 'false');
        document.body.classList.add('no-scroll');
    }

    // ── Panel toggles ─────────────────────────────────────────────────────
    sb.querySelectorAll('[data-sb-toggle]').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            var field   = this.closest('.sb-field');
            var isOpen  = field.classList.contains('is-open');
            var panelId = 'sb-panel-' + this.dataset.sbToggle;

            closeAllPanels();

            if (!isOpen) {
                field.classList.add('is-open');
                this.setAttribute('aria-expanded', 'true');
                var panel = document.getElementById(panelId);
                if (panel) panel.setAttribute('aria-hidden', 'false');
                document.body.classList.add('no-scroll');
            }
        });
    });

    document.addEventListener('click', function (e) {
        if (!sb.contains(e.target)) closeAllPanels();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            if (sb.classList.contains('is-popup-open')) {
                closePopup();
            } else {
                closeAllPanels();
            }
        }
    });

    // ── Loại hình — option list ───────────────────────────────────────────
    var typePanel = document.getElementById('sb-panel-type');
    if (typePanel) {
        typePanel.addEventListener('click', function (e) {
            var item = e.target.closest('.sb-type-item');
            if (!item) return;

            var val   = item.dataset.value;
            var label = item.dataset.label;

            typePanel.querySelectorAll('.sb-type-item').forEach(function (i) { i.classList.remove('is-active'); });
            item.classList.add('is-active');

            var typeInput = document.getElementById('sb-input-type');
            if (typeInput) typeInput.value = val;

            var valEl = document.getElementById('sb-val-type');
            if (valEl) valEl.textContent = label;

            updateMobileSummary();
            openPanel('time');
        });
    }

    // ── Time pills — individual listeners ─────────────────────────────────
    sb.querySelectorAll('.sb-time-pill').forEach(function (pill) {
        pill.addEventListener('click', function (e) {
            e.stopPropagation();

            var val       = this.dataset.value;
            var label     = this.dataset.label || this.textContent.trim();
            var input     = document.getElementById('sb-input-time');
            var dateInput = document.getElementById('sb-input-date');
            var valEl     = document.getElementById('sb-val-time');

            if (input)     input.value      = val;
            if (dateInput) dateInput.value  = '';
            if (valEl)     valEl.textContent = label;

            sb.querySelectorAll('.sb-time-pill').forEach(function (p) { p.classList.remove('is-active'); });
            this.classList.add('is-active');

            if (window._sbFlatpickr) window._sbFlatpickr.clear();

            updateMobileSummary();
            openPanel('price');
        });
    });

    // ── Mức giá — chọn 1 (radio) ─────────────────────────────────────────
    var pricePanel = document.getElementById('sb-panel-price');
    if (pricePanel) {
        pricePanel.addEventListener('click', function (e) {
            var item = e.target.closest('.sb-price-item');
            if (!item) return;

            var val   = item.dataset.value;
            var label = item.dataset.label || item.textContent.trim();

            // Bấm lại option đang chọn → bỏ chọn
            if (priceValue === val) {
                priceValue = '';
                item.classList.remove('is-active');
            } else {
                priceValue = val;
                pricePanel.querySelectorAll('.sb-price-item').forEach(function (i) { i.classList.remove('is-active'); });
                item.classList.add('is-active');
            }

            var hiddenInput = document.getElementById('sb-input-price');
            if (hiddenInput) hiddenInput.value = priceValue;

            updatePriceSummary();
            updateMobileSummary();
        });
    }

    function updatePriceSummary() {
        var valEl = document.getElementById('sb-val-price');
        if (!valEl) return;
        if (priceValue) {
            var active = pricePanel ? pricePanel.querySelector('.sb-price-item.is-active') : null;
            valEl.textContent = active ? (active.dataset.label || active.textContent.trim()) : 'Chọn mức giá';
        } else {
            valEl.textContent = 'Chọn mức giá';
        }
    }

    // ── Price footer: Xóa + Áp dụng (mobile) ──────────────────────────────
    var clearBtn = document.getElementById('sb-price-clear');
    var applyBtn = document.getElementById('sb-price-apply');

    function clearPrice() {
        priceValue = '';
        if (pricePanel) pricePanel.querySelectorAll('.sb-price-item').forEach(function (i) { i.classList.remove('is-active'); });
        var hiddenInput = document.getElementById('sb-input-price');
        if (hiddenInput) hiddenInput.value = '';
        updatePriceSummary();
        updateMobileSummary();
    }

    if (clearBtn) {
        clearBtn.addEventListener('click', clearPrice);
    }

    if (applyBtn) {
        applyBtn.addEventListener('click', function () {
            closeAllPanels();
            // Trong popup mobile, không submit ngay — dùng nút Tìm kiếm ở footer
            if (!sb.classList.contains('is-popup-open') && sbForm) {
                sbForm.requestSubmit();
            }
        });
    }

    // ── Submit validation — toast nếu chưa chọn bất kỳ bước nào ─────────
    var toastEl = null;
    var toastTimer = null;

    function showToast(msg) {
        if (!toastEl) {
            toastEl = document.createElement('div');
            toastEl.className = 'sb-toast';
            toastEl.setAttribute('role', 'status');
            toastEl.setAttribute('aria-live', 'polite');
            toastEl.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/><path d="M12 8v4m0 4h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg><span></span>';
            document.body.appendChild(toastEl);
        }
        toastEl.querySelector('span').textContent = msg;
        toastEl.classList.add('is-visible');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(function () {
            toastEl.classList.remove('is-visible');
        }, 3000);
    }

    var sbForm = document.getElementById('sb-form');
    if (sbForm) {
        sbForm.addEventListener('submit', function (e) {
            var hasType  = !!(document.getElementById('sb-input-type')    || {}).value ||
                           !!(document.getElementById('sb-input-subterm') || {}).value;
            var hasTime  = !!(document.getElementById('sb-input-time')    || {}).value ||
                           !!(document.getElementById('sb-input-date')    || {}).value;
            var hasPrice = !!(document.getElementById('sb-input-price') || {}).value;

            if (!hasType && !hasTime && !hasPrice) {
                e.preventDefault();
                showToast('Vui lòng chọn ít nhất một tiêu chí tìm kiếm');
                return;
            }
        });
    }

    // ── Flatpickr ─────────────────────────────────────────────────────────
    try {
        var fpTrigger = document.getElementById('sb-flatpickr-trigger');
        if (fpTrigger && typeof flatpickr === 'function') {
            window._sbFlatpickr = flatpickr(fpTrigger, {
                inline:  true,
                minDate: 'today',
                locale: viLocale,
                onChange: function (selectedDates, dateStr) {
                    var timeInput = document.getElementById('sb-input-time');
                    var dateInput = document.getElementById('sb-input-date');
                    var valEl     = document.getElementById('sb-val-time');

                    if (timeInput) timeInput.value = '';
                    if (dateInput) dateInput.value = dateStr;

                    if (valEl && selectedDates[0]) {
                        valEl.textContent = selectedDates[0].toLocaleDateString('vi-VN', { day: 'numeric', month: 'short', year: 'numeric' });
                    }

                    sb.querySelectorAll('.sb-time-pill').forEach(function (p) { p.classList.remove('is-active'); });

                    updateMobileSummary();
                    setTimeout(function () { openPanel('price'); }, 300);
                },
            });
        }
    } catch (err) {
        // flatpickr init failed — date picker falls back to hidden input only
    }

    // ── Mobile Popup (Airbnb-style) ───────────────────────────────────────
    var popupTrigger  = document.getElementById('sb-mobile-trigger');
    var popupOverlay  = document.getElementById('sb-popup-overlay');
    var popupClose    = document.getElementById('sb-popup-close');
    var popupSubmit   = document.getElementById('sb-popup-submit');
    var popupClearAll = document.getElementById('sb-popup-clear-all');

    function openPopup() {
        sb.classList.add('is-popup-open');
        if (popupOverlay) popupOverlay.setAttribute('aria-hidden', 'false');
        document.body.classList.add('no-scroll');
        var gsbButtons = document.querySelector('.gsb-buttons');
        if (gsbButtons) gsbButtons.style.display = 'none';
        var zaloWidget = document.querySelector('.zalo-chat-widget');
        if (zaloWidget) zaloWidget.style.display = 'none';
        // Auto-open Loại hình nếu chưa chọn gì
        var typeVal = (document.getElementById('sb-input-type') || {}).value;
        if (!typeVal) {
            setTimeout(function () { openPanel('type'); }, 50);
        }
    }

    function closePopup() {
        closeAllPanels();
        sb.classList.remove('is-popup-open');
        if (popupOverlay) popupOverlay.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('no-scroll');
        var gsbButtons = document.querySelector('.gsb-buttons');
        if (gsbButtons) gsbButtons.style.display = '';
        var zaloWidget = document.querySelector('.zalo-chat-widget');
        if (zaloWidget) zaloWidget.style.display = '';
    }

    if (popupTrigger) {
        popupTrigger.addEventListener('click', openPopup);
    }

    if (popupClose) {
        popupClose.addEventListener('click', closePopup);
    }

    if (popupSubmit) {
        popupSubmit.addEventListener('click', function () {
            closePopup();
            if (sbForm) sbForm.requestSubmit();
        });
    }

    if (popupClearAll) {
        popupClearAll.addEventListener('click', function () {
            // Xóa loại hình
            var typeInput    = document.getElementById('sb-input-type');
            var subtermInput = document.getElementById('sb-input-subterm');
            var valType      = document.getElementById('sb-val-type');
            if (typeInput)    typeInput.value    = '';
            if (subtermInput) subtermInput.value = '';
            if (valType)      valType.textContent = 'Chọn loại hình';
            sb.querySelectorAll('.sb-type-item').forEach(function (i) { i.classList.remove('is-active'); });

            // Xóa thời gian
            var timeInput = document.getElementById('sb-input-time');
            var dateInput = document.getElementById('sb-input-date');
            var valTime   = document.getElementById('sb-val-time');
            if (timeInput) timeInput.value     = '';
            if (dateInput) dateInput.value     = '';
            if (valTime)   valTime.textContent = 'Ngày đặt lịch';
            sb.querySelectorAll('.sb-time-pill').forEach(function (p) { p.classList.remove('is-active'); });
            if (window._sbFlatpickr) window._sbFlatpickr.clear();

            // Xóa mức giá
            clearPrice();
        });
    }

    function updateMobileSummary() {
        var summaryEl = document.getElementById('sb-mobile-summary');
        if (!summaryEl) return;
        var parts = [];

        var typeInput = document.getElementById('sb-input-type');
        var valType   = document.getElementById('sb-val-type');
        if (typeInput && typeInput.value) {
            var typeText = valType ? valType.textContent.trim() : typeInput.value;
            if (typeText && typeText !== 'Chọn loại hình') parts.push(typeText);
        }

        var timeInput = document.getElementById('sb-input-time');
        var dateInput = document.getElementById('sb-input-date');
        var valTime   = document.getElementById('sb-val-time');
        if ((timeInput && timeInput.value) || (dateInput && dateInput.value)) {
            var timeText = valTime ? valTime.textContent.trim() : '';
            if (timeText && timeText !== 'Ngày đặt lịch') parts.push(timeText);
        }

        var priceInput = document.getElementById('sb-input-price');
        var valPrice   = document.getElementById('sb-val-price');
        if (priceInput && priceInput.value) {
            var priceText = valPrice ? valPrice.textContent.trim() : '';
            if (priceText && priceText !== 'Chọn mức giá') parts.push(priceText);
        }

        summaryEl.textContent = parts.length > 0 ? parts.join(' · ') : 'Loại hình · Thời gian · Mức giá';
    }

})();
