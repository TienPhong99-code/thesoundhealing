$(document).ready(function () {
    initTocScroll();
    initTocScrollspy();
});

function initTocScroll() {
    $(document).on('click', 'a[href^="#"]', function (e) {
        const hash = $(this).attr('href');
        if (!hash || hash === '#') return;

        const target = document.querySelector(hash);
        if (!target) return;

        e.preventDefault();

        if (window.lenis) {
            window.lenis.scrollTo(target, { offset: -100 });
        } else {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
}

function initTocScrollspy() {
    const tocLinks = document.querySelectorAll('#ez-toc-container .ez-toc-list a');
    if (!tocLinks.length) return;

    const headings = Array.from(tocLinks).map(function (a) {
        const id = decodeURIComponent(a.getAttribute('href').replace('#', ''));
        return document.getElementById(id);
    }).filter(Boolean);

    if (!headings.length) return;

    function setActive(activeEl) {
        tocLinks.forEach(function (a) {
            const li = a.closest('li');
            const id = decodeURIComponent(a.getAttribute('href').replace('#', ''));
            if (li) li.classList.toggle('active', id === activeEl.id);
        });
    }

    function onScroll() {
        const scrollY = window.scrollY + 120;
        let current = headings[0];

        headings.forEach(function (h) {
            if (h.offsetTop <= scrollY) current = h;
        });

        setActive(current);
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
}
