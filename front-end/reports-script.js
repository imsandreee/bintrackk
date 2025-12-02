document.addEventListener('DOMContentLoaded', function() {
    
    // ==========================================================
    // ACTION DROPDOWN (EDIT/DELETE) INTERACTIVITY 
    // This is the only function needed for the Reports Table.
    // ==========================================================

    const reportsTable = document.querySelector('.reports-table');
    
    // Store a reference to the currently open dropdown element
    let activeDropdown = null; 

    // 1. Toggle the dropdown menu on button click (Delegated Event)
    reportsTable.addEventListener('click', function(e) {
        const targetBtn = e.target.closest('.action-btn');

        if (targetBtn) {
            e.preventDefault();
            const dropdown = targetBtn.closest('.action-dropdown');

            // Logic for toggling the dropdown
            if (dropdown === activeDropdown) {
                // If clicking the same one, close it
                dropdown.classList.remove('active');
                activeDropdown = null;
            } else {
                // Close any currently open dropdown
                if (activeDropdown) {
                    activeDropdown.classList.remove('active');
                }
                // Open the new dropdown
                dropdown.classList.add('active');
                activeDropdown = dropdown;
            }
        } 
        
        // 2. Handle the delete action click (Delegated Event)
        const deleteLink = e.target.closest('.delete-action');
        if (deleteLink) {
            e.preventDefault(); 
            
            const reportId = deleteLink.getAttribute('data-id');

            if (confirm(`Are you sure you want to delete Report #${reportId}? This action cannot be undone.`)) {
                // In a real application, send a DELETE request here.
                alert(`Report #${reportId} has been virtually deleted.`);
                
                const row = deleteLink.closest('tr');
                row.remove();
            }
            // Ensure the dropdown closes after the action
            if (activeDropdown) {
                activeDropdown.classList.remove('active');
                activeDropdown = null;
            }
        }
    });

    // 3. Close the dropdowns if the user clicks anywhere outside of them
    window.addEventListener('click', function(e) {
        // If there is an active dropdown AND the click target is NOT inside that dropdown
        if (activeDropdown && !activeDropdown.contains(e.target) && !e.target.matches('.action-btn') && !e.target.closest('.action-btn')) {
            activeDropdown.classList.remove('active');
            activeDropdown = null;
        }
    });
});