document.addEventListener('DOMContentLoaded', function() {
    const rows = Array.from(document.querySelectorAll('table tbody tr'));
    const itemsPerPageSelect = document.getElementById('itemsPerPage');
    const prevPageBtn = document.getElementById('prevPage');
    const nextPageBtn = document.getElementById('nextPage');
    const currentPageDisplay = document.getElementById('currentPage');
    const totalPagesDisplay = document.getElementById('totalPages');

    let currentPage = 1;
    let itemsPerPage = parseInt(itemsPerPageSelect.value, 10);

    function updateTable() {
        // Calculate total pages
        const totalPages = Math.ceil(rows.length / itemsPerPage);
        totalPagesDisplay.textContent = totalPages;

        // Hide all rows
        rows.forEach(row => row.style.display = 'none');

        // Show only the rows for the current page
        const startIdx = (currentPage - 1) * itemsPerPage;
        const endIdx = startIdx + itemsPerPage;
        rows.slice(startIdx, endIdx).forEach(row => row.style.display = '');

        // Update page display
        currentPageDisplay.textContent = currentPage;

        // Enable/disable buttons
        prevPageBtn.disabled = currentPage === 1;
        nextPageBtn.disabled = currentPage === totalPages;
    }

    // Event Listeners
    itemsPerPageSelect.addEventListener('change', (e) => {
        itemsPerPage = parseInt(e.target.value, 10);
        currentPage = 1;
        updateTable();
    });

    prevPageBtn.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            updateTable();
        }
    });

    nextPageBtn.addEventListener('click', () => {
        const totalPages = Math.ceil(rows.length / itemsPerPage);
        if (currentPage < totalPages) {
            currentPage++;
            updateTable();
        }
    });

    // Initial table update
    updateTable();
});
