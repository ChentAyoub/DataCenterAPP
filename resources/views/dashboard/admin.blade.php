@extends('layouts.dashboard')

@section('content')

    <div class="dashboard-header">
        <h2 class="page-title">Admin Panel</h2>
        <p class="page-subtitle">System metrics and user management.</p>
    </div>

    <div class="charts-container" style="display: flex; gap: 20px; margin-bottom: 40px;">
        
        <div class="chart-card" style="flex: 1; background: #16171d; padding: 20px; border-radius: 12px; border: 1px solid #1f1f1f;">
            <h3 style="color: white; margin-bottom: 15px; font-size: 16px;">User Distribution</h3>
            <div style="height: 250px;">
                <canvas id="userRolesChart"></canvas>
            </div>
        </div>

        <div class="chart-card" style="flex: 1; background: #16171d; padding: 20px; border-radius: 12px; border: 1px solid #1f1f1f;">
            <h3 style="color: white; margin-bottom: 15px; font-size: 16px;">Global Inventory Status</h3>
            <div style="height: 250px;">
                <canvas id="systemHealthChart"></canvas>
            </div>
        </div>

    </div>

    <div class="section-header-wrapper">
        <h3 class="section-title">Newest Users</h3>
        <button class="btn btn-secondary">View All Users</button>
    </div>

    <div class="table-container">
        <table class="table-dark">
            <thead>
                <tr>
                    <th>User Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Joined Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recent_users as $u)
                <tr>
                    <td><strong>{{ $u->name }}</strong></td>
                    <td class="text-muted">{{ $u->email }}</td>
                    <td>
                        @if($u->role === 'admin')
                            <span class="badge badge-admin">Admin</span>
                        @elseif($u->role === 'manager')
                            <span class="badge badge-manager">Manager</span>
                        @else
                            <span class="badge badge-student">Student</span>
                        @endif
                    </td>
                    <td>{{ $u->created_at->format('M d, Y') }}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn btn-edit-sm">Edit</button>
                            @if(Auth::id() !== $u->id)
                                <button class="btn btn-delete-sm">Delete</button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const roleData = @json($role_counts);
        const resourceData = @json($resource_stats);
        new Chart(document.getElementById('userRolesChart'), {
            type: 'doughnut',
            data: {
                labels: ['Students', 'Managers', 'Admins'],
                datasets: [{
                    data: [roleData.student, roleData.manager, roleData.admin],
                    backgroundColor: ['#34495e', '#f1c40f', '#e74c3c'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right', labels: { color: '#fff' } }
                }
            }
        });

        new Chart(document.getElementById('systemHealthChart'), {
            type: 'pie',
            data: {
                labels: ['Available Resources', 'In Maintenance'],
                datasets: [{
                    data: [resourceData.available, resourceData.maintenance],
                    backgroundColor: ['#2ecc71', '#e74c3c'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right', labels: { color: '#fff' } }
                }
            }
        });
    </script>

@endsection