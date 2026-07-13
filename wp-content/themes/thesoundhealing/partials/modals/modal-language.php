<?php
defined('ABSPATH') || exit;

/**
 * Popup chọn ngôn ngữ (hiện ở trang chủ, lần đầu).
 * Chỉ render khi WPML bật và có >= 2 ngôn ngữ.
 */
if (!function_exists('icl_get_languages')) {
    return;
}

$mona_langs = icl_get_languages('skip_missing=0&orderby=KEY&order=DIR');
if (empty($mona_langs) || !is_array($mona_langs) || count($mona_langs) < 2) {
    return;
}

$mona_lang_names = ['vi' => 'Tiếng Việt', 'en' => 'English'];
?>

<div data-modal="lang"
    class="modal-overlay fixed inset-0 z-[9999] flex items-center justify-center p-4">
    <div class="modal-box bg-white w-full max-w-[430px] max-md:p-4 p-6 rounded-2xl max-md:rounded-lg relative flex flex-col gap-5">

        <!-- Close -->
        <button type="button" data-modal-close
            class="cursor-pointer absolute top-4 right-4 w-8 h-8 flex items-center justify-center text-[#808080] hover:text-[#1b1c19] transition-colors"
            aria-label="<?php echo esc_attr__('Đóng', 'monamedia'); ?>">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                <path d="M18 6 6 18M6 6l12 12" />
            </svg>
        </button>

        <!-- Header -->
        <div class="flex flex-col gap-1.5 pr-8">
            <h2 class="text-[22px] max-md:text-[16px] font-bold text-[#1b1c19]">
                <?php esc_html_e('Chọn ngôn ngữ', 'monamedia'); ?>
                <span class="text-[#808080] font-normal"> / Select language</span>
            </h2>
        </div>

        <!-- Options -->
        <div class="flex flex-col gap-3">
            <?php foreach ($mona_langs as $mona_lang) :
                $mona_code      = $mona_lang['language_code'];
                $mona_label     = $mona_lang_names[$mona_code] ?? $mona_lang['native_name'];
                $mona_is_active = !empty($mona_lang['active']);
            ?>
                <a href="<?php echo esc_url($mona_lang['url']); ?>"
                    data-lang-code="<?php echo esc_attr($mona_code); ?>"
                    data-lang-active="<?php echo $mona_is_active ? '1' : '0'; ?>"
                    class="js-lang-choice flex items-center justify-between w-full py-3 px-4 rounded-xl border text-[15px] font-medium transition-colors <?php echo $mona_is_active ? 'border-[#c2a056] bg-[#faf7ef] text-[#1b1c19]' : 'border-[#c0c8c6] text-[#1b1c19] hover:bg-[#f5f4f1]'; ?>">
                    <span><?php echo esc_html($mona_label); ?></span>
                    <?php if ($mona_is_active) : ?>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#c2a056" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 6 9 17l-5-5" />
                        </svg>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>

    </div>
</div>