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

    // Copy thông tin chuyển khoản ngân hàng
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.tsh-copy-btn');
        if (!btn) return;
        var val = btn.closest('.tsh-bacs-qr__val');
        var strong = val ? val.querySelector('strong') : null;
        var text = strong ? strong.textContent.trim() : '';
        if (!text || text === '—') return;
        function showCopied() {
            btn.classList.add('is-copied');
            setTimeout(function () { btn.classList.remove('is-copied'); }, 1500);
        }
        function fallback() {
            var ta = document.createElement('textarea');
            ta.value = text;
            ta.style.position = 'fixed';
            ta.style.opacity = '0';
            document.body.appendChild(ta);
            ta.focus();
            ta.select();
            try { document.execCommand('copy'); } catch (err) {}
            document.body.removeChild(ta);
        }
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(showCopied, function () { fallback(); showCopied(); });
        } else {
            fallback();
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

   // Sau khi CF7 submit thành công: redirect sang WC checkout nếu form nằm trong wrapper có data-buy-url
   document.addEventListener('wpcf7mailsent', function (e) {
      var wrapper = e.target.closest('[data-buy-url]');
      if (!wrapper) return;
      var url = wrapper.getAttribute('data-buy-url');
      if (url) window.location.href = url;
   }, false);

   // Chỉ hiển thị lỗi CF7 khi submit, không hiện lại khi user đang nhập/thay đổi field
   (function () {
      function initCf7SubmitOnlyErrors(form) {
         var submitting = false;

         // Đánh dấu đang trong quá trình submit
         form.addEventListener('submit', function () { submitting = true; });

         // Sau khi lỗi được hiện (wpcf7:invalid), tắt cờ submit sau 600ms
         // (đủ để CF7 insert tất cả error tips vào DOM)
         form.addEventListener('wpcf7:invalid', function () {
            setTimeout(function () { submitting = false; }, 600);
         });

         // Khi user thay đổi bất kỳ field nào → xoá lỗi của field đó
         function clearFieldError(e) {
            var wrap = e.target.closest('.wpcf7-form-control-wrap');
            if (!wrap) return;
            wrap.classList.remove('wpcf7-not-valid');
            var tip = wrap.querySelector('.wpcf7-not-valid-tip');
            if (tip) tip.remove();
         }
         form.addEventListener('input', clearFieldError);
         form.addEventListener('change', clearFieldError);

         // Chặn CF7 thêm lỗi mới trong live-validation (sau khi submit window đóng)
         new MutationObserver(function (mutations) {
            if (submitting) return;
            mutations.forEach(function (mut) {
               mut.addedNodes.forEach(function (node) {
                  if (node.nodeType === 1 && node.classList && node.classList.contains('wpcf7-not-valid-tip')) {
                     node.remove();
                  }
               });
               if (mut.type === 'attributes' && mut.target.classList.contains('wpcf7-form-control-wrap')) {
                  var oldVal = mut.oldValue || '';
                  if (!oldVal.includes('wpcf7-not-valid') && mut.target.classList.contains('wpcf7-not-valid')) {
                     mut.target.classList.remove('wpcf7-not-valid');
                  }
               }
            });
         }).observe(form, {
            childList: true,
            subtree: true,
            attributes: true,
            attributeFilter: ['class'],
            attributeOldValue: true,
         });
      }

      // Init ngay cho các form đã có trong DOM
      document.querySelectorAll('.wpcf7-form').forEach(initCf7SubmitOnlyErrors);

      // Init cho các form được render sau (lazy/shortcode)
      new MutationObserver(function (muts) {
         muts.forEach(function (m) {
            m.addedNodes.forEach(function (n) {
               if (n.nodeType !== 1) return;
               if (n.classList && n.classList.contains('wpcf7-form')) initCf7SubmitOnlyErrors(n);
               n.querySelectorAll && n.querySelectorAll('.wpcf7-form').forEach(initCf7SubmitOnlyErrors);
            });
         });
      }).observe(document.body, { childList: true, subtree: true });
   })();
});