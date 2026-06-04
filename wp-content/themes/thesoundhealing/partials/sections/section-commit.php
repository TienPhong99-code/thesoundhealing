<?php
defined('ABSPATH') || exit;

$section = get_field('section_commit', 'option');

if (empty($args['show']) || empty($section)) return;
?>

<?php if (! empty($section['list'])
    && is_array($section['list'])) : ?>
    <div class="popup" data-popup-id="slider">
        <div class="popup-overlay"></div>
        <div class="popup-main popup-slider">
            <div class="popup-main-wrapper">
                <div class="popup-over">
                    <div class="popup-wrapper">
                        <div class="popup-slider__wrapper">
                            <div class="swiper row gap-res">
                                <div class="swiper-wrapper">
                                    <?php foreach ($section['list'] as $index => $item) : ?>
                                        <div class="swiper-slide col">
                                            <div class="popup-slider__item">
                                                <div class="inner">
                                                    <div class="img">
                                                        <div class="img-inner">
                                                            <?php echo mona_get_image_by_id($item['image']); ?>
                                                        </div>
                                                    </div>

                                                    <div class="info">
                                                        <?php if (! empty($item['title'])) : ?>
                                                            <h4 class="info-tt"><?php echo esc_html($item['title']); ?></h4>
                                                        <?php endif; ?>

                                                        <?php if (! empty($item['description'])) : ?>
                                                            <p class="info-des"><?php echo wp_kses($item['description'], [
                                                                'br' => true,
                                                            ]); ?></p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="popup-close"><i class="fas fa-times icon"></i></div>
        </div>
    </div>
<?php endif; ?>

<section class="sec-homes-seven">
    <div class="homes-seven">
        <?php if (! empty($section['background'])) : ?>
            <div class="bg imgPara">
                <?php echo mona_get_image_by_id($section['background']); ?>
            </div>
        <?php endif; ?>

        <div class="container">
            <div class="homes-seven__content">
                <div class="head-verti center">
                    <?php if (! empty($section['title'])) : ?>
                        <h2 class="title title-48 add-class t-medium text-hori t-white" data-spl="data-spl"
                            ><?php echo esc_html($section['title']); ?></h2>
                    <?php endif; ?>

                    <?php if (! empty($section['list'])
                        && is_array($section['list'])) : ?>
                        <div class="homes-seven__btn popup-open" data-popup="slider">
                            <img src="<?php echo MONA_THEME_PATH_URI; ?>/assets/images/ic-slide.svg" alt="image" loading="lazy" />
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>