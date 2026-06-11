<?php
defined('ABSPATH') || exit;

$ppp = 6;

$query = new WP_Query([
    'post_type'      => 'khoa_hoc',
    'post_status'    => 'publish',
    'posts_per_page' => $ppp,
    'orderby'        => 'menu_order date',
    'order'          => 'ASC',
]);

$sample = [
    [
        'image'      => ['url' => MONA_THEME_PATH_URI . '/assets/images/courses-img-1.png', 'alt' => 'Hoà âm 7 chuông pha lê'],
        'level'      => 'Foundation',
        'term'       => 'Bộ Môn Âm Thanh',
        'title'      => 'Hoà âm 7 chuông pha lê',
        'desc'       => 'Nắm vững kỹ thuật chơi và hoà âm 7 luân xa với chuông pha lê, mang lại sự cân bằng sâu sắc cho cơ thể và tâm trí.',
        'time'       => '09:00 – 17:00',
        'start_date' => '15 THÁNG 2, 2025',
        'duration'   => '4 tuần · Cuối tuần',
        'instructor' => 'Linh Tâm',
        'location'   => 'Aetheria Studio — Quận 1, TP.HCM',
        'branch'     => 'Cơ sở Quận 1',
        'status'     => 'open',
        'spots'      => 15,
        'price'      => '12.000.000 VNĐ',
        'url'        => '#',
    ],
    [
        'image'      => ['url' => MONA_THEME_PATH_URI . '/assets/images/courses-img-2.png', 'alt' => 'Liệu pháp chuông đồng'],
        'level'      => 'Mastery',
        'term'       => 'Bộ Môn Âm Thanh',
        'title'      => 'Liệu pháp chuông đồng',
        'desc'       => 'Khám phá nghệ thuật chữa lành cổ xưa qua rung động vật lý của chuông đồng nguyên bản Himalaya.',
        'time'       => '09:00 – 17:00',
        'start_date' => '01 THÁNG 3, 2025',
        'duration'   => '2 ngày · Cuối tuần',
        'instructor' => 'Linh Tâm',
        'location'   => 'Aetheria Studio — Quận 1, TP.HCM',
        'branch'     => 'Cơ sở Quận 1',
        'status'     => 'open',
        'spots'      => 15,
        'price'      => '15.000.000 VNĐ',
        'url'        => '#',
    ],
    [
        'image'      => ['url' => MONA_THEME_PATH_URI . '/assets/images/courses-img-3.png', 'alt' => 'Liệu pháp Sound Bath'],
        'level'      => 'Chuyên sâu',
        'term'       => 'Bộ Môn Âm Thanh',
        'title'      => 'Liệu pháp Sound Bath',
        'desc'       => 'Đào tạo chuyên sâu kỹ năng tổ chức và dẫn dắt các buổi tắm âm thanh trị liệu chuyên nghiệp.',
        'time'       => '09:00 – 17:00',
        'start_date' => '15 THÁNG 3, 2025',
        'duration'   => '6 tuần · Cuối tuần',
        'instructor' => 'Linh Tâm',
        'location'   => 'Aetheria Studio — Quận 1, TP.HCM',
        'branch'     => 'Cơ sở Quận 1',
        'status'     => 'open',
        'spots'      => 15,
        'price'      => '20.000.000 VNĐ',
        'url'        => '#',
    ],
    [
        'image'      => ['url' => MONA_THEME_PATH_URI . '/assets/images/courses-img-1.png', 'alt' => 'Năng Lượng Usui Reiki Level 2'],
        'level'      => 'Nâng cao',
        'term'       => 'Bộ Môn Năng Lượng',
        'title'      => 'Năng Lượng Usui Reiki Level 2',
        'desc'       => 'Mở rộng khả năng trị liệu cho người khác, kết nối sâu hơn với nguồn năng lượng vũ trụ.',
        'time'       => '09:00 – 17:00',
        'start_date' => '05 THÁNG 4, 2025',
        'duration'   => '2 ngày · Cuối tuần',
        'instructor' => 'Linh Tâm',
        'location'   => 'Aetheria Studio — Quận 1, TP.HCM',
        'branch'     => 'Cơ sở Quận 1',
        'status'     => 'open',
        'spots'      => 15,
        'price'      => '5.500.000 VNĐ',
        'url'        => '#',
    ],
    [
        'image'      => ['url' => MONA_THEME_PATH_URI . '/assets/images/courses-img-2.png', 'alt' => 'Thiền Định Nâng Cao'],
        'level'      => 'Intermediate',
        'term'       => 'Bộ Môn Thiền Định',
        'title'      => 'Thiền Định Nâng Cao',
        'desc'       => 'Khóa học thiền sâu kết hợp âm thanh và hơi thở, giúp làm chủ trạng thái tâm trí trong cuộc sống.',
        'time'       => '09:00 – 17:00',
        'start_date' => '19 THÁNG 4, 2025',
        'duration'   => '3 tuần · Thứ 3, 5, 7',
        'instructor' => 'Linh Tâm',
        'location'   => 'Aetheria Studio — Quận 1, TP.HCM',
        'branch'     => 'Cơ sở Quận 1',
        'status'     => 'open',
        'spots'      => 15,
        'price'      => '6.800.000 VNĐ',
        'url'        => '#',
    ],
    [
        'image'      => ['url' => MONA_THEME_PATH_URI . '/assets/images/courses-img-3.png', 'alt' => 'Hòa Âm Trống Shaman'],
        'level'      => 'Foundation',
        'term'       => 'Bộ Môn Âm Thanh',
        'title'      => 'Hòa Âm Trống Shaman',
        'desc'       => 'Khám phá rung động nguyên thủy của trống Shaman — công cụ chữa lành linh thiêng từ ngàn năm.',
        'time'       => '09:00 – 17:00',
        'start_date' => '10 THÁNG 5, 2025',
        'duration'   => '2 ngày · Cuối tuần',
        'instructor' => 'Linh Tâm',
        'location'   => 'Aetheria Studio — Quận 1, TP.HCM',
        'branch'     => 'Cơ sở Quận 1',
        'status'     => 'open',
        'spots'      => 15,
        'price'      => '8.000.000 VNĐ',
        'url'        => '#',
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
        $terms     = get_the_terms($post_id, 'bo_mon_khoa_hoc');
        $term_name = (!is_wp_error($terms) && !empty($terms)) ? $terms[0]->name : '';

        $items[] = [
            'image'      => ['url' => $thumb ?: '', 'alt' => get_the_title($post_id)],
            'level'      => get_field('level',           $post_id),
            'term'       => $term_name,
            'format'     => get_field('kh_format',       $post_id) ?: 'Onsite',
            'title'      => get_the_title($post_id),
            'desc'       => get_field('short_desc',      $post_id),
            'time'       => get_field('kh_time',         $post_id),
            'start_date' => get_field('start_date',      $post_id),
            'duration'   => get_field('duration',        $post_id),
            'instructor' => get_field('instructor_name', $post_id),
            'location'   => get_field('location',        $post_id),
            'branch'     => get_field('kh_branch',       $post_id),
            'status'     => get_field('kh_status',       $post_id) ?: '',
            'price'      => get_field('price',           $post_id),
            'spots'      => get_field('kh_spots',        $post_id),
            'url'        => get_permalink($post_id),
        ];
    }
    wp_reset_postdata();
}
?>

<section class="sec-kh-list pt-0 pb-(--pd-sc)"
    <?php if (!$use_sample) : ?>
    data-total="<?php echo $total; ?>"
    data-ajaxurl="<?php echo esc_url(admin_url('admin-ajax.php')); ?>"
    <?php endif; ?>>
    <div class="container">
        <div class="row js-kh-list-grid">
            <?php foreach ($items as $item) : ?>
                <div class="col col-4 max-lg:!w-1/2 max-sm:!w-full">
                    <?php get_template_part('partials/components/card-khoa-hoc', null, ['item' => $item]); ?>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (!$use_sample && $total > $ppp) : ?>
            <div class="js-kh-list-loading hidden justify-center py-8">
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
            <div class="js-kh-list-sentinel h-px"></div>
        <?php endif; ?>
    </div>
</section>

<?php if (!$use_sample && $total > $ppp) : ?>
    <script>
        (function() {
            const section = document.querySelector('.sec-kh-list');
            if (!section) return;

            const grid = section.querySelector('.js-kh-list-grid');
            const sentinel = section.querySelector('.js-kh-list-sentinel');
            const loader = section.querySelector('.js-kh-list-loading');
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
                            action: 'kiena_load_more_khoa_hoc',
                            offset
                        }),
                    })
                    .then(r => r.json())
                    .then(res => {
                        if (res.success && res.html) {
                            grid.insertAdjacentHTML('beforeend', res.html);
                            offset += 3;
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