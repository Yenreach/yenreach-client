'use strict';

const hamburger = document.querySelector('.mobile-nav-toggle');
const closeNav = document.querySelector('.close');
const navBar = document.querySelector('#navbar');
const header = document.querySelector('#header');
const getStarted = document.querySelector('.get-started-btn');
const heroContainer = document.querySelectorAll('.row-top');

const loginPassword = document.querySelector('.login-password');
const passwordIcon = document.querySelector('.login-password i');
const loginInput = document.querySelector('.login-input');

const signUpPassword = document.querySelector('.sign-up-password');
const signIcon = document.querySelector('.sign-up-password i');
const signInput = document.querySelector('.sign-up-input');

const confirmPassword = document.querySelector('.confirm-password');
const confirmIcon = document.querySelector('.confirm-password i');
const confirmInput = document.querySelector('.confirm-input');

const overlay = document.querySelector('.overlay');
const acceptButton = document.querySelector('.accept-button');


// TOGGLE NAVBAR

const showToggle = () => {
  closeNav.classList.add('toggle');
  hamburger.addEventListener('click', () => {
    navBar.classList.add('navbar-mobile');
    navBar.children[1].classList.add('toggle');
    navBar.children[2].classList.remove('toggle');
  });

  closeNav.addEventListener('click', () => {
    navBar.classList.remove('navbar-mobile');
    // navBar.children[2].style.display = 'none';
    navBar.children[2].classList.add('toggle');
    navBar.children[1].classList.remove('toggle');

    // navBar.children[1].style.display = 'block';
  });
};
showToggle();
// ON SCROLL

const showNabar = () => {
  window.addEventListener('scroll', e => {
    if (window.scrollY > 100) {
      header.classList.add('.header-scrolled');
      getStarted.style.backgroundColor = '#fff';
      getStarted.style.color = '#00c853';
    } else {
      header.style.backgroundColor = 'transparent';
    //   Link.style.color = '#fff';
      getStarted.style.color = '#fff';
      getStarted.style.backgroundColor = '#00c853';
    }
  });
};

window.addEventListener('load', () => {
  // showNabar();
});

// CAROUSEL FUNCIONALITY
const heroSliderArray = [...heroContainer];

let position = 0;

const hideSlider = () => {
  heroSliderArray.forEach(sliderElement => {
    sliderElement.style.display = 'none';
  });
};
hideSlider();

// SHOW THE FIRST CAROUSEL
const showFirstSlider = () => {
  heroSliderArray[position].style.display = 'block';
};
showFirstSlider();

// GO TO THE NEXT CAROUSEL
const nextSlider = () => {
  // HIDE IMAGES
  hideSlider();

  if (position === heroSliderArray.length - 1) {
    position = 0;
  } else {
    position++;
  }
  heroSliderArray[position].style.display = 'block';
};
// GO TO THE PREV CAROUSEL
const prevSlider = () => {
  // HIDE IMAGES
  hideSlider();

  if (position === 0) {
    position = heroSliderArray.length - 1;
  } else {
    position--;
  }
  heroSliderArray[position].style.display = 'block';
};

// EVENLISTENER FOR THE SLIDER
// SET INTERVAL FOR THE SLIDER

setInterval(() => {
  nextSlider();
}, 3000);





window.addEventListener('load', () => {
  setTimeout(() => {
    overlay.classList.add('add-overlay');
  }, 5000);
});

acceptButton.addEventListener('click', () => {
  overlay.classList.remove('add-overlay');
});


