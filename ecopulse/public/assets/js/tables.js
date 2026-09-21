document.addEventListener('DOMContentLoaded', () => {
    // Client-side table search
    const searchInputs = document.querySelectorAll('[data-table-search]');
    searchInputs.forEach(input => {
        input.addEventListener('input', (e) => {
            const tableId = e.target.getAttribute('data-table-search');
            const table = document.getElementById(tableId);
            if (!table) return;
            
            const term = e.target.value.toLowerCase();
            const tbody = table.querySelector('tbody');
            const rows = tbody.querySelectorAll('tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
        });
    });

    // Simple table sorting
    document.querySelectorAll('th[data-sortable]').forEach(th => {
        th.style.cursor = 'pointer';
        th.addEventListener('click', () => {
            const table = th.closest('table');
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            const index = Array.from(th.parentNode.children).indexOf(th);
            
            let isAsc = th.classList.contains('asc');
            
            table.querySelectorAll('th').forEach(h => h.classList.remove('asc', 'desc'));
            th.classList.add(isAsc ? 'desc' : 'asc');
            
            rows.sort((a, b) => {
                const aVal = a.children[index].textContent.trim();
                const bVal = b.children[index].textContent.trim();
                
                if (!isNaN(aVal) && !isNaN(bVal)) {
                    return isAsc ? bVal - aVal : aVal - bVal;
                }
                
                return isAsc ? bVal.localeCompare(aVal) : aVal.localeCompare(bVal);
            });
            
            rows.forEach(row => tbody.appendChild(row));
        });
    });
});
