document.addEventListener('DOMContentLoaded', () => {
    // Background Scroller logic
    let currentSlide = 0;
    const slides = document.querySelectorAll('.bg-slide');
    if (slides.length > 0) {
        setInterval(() => {
            slides[currentSlide].classList.remove('active');
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].classList.add('active');
        }, 6000);
    }

    // Reveal on Scroll
    const observerOptions = { threshold: 0.1 };
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, observerOptions);

    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

    // Stats Counter Animation
    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const target = entry.target;
                const endValue = parseInt(target.getAttribute('data-value'));
                let startValue = 0;
                const duration = 2000;
                const stepTime = Math.abs(Math.floor(duration / endValue));

                const timer = setInterval(() => {
                    startValue += 1;
                    target.innerText = startValue;
                    if (startValue >= endValue) {
                        target.innerText = endValue + '+';
                        clearInterval(timer);
                    }
                }, stepTime);
                statsObserver.unobserve(target);
            }
        });
    }, { threshold: 0.5 });

    document.querySelectorAll('.stat-number').forEach(stat => statsObserver.observe(stat));
});
