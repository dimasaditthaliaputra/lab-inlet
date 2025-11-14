window.addEventListener("load", () => {
  window.scrollTo({
    top: 0,
    behavior: "smooth",
  });
});

// ===== GLOBAL FADE ANIMATION SYSTEM =====
class FadeAnimation {
  constructor(options = {}) {
    this.threshold = options.threshold || 0.15;
    this.rootMargin = options.rootMargin || '0px 0px -50px 0px';
    this.once = options.once !== false;
    this.observer = null;
  }

  // Init dengan Intersection Observer
  init(selector = '.fade-animate') {
    const elements = document.querySelectorAll(selector);
    
    if (elements.length === 0) return;

    this.observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animated');
          
          if (this.once) {
            this.observer.unobserve(entry.target);
          }
        } else if (!this.once) {
          entry.target.classList.remove('animated');
        }
      });
    }, {
      threshold: this.threshold,
      rootMargin: this.rootMargin
    });

    elements.forEach(element => {
      this.observer.observe(element);
    });
  }

  // Fade In manual
  fadeIn(element, duration = 500) {
    if (typeof element === 'string') {
      element = document.querySelector(element);
    }
    
    if (!element) return;

    element.style.transition = `opacity ${duration}ms ease`;
    element.style.opacity = '0';
    element.style.visibility = 'visible';
    
    setTimeout(() => {
      element.style.opacity = '1';
    }, 10);
  }

  // Fade Out manual
  fadeOut(element, duration = 500, callback) {
    if (typeof element === 'string') {
      element = document.querySelector(element);
    }
    
    if (!element) return;

    element.style.transition = `opacity ${duration}ms ease`;
    element.style.opacity = '1';
    
    setTimeout(() => {
      element.style.opacity = '0';
      
      setTimeout(() => {
        element.style.visibility = 'hidden';
        if (callback) callback();
      }, duration);
    }, 10);
  }

  // Toggle fade
  fadeToggle(element, duration = 500) {
    if (typeof element === 'string') {
      element = document.querySelector(element);
    }
    
    if (!element) return;

    const isVisible = window.getComputedStyle(element).opacity === '1';
    
    if (isVisible) {
      this.fadeOut(element, duration);
    } else {
      this.fadeIn(element, duration);
    }
  }

  // Trigger animasi (untuk class-based animations)
  trigger(element) {
    if (typeof element === 'string') {
      element = document.querySelector(element);
    }
    if (element) {
      element.classList.add('animated');
    }
  }

  // Reset animasi
  reset(element) {
    if (typeof element === 'string') {
      element = document.querySelector(element);
    }
    if (element) {
      element.classList.remove('animated');
      void element.offsetWidth;
    }
  }

  // Batch fade in dengan delay
  batchFadeIn(selector, delayIncrement = 100) {
    const elements = document.querySelectorAll(selector);
    elements.forEach((element, index) => {
      setTimeout(() => {
        this.fadeIn(element);
      }, index * delayIncrement);
    });
  }

  // Batch fade out dengan delay
  batchFadeOut(selector, delayIncrement = 100) {
    const elements = document.querySelectorAll(selector);
    elements.forEach((element, index) => {
      setTimeout(() => {
        this.fadeOut(element);
      }, index * delayIncrement);
    });
  }

  // Destroy observer
  destroy() {
    if (this.observer) {
      this.observer.disconnect();
    }
  }
}

// Instance global
const fadeAnim = new FadeAnimation({
  threshold: 0.15,
  rootMargin: '0px 0px -50px 0px',
  once: true
});

// Auto init saat DOM ready
document.addEventListener('DOMContentLoaded', function() {
  fadeAnim.init('.fade-animate');
});

// Helper functions
function initFadeWithDelay(selector, delayIncrement = 100) {
  const elements = document.querySelectorAll(selector);
  elements.forEach((element, index) => {
    element.style.animationDelay = `${index * delayIncrement}ms`;
  });
}

// ===== GLOBAL SLIDE ANIMATION SYSTEM =====
class GlobalSlideAnimation {
  constructor(options = {}) {
    this.threshold = options.threshold || 0.2;
    this.rootMargin = options.rootMargin || '0px 0px -50px 0px';
    this.once = options.once !== false; // Default true (animate sekali saja)
    this.observer = null;
  }

  init(selector = '.slide-animate') {
    const elements = document.querySelectorAll(selector);
    
    if (elements.length === 0) return;

    this.observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animated');
          
          // Jika once = true, stop observing setelah animate
          if (this.once) {
            this.observer.unobserve(entry.target);
          }
        } else if (!this.once) {
          // Jika once = false, bisa repeat animasi
          entry.target.classList.remove('animated');
        }
      });
    }, {
      threshold: this.threshold,
      rootMargin: this.rootMargin
    });

    elements.forEach(element => {
      this.observer.observe(element);
    });
  }

  // Trigger animasi manual
  trigger(element) {
    if (typeof element === 'string') {
      element = document.querySelector(element);
    }
    if (element) {
      element.classList.add('animated');
    }
  }

  // Reset animasi
  reset(element) {
    if (typeof element === 'string') {
      element = document.querySelector(element);
    }
    if (element) {
      element.classList.remove('animated');
      void element.offsetWidth; // Force reflow
    }
  }

  // Destroy observer
  destroy() {
    if (this.observer) {
      this.observer.disconnect();
    }
  }
}

// Instance global
const slideAnim = new GlobalSlideAnimation({
  threshold: 0.2,
  rootMargin: '0px 0px -50px 0px',
  once: true
});

// Auto init saat DOM ready
document.addEventListener('DOMContentLoaded', function() {
  slideAnim.init('.slide-animate');
});

// Helper function untuk batch animation dengan delay
function initSlideAnimationWithDelay(selector, delayIncrement = 100) {
  const elements = document.querySelectorAll(selector);
  elements.forEach((element, index) => {
    element.style.animationDelay = `${index * delayIncrement}ms`;
  });
}

// deklarasi tombol dan menu
const tombol = document.querySelector(".tombol");
const menu = document.querySelector(".menu");

tombol.addEventListener("click", function () {
  menu.classList.toggle("aktif");
});

// Navbar scroll effect
const navbar = document.querySelector(".navbar");



window.addEventListener("scroll", function () {
  if (window.scrollY > 50) {
    navbar.classList.add("scrolled");
  } else {
    navbar.classList.remove("scrolled");
  }
});

// ===== REUSABLE TEXT SLIDE ANIMATION =====
class TextSlideAnimation {
  constructor(options = {}) {
    this.charDelay = options.charDelay || 0.03; // Delay per karakter (detik)
    this.baseDelay = options.baseDelay || 0; // Delay awal sebelum animasi mulai
  }

  // Setup text - pisahkan karakter dan wrap dengan span
  setup(element) {
    const text = element.getAttribute("data-text") || element.textContent;
    element.setAttribute("data-text", text); // Simpan text original
    element.innerHTML = "";

    text.split("").forEach((char, index) => {
      const span = document.createElement("span");
      span.textContent = char === " " ? "\u00A0" : char;
      span.style.animationDelay = `${this.baseDelay + (index * this.charDelay)}s`;
      element.appendChild(span);
    });
  }

  // Trigger animasi
  animate(element) {
    element.classList.add("animate");
  }

  // Reset animasi
  reset(element) {
    element.classList.remove("animate");
    // Force reflow untuk restart animasi
    void element.offsetWidth;
  }

  // Setup dan langsung animate
  setupAndAnimate(element, delay = 0) {
    this.setup(element);
    setTimeout(() => {
      this.animate(element);
    }, delay);
  }

  // Batch setup multiple elements
  setupMultiple(elements) {
    elements.forEach(element => {
      this.setup(element);
    });
  }

  // Batch animate multiple elements dengan delay increment
  animateMultiple(elements, delayBetween = 200) {
    elements.forEach((element, index) => {
      setTimeout(() => {
        this.animate(element);
      }, index * delayBetween);
    });
  }
}

// Instance default yang bisa digunakan global
const textSlideAnim = new TextSlideAnimation({
  charDelay: 0.03,
  baseDelay: 0
});

// Fungsi helper untuk Intersection Observer (animasi saat scroll ke view)
function initTextSlideOnScroll(selector, options = {}) {
  const elements = document.querySelectorAll(selector);
  const animation = new TextSlideAnimation(options);

  // Setup semua element
  elements.forEach(element => {
    animation.setup(element);
  });

  // Observer untuk trigger animasi saat terlihat
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting && !entry.target.classList.contains('animate')) {
        animation.animate(entry.target);
      }
    });
  }, {
    threshold: 0.5,
    rootMargin: '0px 0px -100px 0px'
  });

  elements.forEach(element => {
    observer.observe(element);
  });

  return { animation, observer };
}

// Slider functionality
let slider = document.querySelector(".home-slider .home-list");
let items = document.querySelectorAll(".home-slider .home-list .item");
let dots = document.querySelectorAll(".home-slider .slider-dots li");

let lengthItems = items.length - 1;
let active = 0;

function setupText(element) {
  const text = element.getAttribute("data-text");
  element.innerHTML = "";

  text.split("").forEach((char, index) => {
    const span = document.createElement("span");
    span.textContent = char === " " ? "\u00A0" : char;

    let delay = index * 0.05;

    if (element.classList.contains("description")) {
      delay += 0.3;
    }

    span.style.animationDelay = `${delay}s`;
    element.appendChild(span);
  });
}

let refreshInterval = setInterval(() => {
  active = active + 1 <= lengthItems ? active + 1 : 0;
  reloadSlider();
}, 5000);

function reloadSlider() {
  let last_active_item = document.querySelector(".home-slider .home-list .item.active");
  if (last_active_item) {
    last_active_item.classList.remove("active");
  }

  slider.style.left = -items[active].offsetLeft + "px";

  let last_active_dot = document.querySelector(".home-slider .slider-dots li.active");
  last_active_dot.classList.remove("active");
  dots[active].classList.add("active");

  clearInterval(refreshInterval);
  refreshInterval = setInterval(() => {
    active = active + 1 <= lengthItems ? active + 1 : 0;
    reloadSlider();
  }, 5000);

  setTimeout(() => {
    items[active].classList.add("active");
  }, 1000);
}

dots.forEach((li, key) => {
  li.addEventListener("click", () => {
    active = key;
    reloadSlider();
  });
});

window.onresize = function (event) {
  reloadSlider();
};

// Event 'load' yang baru
window.addEventListener("load", function () {
  items.forEach((item) => {
    const title = item.querySelector(".title");
    const description = item.querySelector(".description");
    if (title) setupText(title);
    if (description) setupText(description);
  });

  items[active].classList.add("active");
});

// Smooth blob animations
document.addEventListener("DOMContentLoaded", function () {
  const blobImages = document.querySelectorAll(".blob-image");
  const blobBgs = document.querySelectorAll(".blob-bg");

  // Animasi floating untuk background blobs
  function animateBlob(blob, index) {
    const duration = 4000 + index * 1000; // Durasi berbeda tiap blob
    const delay = index * 500; // Delay berbeda tiap blob
    const maxOffset = 20 + index * 5; // Offset berbeda tiap blob

    function animate() {
      const startTime = performance.now();

      function step(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = (elapsed % duration) / duration;

        // Smooth sine wave movement
        const yOffset = Math.sin(progress * Math.PI * 2) * maxOffset;
        const rotation = Math.sin(progress * Math.PI * 2) * 10;
        const scale = 1 + Math.sin(progress * Math.PI * 2) * 0.1;

        blob.style.transform = `
          translateY(${yOffset}px) 
          rotate(${45 + rotation}deg) 
          scale(${scale})
        `;

        requestAnimationFrame(step);
      }

      setTimeout(() => {
        requestAnimationFrame(step);
      }, delay);
    }

    animate();
  }

  // Terapkan animasi ke setiap background blob
  blobBgs.forEach((blob, index) => {
    animateBlob(blob, index);
  });

  // Hover effect untuk foto blobs
  blobImages.forEach((blob, index) => {
    const originalTransform = getComputedStyle(blob).transform;
    let currentRotation = 0;
    let currentScale = 1;

    // Parse rotation dari transform awal
    if (blob.classList.contains("blob-1")) currentRotation = -8;
    if (blob.classList.contains("blob-2")) currentRotation = 12;
    if (blob.classList.contains("blob-3")) currentRotation = 15;

    blob.addEventListener("mouseenter", function () {
      animateToHover(blob, currentRotation, currentScale);
    });

    blob.addEventListener("mouseleave", function () {
      animateToNormal(blob, currentRotation);
    });
  });

  // Animasi smooth saat hover
  function animateToHover(element, fromRotation, fromScale) {
    const duration = 400;
    const startTime = performance.now();

    function step(currentTime) {
      const elapsed = currentTime - startTime;
      const progress = Math.min(elapsed / duration, 1);

      // Easing function (ease-out-back)
      const easeProgress = 1 - Math.pow(1 - progress, 3);

      const rotation = fromRotation + (0 - fromRotation) * easeProgress;
      const scale = fromScale + (1.15 - fromScale) * easeProgress;

      if (element.classList.contains("blob-2")) {
        element.style.transform = `translateX(-50%) rotate(${rotation}deg) scale(${scale})`;
      } else {
        element.style.transform = `rotate(${rotation}deg) scale(${scale})`;
      }

      if (progress < 1) {
        requestAnimationFrame(step);
      }
    }

    requestAnimationFrame(step);
  }

  // Animasi smooth saat hover keluar
  function animateToNormal(element, toRotation) {
    const duration = 400;
    const startTime = performance.now();
    const currentTransform = getComputedStyle(element).transform;

    function step(currentTime) {
      const elapsed = currentTime - startTime;
      const progress = Math.min(elapsed / duration, 1);

      // Easing function (ease-out)
      const easeProgress = 1 - Math.pow(1 - progress, 2);

      const rotation = 0 + (toRotation - 0) * easeProgress;
      const scale = 1.15 + (1 - 1.15) * easeProgress;

      if (element.classList.contains("blob-2")) {
        element.style.transform = `translateX(-50%) rotate(${rotation}deg) scale(${scale})`;
      } else {
        element.style.transform = `rotate(${rotation}deg) scale(${scale})`;
      }

      if (progress < 1) {
        requestAnimationFrame(step);
      }
    }

    requestAnimationFrame(step);
  }
});
