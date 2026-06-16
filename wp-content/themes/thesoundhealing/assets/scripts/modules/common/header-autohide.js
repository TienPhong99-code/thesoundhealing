// =============================================
// Auto-hide header khi cuộn — CHỈ trong in-app browser
// ---------------------------------------------
//  - Cuộn XUỐNG  -> ẩn header (.hd thêm class .is-hidden: opacity 0 + pointer-events none)
//  - Nhích LÊN   -> hiện lại ngay
//  - Gần đỉnh trang -> luôn hiện
//
//  Kích hoạt khi phát hiện in-app browser trên iOS (Telegram, FB, IG, Zalo, TikTok...).
//  Android, Safari/Chrome/Firefox thường và PWA standalone -> KHÔNG đụng tới.
// =============================================
(function () {
   'use strict';

   var header = document.querySelector('.hd');
   if (!header) return;

   // ---- Phát hiện in-app browser ----
   function isInAppBrowser() {
      var ua = navigator.userAgent || '';

      // Không auto-hide trên Android -> loại sớm
      if (/Android/i.test(ua)) return false;

      // (1) Token UA của các app phổ biến
      if (/FBAN|FBAV|FB_IAB|Instagram|Line\/|Twitter|MicroMessenger|TikTok|musical_ly|BytedanceWebview|Snapchat|LinkedInApp|Pinterest|KAKAOTALK|Zalo|WhatsApp|Viber|Discord|Slack|GSA\/|Telegram/i.test(ua)) {
         return true;
      }

      // (2) Cầu nối JS mà Telegram tiêm vào webview -> chắc chắn là Telegram
      if (window.TelegramWebviewProxy || window.TelegramWebviewProxyProto) {
         return true;
      }

      // (3) iOS: tách webview nhúng khỏi Safari thật bằng navigator.standalone
      //     Safari tab : standalone === false
      //     PWA add-home: standalone === true
      //     WKWebView nhúng (Telegram in-app): standalone === undefined
      if (/iPhone|iPod|iPad/.test(ua)) {
         if (/CriOS|FxiOS|EdgiOS|OPiOS|mercury/i.test(ua)) return false; // trình duyệt iOS khác
         var sa = window.navigator.standalone;
         if (sa === true || sa === false) return false; // Safari thật / PWA -> bỏ qua
         return true; // undefined -> webview nhúng
      }

      return false;
   }

   if (!isInAppBrowser()) return;

   var HIDDEN = 'is-hidden';
   var TOP_GUARD = 80; // px: trong vùng này gần đỉnh thì luôn hiện
   var DEADZONE = 2;   // px: bỏ qua rung nhẹ (không tính là cuộn)

   var lastY = window.pageYOffset || 0;
   var raf = null;

   function onFrame() {
      raf = null;
      var y = window.pageYOffset || 0;
      var diff = y - lastY;

      if (Math.abs(diff) < DEADZONE) return; // rung nhẹ -> giữ nguyên, gom tiếp ở frame sau

      if (y <= TOP_GUARD) {
         header.classList.remove(HIDDEN); // gần đỉnh -> luôn hiện
      } else if (diff > 0) {
         header.classList.add(HIDDEN); // cuộn xuống -> ẩn
      } else {
         header.classList.remove(HIDDEN); // nhích lên -> hiện
      }

      lastY = y;
   }

   window.addEventListener(
      'scroll',
      function () {
         if (raf === null) raf = window.requestAnimationFrame(onFrame);
      },
      { passive: true }
   );
})();
