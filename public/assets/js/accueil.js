document.addEventListener("DOMContentLoaded", function() {
    const tbody = document.getElementById('scrollable-tbody');
        const rows = tbody.getElementsByTagName('tr');
        const rowsPerPage = 5;
        let currentPage = 0;
        let scrollingDirection = 'down'; // Direction du défilement automatique

        function showPage(pageNumber) {
        const startIndex = pageNumber * rowsPerPage;
        const endIndex = startIndex + rowsPerPage;

        for (let i = 0; i < rows.length; i++) {
            if (i >= startIndex && i < endIndex) {
            rows[i].style.display = 'table-row';
            } else {
            rows[i].style.display = 'none';
            }
        }
        }

        function scrollPage(direction) {
        if (direction === 'up') {
            if (currentPage > 0) {
            currentPage--;
            showPage(currentPage);
            }
        } else if (direction === 'down') {
            if (currentPage < Math.ceil(rows.length / rowsPerPage) - 1) {
            currentPage++;
            showPage(currentPage);
            } else {
            // Revenir au début du défilement
            currentPage = 0;
            showPage(currentPage);
            }
        }
        }

        // Défilement automatique toutes les 3 secondes vers le bas
        setInterval(() => {
        scrollPage(scrollingDirection);
        }, 3000);

        showPage(currentPage);

        const scrollUpBtn = document.getElementById('scrollUpBtn');
        scrollUpBtn.addEventListener('click', () => scrollPage('up'));

        const scrollDownBtn = document.getElementById('scrollDownBtn');
        scrollDownBtn.addEventListener('click', () => scrollPage('down'));


    // Adhesion
    document.querySelectorAll('.card-rotation').forEach(card => {
        card.addEventListener('mouseenter', () => {
          card.querySelector('.card-inner').classList.add('rotate');
          console.log('survoler')
        });
      
        card.addEventListener('mouseleave', () => {
          card.querySelector('.card-inner').classList.remove('rotate');
          console.log('quitter')
        });
      });
      
});
