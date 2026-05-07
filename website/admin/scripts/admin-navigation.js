function pageFadeIn() {
  const main = document.querySelector('main');
  const footer = document.querySelector('footer');

  setTimeout(() => {
    if (main) main.classList.add('fade-in');
    if (footer) footer.classList.add('fade-in');
  }, 10);
}

function pageFadeOut(href) {
  // fade out effect and redirect to other html pages
  const main = document.querySelector('main');
  const footer = document.querySelector('footer');
  
  if (main) {
    main.classList.add('fade-out');
  }
  if (footer) {
    footer.classList.add('fade-out');
  }

  setTimeout(()=> {
    window.location.href = href;
  }, 600);
}

document.addEventListener('DOMContentLoaded', () => {
  pageFadeIn();
  const pages = document.querySelectorAll('a');

  pages.forEach(page => {
    page.addEventListener('click', e => {
      const href = page.getAttribute('href');
      
      if (!href || href.startsWith('#')) return;

      e.preventDefault();
      pageFadeOut(href);
    });
  });
});

// Login/SignUp Page Home Button
// From login/signup page, it will redirect back to home page
const homeBtn = document.querySelector('.home-icon');

if (homeBtn) {
  homeBtn.addEventListener('click', ()=>{
    pageFadeOut('../../user/pages/home.php');
  });
}

// User Login/Sign-up Page Redirect Button
const userRedirectBtn = document.querySelector('.user-login');

if (userRedirectBtn) {
  userRedirectBtn.addEventListener('click', () => {
    pageFadeOut('../../user/pages/login-signup.php');
  });
}

// Sidebar
const userPfp = document.querySelector('.user-profile');
const sidebar_overlay = document.querySelector('.sidebar-overlay');
const sidebar = document.querySelector('.sidebar');
const exitSidebar = document.querySelector('.exit-sidebar');

if (userPfp) {
  userPfp.addEventListener('click', () => {
    sidebar_overlay.style.display = 'block';

    sidebar.style.animation = 'none';
    sidebar.offsetHeight;

    sidebar_overlay.style.animation = 'overlay-entry 0.6s ease forwards';
    sidebar.style.animation = 'sidebar-entry 0.6s ease forwards';
  });
}

if (exitSidebar) {
  exitSidebar.addEventListener('click', () => {
    sidebar_overlay.style.animation = 'overlay-exit 0.6s ease forwards';
    sidebar.style.animation = 'sidebar-exit 0.6s ease forwards';

    setTimeout(() => {
      sidebar_overlay.style.display = 'none';

      sidebar_overlay.style.animation = '';
      sidebar.style.animation = '';

      sidebar_overlay.style.opacity = '0';
      sidebar.style.transform = 'translateX(100%)';
      sidebar.style.opacity = '0';
    }, 600);
  });
}
