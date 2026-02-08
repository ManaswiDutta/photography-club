document.addEventListener('DOMContentLoaded', () => {
    // Custom Cursor
    const cursor = document.createElement('div');
    cursor.className = 'custom-cursor';
    cursor.style.pointerEvents = 'none';
    document.body.appendChild(cursor);

    const follower = document.createElement('div');
    follower.className = 'cursor-follower';
    follower.style.pointerEvents = 'none';
    document.body.appendChild(follower);

    document.addEventListener('mousemove', (e) => {
        cursor.style.left = e.clientX + 'px';
        cursor.style.top = e.clientY + 'px';

        setTimeout(() => {
            follower.style.left = e.clientX + 'px';
            follower.style.top = e.clientY + 'px';
        }, 50);
    });

    document.querySelectorAll('a, button, .glass-card').forEach(el => {
        el.addEventListener('mouseenter', () => {
            follower.classList.add('active');
            cursor.classList.add('active');
        });
        el.addEventListener('mouseleave', () => {
            follower.classList.remove('active');
            cursor.classList.remove('active');
        });
    });

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

    // Mobile Nav Toggle
    const navToggle = document.querySelector('.nav-toggle');
    const headerNav = document.querySelector('header nav');

    if (navToggle && headerNav) {
        navToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            navToggle.classList.toggle('active');
            headerNav.classList.toggle('active');
            document.body.style.overflow = headerNav.classList.contains('active') ? 'hidden' : '';
        });

        // Close menu on link click
        headerNav.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                navToggle.classList.remove('active');
                headerNav.classList.remove('active');
                document.body.style.overflow = '';
            });
        });

        // Close menu on click outside
        document.addEventListener('click', (e) => {
            if (headerNav.classList.contains('active') && !headerNav.contains(e.target) && !navToggle.contains(e.target)) {
                navToggle.classList.remove('active');
                headerNav.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    }
});
