<?php
defined('ABSPATH') || exit;

$page_id = get_queried_object_id();

$sample = [
    'address'       => "123 Đường Tĩnh Lặng, Phường Yên Bình\nQuận 1, Hồ Chí Minh",
    'phone'         => '+84 90 123 4567',
    'email'         => 'connect@aetheria.vn',
    'instagram_url' => '#',
    'facebook_url'  => '#',
    'cf7_shortcode' => '[contact-form-7 id="contact-form" title="Liên Hệ"]',
];

$data = [
    'address'       => get_field('lh_address', $page_id)       ?: $sample['address'],
    'phone'         => get_field('lh_phone', $page_id)         ?: $sample['phone'],
    'email'         => get_field('lh_email', $page_id)         ?: $sample['email'],
    'instagram_url' => get_field('lh_instagram_url', $page_id) ?: $sample['instagram_url'],
    'facebook_url'  => get_field('lh_facebook_url', $page_id)  ?: $sample['facebook_url'],
    'cf7_shortcode' => get_field('lh_cf7_shortcode', $page_id) ?: $sample['cf7_shortcode'],
];
?>

<section class="sec-lh-form section-pd">
    <div class="container">
        <div class="relative">
            <div class="row">

                <!-- Left: Contact Info -->
                <div class="col col-5 max-md:!w-full">
                    <div class="flex flex-col justify-center py-[48px]">
                        <div class="flex flex-col gap-8">

                            <!-- Heading -->
                            <div class="border-b border-[#e4e2dd] pb-4">
                                <h2 class="font-title text-sec text-[24px] font-normal leading-[32px]">
                                    Thông Tin Liên Hệ
                                </h2>
                            </div>

                            <!-- Info items -->
                            <div class="flex flex-col gap-6">

                                <!-- Address -->
                                <div class="flex gap-4 items-start">
                                    <div class="w-4 h-5 shrink-0 mt-[2px]">
                                        <img src="<?php echo esc_url(MONA_THEME_PATH_URI . '/assets/images/ic-contact-address.svg'); ?>"
                                            class="block w-full h-full object-contain" alt="">
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        <span class="text-[#414847] text-[12px] font-semibold uppercase tracking-[1.2px] leading-[16px]">Địa Chỉ</span>
                                        <p class="text-[#1b1c19] text-[16px] leading-[24px]">
                                            <?php echo nl2br(esc_html($data['address'])); ?>
                                        </p>
                                    </div>
                                </div>

                                <!-- Phone -->
                                <div class="flex gap-4 items-start">
                                    <div class="size-[18px] shrink-0 mt-[2px]">
                                        <img src="<?php echo esc_url(MONA_THEME_PATH_URI . '/assets/images/ic-contact-phone.svg'); ?>"
                                            class="block w-full h-full object-contain" alt="">
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        <span class="text-[#414847] text-[12px] font-semibold uppercase tracking-[1.2px] leading-[16px]">Điện Thoại</span>
                                        <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $data['phone'])); ?>"
                                            class="text-[#1b1c19] text-[16px] leading-[24px] hover:text-pri transition-colors">
                                            <?php echo esc_html($data['phone']); ?>
                                        </a>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="flex gap-4 items-start">
                                    <div class="w-5 h-4 shrink-0 mt-[4px]">
                                        <img src="<?php echo esc_url(MONA_THEME_PATH_URI . '/assets/images/ic-contact-email.svg'); ?>"
                                            class="block w-full h-full object-contain" alt="">
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        <span class="text-[#414847] text-[12px] font-semibold uppercase tracking-[1.2px] leading-[16px]">Email</span>
                                        <a href="mailto:<?php echo esc_attr($data['email']); ?>"
                                            class="text-[#1b1c19] text-[16px] leading-[24px] hover:text-pri transition-colors">
                                            <?php echo esc_html($data['email']); ?>
                                        </a>
                                    </div>
                                </div>

                            </div>

                            <!-- Social links -->
                            <div class="flex flex-col gap-4 pt-12">
                                <span class="text-[#414847] text-[12px] font-semibold uppercase tracking-[1.2px] leading-[16px]">Mạng Xã Hội</span>
                                <div class="flex gap-4">
                                    <?php if ($data['instagram_url']) : ?>
                                        <a href="<?php echo esc_url($data['instagram_url']); ?>"
                                            class="text-[#1b1c19] text-[12px] font-semibold tracking-[1.2px] underline leading-[16px] hover:text-pri transition-colors"
                                            target="_blank" rel="noopener noreferrer">
                                            Instagram
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($data['facebook_url']) : ?>
                                        <a href="<?php echo esc_url($data['facebook_url']); ?>"
                                            class="text-[#1b1c19] text-[12px] font-semibold tracking-[1.2px] underline leading-[16px] hover:text-pri transition-colors"
                                            target="_blank" rel="noopener noreferrer">
                                            Facebook
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Right: Contact Form -->
                <div class="col col-7 max-md:!w-full">
                    <div class="flex flex-col justify-center">
                        <div class="bg-[#f5f3ee] rounded-[8px] drop-shadow-[0px_10px_20px_rgba(44,81,76,0.05)] p-4 md:p-8">
                            <div class="sec-lh-form__cf7 cf7-lien-he">
                                <?php echo do_shortcode($data['cf7_shortcode']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>