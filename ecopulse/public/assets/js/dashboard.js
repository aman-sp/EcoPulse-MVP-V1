function initDashboardCharts() {
    if (typeof Chart === 'undefined') {
        console.warn('Chart.js is not yet loaded, retrying...');
        setTimeout(initDashboardCharts, 100);
        return;
    }

    // Load data from window or JSON script element
    let data = window.dashboardData || {};
    const dataEl = document.getElementById('dashboard-data');
    if (dataEl) {
        try {
            const parsed = JSON.parse(dataEl.textContent);
            data = Object.assign({}, data, parsed);
        } catch (e) {
            console.warn('Could not parse dashboard data JSON:', e);
        }
    }

    // Default fallback months if none present
    const defaultMonths = ['Jan 2024', 'Feb 2024', 'Mar 2024', 'Apr 2024', 'May 2024', 'Jun 2024'];
    const months = (data.months && data.months.length > 0) 
        ? data.months 
        : ((data.carbonTrend && data.carbonTrend.labels && data.carbonTrend.labels.length > 0) 
            ? data.carbonTrend.labels 
            : defaultMonths);

    const isDark = () => document.documentElement.getAttribute('data-theme') === 'dark';
    const getTextColor = () => isDark() ? '#F1F5F9' : '#1E293B';
    const getGridColor = () => isDark() ? 'rgba(255, 255, 255, 0.08)' : 'rgba(0, 0, 0, 0.06)';

    Chart.defaults.font.family = "'Inter', -apple-system, BlinkMacSystemFont, sans-serif";
    Chart.defaults.color = getTextColor();
    Chart.defaults.scale.grid.color = getGridColor();

    window.addEventListener('themeChanged', () => {
        Chart.defaults.color = getTextColor();
        Chart.defaults.scale.grid.color = getGridColor();
        Object.values(Chart.instances).forEach(chart => chart.update());
    });

    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        animation: { duration: 600 },
        interaction: {
            mode: 'index',
            intersect: false,
        },
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(15, 23, 42, 0.92)',
                titleColor: '#FFFFFF',
                bodyColor: '#E2E8F0',
                padding: 10,
                cornerRadius: 8,
                titleFont: { weight: '600', size: 12 },
                bodyFont: { size: 12 }
            }
        },
        scales: {
            x: {
                grid: { display: false },
                ticks: { color: getTextColor(), font: { size: 11 } }
            },
            y: {
                beginAtZero: true,
                grid: { color: getGridColor() },
                ticks: { color: getTextColor(), font: { size: 11 } }
            }
        }
    };

    function createGradient(ctx, colorRgb) {
        try {
            const gradient = ctx.createLinearGradient(0, 0, 0, 260);
            gradient.addColorStop(0, `rgba(${colorRgb}, 0.35)`);
            gradient.addColorStop(1, `rgba(${colorRgb}, 0.0)`);
            return gradient;
        } catch (e) {
            return `rgba(${colorRgb}, 0.2)`;
        }
    }

    // 1. Electricity Chart (kWh)
    const ctxElec = document.getElementById('electricityChart');
    if (ctxElec) {
        const rawValues = data.electricity || (data.chart_data && data.chart_data.electricity) || [];
        const values = rawValues.length > 0 ? rawValues : months.map(() => 0);
        const ctx = ctxElec.getContext('2d');
        new Chart(ctxElec, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Electricity (kWh)',
                    data: values,
                    borderColor: '#10B981',
                    backgroundColor: createGradient(ctx, '16, 185, 129'),
                    fill: true,
                    tension: 0.35,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#10B981',
                    pointBorderColor: '#FFFFFF',
                    pointBorderWidth: 1.5,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: commonOptions
        });
    }

    // 2. Water Chart (kL)
    const ctxWater = document.getElementById('waterChart');
    if (ctxWater) {
        const rawValues = data.water || (data.chart_data && data.chart_data.water) || [];
        const values = rawValues.length > 0 ? rawValues : months.map(() => 0);
        new Chart(ctxWater, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Water (kL)',
                    data: values,
                    backgroundColor: '#0EA5E9',
                    borderRadius: 6,
                    maxBarThickness: 32
                }]
            },
            options: commonOptions
        });
    }

    // 3. Diesel Chart (Liters)
    const ctxDiesel = document.getElementById('dieselChart');
    if (ctxDiesel) {
        const rawValues = data.diesel || (data.chart_data && data.chart_data.diesel) || [];
        const values = rawValues.length > 0 ? rawValues : months.map(() => 0);
        const ctx = ctxDiesel.getContext('2d');
        new Chart(ctxDiesel, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Diesel Used (L)',
                    data: values,
                    borderColor: '#F59E0B',
                    backgroundColor: createGradient(ctx, '245, 158, 11'),
                    fill: true,
                    tension: 0.35,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#F59E0B',
                    pointBorderColor: '#FFFFFF',
                    pointBorderWidth: 1.5,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: commonOptions
        });
    }

    // 4. Waste Chart (kg)
    const ctxWaste = document.getElementById('wasteChart');
    if (ctxWaste) {
        const rawValues = data.waste || (data.chart_data && data.chart_data.waste) || [];
        const values = rawValues.length > 0 ? rawValues : months.map(() => 0);
        new Chart(ctxWaste, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Waste (kg)',
                    data: values,
                    backgroundColor: '#EC4899',
                    borderRadius: 6,
                    maxBarThickness: 32
                }]
            },
            options: commonOptions
        });
    }

    // 5. Total Carbon Footprint Trend (tCO2e)
    const ctxCarbon = document.getElementById('carbonChart') || document.getElementById('carbonTrendChart');
    if (ctxCarbon) {
        const rawValues = data.carbon || (data.carbonTrend ? data.carbonTrend.values : []) || (data.chart_data ? data.chart_data.carbon : []) || [];
        const values = rawValues.length > 0 ? rawValues : months.map(() => 0);
        const ctx = ctxCarbon.getContext('2d');
        new Chart(ctxCarbon, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Total Emissions (tCO2e)',
                    data: values,
                    borderColor: '#10B981',
                    backgroundColor: createGradient(ctx, '16, 185, 129'),
                    fill: true,
                    tension: 0.35,
                    borderWidth: 3,
                    pointBackgroundColor: '#10B981',
                    pointBorderColor: '#FFFFFF',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: Object.assign({}, commonOptions, {
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (context) => ` ${context.parsed.y !== null ? context.parsed.y.toFixed(2) : 0} tCO2e`
                        }
                    }
                }
            })
        });
    }

    // 6. Monthly Submissions Chart (Admin)
    const ctxMonthly = document.getElementById('monthlySubmissionChart');
    if (ctxMonthly) {
        const subLabels = (data.monthlySubmissions && data.monthlySubmissions.labels) || months;
        const subValues = (data.monthlySubmissions && data.monthlySubmissions.values) || subLabels.map(() => 0);
        new Chart(ctxMonthly, {
            type: 'bar',
            data: {
                labels: subLabels,
                datasets: [{
                    label: 'Hospital Submissions',
                    data: subValues,
                    backgroundColor: '#6366F1',
                    borderRadius: 6,
                    maxBarThickness: 36
                }]
            },
            options: Object.assign({}, commonOptions, {
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (context) => ` ${context.parsed.y} Submissions`
                        }
                    }
                }
            })
        });
    }

    // 7. Hospital Registration Trend Chart (Admin)
    const ctxHosp = document.getElementById('hospitalTrendChart');
    if (ctxHosp) {
        const hospLabels = (data.hospitalTrend && data.hospitalTrend.labels) || (data.monthlySubmissions && data.monthlySubmissions.labels) || months;
        const hospValues = (data.hospitalTrend && data.hospitalTrend.values) || hospLabels.map((_, i) => i + 1);
        const ctx = ctxHosp.getContext('2d');
        new Chart(ctxHosp, {
            type: 'line',
            data: {
                labels: hospLabels,
                datasets: [{
                    label: 'Registered Hospitals',
                    data: hospValues,
                    borderColor: '#3B82F6',
                    backgroundColor: createGradient(ctx, '59, 130, 246'),
                    fill: true,
                    tension: 0.3,
                    borderWidth: 3,
                    pointBackgroundColor: '#3B82F6',
                    pointBorderColor: '#FFFFFF',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: Object.assign({}, commonOptions, {
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (context) => ` ${context.parsed.y} Hospitals Registered`
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: getTextColor(), font: { size: 11 } }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0,
                            color: getTextColor(),
                            font: { size: 11 }
                        },
                        grid: { color: getGridColor() }
                    }
                }
            })
        });
    }
}

// Auto-run when DOM is ready or immediately if already loaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initDashboardCharts);
} else {
    initDashboardCharts();
}


