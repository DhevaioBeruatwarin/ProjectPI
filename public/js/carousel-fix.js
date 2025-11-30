// public/js/carousel-fix.js
// Enhanced Carousel with proper scrolling and wrap mode

(function () {
  const GAP = 24; // Must match CSS gap
  const carousel = document.getElementById('productCarousel');
  const prevBtn = document.querySelector('.carousel-btn.prev');
  const nextBtn = document.querySelector('.carousel-btn.next');
  const indicators = document.getElementById('indicators');
  
  if (!carousel) return;

  let currentSlide = 0;
  let isTransitioning = false;

  // Get all product cards
  function getCards() {
    return Array.from(carousel.querySelectorAll('.product-card'));
  }

  // Get cards per view based on screen width
  function getCardsPerView() {
    const width = window.innerWidth;
    if (width <= 480) return 1;
    if (width <= 820) return 2;
    if (width <= 1100) return 3;
    return 4;
  }

  // Check if wrap mode should be enabled
  function shouldUseWrapMode() {
    const cards = getCards();
    const cardsPerView = getCardsPerView();
    // Use wrap mode if items fit in 2 pages or less
    return cards.length <= cardsPerView * 2;
  }

  // Enable wrap mode (grid layout)
  function enableWrapMode() {
    carousel.classList.add('wrap');
    carousel.style.transform = 'none';
    carousel.style.transition = 'none';
    
    if (prevBtn) prevBtn.style.display = 'none';
    if (nextBtn) nextBtn.style.display = 'none';
    if (indicators) indicators.style.display = 'none';
  }

  // Disable wrap mode (carousel layout)
  function disableWrapMode() {
    carousel.classList.remove('wrap');
    carousel.style.transition = 'transform 0.45s cubic-bezier(0.2, 0.8, 0.2, 1)';
    
    if (prevBtn) prevBtn.style.display = '';
    if (nextBtn) nextBtn.style.display = '';
    if (indicators) indicators.style.display = '';
  }

  // Calculate total slides/pages
  function getTotalSlides() {
    const cards = getCards();
    const cardsPerView = getCardsPerView();
    return Math.ceil(cards.length / cardsPerView);
  }

  // Update button states
  function updateButtonStates() {
    if (!prevBtn || !nextBtn) return;
    
    const totalSlides = getTotalSlides();
    
    // Disable prev button on first slide
    if (currentSlide === 0) {
      prevBtn.style.opacity = '0.4';
      prevBtn.style.cursor = 'not-allowed';
      prevBtn.disabled = true;
    } else {
      prevBtn.style.opacity = '1';
      prevBtn.style.cursor = 'pointer';
      prevBtn.disabled = false;
    }
    
    // Disable next button on last slide
    if (currentSlide >= totalSlides - 1) {
      nextBtn.style.opacity = '0.4';
      nextBtn.style.cursor = 'not-allowed';
      nextBtn.disabled = true;
    } else {
      nextBtn.style.opacity = '1';
      nextBtn.style.cursor = 'pointer';
      nextBtn.disabled = false;
    }
  }

  // Create indicators
  function createIndicators() {
    if (!indicators) return;
    
    const totalSlides = getTotalSlides();
    indicators.innerHTML = '';
    
    for (let i = 0; i < totalSlides; i++) {
      const dot = document.createElement('span');
      dot.className = 'indicator' + (i === currentSlide ? ' active' : '');
      dot.style.cursor = 'pointer';
      dot.setAttribute('data-index', i);
      dot.addEventListener('click', () => goToSlide(i));
      indicators.appendChild(dot);
    }
  }

  // Update active indicator
  function updateIndicators() {
    if (!indicators) return;
    
    const dots = indicators.querySelectorAll('.indicator');
    dots.forEach((dot, index) => {
      if (index === currentSlide) {
        dot.classList.add('active');
      } else {
        dot.classList.remove('active');
      }
    });
  }

  // Go to specific slide
  function goToSlide(index) {
    if (isTransitioning || carousel.classList.contains('wrap')) return;
    
    const totalSlides = getTotalSlides();
    const cards = getCards();
    
    if (cards.length === 0) return;
    
    // Clamp index to valid range
    currentSlide = Math.max(0, Math.min(index, totalSlides - 1));
    
    // Calculate scroll amount
    const cardsPerView = getCardsPerView();
    const cardWidth = cards[0].offsetWidth;
    const scrollAmount = (cardWidth + GAP) * cardsPerView;
    
    // Apply transform
    carousel.style.transform = `translateX(-${currentSlide * scrollAmount}px)`;
    
    // Update UI
    updateIndicators();
    updateButtonStates();
    
    // Prevent rapid clicking
    isTransitioning = true;
    setTimeout(() => {
      isTransitioning = false;
    }, 450);
  }

  // Scroll carousel by direction
  function scrollCarousel(direction) {
    if (isTransitioning || carousel.classList.contains('wrap')) return;
    
    const totalSlides = getTotalSlides();
    let newSlide = currentSlide + direction;
    
    // Clamp to valid range
    newSlide = Math.max(0, Math.min(newSlide, totalSlides - 1));
    
    if (newSlide !== currentSlide) {
      goToSlide(newSlide);
    }
  }

  // Update layout on resize or init
  function updateLayout() {
    const cards = getCards();
    
    if (cards.length === 0) {
      if (prevBtn) prevBtn.style.display = 'none';
      if (nextBtn) nextBtn.style.display = 'none';
      if (indicators) indicators.style.display = 'none';
      return;
    }
    
    if (shouldUseWrapMode()) {
      enableWrapMode();
    } else {
      disableWrapMode();
      
      // Reset to valid slide if needed
      const totalSlides = getTotalSlides();
      if (currentSlide >= totalSlides) {
        currentSlide = Math.max(0, totalSlides - 1);
      }
      
      // Update UI
      createIndicators();
      goToSlide(currentSlide);
    }
  }

  // Event listeners
  if (prevBtn) {
    prevBtn.addEventListener('click', () => scrollCarousel(-1));
  }
  
  if (nextBtn) {
    nextBtn.addEventListener('click', () => scrollCarousel(1));
  }

  // Keyboard navigation
  document.addEventListener('keydown', (e) => {
    if (carousel.classList.contains('wrap')) return;
    
    if (e.key === 'ArrowLeft') {
      scrollCarousel(-1);
    } else if (e.key === 'ArrowRight') {
      scrollCarousel(1);
    }
  });

  // Touch/swipe support
  let touchStartX = 0;
  let touchEndX = 0;
  
  carousel.addEventListener('touchstart', (e) => {
    touchStartX = e.changedTouches[0].screenX;
  }, { passive: true });
  
  carousel.addEventListener('touchend', (e) => {
    touchEndX = e.changedTouches[0].screenX;
    handleSwipe();
  }, { passive: true });
  
  function handleSwipe() {
    if (carousel.classList.contains('wrap')) return;
    
    const swipeThreshold = 50;
    const diff = touchStartX - touchEndX;
    
    if (Math.abs(diff) > swipeThreshold) {
      if (diff > 0) {
        // Swipe left - go next
        scrollCarousel(1);
      } else {
        // Swipe right - go prev
        scrollCarousel(-1);
      }
    }
  }

  // Debounced resize handler
  let resizeTimeout;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
      updateLayout();
    }, 150);
  });

  // Initialize
  updateLayout();

  // Expose scrollCarousel to global scope for button onclick
  window.scrollCarousel = scrollCarousel;
})();   