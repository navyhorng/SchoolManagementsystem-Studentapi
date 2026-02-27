@extends(backpack_view('blank'))

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-end">
            <div>
                <h2 class="mb-1">Admin Dashboard</h2>
                <div class="text-muted">Live system analytics (auto refresh every 10 seconds)</div>
            </div>
            <small class="text-muted">Last updated: <span id="last-updated">-</span></small>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-6 col-xl-3"><div class="card"><div class="card-body"><div class="text-muted">Students</div><div class="h2 mb-0" id="students">0</div></div></div></div>
        <div class="col-6 col-xl-3"><div class="card"><div class="card-body"><div class="text-muted">Active Students</div><div class="h2 mb-0" id="active_students">0</div></div></div></div>
        <div class="col-6 col-xl-3"><div class="card"><div class="card-body"><div class="text-muted">Teachers</div><div class="h2 mb-0" id="teachers">0</div></div></div></div>
        <div class="col-6 col-xl-3"><div class="card"><div class="card-body"><div class="text-muted">Users</div><div class="h2 mb-0" id="users">0</div></div></div></div>
        <div class="col-6 col-xl-3"><div class="card"><div class="card-body"><div class="text-muted">Tasks</div><div class="h2 mb-0" id="tasks">0</div></div></div></div>
        <div class="col-6 col-xl-3"><div class="card"><div class="card-body"><div class="text-muted">Completed Tasks</div><div class="h2 mb-0" id="completed_tasks">0</div></div></div></div>
        <div class="col-6 col-xl-3"><div class="card"><div class="card-body"><div class="text-muted">Attendance Today</div><div class="h2 mb-0" id="attendance_today">0</div></div></div></div>
        <div class="col-6 col-xl-3"><div class="card"><div class="card-body"><div class="text-muted">Revenue Collected</div><div class="h2 mb-0" id="revenue_collected">$0.00</div></div></div></div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">Students & Completed Tasks (7 days)</div>
                <div class="card-body">
                    <canvas id="growthChart" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">Payment Collection (7 days)</div>
                <div class="card-body">
                    <canvas id="paymentsChart" height="120"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">Latest Activity</div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Message</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody id="latest-activity-body"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">Recent Users</div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody id="recent-users-body"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">Recent Payments</div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody id="recent-payments-body"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('after_scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function () {
    const endpoint = "{{ backpack_url('dashboard/stats') }}";
    const totalsFields = [
        'students',
        'active_students',
        'teachers',
        'users',
        'tasks',
        'completed_tasks',
        'attendance_today'
    ];

    let growthChart = null;
    let paymentsChart = null;

    function setValue(id, value) {
        const el = document.getElementById(id);
        if (el) {
            el.textContent = value;
        }
    }

    function money(value) {
        return '$' + Number(value || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    function renderRows(targetId, rows, colspan) {
        const target = document.getElementById(targetId);
        if (!target) {
            return;
        }

        if (!rows || rows.length === 0) {
            target.innerHTML = `<tr><td colspan="${colspan}" class="text-muted">No data</td></tr>`;
            return;
        }

        target.innerHTML = rows.join('');
    }

    function initCharts(labels, students, tasksCompleted, payments) {
        const growthCtx = document.getElementById('growthChart');
        const paymentsCtx = document.getElementById('paymentsChart');
        if (typeof Chart === 'undefined') {
            return;
        }

        if (growthCtx && !growthChart) {
            growthChart = new Chart(growthCtx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [
                        {
                            label: 'Students Added',
                            data: students,
                            borderColor: '#0d6efd',
                            backgroundColor: 'rgba(13,110,253,0.15)',
                            tension: 0.3,
                            fill: true
                        },
                        {
                            label: 'Tasks Completed',
                            data: tasksCompleted,
                            borderColor: '#20c997',
                            backgroundColor: 'rgba(32,201,151,0.12)',
                            tension: 0.3,
                            fill: true
                        }
                    ]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });
        }

        if (paymentsCtx && !paymentsChart) {
            paymentsChart = new Chart(paymentsCtx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [
                        {
                            label: 'Payments Collected',
                            data: payments,
                            backgroundColor: '#fd7e14'
                        }
                    ]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });
        }
    }

    function updateCharts(labels, students, tasksCompleted, payments) {
        if (typeof Chart === 'undefined') {
            return;
        }

        if (growthChart) {
            growthChart.data.labels = labels;
            growthChart.data.datasets[0].data = students;
            growthChart.data.datasets[1].data = tasksCompleted;
            growthChart.update();
        }

        if (paymentsChart) {
            paymentsChart.data.labels = labels;
            paymentsChart.data.datasets[0].data = payments;
            paymentsChart.update();
        }
    }

    async function loadStats() {
        try {
            const response = await fetch(endpoint, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (!response.ok) {
                return;
            }

            const data = await response.json();
            const totals = data.totals || {};
            const charts = data.charts || {};

            totalsFields.forEach((key) => setValue(key, totals[key] ?? 0));
            setValue('revenue_collected', money(totals.revenue_collected));
            setValue('last-updated', data.generated_at || '-');

            const labels = charts.labels || [];
            const students = charts.students || [];
            const tasksCompleted = charts.tasks_completed || [];
            const payments = charts.payments || [];

            if (!growthChart || !paymentsChart) {
                initCharts(labels, students, tasksCompleted, payments);
            } else {
                updateCharts(labels, students, tasksCompleted, payments);
            }

            const userRows = (data.recent_users || []).map((item) =>
                `<tr><td>${escapeHtml(item.name)}</td><td>${escapeHtml(item.email)}</td><td>${escapeHtml(item.created_at)}</td></tr>`
            );
            renderRows('recent-users-body', userRows, 3);

            const paymentRows = (data.recent_payments || []).map((item) =>
                `<tr><td>${escapeHtml(item.student)}</td><td>${money(item.amount)}</td><td>${escapeHtml(item.status)}</td><td>${escapeHtml(item.payment_date ?? item.created_at)}</td></tr>`
            );
            renderRows('recent-payments-body', paymentRows, 4);

            const activityRows = (data.latest_activity || []).map((item) =>
                `<tr><td>${escapeHtml(item.type)}</td><td>${escapeHtml(item.message)}</td><td>${escapeHtml(item.time)}</td></tr>`
            );
            renderRows('latest-activity-body', activityRows, 3);
        } catch (e) {
            // keep previous values when request fails
        }
    }

    loadStats();
    setInterval(loadStats, 10000);
})();
</script>
@endpush
