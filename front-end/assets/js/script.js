document.addEventListener('DOMContentLoaded', () => {

    // --- Real-Time Clock ---
    function updateTime() {
        const now = new Date();
        document.getElementById('last-updated').textContent = now.toLocaleTimeString('en-US', { 
            hour: '2-digit', minute: '2-digit', second: '2-digit' 
        });
    }
    updateTime();
    setInterval(updateTime, 1000); // Update every second

    // --- Summary Card Calculation ---
    function updateSummaryCards() {
        const totalBins = binDataList.length;
        const needsCollection = binDataList.filter(bin => bin.fill >= 75).length;
        const offlineSensors = binDataList.filter(bin => bin.status === 'Offline').length;
        const totalWeight = binDataList.reduce((sum, bin) => sum + bin.weight, 0);

        document.getElementById('total-bins').textContent = totalBins;
        document.getElementById('full-bins').textContent = needsCollection;
        document.getElementById('offline-sensors').textContent = offlineSensors;
        document.getElementById('total-weight').textContent = `${totalWeight.toFixed(1)} kg`;
    }

    // --- Table Rendering ---
    const binStatusTableBody = document.getElementById('bin-status-table-body');

    function getFillLevelColor(level) {
        if (level >= 90) return 'bg-red-500';
        if (level >= 75) return 'bg-yellow-500';
        if (level >= 50) return 'bg-blue-500';
        return 'bg-green-500';
    }

    function getStatusBadge(status) {
        const color = status === 'Online' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
        return `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${color}">${status}</span>`;
    }

    function renderBinStatusTable() {
        binStatusTableBody.innerHTML = '';
        binDataList.forEach(bin => {
            const row = document.createElement('tr');
            const fillColor = getFillLevelColor(bin.fill);

            row.className = bin.status === 'Offline' ? 'bg-gray-100 hover:bg-gray-200 transition' : 'hover:bg-gray-50 transition';
            row.innerHTML = `
                <td class="px-3 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${bin.id}</td>
                <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">${bin.location}</td>
                <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">
                    <div class="flex items-center space-x-2">
                        <div class="fill-level-bar">
                            <div style="width: ${bin.fill}%;" class="h-full ${fillColor} rounded-full transition-all duration-500"></div>
                        </div>
                        <span class="w-10 text-right font-medium text-xs">${bin.fill}%</span>
                    </div>
                </td>
                <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">${bin.weight.toFixed(1)}</td>
                <td class="px-3 py-4 whitespace-nowrap">${getStatusBadge(bin.status)}</td>
                <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">${bin.lastUpdate}</td>
            `;
            binStatusTableBody.appendChild(row);
        });
    }

    // --- Chart Rendering ---
    const statusDistributionCtx = document.getElementById('statusDistributionChart').getContext('2d');
    let statusChart; // global chart instance

    function renderStatusChart() {
        const counts = binDataList.reduce((acc, bin) => {
            let group = 'Low (0-50%)';
            if (bin.fill >= 90) group = 'Full (Need Immediate Collection)';
            else if (bin.fill >= 75) group = 'High (75-90%)';
            else if (bin.fill >= 50) group = 'Medium (50-75%)';
            acc[group] = (acc[group] || 0) + 1;
            return acc;
        }, {});

        const labels = ['Full (Need Immediate Collection)', 'High (75-90%)', 'Medium (50-75%)', 'Low (0-50%)'];
        const dataValues = labels.map(label => counts[label] || 0);

        if (statusChart) {
            statusChart.data.datasets[0].data = dataValues;
            statusChart.update();
        } else {
            statusChart = new Chart(statusDistributionCtx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Number of Bins',
                        data: dataValues,
                        backgroundColor: ['#ef4444', '#f59e0b', '#3b82f6', '#10b981'],
                        borderColor: ['#b91c1c', '#d97706', '#2563eb', '#059669'],
                        borderWidth: 1,
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    scales: {
                        x: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: 'rgba(0,0,0,0.05)' } },
                        y: { grid: { display: false } }
                    },
                    plugins: { legend: { display: false }, title: { display: false } }
                }
            });
        }
    }

    // --- Quick Action Handler ---
    function handleQuickAction(action) {
        console.log(`Action requested: ${action}`);
        const messageBox = document.createElement('div');
        messageBox.className = 'fixed top-4 right-4 bg-green-600 text-white px-6 py-3 rounded-xl shadow-2xl transition-opacity duration-300 z-50';
        messageBox.textContent = `"${action}" initiated. Simulating task deployment...`;
        document.body.appendChild(messageBox);
        setTimeout(() => {
            messageBox.style.opacity = '0';
            setTimeout(() => messageBox.remove(), 300);
        }, 3000);
    }
    window.handleQuickAction = handleQuickAction;

    // --- Initial Render ---
    updateSummaryCards();
    renderBinStatusTable();
    renderStatusChart();

    // --- Auto Refresh (Optional) ---
    setInterval(() => {
        updateSummaryCards();
        renderBinStatusTable();
        renderStatusChart();
    }, 5000); // refresh every 5 seconds

});
