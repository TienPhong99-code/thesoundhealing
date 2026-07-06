(function () {
  'use strict';
  var btn = document.getElementById('tsh-eticket-btn');
  var voucher = document.querySelector('.tsh-voucher');
  if (!btn || !voucher || typeof html2canvas === 'undefined') return;

  btn.addEventListener('click', function () {
    var original = btn.textContent;
    btn.disabled = true;
    btn.textContent = 'Đang tạo...';

    html2canvas(voucher, { scale: 2, useCORS: true, backgroundColor: '#ffffff' })
      .then(function (canvas) {
        canvas.toBlob(function (blob) {
          var url = URL.createObjectURL(blob);
          var a = document.createElement('a');
          a.href = url;
          a.download = 'e-ticket-' + (btn.getAttribute('data-order') || 'voucher') + '.png';
          document.body.appendChild(a);
          a.click();
          document.body.removeChild(a);
          URL.revokeObjectURL(url);
          btn.disabled = false;
          btn.textContent = original;
        }, 'image/png');
      })
      .catch(function () {
        btn.disabled = false;
        btn.textContent = original;
        alert('Không tạo được e-ticket, vui lòng thử lại.');
      });
  });
})();
