// =============================================
// Share modal
// =============================================
(function () {
    function updateShareModal(url, title) {
        var modal = document.querySelector('[data-modal="share"]');
        if (!modal) return;
        var enc = encodeURIComponent;
        var fb = modal.querySelector('.share-fb-link');
        var xLink = modal.querySelector('.share-x-link');
        var li = modal.querySelector('.share-li-link');
        var copyBtn = modal.querySelector('.share-copy-btn');
        var qrBtn = modal.querySelector('.share-qr-btn');
        if (fb) fb.href = 'https://www.facebook.com/sharer/sharer.php?u=' + enc(url);
        if (xLink) xLink.href = 'https://twitter.com/intent/tweet?url=' + enc(url) + '&text=' + enc(title);
        if (li) li.href = 'https://www.linkedin.com/sharing/share-offsite/?url=' + enc(url);
        if (copyBtn) copyBtn.setAttribute('data-copy-url', url);
        if (qrBtn) qrBtn.setAttribute('data-qr-url', url);
    }

    document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-share-url]');
        if (!btn) return;
        updateShareModal(
            btn.getAttribute('data-share-url') || window.location.href,
            btn.getAttribute('data-share-title') || document.title
        );
    });

    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.share-copy-btn');
        if (!btn) return;
        var url = btn.getAttribute('data-copy-url') || window.location.href;
        var orig = btn.textContent;
        function showCopied() {
            btn.textContent = 'Đã sao chép!';
            setTimeout(function () { btn.textContent = orig; }, 2000);
        }
        if (navigator.clipboard) {
            navigator.clipboard.writeText(url).then(showCopied).catch(showCopied);
        } else {
            var ta = document.createElement('textarea');
            ta.value = url;
            document.body.appendChild(ta);
            ta.select();
            document.execCommand('copy');
            document.body.removeChild(ta);
            showCopied();
        }
    });

    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.share-qr-btn');
        if (!btn) return;
        var url = btn.getAttribute('data-qr-url') || window.location.href;
        var qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' + encodeURIComponent(url);
        fetch(qrUrl)
            .then(function (r) { return r.blob(); })
            .then(function (blob) {
                var a = document.createElement('a');
                a.href = URL.createObjectURL(blob);
                a.download = 'qr-code.png';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(a.href);
            })
            .catch(function () { window.open(qrUrl, '_blank'); });
    });
})();

gsap.registerPlugin(ScrollTrigger);
gsap.ticker.lagSmoothing(0);

$(document).ready(function () {
   function functionSlider(selector, options = {}, pagiType = 'bullets') {
   const wrappers = document.querySelectorAll(selector);
   if (!wrappers.length) return;

   wrappers.forEach((wrap) => {
      const swiper = wrap.querySelector('.swiper');
      const pagi   = wrap.querySelector('.swiper-pagination');
      const next   = wrap.querySelector('.swiper-next');
      const prev   = wrap.querySelector('.swiper-prev');
      if (!swiper) return;

      new Swiper(swiper, {
         watchSlidesProgress: true,
         pagination: { el: pagi, type: pagiType, clickable: true },
         navigation:  { nextEl: next, prevEl: prev },
         ...options,
      });
   });
}
   // =============================================
   // Mobile nav drawer
   // =============================================
   const $nav      = $('#hd-nav');
   const $backdrop = $('<div class="hd-nav-backdrop"></div>').appendTo('body');

   function openNav() {
      $nav.addClass('is-open').attr('aria-hidden', 'false');
      $backdrop.addClass('is-open');
      $('body').addClass('no-scroll');
   }
   function closeNav() {
      $nav.removeClass('is-open').attr('aria-hidden', 'true');
      $backdrop.removeClass('is-open');
      $('body').removeClass('no-scroll');
   }

   $(document).on('click', '.js-nav-open', openNav);
   $(document).on('click', '.js-nav-close', closeNav);
   $backdrop.on('click', closeNav);
   $(document).on('keydown', function (e) {
      if (e.key === 'Escape') closeNav();
   });

   // Mobile dropdown toggle — click icon only, link still navigates
   $(document).on('click', '#hd-nav .dd-toggle', function () {
      $(this).closest('.dropdown').toggleClass('is-open');
   });

   // =============================================
   // Header sticky on scroll
   // =============================================
   const $hd = $('.hd');
   $(window).on('scroll', function () {
      if ($(this).scrollTop() > 100) {
         $hd.addClass('hd-sticky');
      } else {
         $hd.removeClass('hd-sticky');
      }
   });

   functionSlider('.slideSw', {
      speed: 1200,
      loop: false,
      slidesPerView: 'auto',
      autoplay: { delay: 2600 },
   });

   // =============================================
   // Feedback slider
   // =============================================
   functionSlider('.feedback-slider', {
      speed: 800,
      loop: true,
      slidesPerView: 1,
      spaceBetween: 24,
      autoplay: { delay: 4000, disableOnInteraction: false },
      breakpoints: {
         768:  { slidesPerView: 2 },
         1024: { slidesPerView: 3 },
      },
   });

   // =============================================
   // CF7 lien-he — submit loading state
   // =============================================
   (function () {
      var form = document.querySelector('.cf7-lien-he .wpcf7-form');
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
   })();

   // =============================================
   // Gallery trigger — mở Fancybox từ nút "Xem tất cả ảnh"
   // =============================================
   document.querySelectorAll('[data-gallery-trigger]').forEach(function (btn) {
      btn.addEventListener('click', function () {
         var id = btn.getAttribute('data-gallery-trigger');
         var first = document.querySelector('[data-fancybox="' + id + '"]');
         if (first) first.click();
      });
   });
});