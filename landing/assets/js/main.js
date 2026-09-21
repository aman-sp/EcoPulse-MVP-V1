// ============================================
// EcoPulse Landing Page — main.js
// ============================================

document.addEventListener("DOMContentLoaded", () => {

  // 1. Announcement bar dismiss
  const bar = document.getElementById("announcement-bar");
  const closeBar = document.getElementById("close-bar");
  if (closeBar && bar) {
    closeBar.addEventListener("click", () => {
      bar.style.display = "none";
      sessionStorage.setItem("bar-dismissed", "1");
    });
    if (sessionStorage.getItem("bar-dismissed")) bar.style.display = "none";
  }

  // 2. Sticky navbar shadow
  const navbar = document.getElementById("navbar");
  window.addEventListener("scroll", () => {
    navbar.classList.toggle("scrolled", window.scrollY > 10);
  });

  // 3. Hamburger menu
  const hamburger = document.getElementById("hamburger");
  const mobileNav = document.getElementById("mobile-nav");
  if (hamburger && mobileNav) {
    hamburger.addEventListener("click", () => {
      hamburger.classList.toggle("active");
      mobileNav.classList.toggle("open");
    });
    mobileNav.querySelectorAll("a").forEach(link => {
      link.addEventListener("click", () => {
        hamburger.classList.remove("active");
        mobileNav.classList.remove("open");
      });
    });
  }

  // 4. Smooth scroll for all anchor links
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener("click", e => {
      const target = document.querySelector(anchor.getAttribute("href"));
      if (target) {
        e.preventDefault();
        const offset = navbar ? navbar.offsetHeight + 8 : 72;
        window.scrollTo({ top: target.offsetTop - offset, behavior: "smooth" });
      }
    });
  });

  // 5. Scroll reveal with IntersectionObserver
  const revealEls = document.querySelectorAll(".reveal, .reveal-left, .reveal-right");
  const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add("visible");
        revealObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.12, rootMargin: "0px 0px -40px 0px" });
  revealEls.forEach(el => revealObserver.observe(el));

  // 6. Animated number counters
  function animateCounter(el) {
    const target = parseFloat(el.dataset.target);
    const suffix = el.dataset.suffix || "";
    const prefix = el.dataset.prefix || "";
    const decimals = el.dataset.decimals ? parseInt(el.dataset.decimals) : 0;
    const duration = 2000;
    const step = 16;
    const steps = duration / step;
    const increment = target / steps;
    let current = 0;
    const timer = setInterval(() => {
      current += increment;
      if (current >= target) {
        current = target;
        clearInterval(timer);
      }
      el.textContent = prefix + current.toFixed(decimals) + suffix;
    }, step);
  }

  const counterEls = document.querySelectorAll(".counter");
  const counterObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting && !entry.target.dataset.animated) {
        entry.target.dataset.animated = "1";
        animateCounter(entry.target);
        counterObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.5 });
  counterEls.forEach(el => counterObserver.observe(el));

  // 7. Animated mockup bars (hero)
  const bars = document.querySelectorAll(".mockup-bar-fill");
  const barObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.width = entry.target.dataset.width;
        barObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.5 });
  bars.forEach(bar => barObserver.observe(bar));

  // 8. Countdown timer (to India Net Zero 2070 target: Jan 1, 2070)
  function updateCountdown() {
    const target = new Date("2070-01-01T00:00:00");
    const now    = new Date();
    const diff   = target - now;
    if (diff <= 0) return;
    const days    = Math.floor(diff / (1000 * 60 * 60 * 24));
    const hours   = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((diff % (1000 * 60)) / 1000);
    const set = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = String(val).padStart(2, "0"); };
    set("cd-days",    days);
    set("cd-hours",   hours);
    set("cd-minutes", minutes);
    set("cd-seconds", seconds);
  }
  updateCountdown();
  setInterval(updateCountdown, 1000);

  // 9. Demo form submission (Formspree)
  const demoForm = document.getElementById("demo-form");
  const formSuccess = document.getElementById("form-success");
  if (demoForm) {
    demoForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      const btn = demoForm.querySelector(".form-submit");
      btn.textContent = "Sending…";
      btn.disabled = true;
      const data = new FormData(demoForm);
      try {
        const res = await fetch(demoForm.action, {
          method: "POST", body: data, headers: { "Accept": "application/json" }
        });
        if (res.ok) {
          demoForm.style.display = "none";
          if (formSuccess) formSuccess.style.display = "block";
        } else {
          btn.textContent = "Try Again";
          btn.disabled = false;
          alert("Something went wrong. Please email us directly at ecopulse@example.com");
        }
      } catch {
        btn.textContent = "Try Again";
        btn.disabled = false;
        alert("Network error. Please try again.");
      }
    });
  }

  // 10. Active nav link highlight on scroll
  const sections = document.querySelectorAll("section[id]");
  const navAnchors = document.querySelectorAll(".nav-links a");
  const activeObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        navAnchors.forEach(a => {
          a.style.color = a.getAttribute("href") === "#" + entry.target.id ? "var(--green)" : "";
        });
      }
    });
  }, { rootMargin: "-40% 0px -55% 0px" });
  sections.forEach(s => activeObserver.observe(s));

  // 11. Staggered reveal for feature cards
  document.querySelectorAll(".feature-card, .step-card, .testimonial-card").forEach((card, i) => {
    card.style.transitionDelay = `${i * 80}ms`;
    card.classList.add("reveal");
    revealObserver.observe(card);
  });

});
