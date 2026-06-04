<?php
defined('ABSPATH') || exit;

$ppp = 6;

$query = new WP_Query([
    'post_type'      => 'dich_vu',
    'post_status'    => 'publish',
    'posts_per_page' => $ppp,
    'orderby'        => 'menu_order date',
    'order'          => 'ASC',
]);

$sample = [
    [
        'image'    => ['url' => MONA_THEME_PATH_URI . '/assets/images/dv-tam-am-ngu-ngon-nhom.jpg', 'alt' => 'Tắm Âm Ngủ Ngon (Nhóm)'],
        'category' => 'SOUND HEALING',
        'title'    => 'Tắm Âm Ngủ Ngon (Nhóm)',
        'desc'     => 'Trải nghiệm sóng âm thư giãn cùng nhóm để cải thiện chất lượng giấc ngủ và giảm căng thẳng tích tụ.',
        'url'      => '#',
    ],
    [
        'image'    => ['url' => MONA_THEME_PATH_URI . '/assets/images/dv-tam-am-ngu-ngon-rieng-tu.jpg', 'alt' => 'Tắm Âm Ngủ Ngon (Riêng Tư)'],
        'category' => 'PRIVATE EXPERIENCE',
        'title'    => 'Tắm Âm Ngủ Ngon (Riêng Tư)',
        'desc'     => 'Không gian trị liệu âm thanh dành riêng cho bạn, tập trung vào nhu cầu phục hồi sâu sắc của cá nhân.',
        'url'      => '#',
    ],
    [
        'image'    => ['url' => MONA_THEME_PATH_URI . '/assets/images/dv-tri-lieu-chuong-do-rieng-tu.jpg', 'alt' => 'Trị Liệu Chuông Đồ (Riêng Tư)'],
        'category' => 'VIBRATIONAL THERAPY',
        'title'    => 'Trị Liệu Chuông Đồ (Riêng Tư)',
        'desc'     => 'Kỹ thuật đặt chuông trực tiếp lên cơ thể để các rung động tác động sâu vào từng tế bào và huyệt đạo.',
        'url'      => '#',
    ],
    [
        'image'    => ['url' => MONA_THEME_PATH_URI . '/assets/images/dv-chua-lanh-reiki-nhom.jpg', 'alt' => 'Chữa Lành Usui Reiki (Group)'],
        'category' => 'ENERGY HEALING',
        'title'    => 'Chữa Lành Usui Reiki (Group)',
        'desc'     => 'Kết nối năng lượng vũ trụ trong không gian cộng hưởng nhóm để thanh tẩy và cân bằng tâm trí.',
        'url'      => '#',
    ],
    [
        'image'    => ['url' => MONA_THEME_PATH_URI . '/assets/images/dv-chua-lanh-reiki-rieng-tu.jpg', 'alt' => 'Chữa Lành Usui Reiki (Riêng Tư)'],
        'category' => 'ENERGY HEALING',
        'title'    => 'Chữa Lành Usui Reiki (Riêng Tư)',
        'desc'     => 'Phiên trị liệu năng lượng chuyên sâu 1-1 giúp giải quyết các tắc nghẽn cảm xúc và thể chất cụ thể.',
        'url'      => '#',
    ],
    [
        'image'    => ['url' => MONA_THEME_PATH_URI . '/assets/images/dv-khai-van-huyen-hoc.jpg', 'alt' => 'Khai Vấn Dự Đoán Huyền Học'],
        'category' => 'INTUITIVE ARTS',
        'title'    => 'Khai Vấn Dự Đoán Huyền Học',
        'desc'     => 'Sử dụng Soul Mirror Cards để soi chiếu nội tâm và tìm kiếm những chỉ dẫn trực giác cho hành trình sống.',
        'url'      => '#',
    ],
];

$use_sample = !$query->have_posts();
$total      = $use_sample ? 0 : (int) $query->found_posts;

$items = [];
if ($use_sample) {
    $items = $sample;
} else {
    while ($query->have_posts()) {
        $query->the_post();
        $post_id   = get_the_ID();
        $thumb     = get_the_post_thumbnail_url($post_id, 'full');
        $terms     = get_the_terms($post_id, 'loai_dich_vu');
        $term_name = (!is_wp_error($terms) && !empty($terms)) ? $terms[0]->name : '';

        $items[] = [
            'image'    => ['url' => $thumb ?: '', 'alt' => get_the_title($post_id)],
            'category' => $term_name,
            'title'    => get_the_title($post_id),
            'desc'     => get_field('dv_short_desc', $post_id),
            'url'      => get_permalink($post_id),
        ];
    }
    wp_reset_postdata();
}
?>

<section class="sec-dv-list pt-0 pb-(--pd-sc) bg-[#fbf9f4]"
    <?php if (!$use_sample) : ?>
    data-total="<?php echo $total; ?>"
    data-ajaxurl="<?php echo esc_url(admin_url('admin-ajax.php')); ?>"
    <?php endif; ?>>
    <div class="container">
        <div class="row js-dv-list-grid">
            <?php foreach ($items as $item) : ?>
                <div class="col col-4 max-lg:!w-1/2 max-sm:!w-full">
                    <?php get_template_part('partials/components/card-dich-vu', null, ['item' => $item]); ?>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (!$use_sample && $total > $ppp) : ?>
            <div class="js-dv-list-loading hidden justify-center py-8">
                <div class="w-10 h-10 flex justify-center items-center">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 2400 2400" xml:space="preserve">
                        <g stroke-width="200" stroke-linecap="round" stroke="currentColor" fill="none" id="spinner">
                            <line x1="1200" y1="600" x2="1200" y2="100" />
                            <line opacity="0.5" x1="1200" y1="2300" x2="1200" y2="1800" />
                            <line opacity="0.917" x1="900" y1="680.4" x2="650" y2="247.4" />
                            <line opacity="0.417" x1="1750" y1="2152.6" x2="1500" y2="1719.6" />
                            <line opacity="0.833" x1="680.4" y1="900" x2="247.4" y2="650" />
                            <line opacity="0.333" x1="2152.6" y1="1750" x2="1719.6" y2="1500" />
                            <line opacity="0.75" x1="600" y1="1200" x2="100" y2="1200" />
                            <line opacity="0.25" x1="2300" y1="1200" x2="1800" y2="1200" />
                            <line opacity="0.667" x1="680.4" y1="1500" x2="247.4" y2="1750" />
                            <line opacity="0.167" x1="2152.6" y1="650" x2="1719.6" y2="900" />
                            <line opacity="0.583" x1="900" y1="1719.6" x2="650" y2="2152.6" />
                            <line opacity="0.083" x1="1750" y1="247.4" x2="1500" y2="680.4" />
                            <animateTransform attributeName="transform" attributeType="XML" type="rotate" keyTimes="0;0.08333;0.16667;0.25;0.33333;0.41667;0.5;0.58333;0.66667;0.75;0.83333;0.91667" values="0 1199 1199;30 1199 1199;60 1199 1199;90 1199 1199;120 1199 1199;150 1199 1199;180 1199 1199;210 1199 1199;240 1199 1199;270 1199 1199;300 1199 1199;330 1199 1199" dur="0.83333s" begin="0s" repeatCount="indefinite" calcMode="discrete" />
                        </g>
                    </svg>
                </div>
            </div>
            <div class="js-dv-list-sentinel h-px"></div>
        <?php endif; ?>
    </div>
</section>

<?php if (!$use_sample && $total > $ppp) : ?>
    <script>
        (function() {
            const section = document.querySelector('.sec-dv-list');
            if (!section) return;

            const grid = section.querySelector('.js-dv-list-grid');
            const sentinel = section.querySelector('.js-dv-list-sentinel');
            const loader = section.querySelector('.js-dv-list-loading');
            const total = parseInt(section.dataset.total, 10);
            const ajaxurl = section.dataset.ajaxurl;

            let offset = <?php echo $ppp; ?>;
            let loading = false;

            if (offset >= total) return;

            function loadMore() {
                if (loading || offset >= total) return;
                loading = true;
                loader.classList.remove('hidden');
                loader.classList.add('flex');

                fetch(ajaxurl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            action: 'mona_load_more_dich_vu',
                            offset
                        }),
                    })
                    .then(r => r.json())
                    .then(res => {
                        if (res.success && res.html) {
                            grid.insertAdjacentHTML('beforeend', res.html);
                            offset += 6;
                        }
                        loader.classList.add('hidden');
                        loader.classList.remove('flex');
                        if (!res.has_more || offset >= total) observer.disconnect();
                        loading = false;

                        if (res.has_more && offset < total) {
                            const rect = sentinel.getBoundingClientRect();
                            if (rect.top < window.innerHeight) loadMore();
                        }
                    })
                    .catch(() => {
                        loader.classList.add('hidden');
                        loader.classList.remove('flex');
                        loading = false;
                    });
            }

            const observer = new IntersectionObserver(
                entries => {
                    if (entries[0].isIntersecting) loadMore();
                }, {
                    rootMargin: '0px'
                }
            );

            observer.observe(sentinel);
        })();
    </script>
<?php endif; ?>