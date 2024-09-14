document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('.burger-menu').addEventListener('click', function() {
        document.querySelector('.navbar-box').classList.toggle('open');
    });
});
