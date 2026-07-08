// Popup chọn ngôn ngữ — hiện ở trang chủ lần đầu, nhớ lựa chọn bằng localStorage
(function () {
  'use strict';

  var STORAGE_KEY = 'tsh_lang';

  function getStored() {
    try {
      return localStorage.getItem(STORAGE_KEY);
    } catch (e) {
      return null;
    }
  }

  function setStored(code) {
    try {
      localStorage.setItem(STORAGE_KEY, code);
    } catch (e) {
      /* private mode: bỏ qua, không nhớ được */
    }
  }

  function closeLang(modal) {
    if (window.modalClose) {
      window.modalClose('lang');
    } else {
      modal.classList.remove('is-active');
      document.body.classList.remove('no-scroll');
    }
  }

  function openLang(modal) {
    if (window.modalOpen) {
      window.modalOpen('lang');
    } else {
      modal.classList.add('is-active');
      document.body.classList.add('no-scroll');
    }
  }

  function init() {
    var modal = document.querySelector('[data-modal="lang"]');
    if (!modal) return; // không ở trang chủ / WPML tắt

    // Lưu lựa chọn khi bấm 1 ngôn ngữ
    var links = modal.querySelectorAll('.js-lang-choice');
    Array.prototype.forEach.call(links, function (link) {
      link.addEventListener('click', function (e) {
        setStored(link.getAttribute('data-lang-code') || 'vi');
        // Ngôn ngữ đang xem: không cần reload, chỉ đóng cho mượt
        if (link.getAttribute('data-lang-active') === '1') {
          e.preventDefault();
          closeLang(modal);
        }
        // Ngôn ngữ khác: để link tự điều hướng (vd EN → /en/)
      });
    });

    if (getStored()) return; // đã hỏi rồi → không mở lại

    setStored('vi'); // mặc định: dù đóng kiểu nào cũng không hỏi lại
    openLang(modal);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
