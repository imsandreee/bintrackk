document.addEventListener('DOMContentLoaded', function() {
    
    // ==========================================================
    // 1. LEAFLET MAP INITIALIZATION & MARKERS
    // ==========================================================

    // Initialize the map: centered on an example location with a good zoom level
    const map = L.map('binMap').setView([14.5995, 120.9842], 13); 

    // Add the Tile Layer (The actual map background)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Simulated bin data with coordinates and fill status
    const binData = [
        { lat: 14.6050, lon: 120.9850, status: 'full', id: 101, fill: '95%' },
        { lat: 14.5900, lon: 120.9950, status: 'half', id: 102, fill: '55%' },
        { lat: 14.6000, lon: 120.9750, status: 'empty', id: 103, fill: '20%' },
        { lat: 14.5920, lon: 120.9790, status: 'full', id: 104, fill: '88%' },
        { lat: 14.6100, lon: 120.9880, status: 'half', id: 105, fill: '40%' },
    ];

    // Function to get the correct icon class based on status
    function getIconColor(status) {
        if (status === 'full') return 'red';
        if (status === 'half') return 'orange';
        return 'green';
    }

    // Add markers to the map
    binData.forEach(bin => {
        const statusColor = getIconColor(bin.status);

        // Custom DivIcon for status-based coloring
        const binIcon = L.divIcon({
            className: 'custom-bin-marker ' + statusColor,
            html: '<i class="fas fa-trash-can"></i>', 
            iconSize: [30, 30],
            iconAnchor: [15, 30] 
        });

        // Add marker to the map with a popup
        L.marker([bin.lat, bin.lon], { icon: binIcon })
            .addTo(map)
            .bindPopup(`
                **Bin ID: ${bin.id}**<br>
                Status: ${bin.status.toUpperCase()}<br>
                Fill Level: ${bin.fill}<br>
                Last Update: ${new Date().toLocaleTimeString()}
            `);
    });


    // ==========================================================
    // 2. REPORT ISSUE FORM SUBMISSION
    // ==========================================================

    const reportForm = document.querySelector('.report-issue-card form');
    reportForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const issueType = document.getElementById('issue-type').value;
        const description = this.querySelector('textarea').value;

        if (issueType && description) {
            // In a real system, use fetch() to send data to the server
            alert(`Report Submitted!\nType: ${issueType}\nDescription: ${description}\nThank you for helping keep the city clean!`);
            
            // For dashboard demo purposes: simulate success and reset form
            reportForm.reset();
        } else {
            alert("Please select an issue type and provide a description.");
        }
    });


});
