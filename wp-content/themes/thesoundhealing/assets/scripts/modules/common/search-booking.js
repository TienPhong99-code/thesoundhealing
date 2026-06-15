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

    var guestCounts = {
        adult:  parseInt((document.getElementById('sb-input-adult')  || {}).value) || 0,
        child:  parseInt((document.getElementById('sb-input-child')  || {}).value) || 0,
        infant: parseInt((document.getElementById('sb-input-infant') || {}).value) || 0,
    };

    var guestInputMap = {
        adult:  'sb-input-adult',
        child:  'sb-input-child',
        infant: 'sb-input-infant',
    };

    // ── Helpers ───────────────────────────────────────────────────────────
    function closeAllPanels() {
        sb.querySelectorAll('.sb-field.is-open').forEach(function (f) {
            f.classList.remove('is-open');
            var btn = f.querySelector('[data-sb-toggle]');
            if (btn) btn.setAttribute('aria-expanded', 'false');
            var panel = f.querySelector('.sb-panel');
            if (panel) panel.setAttribute('aria-hidden', 'true');
        });
        document.body.classList.remove('no-scroll');
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
        if (e.key === 'Escape') closeAllPanels();
    });

    // ── Loại hình — category pills + sub-terms ───────────────────────────
    var typePanel = document.getElementById('sb-panel-type');
    if (typePanel) {

        var catLabels = { 'dich-vu': 'Dịch vụ', 'khoa-hoc': 'Khóa học', 'workshop': 'Workshop' };

        function filterSubterms(catFilter) {
            typePanel.querySelectorAll('.sb-subterm').forEach(function (s) {
                if (!catFilter || s.dataset.cat === catFilter) {
                    s.classList.remove('sb-subterm--hidden');
                } else {
                    s.classList.add('sb-subterm--hidden');
                }
            });
        }

        typePanel.addEventListener('click', function (e) {

            // Category pill click — filter sub-terms, don't close panel
            var catBtn = e.target.closest('.sb-type-cat');
            if (catBtn) {
                var catFilter = catBtn.dataset.catFilter;
                var catLabel  = catBtn.dataset.catLabel;

                typePanel.querySelectorAll('.sb-type-cat').forEach(function (p) { p.classList.remove('is-active'); });
                catBtn.classList.add('is-active');

                // Clear sub-term selection
                var subtermInput = document.getElementById('sb-input-subterm');
                if (subtermInput) subtermInput.value = '';
                typePanel.querySelectorAll('.sb-subterm').forEach(function (s) { s.classList.remove('is-active'); });

                var typeInput = document.getElementById('sb-input-type');
                if (typeInput) typeInput.value = catFilter;

                var valEl = document.getElementById('sb-val-type');
                if (valEl) valEl.textContent = catFilter ? catLabel : 'Chọn loại hình';

                filterSubterms(catFilter);
                return;
            }

            // Sub-term click — set both loai-hinh + chuyen-mon, close panel
            var subterm = e.target.closest('.sb-subterm');
            if (subterm) {
                var val   = subterm.dataset.value;
                var label = subterm.dataset.label;
                var cat   = subterm.dataset.cat;

                typePanel.querySelectorAll('.sb-subterm').forEach(function (s) { s.classList.remove('is-active'); });
                subterm.classList.add('is-active');

                // Sync category pill
                typePanel.querySelectorAll('.sb-type-cat').forEach(function (p) { p.classList.remove('is-active'); });
                var matchPill = typePanel.querySelector('[data-cat-filter="' + cat + '"]');
                if (matchPill) matchPill.classList.add('is-active');

                var typeInput    = document.getElementById('sb-input-type');
                var subtermInput = document.getElementById('sb-input-subterm');
                if (typeInput)    typeInput.value    = cat;
                if (subtermInput) subtermInput.value = val;

                var catDisplayLabel = catLabels[cat] || '';
                var display = catDisplayLabel ? catDisplayLabel + ' · ' + label : label;
                var valEl = document.getElementById('sb-val-type');
                if (valEl) valEl.textContent = display;

                filterSubterms(cat);
                openPanel('time');
            }
        });
    }

    // ── Time pills — individual listeners (không có child elements) ───────
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

            openPanel('guest');
        });
    });

    // ── Guest counters — delegation ───────────────────────────────────────
    var guestPanel = document.getElementById('sb-panel-guest');
    if (guestPanel) {
        guestPanel.addEventListener('click', function (e) {
            var btn = e.target.closest('.sb-counter-btn');
            if (!btn) return;

            var target  = btn.dataset.target;
            var isMinus = btn.classList.contains('sb-counter-minus');
            var current = guestCounts[target] || 0;

            current = isMinus ? Math.max(0, current - 1) : Math.min(10, current + 1);
            guestCounts[target] = current;

            var countEl  = document.getElementById('sb-count-' + target);
            if (countEl) countEl.textContent = current;

            var minusBtn = btn.closest('.sb-guest-counter').querySelector('.sb-counter-minus');
            if (minusBtn) minusBtn.disabled = (current === 0);

            var hiddenInput = document.getElementById(guestInputMap[target]);
            if (hiddenInput) hiddenInput.value = current;

            updateGuestSummary();
        });
    }

    function updateGuestSummary() {
        var parts = [];
        if (guestCounts.adult  > 0) parts.push(guestCounts.adult  + ' người lớn');
        if (guestCounts.child  > 0) parts.push(guestCounts.child  + ' trẻ em');
        if (guestCounts.infant > 0) parts.push(guestCounts.infant + ' em bé');
        var valEl = document.getElementById('sb-val-guest');
        if (valEl) valEl.textContent = parts.length > 0 ? parts.join(', ') : 'Thêm khách';
    }

    // ── Submit validation — toast nếu chưa chọn bất kỳ bước nào ────────────
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
            var hasGuest = (guestCounts.adult + guestCounts.child + guestCounts.infant) > 0;

            if (!hasType && !hasTime && !hasGuest) {
                e.preventDefault();
                showToast('Vui lòng chọn ít nhất một tiêu chí tìm kiếm');
                return;
            }
        });
    }

    // ── Flatpickr — init cuối cùng, wrap try-catch để không break script ──
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

                    setTimeout(function () { openPanel('guest'); }, 300);
                },
            });
        }
    } catch (err) {
        // flatpickr init failed — date picker falls back to hidden input only
    }

})();
