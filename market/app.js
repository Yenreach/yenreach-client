const closeIcon = document.querySelector(".bi-x");
const mobileToggle = document.querySelector(".bi-list");
const mobileNav = document.querySelector(".navbar ul");
const header = document.getElementById("header");

closeIcon.style.display = "none";

mobileToggle.addEventListener("click", () => {
  mobileNav.classList.add("ul-active");
  closeIcon.style.display = "block";
});

closeIcon.addEventListener("click", () => {
  mobileNav.classList.remove("ul-active");
  closeIcon.style.display = "none";
});

window.addEventListener("scroll", (e) => {
  if (scrollY >= 250) {
    header.style.background = "#fff";
  } else {
    header.style.background = "transparent";
  }
});
