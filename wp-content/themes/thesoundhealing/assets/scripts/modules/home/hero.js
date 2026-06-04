function initVideoLoopCrossfade() {
   document.querySelectorAll('.section-banner .swiper-slide').forEach(function (slide) {
      var a = slide.querySelector('.video-loop-a');
      var b = slide.querySelector('.video-loop-b');
      if (!a || !b) return;

      var BEFORE = 0.5;
      var FADE   = 0.5;
      var switching = false;

      function crossfade(from, to) {
         if (switching) return;
         switching = true;

         to.currentTime = 0;
         to.play();
         to.style.transition   = 'opacity ' + FADE + 's';
         to.style.opacity      = 1;
         from.style.transition = 'opacity ' + FADE + 's';
         from.style.opacity    = 0;

         setTimeout(function () {
            from.pause();
            switching = false;
         }, FADE * 1000);
      }

      a.addEventListener('timeupdate', function () {
         if (a.duration && a.currentTime >= a.duration - BEFORE) crossfade(a, b);
      });

      b.addEventListener('timeupdate', function () {
         if (b.duration && b.currentTime >= b.duration - BEFORE) crossfade(b, a);
      });
   });
}

function initBannerIntro() {
   const banner = document.querySelector('.section-banner');
   if (!banner) return;

   gsap.registerPlugin(MorphSVGPlugin, SplitText);

   const logoPath = document.querySelector('#banner-logo-path');
   if (!logoPath) return;

   const logoD = logoPath.getAttribute('d');
   const pillD = 'M14,45 Q14,44 16,44 L450,44 Q452,44 452,45 L452,51 Q452,52 450,52 L16,52 Q14,52 14,51 Z';

   // ── SplitText một lần — reuse trên các lần replay ────────────────────────
   var titleEls = gsap.utils.toArray('.banner-hero-title', banner);
   var allWords  = [];
   titleEls.forEach(function (el) {
      try {
         var split = new SplitText(el, { type: 'words', wordsClass: 'banner-word' });
         allWords  = allWords.concat(split.words);
      } catch (e) {}
   });

   var currentTl   = null;
   var introsDone  = false;
   var hasLeftView = false;
   var exitTl      = null;

   function runExit() {
      if (exitTl) exitTl.kill();
      exitTl = gsap.timeline();
      exitTl.to('.banner-scroll-down',  { opacity: 0, y: 10, duration: 0.3,  ease: 'power2.in' });
      exitTl.to('.banner-hero-caption', { opacity: 0, y: 12, duration: 0.35, ease: 'power2.in' }, '-=0.2');
      if (allWords.length) {
         exitTl.to(allWords, {
            opacity: 0,
            x: 40,
            duration: 0.5,
            stagger: { each: 0.1, from: 'end' },
            ease: 'power3.in',
         }, '-=0.25');
      }
      exitTl.to('.banner-hero-overlay', { opacity: 0, duration: 0.15 });
   }

   function onScroll() {
      if (!introsDone) return;
      introsDone = false;
      window.removeEventListener('scroll', onScroll);
      runExit();
   }

   function playIntro() {
      introsDone = false;
      window.removeEventListener('scroll', onScroll);
      if (exitTl)    { exitTl.kill();    exitTl = null; }
      if (currentTl) { currentTl.kill(); }

      // ── Reset toàn bộ trạng thái ──────────────────────────────────────────
      gsap.set(logoPath,               { morphSVG: pillD });
      gsap.set('#banner-logo-svg',     { opacity: 1 });
      gsap.set('.frame-logo',          { opacity: 1, y: 0 });
      gsap.set('.banner-hero-overlay', { opacity: 0 });
      gsap.set('.banner-scroll-down',  { opacity: 0, y: 10 });
      if (allWords.length) gsap.set(allWords, { opacity: 0, x: 40 });
      gsap.set('.banner-hero-caption', { opacity: 0, y: 12 });

      currentTl = gsap.timeline({
         delay: 0.2,
         onComplete: function () {
            introsDone = true;
            document.dispatchEvent(new Event('bannerIntroComplete'));
            // Lắng nghe scroll để kích exit
            window.addEventListener('scroll', onScroll, { passive: true });
         },
      });

      // ── Phase 1: Pill → logo ───────────────────────────────────────────────
      currentTl.to(logoPath, { morphSVG: logoD, duration: 1.6, ease: 'expo.inOut' }, 0);

      // ── Phase 2: Hold logo ─────────────────────────────────────────────────
      currentTl.to({}, { duration: 0.9 });

      // ── Phase 3: Logo exits upward ─────────────────────────────────────────
      currentTl.to('.frame-logo', { opacity: 0, y: -28, duration: 0.6, ease: 'power3.inOut' });

      // ── Phase 4: Text reveals ──────────────────────────────────────────────
      currentTl.set('.banner-hero-overlay', { opacity: 1 });

      if (allWords.length) {
         currentTl.to(allWords, {
            opacity: 1, x: 0, duration: 0.5, stagger: 0.15, ease: 'power3.out',
         }, '-=0.08');
      }

      currentTl.to('.banner-hero-caption', { opacity: 1, y: 0, duration: 0.35, ease: 'power2.out' }, '-=0.3');
      currentTl.to('.banner-scroll-down',  { opacity: 1, y: 0, duration: 0.3,  ease: 'power2.out' }, '-=0.2');
   }

   // ── Lần đầu ───────────────────────────────────────────────────────────────
   playIntro();

   // ── Replay khi scroll ngược lại banner ────────────────────────────────────
   var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
         if (!entry.isIntersecting) {
            hasLeftView = true;
         } else if (hasLeftView) {
            hasLeftView = false;
            playIntro();
         }
      });
   }, { threshold: 0.2 });

   observer.observe(banner);
}
