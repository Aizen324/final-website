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


// BUTTONS FUNCTIONALITY

// Logo Button
// Will redirect to home page
const logoBtn = document.querySelector('.logo');
if (logoBtn) {
  logoBtn.addEventListener('click', (e) => {
    e.preventDefault();
    pageFadeOut('home.php');
  });
}

// Sign In Button
const signInBtn = document.querySelector('.sign-in-btn');
if (signInBtn) {
  signInBtn.addEventListener('click', () => {
    pageFadeOut('login-signup.php');
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

// Home Page Learn More Button
// Will redirect to services page
const learnMoreBtn = document.querySelector('.services-btn');
if (learnMoreBtn) {
  learnMoreBtn.addEventListener('click', () => {
    pageFadeOut('services.php');
  });
}

// Home Page Read More Button
// Will redirect to about page
const readMoreBtn = document.querySelector('.about-btn');
if (readMoreBtn) {
  readMoreBtn.addEventListener('click', ()=>{
    pageFadeOut('about.php');
  });
}

// Login/SignUp Page Home Button
// From login/signup page, it will redirect back to home page
const homeBtn = document.querySelector('.home-icon');

if (homeBtn) {
  homeBtn.addEventListener('click', ()=>{
    pageFadeOut('home.php');
  });
}

// Login as Admin Button
const adminRedirectBtn = document.querySelector('.admin-login');

if (adminRedirectBtn) {
  adminRedirectBtn.addEventListener('click', () => {
    pageFadeOut('../../admin/pages/admin-login.php');
  });
}

// Services See More Adoption and Rehoming Page
const adoptionBtn = document.querySelector('.adoption-btn');
const rehomingBtn = document.querySelector('.rehoming-btn');
const noUser = document.querySelectorAll('.no-user');

if (noUser.length > 0) {
  noUser.forEach(element => {
    element.addEventListener('click', () => {
      alert("Currently not signed in.");
    });
  });
}

if (adoptionBtn) {
  adoptionBtn.addEventListener('click', () => {
    pageFadeOut('adoption-page.php');
  });
}
if (rehomingBtn) {
  rehomingBtn.addEventListener('click', () => {
    pageFadeOut('rehoming-page.php');
  });
}

// Redirect to rehoming form from rehoming page
const addBtn = document.querySelector('.add-card');

if (addBtn) {
  addBtn.addEventListener('click',() => {
    pageFadeOut('rehoming-form.php');
  });
}

// Cancel button in rehoming form
// Goes back to rehoming page
const cancelBtn = document.querySelector('.cancel-form-btn');

if (cancelBtn) {
  cancelBtn.addEventListener('click', () => {
    window.location.href = "rehoming-page.php";
  });
}