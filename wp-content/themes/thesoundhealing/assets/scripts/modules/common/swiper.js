// assets/scripts/modules/common/swiper.js

/**
 * Khởi tạo Swiper slider
 * @param {string} element - CSS selector của wrapper bọc ngoài swiper
 * @param {object} customizeOption - Tuỳ chỉnh options Swiper
 * @param {string} typePagi - Loại pagination: bullets | fraction | progressbar
 * 
 * Cách dùng:
 * HTML chỉ cần thêm class wrapper đã định nghĩa
 * Không cần viết thêm JS cho từng slider
 */
export const functionSlider = (element, customizeOption = {}, typePagi = 'bullets') => {
  const swiperSliders = document.querySelectorAll(element);

  if (!swiperSliders.length) return;

  swiperSliders.forEach((item) => {
    const swiper = item.querySelector('.swiper');
    const pagi   = item.querySelector('.swiper-pagination');
    const next   = item.querySelector('.swiper-next');
    const prev   = item.querySelector('.swiper-prev');

    new Swiper(swiper, {
      watchSlidesProgress: true,
      pagination: {
        el: pagi,
        type: typePagi,
        clickable: true,
      },
      navigation: {
        nextEl: next,
        prevEl: prev,
      },
      fadeEffect: {
        crossFade: true,
      },
      ...customizeOption,
    });
  });
};

/**
 * Init tất cả slider đã định nghĩa sẵn
 * Thêm slider mới → thêm vào đây
 */
export const initSwipers = () => {
  functionSlider('.slideSw', {
    speed: 1200,
    autoplay: {
      delay: 2600,
    },
    slidesPerView: 'auto',
    initialSlide: 0,
    centeredSlides: false,
    loop: false,
    effect: 'slide',
  });
};