<?php
defined('ABSPATH') || exit;

/**
 * Load files
 */
return [
    // classes
    MONA_THEME_INC_PATH . '/classes/class.Mona_SetupTheme.php',
    MONA_THEME_INC_PATH . '/classes/walkers/class.Mona_Walker_Nav_Menu_Desktop.php',
    MONA_THEME_INC_PATH . '/classes/walkers/class.Mona_Walker_Nav_Menu_Mobile.php',

    // Functions
    MONA_THEME_INC_PATH . '/functions/CommonFunction.php',
    MONA_THEME_INC_PATH . '/functions/ImageFunction.php',
    MONA_THEME_INC_PATH . '/functions/PaginationFunction.php',
    MONA_THEME_INC_PATH . '/functions/PostFunction.php',
    MONA_THEME_INC_PATH . '/functions/TaxonomyFunction.php',
    MONA_THEME_INC_PATH . '/functions/CommentFunction.php',
    MONA_THEME_INC_PATH . '/functions/ACFFunction.php',

    // Hooks
    MONA_THEME_INC_PATH . '/hooks/CommonHook.php',
    MONA_THEME_INC_PATH . '/hooks/ImageHook.php',
    MONA_THEME_INC_PATH . '/hooks/PostHook.php',
    MONA_THEME_INC_PATH . '/hooks/AjaxHook.php',
    MONA_THEME_INC_PATH . '/hooks/ShortcodeHook.php',
    MONA_THEME_INC_PATH . '/hooks/CF7Hook.php',

    // Caches
    MONA_THEME_INC_PATH . '/caches/MenuCache.php',

    // Ajax
    MONA_THEME_INC_PATH . '/ajax/PostAjax.php',

    // CPT
    MONA_THEME_INC_PATH . '/cpt/DuAnCPT.php',
    MONA_THEME_INC_PATH . '/cpt/TuyenDungCPT.php',
    MONA_THEME_INC_PATH . '/cpt/KhoaHocCPT.php',
    MONA_THEME_INC_PATH . '/cpt/WorkshopCPT.php',
    MONA_THEME_INC_PATH . '/cpt/DichVuCPT.php',

    // Seeders — comment lại sau khi đã chạy xong
    // MONA_THEME_INC_PATH . '/seeders/HoatDongSeeder.php',
    // MONA_THEME_INC_PATH . '/seeders/KhoaHocSeeder.php',
    // MONA_THEME_INC_PATH . '/seeders/AboutSeeder.php',
    // MONA_THEME_INC_PATH . '/seeders/KhoaHocV2Seeder.php',
    // MONA_THEME_INC_PATH . '/seeders/WorkshopSeeder.php',
    // MONA_THEME_INC_PATH . '/seeders/WorkshopV2Seeder.php',
    // MONA_THEME_INC_PATH . '/seeders/DichVuSeeder.php',

    // ACF
    MONA_THEME_INC_PATH . '/acf/FooterACF.php',
    MONA_THEME_INC_PATH . '/acf/GeneralACF.php',
    MONA_THEME_INC_PATH . '/acf/MenuACF.php',
    MONA_THEME_INC_PATH . '/acf/SoundHealingHomeACF.php',
    MONA_THEME_INC_PATH . '/acf/KhoaHocACF.php',
    MONA_THEME_INC_PATH . '/acf/DuAnACF.php',
    MONA_THEME_INC_PATH . '/acf/HoatDongCongDongACF.php',
    MONA_THEME_INC_PATH . '/acf/TuyenDungACF.php',
    MONA_THEME_INC_PATH . '/acf/AboutACF.php',
    MONA_THEME_INC_PATH . '/acf/LienHeACF.php',
    MONA_THEME_INC_PATH . '/acf/KhoaHocPageACF.php',
    MONA_THEME_INC_PATH . '/acf/WorkshopACF.php',
    MONA_THEME_INC_PATH . '/acf/WorkshopPageACF.php',
    MONA_THEME_INC_PATH . '/acf/KhoaHocWorkshopPageACF.php',
    MONA_THEME_INC_PATH . '/acf/DichVuACF.php',
    MONA_THEME_INC_PATH . '/acf/DichVuPageACF.php',
];
