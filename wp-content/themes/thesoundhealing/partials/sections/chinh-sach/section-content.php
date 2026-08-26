<?php

/**
 * Render trang "Chính sách & Điều khoản": hero + mục lục neo + 5 mục.
 *
 * Nội dung lấy từ inc/data/chinh-sach.php, chọn theo ngôn ngữ hiện tại (WPML).
 * Cùng một template dùng cho cả trang VN lẫn trang EN — tạo 2 Page trong
 * wp-admin, gán template này, WPML nối 2 trang là xong.
 */

defined('ABSPATH') || exit;

$_cs_all = require MONA_THEME_INC_PATH . '/data/chinh-sach.php';

// WPML đặt ICL_LANGUAGE_CODE; không có WPML thì rơi về locale của WordPress.
$_cs_lang = defined('ICL_LANGUAGE_CODE')
    ? (string) ICL_LANGUAGE_CODE
    : substr((string) determine_locale(), 0, 2);

$cs = $_cs_all[$_cs_lang] ?? $_cs_all['vi'];
?>

<section class="sec-cs py-(--pd-sc)">
    <div class="container">

        <!-- Hero -->
        <div class="flex flex-col items-center text-center gap-6 max-w-[768px] mx-auto">
            <h1 class="font-title text-pri text-[56px] font-normal tracking-[-1.12px] leading-[64px] max-md:text-[40px] max-md:leading-[48px]">
                <?php echo esc_html($cs['title']); ?>
            </h1>
            <p class="text-[#414847] text-[18px] leading-[28px]">
                <?php echo esc_html($cs['intro']); ?>
            </p>
        </div>

        <div class="max-w-[860px] mx-auto mt-12 max-md:mt-8">

            <!-- Mục lục neo -->
            <nav class="rounded-[--rs] border border-pri/25 bg-pri/5 p-6 max-md:p-4" aria-label="<?php echo esc_attr($cs['toc_label']); ?>">
                <p class="font-title text-pri text-[20px] leading-[28px] mb-3">
                    <?php echo esc_html($cs['toc_label']); ?>
                </p>
                <ol class="list-decimal pl-5 flex flex-col gap-2 text-[16px] leading-[24px]">
                    <?php foreach ($cs['sections'] as $sec) : ?>
                        <li>
                            <a class="text-[#414847] underline underline-offset-4 hover:text-pri transition-colors"
                                href="#<?php echo esc_attr($sec['id']); ?>">
                                <?php
                                // Bỏ tiền tố "1. " vì <ol> đã tự đánh số.
                                echo esc_html(preg_replace('/^\d+\.\s*/', '', $sec['title']));
                                ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </nav>

            <!-- 5 mục nội dung -->
            <?php foreach ($cs['sections'] as $sec) : ?>
                <article id="<?php echo esc_attr($sec['id']); ?>" class="mt-12 scroll-mt-[calc(var(--size-hd)+24px)] max-md:mt-8">
                    <h2 class="font-title text-pri text-[32px] leading-[40px] pb-3 border-b border-pri/25 max-md:text-[26px] max-md:leading-[34px]">
                        <?php echo esc_html($sec['title']); ?>
                    </h2>

                    <?php foreach ($sec['blocks'] as $block) : ?>
                        <div class="mt-6 max-md:mt-5">
                            <h3 class="text-sec text-[18px] font-semibold leading-[26px] mb-2">
                                <?php echo esc_html($block['h']); ?>
                            </h3>

                            <?php foreach ($block['parts'] as [$type, $content]) : ?>
                                <?php if ($type === 'p') : ?>
                                    <p class="text-[#414847] text-[16px] leading-[26px] mb-2 last:mb-0">
                                        <?php echo esc_html($content); ?>
                                    </p>

                                <?php elseif ($type === 'ul') : ?>
                                    <ul class="list-disc pl-5 flex flex-col gap-1.5 text-[#414847] text-[16px] leading-[26px] mb-2 last:mb-0">
                                        <?php foreach ($content as $li) : ?>
                                            <li><?php echo esc_html($li); ?></li>
                                        <?php endforeach; ?>
                                    </ul>

                                <?php elseif ($type === 'ol') : ?>
                                    <ol class="list-decimal pl-5 flex flex-col gap-1.5 text-[#414847] text-[16px] leading-[26px] mb-2 last:mb-0">
                                        <?php foreach ($content as $li) : ?>
                                            <li><?php echo esc_html($li); ?></li>
                                        <?php endforeach; ?>
                                    </ol>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </article>
            <?php endforeach; ?>

            <!-- Ghi chú cập nhật -->
            <p class="mt-12 pt-6 border-t border-pri/25 text-[#414847] text-[15px] italic leading-[24px] max-md:mt-8">
                <?php echo esc_html($cs['note']); ?>
            </p>

        </div>
    </div>
</section>
