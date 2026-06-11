// Generic modal system — data-modal-open="id" / data-modal="id" / data-modal-close
(function () {
  'use strict';

  function isSubmitting(modal) {
    return modal && modal.dataset.submitting === '1';
  }

  function openModal(id) {
    var modal = document.querySelector('[data-modal="' + id + '"]');
    if (!modal) return;
    modal.classList.add('is-active');
    document.body.classList.add('no-scroll');
  }

  function closeModal(target) {
    var modal;
    if (typeof target === 'string') {
      modal = document.querySelector('[data-modal="' + target + '"]');
    } else if (target instanceof Element) {
      modal = target.closest('[data-modal]') || target;
    } else {
      modal = document.querySelector('[data-modal].is-active');
    }
    if (!modal || isSubmitting(modal)) return;
    modal.classList.remove('is-active');
    document.body.classList.remove('no-scroll');
  }

  function unlockModal(modal) {
    if (!modal) return;
    delete modal.dataset.submitting;
    var wrap = modal.querySelector('.cf7-submit-wrap');
    if (wrap) wrap.classList.remove('is-loading');
  }

  function fillViTri(modalEl) {
    var viTri = (modalEl && modalEl.dataset.viTri) || '';
    var cf7Input = modalEl ? modalEl.querySelector('[name="vi-tri"]') : null;
    if (!cf7Input) return;
    cf7Input.value = viTri;
    cf7Input.defaultValue = viTri; // form.reset() khôi phục defaultValue → value tự giữ
  }

  document.addEventListener('click', function (e) {
    var openBtn = e.target.closest('[data-modal-open]');
    if (openBtn) {
      e.preventDefault();
      var modalId = openBtn.getAttribute('data-modal-open');
      openModal(modalId);

      var modal = document.querySelector('[data-modal="' + modalId + '"]');
      if (modal) modal.dataset.viTri = openBtn.getAttribute('data-vi-tri') || '';
      fillViTri(modal);

      return;
    }

    var closeBtn = e.target.closest('[data-modal-close]');
    if (closeBtn) {
      closeModal(closeBtn);
      return;
    }

    // Click on overlay backdrop (outside .modal-box)
    var overlay = e.target.closest('[data-modal]');
    if (overlay && !e.target.closest('.modal-box')) {
      closeModal(overlay);
    }
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeModal();
  });

  // CF7: native submit event — luôn fire, đáng tin hơn wpcf7submit
  document.addEventListener('submit', function (e) {
    var form = e.target;
    if (!form.classList.contains('wpcf7-form')) return;
    var modal = form.closest('[data-modal]');
    if (!modal) return;
    modal.dataset.submitting = '1';
    var wrap = modal.querySelector('.cf7-submit-wrap');
    if (wrap) wrap.classList.add('is-loading');
  });

  // CF7: MutationObserver — watch form class, remove loading khi CF7 xóa class "submitting"
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-modal] .wpcf7-form').forEach(function (form) {
      new MutationObserver(function (mutations) {
        mutations.forEach(function (m) {
          if (m.attributeName !== 'class') return;
          if (form.classList.contains('submitting')) return;
          unlockModal(form.closest('[data-modal]'));
        });
      }).observe(form, { attributes: true });
    });
  });

  window.modalOpen  = openModal;
  window.modalClose = closeModal;
})();

// CF7 file input — hiện tên file khi chọn
(function () {
  document.addEventListener('change', function (e) {
    var input = e.target;
    if (!input.classList.contains('wpcf7-file')) return;

    var area = input.closest('.cf7-upload-area');
    if (!area) return;

    var textEl = area.querySelector('.cf7-upload-text');
    if (!textEl) return;

    var file = input.files && input.files[0];
    if (file) {
      textEl.innerHTML = '<strong>' + file.name + '</strong>';
      area.classList.add('has-file');
    } else {
      textEl.innerHTML = '<strong>Tải CV lên</strong> hoặc kéo thả vào đây';
      area.classList.remove('has-file');
    }
  });
})();
