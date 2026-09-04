(() => {
    'use strict';

    const modalElement = document.getElementById('scheduleBuilderModal');
    if (!modalElement) return;

    const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    const shortDays = ['MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN'];
    const presets = {
        standard: {
            label: 'Standard teacher', activeDays: days.slice(0, 5),
            start: '07:30', end: '16:30', breakStart: '12:00', breakEnd: '13:00'
        },
        early: {
            label: 'Early teacher', activeDays: days.slice(0, 5),
            start: '06:30', end: '15:30', breakStart: '11:30', breakEnd: '12:30'
        },
        morning: {
            label: 'Morning part-time', activeDays: days.slice(0, 5),
            start: '07:30', end: '12:00', breakStart: '', breakEnd: ''
        },
        compressed: {
            label: 'Four-day week', activeDays: days.slice(0, 4),
            start: '07:00', end: '17:30', breakStart: '12:00', breakEnd: '12:30'
        }
    };

    const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
    const grid = modalElement.querySelector('[data-week-grid]');
    const content = modalElement.querySelector('[data-builder-content]');
    const loading = modalElement.querySelector('[data-builder-loading]');
    const employeeIdInput = modalElement.querySelector('[data-builder-employee-id]');
    const employeeLabel = modalElement.querySelector('[data-builder-employee]');
    const alertBox = modalElement.querySelector('[data-builder-alert]');
    const saveButton = modalElement.querySelector('[data-save-schedule]');
    const clearButton = modalElement.querySelector('[data-clear-schedule]');
    const summaryDays = modalElement.querySelector('[data-summary-days]');
    const summaryHours = modalElement.querySelector('[data-summary-hours]');
    const summaryAverage = modalElement.querySelector('[data-summary-average]');
    let currentPreset = 'custom';
    let hasExistingSchedule = false;

    const escapeHtml = (value) => String(value).replace(/[&<>'"]/g, character => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;'
    })[character]);

    const rowMarkup = (day, index) => `
        <article class="week-row" data-day-row data-day="${escapeHtml(day)}">
            <div class="day-control">
                <label class="day-toggle">
                    <input type="checkbox" data-day-active aria-label="Enable ${escapeHtml(day)}">
                    <span class="day-switch" aria-hidden="true"></span>
                    <strong>${shortDays[index]}</strong>
                </label>
                <div><b>${escapeHtml(day)}</b><small data-day-status>Not scheduled</small></div>
            </div>
            <label class="time-control"><span>Starts</span><input type="time" data-start-time value="07:30"></label>
            <label class="time-control"><span>Ends</span><input type="time" data-end-time value="16:30"></label>
            <div class="break-control">
                <label class="break-toggle"><input type="checkbox" data-break-enabled><span aria-hidden="true"></span> Break</label>
                <input type="time" data-break-start value="12:00" aria-label="${escapeHtml(day)} break start">
                <span class="break-separator">to</span>
                <input type="time" data-break-end value="13:00" aria-label="${escapeHtml(day)} break end">
            </div>
            <div class="paid-hours"><span>Paid time</span><strong data-paid-hours>—</strong><small data-row-error></small></div>
        </article>`;

    grid.innerHTML = days.map(rowMarkup).join('');

    const getRows = () => [...grid.querySelectorAll('[data-day-row]')];
    const minutes = (time) => {
        if (!/^\d{2}:\d{2}$/.test(time || '')) return null;
        const [hours, mins] = time.split(':').map(Number);
        return hours * 60 + mins;
    };

    function calculateRow(row) {
        const active = row.querySelector('[data-day-active]').checked;
        const breakEnabled = row.querySelector('[data-break-enabled]').checked;
        const startInput = row.querySelector('[data-start-time]');
        const endInput = row.querySelector('[data-end-time]');
        const breakStartInput = row.querySelector('[data-break-start]');
        const breakEndInput = row.querySelector('[data-break-end]');
        const paidLabel = row.querySelector('[data-paid-hours]');
        const status = row.querySelector('[data-day-status]');
        const errorLabel = row.querySelector('[data-row-error]');

        row.classList.toggle('is-active', active);
        [startInput, endInput, row.querySelector('[data-break-enabled]')].forEach(input => input.disabled = !active);
        breakStartInput.disabled = !active || !breakEnabled;
        breakEndInput.disabled = !active || !breakEnabled;

        row.classList.remove('has-error');
        errorLabel.textContent = '';
        if (!active) {
            paidLabel.textContent = '—';
            status.textContent = 'Not scheduled';
            return { active: false, valid: true, hours: 0 };
        }

        const start = minutes(startInput.value);
        const end = minutes(endInput.value);
        let paidMinutes = (end ?? 0) - (start ?? 0);
        let error = '';
        if (start === null || end === null || end <= start) {
            error = 'End must be later than start.';
        } else if (paidMinutes > 16 * 60) {
            error = 'Shift cannot exceed 16 hours.';
        } else if (breakEnabled) {
            const breakStart = minutes(breakStartInput.value);
            const breakEnd = minutes(breakEndInput.value);
            if (breakStart === null || breakEnd === null || breakEnd <= breakStart || breakStart < start || breakEnd > end) {
                error = 'Break must be inside the shift.';
            } else {
                paidMinutes -= breakEnd - breakStart;
            }
        }

        if (paidMinutes <= 0 && !error) error = 'Paid time must be greater than zero.';
        if (error) {
            row.classList.add('has-error');
            paidLabel.textContent = 'Invalid';
            errorLabel.textContent = error;
            status.textContent = 'Needs attention';
            return { active: true, valid: false, hours: 0 };
        }

        const hours = paidMinutes / 60;
        paidLabel.textContent = `${hours.toFixed(2)} hrs`;
        status.textContent = `${startInput.value} – ${endInput.value}`;
        return { active: true, valid: true, hours };
    }

    function refreshSummary() {
        const results = getRows().map(calculateRow);
        const active = results.filter(result => result.active);
        const hours = active.reduce((sum, result) => sum + result.hours, 0);
        const valid = active.length > 0 && active.every(result => result.valid);
        summaryDays.textContent = String(active.length);
        summaryHours.textContent = hours.toFixed(2);
        summaryAverage.textContent = active.length ? (hours / active.length).toFixed(2) : '0.00';
        saveButton.disabled = !valid;
        return { active, hours, valid };
    }

    function selectPresetCard(key) {
        modalElement.querySelectorAll('[data-preset]').forEach(button => {
            const selected = button.dataset.preset === key;
            button.classList.toggle('is-active', selected);
            button.setAttribute('aria-pressed', selected ? 'true' : 'false');
        });
    }

    function applyPreset(key, announce = true) {
        const preset = presets[key];
        if (!preset) return;
        currentPreset = preset.label;
        selectPresetCard(key);
        getRows().forEach(row => {
            const active = preset.activeDays.includes(row.dataset.day);
            row.querySelector('[data-day-active]').checked = active;
            row.querySelector('[data-start-time]').value = preset.start;
            row.querySelector('[data-end-time]').value = preset.end;
            row.querySelector('[data-break-enabled]').checked = Boolean(preset.breakStart && preset.breakEnd);
            row.querySelector('[data-break-start]').value = preset.breakStart || '12:00';
            row.querySelector('[data-break-end]').value = preset.breakEnd || '13:00';
        });
        if (announce) showBuilderAlert(`${preset.label} applied. You can adjust any day before publishing.`, 'info');
        refreshSummary();
    }

    function showBuilderAlert(message, type = 'info') {
        alertBox.textContent = message;
        alertBox.className = `builder-alert is-${type}`;
        alertBox.hidden = false;
    }

    function hideBuilderAlert() {
        alertBox.hidden = true;
        alertBox.textContent = '';
    }

    function setLoading(isLoading) {
        loading.hidden = !isLoading;
        content.hidden = isLoading;
        saveButton.disabled = isLoading;
    }

    async function parseResponse(response) {
        const text = await response.text();
        let data;
        try { data = JSON.parse(text); } catch (_) {
            throw new Error('The server returned an invalid response. Please refresh and try again.');
        }
        if (!response.ok || !data.success) throw new Error(data.message || 'The request could not be completed.');
        return data;
    }

    function hydrateSchedule(schedule) {
        const byDay = new Map((schedule.days || []).map(item => [item.day, item]));
        getRows().forEach(row => {
            const item = byDay.get(row.dataset.day);
            row.querySelector('[data-day-active]').checked = Boolean(item);
            if (item) {
                row.querySelector('[data-start-time]').value = item.start_time || '07:30';
                row.querySelector('[data-end-time]').value = item.end_time || '16:30';
                const hasBreak = Boolean(item.break_start && item.break_end);
                row.querySelector('[data-break-enabled]').checked = hasBreak;
                row.querySelector('[data-break-start]').value = item.break_start || '12:00';
                row.querySelector('[data-break-end]').value = item.break_end || '13:00';
            }
        });
        currentPreset = schedule.preset_name || 'Custom weekly schedule';
        const matchedPreset = Object.entries(presets).find(([, preset]) => preset.label === currentPreset);
        selectPresetCard(matchedPreset ? matchedPreset[0] : '');
        refreshSummary();
    }

    async function openBuilder(button) {
        const employeeId = button.dataset.employeeId;
        const employeeName = button.dataset.employeeName;
        employeeIdInput.value = employeeId;
        employeeLabel.textContent = `${employeeName} · Philippine time (GMT+8)`;
        hideBuilderAlert();
        modal.show();
        setLoading(true);
        try {
            const response = await fetch(`includes/get_employee_schedule.php?employee_id=${encodeURIComponent(employeeId)}`, {
                headers: { Accept: 'application/json' }, credentials: 'same-origin'
            });
            const data = await parseResponse(response);
            hasExistingSchedule = data.schedule.days.length > 0;
            clearButton.hidden = !hasExistingSchedule;
            if (hasExistingSchedule) {
                hydrateSchedule(data.schedule);
                showBuilderAlert(`Loaded ${data.schedule.day_count} scheduled days (${Number(data.schedule.weekly_hours).toFixed(2)} paid hours).`, 'success');
            } else {
                applyPreset('standard', false);
                showBuilderAlert('A recommended 40-hour teacher schedule is ready. Review it, then publish.', 'info');
            }
        } catch (error) {
            applyPreset('standard', false);
            showBuilderAlert(error.message, 'error');
        } finally {
            setLoading(false);
            refreshSummary();
        }
    }

    function collectPayload() {
        const scheduleDays = getRows().filter(row => row.querySelector('[data-day-active]').checked).map(row => {
            const breakEnabled = row.querySelector('[data-break-enabled]').checked;
            return {
                day: row.dataset.day,
                start_time: row.querySelector('[data-start-time]').value,
                end_time: row.querySelector('[data-end-time]').value,
                break_start: breakEnabled ? row.querySelector('[data-break-start]').value : '',
                break_end: breakEnabled ? row.querySelector('[data-break-end]').value : ''
            };
        });
        return { employee_id: employeeIdInput.value, preset_name: currentPreset, days: scheduleDays };
    }

    function updateDirectoryRow(dayCount, weeklyHours, payload) {
        const button = document.querySelector(`[data-build-schedule][data-employee-id="${CSS.escape(employeeIdInput.value)}"]`);
        const row = button?.closest('[data-employee-row]');
        if (!row) return;
        const scheduled = dayCount > 0;
        row.dataset.status = scheduled ? 'scheduled' : 'unscheduled';
        row.querySelector('[data-schedule-coverage]').innerHTML = scheduled
            ? `<span class="coverage-pill is-ready"><i class="fa-solid fa-circle-check" aria-hidden="true"></i>${dayCount} days</span>`
            : '<span class="coverage-pill needs-plan"><i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>Not scheduled</span>';
        row.querySelector('[data-schedule-hours]').textContent = scheduled ? `${Number(weeklyHours).toFixed(2)} hrs` : '—';
        const starts = payload.days.map(day => day.start_time).sort();
        const ends = payload.days.map(day => day.end_time).sort();
        row.querySelector('[data-schedule-window]').textContent = scheduled ? `${starts[0]} – ${ends[ends.length - 1]}` : '—';
        button.innerHTML = scheduled
            ? '<i class="fa-solid fa-pen" aria-hidden="true"></i> Edit schedule'
            : '<i class="fa-solid fa-wand-magic-sparkles" aria-hidden="true"></i> Auto-build';
        const scheduledCount = document.querySelector('[data-scheduled-count]');
        const unscheduledCount = document.querySelector('[data-unscheduled-count]');
        const oldWasScheduled = hasExistingSchedule;
        if (scheduled !== oldWasScheduled) {
            scheduledCount.textContent = String(Math.max(0, Number(scheduledCount.textContent) + (scheduled ? 1 : -1)));
            unscheduledCount.textContent = String(Math.max(0, Number(unscheduledCount.textContent) + (scheduled ? -1 : 1)));
        }
        hasExistingSchedule = scheduled;
        applyDirectoryFilters();
    }

    async function saveSchedule() {
        const summary = refreshSummary();
        if (!summary.valid) {
            showBuilderAlert('Fix the highlighted schedule fields before publishing.', 'error');
            return;
        }
        const payload = collectPayload();
        saveButton.disabled = true;
        saveButton.classList.add('is-loading');
        try {
            const response = await fetch('save_schedule_logic.php', {
                method: 'POST', credentials: 'same-origin',
                headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
                body: JSON.stringify(payload)
            });
            const data = await parseResponse(response);
            updateDirectoryRow(data.day_count, data.weekly_hours, payload);
            modal.hide();
            await Swal.fire({ icon: 'success', title: 'Schedule published', text: `${data.day_count} working days · ${Number(data.weekly_hours).toFixed(2)} paid hours weekly`, confirmButtonColor: '#9f1239' });
        } catch (error) {
            showBuilderAlert(error.message, 'error');
        } finally {
            saveButton.classList.remove('is-loading');
            refreshSummary();
        }
    }

    async function clearSchedule() {
        const confirmation = await Swal.fire({
            icon: 'warning', title: 'Clear this weekly schedule?',
            text: 'Attendance and payroll will no longer have scheduled hours for this employee.',
            showCancelButton: true, confirmButtonText: 'Yes, clear it', confirmButtonColor: '#b42318'
        });
        if (!confirmation.isConfirmed) return;
        clearButton.disabled = true;
        try {
            const response = await fetch('save_schedule_logic.php', {
                method: 'POST', credentials: 'same-origin',
                headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
                body: JSON.stringify({ employee_id: employeeIdInput.value, clear: true })
            });
            await parseResponse(response);
            updateDirectoryRow(0, 0, { days: [] });
            modal.hide();
            await Swal.fire({ icon: 'success', title: 'Schedule cleared', confirmButtonColor: '#9f1239' });
        } catch (error) {
            showBuilderAlert(error.message, 'error');
        } finally {
            clearButton.disabled = false;
        }
    }

    function copyMonday() {
        const monday = getRows()[0];
        const fields = {
            start: monday.querySelector('[data-start-time]').value,
            end: monday.querySelector('[data-end-time]').value,
            breakEnabled: monday.querySelector('[data-break-enabled]').checked,
            breakStart: monday.querySelector('[data-break-start]').value,
            breakEnd: monday.querySelector('[data-break-end]').value
        };
        getRows().slice(1).filter(row => row.querySelector('[data-day-active]').checked).forEach(row => {
            row.querySelector('[data-start-time]').value = fields.start;
            row.querySelector('[data-end-time]').value = fields.end;
            row.querySelector('[data-break-enabled]').checked = fields.breakEnabled;
            row.querySelector('[data-break-start]').value = fields.breakStart;
            row.querySelector('[data-break-end]').value = fields.breakEnd;
        });
        currentPreset = 'Custom weekly schedule';
        selectPresetCard('');
        showBuilderAlert('Monday’s shift was copied to every active day.', 'info');
        refreshSummary();
    }

    const searchInput = document.querySelector('[data-schedule-search]');
    const filterInput = document.querySelector('[data-schedule-filter]');
    const emptyState = document.querySelector('[data-schedule-empty]');
    function applyDirectoryFilters() {
        const term = (searchInput?.value || '').trim().toLowerCase();
        const status = filterInput?.value || 'all';
        let visible = 0;
        document.querySelectorAll('[data-employee-row]').forEach(row => {
            const matches = (!term || row.dataset.search.includes(term)) && (status === 'all' || row.dataset.status === status);
            row.hidden = !matches;
            if (matches) visible += 1;
        });
        if (emptyState) emptyState.hidden = visible !== 0;
    }

    document.querySelectorAll('[data-build-schedule]').forEach(button => button.addEventListener('click', () => openBuilder(button)));
    modalElement.querySelectorAll('[data-preset]').forEach(button => button.addEventListener('click', () => applyPreset(button.dataset.preset)));
    grid.addEventListener('input', event => {
        if (event.target.matches('input')) {
            currentPreset = 'Custom weekly schedule';
            selectPresetCard('');
            hideBuilderAlert();
            refreshSummary();
        }
    });
    modalElement.querySelector('[data-copy-monday]').addEventListener('click', copyMonday);
    saveButton.addEventListener('click', saveSchedule);
    clearButton.addEventListener('click', clearSchedule);
    searchInput?.addEventListener('input', applyDirectoryFilters);
    filterInput?.addEventListener('change', applyDirectoryFilters);
    refreshSummary();
})();
