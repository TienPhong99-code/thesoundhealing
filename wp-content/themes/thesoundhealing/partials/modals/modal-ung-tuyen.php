<?php

/**
 * Modal: Nộp đơn ứng tuyển
 * Trigger: <button data-modal-open="ung-tuyen">
 *
 * Yêu cầu: tạo 1 form CF7 đặt tên "Ứng tuyển" trong WP Admin > Contact.
 * Fields CF7 cần có:
 *   [text* ho-ten placeholder "Nguyễn Văn An"]
 *   [email* email placeholder "Nhập email"]
 *   [tel* dien-thoai placeholder "Nhập số điện thoại của bạn"]
 *   [textarea gioi-thieu placeholder "Giới thiệu về bản thân..."]
 *   [file cv limit:3mb filetypes:pdf]
 *   [hidden vi-tri "Vị trí ứng tuyển"]
 *   [submit "Ứng tuyển ngay"]
 */

defined('ABSPATH') || exit;

$form = get_page_by_title('Ứng tuyển', OBJECT, 'wpcf7_contact_form');
?>

<div data-modal="ung-tuyen"
    class="modal-overlay fixed inset-0 z-[9999] flex items-center justify-center p-4">
    <div class="modal-box bg-white w-full max-w-[620px] max-h-[90dvh] overflow-y-auto p-4 rounded-lg relative flex flex-col gap-2">

        <!-- Close -->
        <button type="button" data-modal-close
            class="cursor-pointer absolute top-4 right-4 w-8 h-8 flex items-center justify-center text-[#808080] hover:text-pri transition-colors"
            aria-label="<?php esc_attr_e('Đóng', 'monamedia'); ?>">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                <path d="M18 6 6 18M6 6l12 12" />
            </svg>
        </button>

        <h2 class="text-[24px] font-bold text-pri pr-8"><?php esc_html_e('Nộp đơn ứng tuyển', 'monamedia'); ?></h2>

        <?php if ($form) : ?>
            <div class="cf7-ung-tuyen">
                <?php echo do_shortcode('[contact-form-7 id="' . esc_attr($form->ID) . '"]'); ?>
            </div>
        <?php else : ?>
            <p class="text-[14px] text-[#808080]">
                Chưa cấu hình form. Vào <strong>WP Admin → Contact → Add New</strong>,
                đặt tên <strong>"Ứng tuyển"</strong> và thêm các field theo hướng dẫn.
            </p>
        <?php endif; ?>

    </div>
</div>