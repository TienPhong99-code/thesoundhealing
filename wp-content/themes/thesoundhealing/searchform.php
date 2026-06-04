<form method="get" action="<?php echo esc_url(home_url('/')); ?>">
    <div class="found-form">
        <input id="search" name="s"  type="text"
            placeholder="<?php echo esc_attr_x('Tìm kiếm sản phẩm', 'placeholder', 'monamedia'); ?>" required/>
        <button type="submit">
            <img src="<?php echo MONA_THEME_PATH_URI; ?>/assets/images/ic-srch.svg" alt="image" loading="lazy" />
        </button>
    </div>
    <input type="hidden" name="post_type" value="product" />
</form>