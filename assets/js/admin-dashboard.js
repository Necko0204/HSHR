(function () {
    'use strict';
    const greeting = document.querySelector('[data-dashboard-greeting]');
    const clock = document.querySelector('[data-dashboard-time]');

    function updateDashboardTime() {
        const now = new Date();
        const hour = Number(new Intl.DateTimeFormat('en-US', {
            timeZone: 'Asia/Manila',
            hour: 'numeric',
            hour12: false
        }).format(now));
        if (greeting) {
            greeting.textContent = hour < 12 ? 'Good morning' : hour < 18 ? 'Good afternoon' : 'Good evening';
        }
        if (clock) {
            clock.textContent = new Intl.DateTimeFormat('en-US', {
                timeZone: 'Asia/Manila',
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            }).format(now);
            clock.dateTime = now.toISOString();
        }
    }

    updateDashboardTime();
    window.setInterval(updateDashboardTime, 60000);
})();
