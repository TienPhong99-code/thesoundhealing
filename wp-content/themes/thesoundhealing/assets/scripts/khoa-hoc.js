(function ($) {
    function injectCourseData() {
        if (typeof kh_course === 'undefined') return;
        $('input[name="course_id"]').val(kh_course.id);
        $('input[name="course_name"]').val(kh_course.name);
    }

    function initSubmitLoading() {
        var form = document.querySelector('.cf7-khoa-hoc .wpcf7-form');
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
        injectCourseData();
        initSubmitLoading();
    });

    // Re-inject sau khi CF7 reset (validation fail, spam)
    $(document).on('wpcf7:invalid wpcf7:spam', injectCourseData);
})(jQuery);
