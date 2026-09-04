(function () {
    'use strict';
    const greeting = document.querySelector('[data-staff-greeting]');
    if (!greeting) return;
    const hour = Number(new Intl.DateTimeFormat('en-US', { timeZone: 'Asia/Manila', hour: 'numeric', hour12: false }).format(new Date()));
    greeting.textContent = hour < 12 ? 'Good morning' : hour < 18 ? 'Good afternoon' : 'Good evening';
})();
