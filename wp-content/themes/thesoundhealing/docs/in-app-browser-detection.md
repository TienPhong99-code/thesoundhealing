# Phát hiện In-App Browser & Auto-hide Header

Tài liệu mô tả cách module [`header-autohide.js`](../assets/scripts/modules/common/header-autohide.js) nhận diện thiết bị/trình duyệt để **chỉ** bật hành vi auto-hide header trong **in-app browser** (Telegram, Facebook, Instagram, Zalo…), không đụng tới Safari/Chrome thường.

## 1. Mục đích

Trong in-app browser trên iOS ≥ 26, thanh URL của app chiếm chỗ phía trên gây cảm giác "hở" header. Hướng xử lý cuối cùng: **ẩn header khi cuộn xuống, hiện lại khi cuộn lên** — nhưng chỉ áp dụng trong in-app browser.

- Cuộn **xuống** → `.hd` thêm class `.is-hidden` (`opacity: 0` + `pointer-events: none`).
- Nhích **lên** → bỏ class, hiện lại ngay.
- Gần đỉnh trang (≤ 80px) → luôn hiện.

> ⚠️ **Không có cách 100%** để nhận diện in-app browser. Telegram không có token User-Agent riêng. Logic dưới đây là **best-effort**, ghép nhiều tín hiệu để đạt độ chính xác cao nhất.

## 2. Bốn tầng nhận diện (`isInAppBrowser()`)

> 🚫 **Android bị loại sớm:** ngay đầu hàm, nếu UA chứa `Android` → trả về `false`. Auto-hide **chỉ chạy trên iOS**, mọi in-app browser trên Android đều bỏ qua.

Sau bước loại Android, hàm trả về `true` ngay khi khớp một trong các tầng, theo thứ tự:

### Tầng 1 — Token User-Agent của app
Nhiều app nhúng tên mình vào UA. Khớp regex là chắc chắn in-app:

```
FBAN, FBAV, FB_IAB        → Facebook
Instagram                 → Instagram
Zalo                      → Zalo
Line/                     → Line
MicroMessenger            → WeChat
TikTok, musical_ly, BytedanceWebview → TikTok
Twitter, Snapchat, LinkedInApp, Pinterest, KAKAOTALK,
WhatsApp, Viber, Discord, Slack, GSA/, Telegram
```

### Tầng 2 — Cầu nối JS của Telegram
Telegram tiêm object bridge vào webview. Nếu tồn tại → chắc chắn Telegram:

```js
window.TelegramWebviewProxy || window.TelegramWebviewProxyProto
```

### Tầng 3 — iOS: dựa vào `navigator.standalone`
Đây là tầng quan trọng nhất để tách **Telegram in-app** khỏi **Safari thật** (vì UA của chúng có thể giống hệt nhau).

Thuộc tính `navigator.standalone` **chỉ Safari trên iOS định nghĩa**:

| Môi trường (iOS) | `navigator.standalone` | Kết luận |
|---|---|---|
| Safari (tab thường) | `false` | **Không** in-app → bỏ qua |
| PWA (add-to-home screen) | `true` | Không in-app → bỏ qua |
| **WKWebView nhúng** (Telegram in-app…) | `undefined` | **In-app** → bật auto-hide |

Trước khi xét `standalone`, loại bỏ các trình duyệt iOS chuyên dụng qua UA (chúng cũng là WKWebView nhưng KHÔNG phải in-app):

```
CriOS  → Chrome    FxiOS → Firefox
EdgiOS → Edge      OPiOS → Opera     mercury → Mercury
```

Tóm tắt logic nhánh iOS:

```js
if (/iPhone|iPod|iPad/.test(ua)) {
  if (/CriOS|FxiOS|EdgiOS|OPiOS|mercury/i.test(ua)) return false; // trình duyệt khác
  var sa = window.navigator.standalone;
  if (sa === true || sa === false) return false; // Safari thật / PWA
  return true;                                   // undefined -> webview nhúng
}
```

## 3. Bảng kết quả tổng hợp

| Trình duyệt | Tầng khớp | Auto-hide |
|---|---|---|
| Telegram in-app (iOS) | 2 hoặc 3 (`standalone === undefined`) | ✅ |
| **Mọi in-app browser trên Android** | — (loại sớm) | ❌ |
| Facebook / Instagram / Zalo / TikTok… (iOS) | 1 (token UA) | ✅ |
| Safari mobile thường (iOS) | — (`standalone === false`) | ❌ |
| Chrome / Firefox iOS | — (UA `CriOS`/`FxiOS`) | ❌ |
| PWA standalone | — (`standalone === true`) | ❌ |
| Desktop (mọi trình duyệt) | — | ❌ |

## 4. Giới hạn đã biết

- **Telegram qua SFSafariViewController:** nếu Telegram mở link bằng `SFSafariViewController` thay vì in-app browser riêng, UA **và** `navigator.standalone` đều giống Safari thật → **không phân biệt được**. Nhưng khi đó cũng không có thanh URL kiểu Telegram (hành vi y hệt Safari) nên không phải vấn đề.
- **App mới / hiếm:** app nào không có token UA và không phải WKWebView trên iOS có thể lọt lưới. Bổ sung token vào regex Tầng 1 khi cần.
- Nhận diện là **heuristic**, không bảo đảm tuyệt đối.

## 5. Thông số tinh chỉnh (trong `header-autohide.js`)

| Biến | Mặc định | Ý nghĩa |
|---|---|---|
| `TOP_GUARD` | `80` | px gần đỉnh trang luôn hiện header |
| `DEADZONE` | `2` | px bỏ qua rung nhẹ khi cuộn (tăng nếu nhấp nháy) |
| Tốc độ fade | `0.3s` | sửa `opacity 0.3s ease` trong `.hd` ở `assets/css/style.css` |

## 6. File liên quan

| File | Vai trò |
|---|---|
| `assets/scripts/modules/common/header-autohide.js` | Logic nhận diện + auto-hide |
| `assets/css/style.css` (`.hd`, `.hd.is-hidden`) | Transition opacity + trạng thái ẩn |
| `inc/hooks/CommonHook.php` | Enqueue script (`mona-header-autohide`) |
