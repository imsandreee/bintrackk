// --- Map Initialization ---

// 1. Initialize the map
// We set the initial view to a central location (e.g., coordinates for a city center) and a zoom level.
const map = L.map('binMap').setView([14.5995, 120.9842], 13); // Example: Manila, Philippines

// 2. Add the Tile Layer (The actual map background)
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);


// --- Bin Data Simulation ---

// Simulating bin data with coordinates and fill status
const binData = [
    { lat: 14.6050, lon: 120.9850, status: 'full', id: 101 },
    { lat: 14.5900, lon: 120.9950, status: 'half', id: 102 },
    { lat: 14.6000, lon: 120.9750, status: 'empty', id: 103 },
    { lat: 14.5920, lon: 120.9790, status: 'full', id: 104 },
    { lat: 14.6100, lon: 120.9880, status: 'half', id: 105 },
];

// --- Custom Marker Icons ---

// Function to get the correct icon class based on status
function getIconColor(status) {
    if (status === 'full') return 'red';
    if (status === 'half') return 'orange';
    return 'green';
}

// Custom DivIcon for status-based coloring
binData.forEach(bin => {
    const statusColor = getIconColor(bin.status);

    // Create a custom icon using a DivIcon
    const binIcon = L.divIcon({
        className: 'custom-bin-marker ' + statusColor,
        html: '<i class="fas fa-trash-can"></i>', // Font Awesome trash icon
        iconSize: [30, 30],
        iconAnchor: [15, 30] // Centers the icon point
    });

    // Add marker to the map
    L.marker([bin.lat, bin.lon], { icon: binIcon })
        .addTo(map)
        .bindPopup(`**Bin ID: ${bin.id}**<br>Status: ${bin.status.toUpperCase()}<br>Last Update: ${new Date().toLocaleTimeString()}`);
});

// --- Simple Form Submission Handling (Bonus) ---

const reportForm = document.querySelector('.report-issue-card form');
reportForm.addEventListener('submit', function(e) {
    e.preventDefault();

    const issueType = document.getElementById('issue-type').value;
    const description = this.querySelector('textarea').value;

    if (issueType && description) {
        alert(`Report Submitted!\nType: ${issueType}\nDescription: ${description}\nThank you for helping keep the city clean!`);
        // In a real application, you would send this data to a server using the Fetch API.
        reportForm.reset();
    } else {
        alert("Please select an issue type and provide a description.");
    }
});