<?php
defined('ABSPATH') || exit;

$ppp = 6;

$query = new WP_Query([
    'post_type'      => 'workshop',
    'post_status'    => 'publish',
    'posts_per_page' => $ppp,
    'orderby'        => 'menu_order date',
    'order'          => 'ASC',
]);

$sample = [
    [
        'image'      => ['url' => MONA_THEME_PATH_URI . '/assets/images/courses-img-1.png', 'alt' => 'Sound Bath Buổi Tối'],
        'type'       => 'Workshop Âm Thanh',
        'status'     => 'open',
        'date'       => '18 THÁNG 1, 2025',
        'time'       => '19:00 – 21:00',
        'duration'   => '2 giờ',
        'title'      => 'Sound Bath Buổi Tối',
        'location'   => 'Aetheria Studio — Quận 1, TP.HCM',
        'instructor' => 'Linh Tâm',
        'desc'       => 'Buổi tắm âm thanh thư giãn cuối tuần với bát pha lê và trống Himalaya, dành cho mọi trình độ.',
        'spots'      => 18,
        'price'      => '850.000 VNĐ',
        'url'        => '#',
    ],
    [
        'image'      => ['url' => MONA_THEME_PATH_URI . '/assets/images/courses-img-2.png', 'alt' => 'Nhập Môn Reiki'],
        'type'       => 'Workshop Năng Lượng',
        'status'     => 'limited',
        'date'       => '25 THÁNG 1, 2025',
        'time'       => '09:00 – 17:00',
        'duration'   => '8 giờ',
        'title'      => 'Nhập Môn Reiki',
        'location'   => 'Aetheria Studio — Quận 1, TP.HCM',
        'instructor' => 'Linh Tâm',
        'desc'       => 'Trải nghiệm một ngày khám phá năng lượng Reiki cơ bản — phù hợp cho người chưa có kiến thức trước.',
        'spots'      => 4,
        'price'      => '1.500.000 VNĐ',
        'url'        => '#',
    ],
    [
        'image'      => ['url' => MONA_THEME_PATH_URI . '/assets/images/courses-img-3.png', 'alt' => 'Hòa Âm Gong'],
        'type'       => 'Workshop Âm Thanh',
        'status'     => 'upcoming',
        'date'       => '8 THÁNG 2, 2025',
        'time'       => '18:00 – 20:30',
        'duration'   => '2.5 giờ',
        'title'      => 'Hòa Âm Gong Thiêng',
        'location'   => 'Aetheria Studio — Quận 1, TP.HCM',
        'instructor' => 'Linh Tâm',
        'desc'       => 'Đắm chìm trong tần số nguyên thủy của trống gong — hành trình đi sâu vào trạng thái thiền sâu.',
        'spots'      => 0,
        'price'      => '1.200.000 VNĐ',
        'url'        => '#',
    ],
    [
        'image'      => ['url' => MONA_THEME_PATH_URI . '/assets/images/courses-img-1.png', 'alt' => 'Kết Nối Thần Số Học'],
        'type'       => 'Workshop Huyền Học',
        'status'     => 'open',
        'date'       => '22 THÁNG 2, 2025',
        'time'       => '09:00 – 12:00',
        'duration'   => '3 giờ',
        'title'      => 'Kết Nối Thần Số Học',
        'location'   => 'Aetheria Studio — Quận 1, TP.HCM',
        'instructor' => 'Linh Tâm',
        'desc'       => 'Giải mã những con số đằng sau tên và ngày sinh để hiểu sứ mệnh và hành trình cuộc đời bạn.',
        'spots'      => 14,
        'price'      => '950.000 VNĐ',
        'url'        => '#',
    ],
    [
        'image'      => ['url' => MONA_THEME_PATH_URI . '/assets/images/courses-img-2.png', 'alt' => 'Tắm Âm Chủ Đề Ngủ Ngon'],
        'type'       => 'Workshop Âm Thanh',
        'status'     => 'limited',
        'date'       => '1 THÁNG 3, 2025',
        'time'       => '20:00 – 22:00',
        'duration'   => '2 giờ',
        'title'      => 'Tắm Âm Chủ Đề: Ngủ Ngon',
        'location'   => 'Aetheria Studio — Quận 1, TP.HCM',
        'instructor' => 'Linh Tâm',
        'desc'       => 'Buổi tắm âm đặc biệt thiết kế để đưa sóng não về trạng thái Delta, hỗ trợ ngủ sâu và ngon giấc.',
        'spots'      => 3,
        'price'      => '750.000 VNĐ',
        'url'        => '#',
    ],
    [
        'image'      => ['url' => MONA_THEME_PATH_URI . '/assets/images/courses-img-3.png', 'alt' => 'Cân Bằng Chakra Nhóm'],
        'type'       => 'Workshop Năng Lượng',
        'status'     => 'open',
        'date'       => '15 THÁNG 3, 2025',
        'time'       => '14:00 – 17:00',
        'duration'   => '3 giờ',
        'title'      => 'Cân Bằng Chakra Nhóm',
        'location'   => 'Aetheria Studio — Quận 1, TP.HCM',
        'instructor' => 'Linh Tâm',
        'desc'       => 'Trải nghiệm phiên cân bằng 7 luân xa trong không gian nhóm với âm thanh và thiền định kết hợp.',
        'spots'      => 12,
        'price'      => '1.100.000 VNĐ',
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
        $terms     = get_the_terms($post_id, 'loai_workshop');
        $type_name = (!is_wp_error($terms) && !empty($terms)) ? $terms[0]->name : '';

        $items[] = [
            'image'    => ['url' => $thumb ?: '', 'alt' => get_the_title($post_id)],
            'type'     => $type_name,
            'status'     => get_field('ws_status',        $post_id) ?: 'open',
            'date'       => get_field('ws_date',          $post_id),
            'time'       => get_field('ws_time',          $post_id),
            'duration'   => get_field('ws_duration',      $post_id),
            'title'      => get_the_title($post_id),
            'location'   => get_field('ws_location',      $post_id),
            'instructor' => get_field('ws_instructor_name', $post_id),
            'desc'       => get_field('ws_short_desc',    $post_id),
            'price'      => get_field('ws_price',         $post_id),
            'spots'      => get_field('ws_spots',         $post_id),
            'url'        => get_permalink($post_id),
        ];
    }
    wp_reset_postdata();
}
?>

<section class="sec-ws-list pt-0 pb-(--pd-sc)"
    <?php if (!$use_sample) : ?>
    data-total="<?php echo $total; ?>"
    data-ajaxurl="<?php echo esc_url(admin_url('admin-ajax.php')); ?>"
    <?php endif; ?>>
    <div class="container">
        <div class="row js-ws-list-grid">
            <?php foreach ($items as $item) : ?>
                <div class="col col-4 max-md:!w-1/2 max-sm:!w-full">
                    <?php get_template_part('partials/components/card-workshop', null, ['item' => $item]); ?>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (!$use_sample && $total > $ppp) : ?>
            <div class="js-ws-list-loading hidden justify-center py-8">
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
            <div class="js-ws-list-sentinel h-px"></div>
        <?php endif; ?>
    </div>
</section>

<?php if (!$use_sample && $total > $ppp) : ?>
    <script>
        (function() {
            const section = document.querySelector('.sec-ws-list');
            if (!section) return;

            const grid = section.querySelector('.js-ws-list-grid');
            const sentinel = section.querySelector('.js-ws-list-sentinel');
            const loader = section.querySelector('.js-ws-list-loading');
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
                            action: 'kiena_load_more_workshop',
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