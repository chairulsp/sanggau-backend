/* Diskominfo Sanggau — Web JS */

// ── Theme ─────────────────────────────────────────────────────────────────────
function toggleTheme() {
    const html = document.documentElement;
    const isDark = html.classList.toggle('dark');
    document.cookie = `theme=${isDark ? 'dark' : 'light'};path=/;max-age=31536000`;
    updateThemeIcon(isDark);
}
function updateThemeIcon(isDark) {
    const sun  = document.getElementById('iconSun');
    const moon = document.getElementById('iconMoon');
    if (sun && moon) {
        sun.style.display  = isDark ? 'none' : 'block';
        moon.style.display = isDark ? 'block' : 'none';
    }
}
// Init theme from cookie
(function() {
    const cookie = document.cookie.split(';').find(c => c.trim().startsWith('theme='));
    const theme  = cookie ? cookie.split('=')[1].trim() : null;
    if (theme === 'dark') {
        document.documentElement.classList.add('dark');
        updateThemeIcon(true);
    }
})();

// ── Mobile Menu ───────────────────────────────────────────────────────────────
function toggleMenu() {
    const menu    = document.getElementById('mobileMenu');
    const overlay = document.getElementById('mobileOverlay');
    const burger  = document.getElementById('hamburger');
    const isOpen  = menu.classList.toggle('active');
    overlay.classList.toggle('active', isOpen);
    burger.classList.toggle('open', isOpen);
    document.body.style.overflow = isOpen ? 'hidden' : '';
}

// ── Scroll events ─────────────────────────────────────────────────────────────
window.addEventListener('scroll', () => {
    const y        = window.scrollY;
    const navbar   = document.getElementById('navbar');
    const topbar   = document.getElementById('topbar');
    const scrollTop = document.getElementById('scrollTop');

    if (navbar)    navbar.classList.toggle('scrolled', y > 20);
    if (topbar)    topbar.classList.toggle('hidden', y > 80);
    if (scrollTop) scrollTop.classList.toggle('visible', y > 500);
}, { passive: true });

// ── Hero Slider ───────────────────────────────────────────────────────────────
(function() {
    const slides   = document.querySelectorAll('.hero-slide');
    const progBars = document.querySelectorAll('.slide-progress-bar');
    if (slides.length <= 1) return;

    let cur = 0;
    let timer;

    function goTo(idx) {
        slides[cur].classList.remove('active');
        cur = (idx + slides.length) % slides.length;
        slides[cur].classList.add('active');
        updateBars();
        resetTimer();
    }

    function updateBars() {
        progBars.forEach((b, i) => {
            b.style.animation = 'none';
            b.style.width = i < cur ? '100%' : (i === cur ? '' : '0%');
            if (i === cur) {
                void b.offsetWidth; // reflow
                b.style.animation = 'slideProgress 5.5s linear forwards';
            }
        });
    }

    function resetTimer() {
        clearInterval(timer);
        timer = setInterval(() => goTo(cur + 1), 5500);
    }

    // Init
    slides[0].classList.add('active');
    updateBars();
    resetTimer();

    // Expose to global for button clicks
    window.heroGoSlide = (dir) => goTo(cur + dir);
    window.heroGoTo    = (idx) => goTo(idx);
})();

// ── Intersection Observer for animations ─────────────────────────────────────
const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            e.target.classList.add('visible');
            observer.unobserve(e.target);
        }
    });
}, { threshold: 0.15 });

document.querySelectorAll('.anim-up').forEach(el => observer.observe(el));

// ── Dismiss alerts ────────────────────────────────────────────────────────────
document.querySelectorAll('.alert-success, .alert-error').forEach(el => {
    setTimeout(() => { el.style.opacity = '0'; el.style.transition = 'opacity .5s'; setTimeout(() => el.remove(), 500); }, 5000);
});
