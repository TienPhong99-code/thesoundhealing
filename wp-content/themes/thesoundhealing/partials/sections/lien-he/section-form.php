<?php
defined('ABSPATH') || exit;

$page_id = get_queried_object_id();

$sample = [
    'company'       => 'HEALIVERSE HOLDINGS.,JSC',
    'address'       => "104/20 Mai Thị Lựu, P. Tân Định, TP.HCM\n(P. Đakao, Q.1 cũ)",
    'phone'         => '0939 624 684',
    'phone_label'   => 'Kennedy - English',
    'phone_2'       => '0906 502 582',
    'phone_2_label' => 'Việt - tiếng Việt',
    'email'         => 'khanh@thesoundhealing.vn',
    'instagram_url' => '#',
    'facebook_url'  => '#',
    'cf7_shortcode' => '[contact-form-7 id="contact-form" title="Liên Hệ"]',
    'map_embed_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.2459524162846!2d106.69589097570346!3d10.792465158896318!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x317528ca6776e131%3A0x9c452ae87947737!2zMTA0LzIwIE1haSBUaOG7iyBM4buxdSwgVMOibiDEkOG7i25oLCBI4buTIENow60gTWluaCA3MDAwMDAsIFZp4buHdCBOYW0!5e0!3m2!1svi!2s!4v1781146482503!5m2!1svi!2s',
];

$data = [
    'company'       => get_field('lh_company', $page_id)       ?: $sample['company'],
    'address'       => get_field('lh_address', $page_id)       ?: $sample['address'],
    'phone'         => get_field('lh_phone', $page_id)         ?: $sample['phone'],
    'phone_label'   => get_field('lh_phone_label', $page_id)   ?: $sample['phone_label'],
    'phone_2'       => get_field('lh_phone_2', $page_id)       ?: $sample['phone_2'],
    'phone_2_label' => get_field('lh_phone_2_label', $page_id) ?: $sample['phone_2_label'],
    'email'         => get_field('lh_email', $page_id)         ?: $sample['email'],
    'instagram_url' => get_field('lh_instagram_url', $page_id) ?: $sample['instagram_url'],
    'facebook_url'  => get_field('lh_facebook_url', $page_id)  ?: $sample['facebook_url'],
    'cf7_shortcode' => get_field('lh_cf7_shortcode', $page_id) ?: $sample['cf7_shortcode'],
    'map_embed_url' => get_field('lh_map_embed_url', $page_id) ?: $sample['map_embed_url'],
];
?>

<section class="sec-lh-form section-pd">
    <div class="container">
        <div class="relative">
            <div class="row items-stretch">

                <!-- Left: Contact Info -->
                <div class="col col-4 max-md:!w-full">
                    <div class="flex flex-col justify-center py-[48px] h-full">
                        <div class="flex flex-col gap-8">

                            <!-- Heading -->
                            <div class="border-b border-[#e4e2dd] pb-4">
                                <h2 class="font-title text-sec text-[24px] font-normal leading-[32px]">
                                    Thông Tin Liên Hệ
                                </h2>
                                <p class="text-[#414847] text-[13px] font-semibold uppercase tracking-[1.2px] leading-[20px] mt-1">
                                    <?php echo esc_html($data['company']); ?>
                                </p>
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

                                <!-- Booking / Hotline -->
                                <div class="flex gap-4 items-start">
                                    <div class="size-[18px] shrink-0 mt-[2px]">
                                        <img src="<?php echo esc_url(MONA_THEME_PATH_URI . '/assets/images/ic-contact-phone.svg'); ?>"
                                            class="block w-full h-full object-contain" alt="">
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <span class="text-[#414847] text-[12px] font-semibold uppercase tracking-[1.2px] leading-[16px]">Booking | Đặt lịch · Hotline | Zalo | WhatsApp</span>
                                        <div class="flex flex-col gap-1">
                                            <div class="flex items-center gap-2">
                                                <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $data['phone'])); ?>"
                                                    class="text-[#1b1c19] text-[16px] leading-[24px] hover:text-pri transition-colors">
                                                    <?php echo esc_html($data['phone']); ?>
                                                </a>
                                                <span class="text-[#414847] text-[13px] leading-[20px]">(<?php echo esc_html($data['phone_label']); ?>)</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $data['phone_2'])); ?>"
                                                    class="text-[#1b1c19] text-[16px] leading-[24px] hover:text-pri transition-colors">
                                                    <?php echo esc_html($data['phone_2']); ?>
                                                </a>
                                                <span class="text-[#414847] text-[13px] leading-[20px]">(<?php echo esc_html($data['phone_2_label']); ?>)</span>
                                            </div>
                                        </div>
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

                <!-- Middle: Map -->
                <div class="col col-4 max-md:!w-full">
                    <div class="h-full w-full aspect-[4/3] overflow-hidden rounded-[8px]">
                        <iframe
                            src="<?php echo esc_url($data['map_embed_url']); ?>"
                            class="w-full h-full border-0"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Bản đồ vị trí">
                        </iframe>
                    </div>
                </div>

                <!-- Right: Contact Form -->
                <div class="col col-4 max-md:!w-full">
                    <div class="flex flex-col justify-center h-full">
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