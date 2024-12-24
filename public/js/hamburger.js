let hamburger = document.querySelectorAll("header>ul:last-child>li>a")[0]
let nav = document.querySelectorAll("header>ul:nth-child(3)")[0]
hamburger.addEventListener('click', () => {
    nav.classList.toggle('active');
})