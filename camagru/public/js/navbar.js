const toggleButton = document.querySelector('.navbar-toggle-button');
const navbarResponsive = document.querySelector('.navbar-responsive');

toggleButton.addEventListener('click', () => {
  navbarResponsive.classList.toggle('open');
});