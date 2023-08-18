    document.addEventListener("DOMContentLoaded", function() {
        const dropdowns = document.querySelectorAll('.dropdown-hover');
        const dropdownMenus = document.querySelectorAll('.dropdown-menu-hover');

        dropdowns.forEach(function(dropdown) {
            dropdown.addEventListener('mouseover', function() {
                dropdown.querySelector('.dropdown-toggle').click();
            });

            dropdown.addEventListener('mouseout', function() {
                dropdown.querySelector('.dropdown-toggle').click();
            });
        });

        dropdownMenus.forEach(function(menu) {
            menu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });


        AOS.init();
    });
