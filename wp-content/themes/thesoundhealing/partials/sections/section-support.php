<?php
defined('ABSPATH') || exit;

$section = get_field('section_support', 'option');
if (empty($section)) return;
?>
<section class="sec-homes-feature">
    <div class="homes-feature ss-mg">
        <div class="container">
            <div class="homes-feature__list row gap-res">
                <?php foreach ($section as $index => $item) : ?>
                    <div class="col">
                        <div class="homes-feature__item">
                            <div class="inner">
                                <div class="iwt">
                                    <?php if (! empty($item['image'])) : ?>
                                        <div class="icon">
                                            <?php echo mona_get_image_by_id($item['image']); ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="b-gr">
                                        <p class="tt"><?php echo esc_html($item['title']); ?></p>

                                        <?php if (! empty($item['description'])) : ?>
                                            <p class="des"><?php echo wp_kses($item['description'], [
                                                'br' => true,
                                            ]); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>