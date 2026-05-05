<script>
        document.addEventListener('DOMContentLoaded', () => {

        //// Get all "navbar-burger" elements
        //// const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);
        // Navbar burger (Bulma)...
        const navbarBurgers = Array.from(document.querySelectorAll(".navbar-burger"));

        // Add a click event on each of them...
        $navbarBurgers.forEach( el => {
                el.addEventListener('click', () => {

                        // Get the target from the "data-target" attribute...
                        const target = el.dataset.target;
                        //// const $target = document.getElementById(target);
                        const menu = document.getElementById(target);

                        // Toggle the "is-active" class on both the "navbar-burger" and the "navbar-menu"...
                        el.classList.toggle('is-active');
                        //// $target.classList.toggle('is-active');
                        if (menu) {
                                menu.classList.toggle("is-active");
                        }
                });
        });

});
</script>

<script src="./js/ajax.js"></script>