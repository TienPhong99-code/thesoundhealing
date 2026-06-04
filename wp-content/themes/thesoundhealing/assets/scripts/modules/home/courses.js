function initCoursesSwiper() {
   const wrapper = document.querySelector('.sec-courses .swiper-courses');
   if (!wrapper) return;

   new Swiper(wrapper.querySelector('.swiper'), {
      slidesPerView: 1.2,
      spaceBetween: 16,
      loop: true,
      speed: 700,
      pagination: {
         el: wrapper.querySelector('.swiper-pagination'),
         clickable: true,
      },
      navigation: {
         nextEl: wrapper.querySelector('.swiper-next'),
         prevEl: wrapper.querySelector('.swiper-prev'),
      },
      breakpoints: {
         640:  { slidesPerView: 2.2, spaceBetween: 20 },
         1024: { slidesPerView: 3,   spaceBetween: 24 },
      },
   });
}
