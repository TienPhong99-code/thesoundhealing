<?php
defined('ABSPATH') || exit;
?>

<div data-modal="share"
    class="modal-overlay fixed inset-0 z-[9999] flex items-center justify-center p-4">
    <div class="modal-box bg-white w-full max-w-[400px] p-6 rounded-2xl relative flex flex-col gap-5">

        <!-- Close -->
        <button type="button" data-modal-close
            class="cursor-pointer absolute top-4 right-4 w-8 h-8 flex items-center justify-center text-[#808080] hover:text-[#1b1c19] transition-colors"
            aria-label="Đóng">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                <path d="M18 6 6 18M6 6l12 12" />
            </svg>
        </button>

        <!-- Header -->
        <div class="flex flex-col gap-1.5 pr-8">
            <h2 class="text-[22px] font-bold text-[#1b1c19]">Chia sẻ</h2>
            <p class="text-[14px] text-[#555] leading-[1.5]">
                Chia sẻ sự kiện để lan tỏa trải nghiệm và xây dựng mạng lưới giới thiệu để nhận ngay ưu đãi!
            </p>
        </div>

        <!-- Social icons -->
        <div class="flex items-center gap-6">

            <a href="#" target="_blank" rel="noopener noreferrer"
                class="share-fb-link flex flex-col items-center gap-2 group">
                <span class="w-12 h-12 rounded-full bg-[#1b1c19] flex items-center justify-center transition-opacity group-hover:opacity-80">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="white">
                        <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
                    </svg>
                </span>
                <span class="text-[12px] text-[#414847]">Facebook</span>
            </a>

            <a href="#" target="_blank" rel="noopener noreferrer"
                class="share-x-link flex flex-col items-center gap-2 group">
                <span class="w-12 h-12 rounded-full bg-[#1b1c19] flex items-center justify-center transition-opacity group-hover:opacity-80">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="white">
                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.737-8.844L1.999 2.25H8.51l4.258 5.63zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                    </svg>
                </span>
                <span class="text-[12px] text-[#414847]">X</span>
            </a>

            <a href="#" target="_blank" rel="noopener noreferrer"
                class="share-li-link flex flex-col items-center gap-2 group">
                <span class="w-12 h-12 rounded-full bg-[#1b1c19] flex items-center justify-center transition-opacity group-hover:opacity-80">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="white">
                        <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z" />
                        <rect x="2" y="9" width="4" height="12" />
                        <circle cx="4" cy="4" r="2" />
                    </svg>
                </span>
                <span class="text-[12px] text-[#414847]">LinkedIn</span>
            </a>

        </div>

        <!-- Divider -->
        <div class="flex flex-col gap-3">
            <p class="text-[13px] text-[#414847]">Chia sẻ đường dẫn với mã giới thiệu của bạn</p>

            <button type="button"
                class="share-copy-btn w-full py-3 px-4 rounded-xl border border-[#c0c8c6] text-[14px] font-medium text-[#1b1c19] hover:bg-[#f5f4f1] transition-colors cursor-pointer">
                Sao Chép Đường Dẫn
            </button>

            <button type="button"
                class="share-qr-btn w-full py-3 px-4 rounded-xl border border-[#c0c8c6] text-[14px] font-medium text-[#1b1c19] hover:bg-[#f5f4f1] transition-colors cursor-pointer">
                Tải Mã QR
            </button>
        </div>

    </div>
</div>