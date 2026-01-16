@extends('layouts.dashboard')

@section('content')

    <div class="dashboard-header">
        <h2 class="page-title">Bienvenue, {{ Auth::user()->name }}</h2>
        <p class="page-subtitle">Overview of lab activity and pending requests.</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon icon-pending">⏳</div>
            <div class="stat-info">
                <h3>{{ $pending_reservations->count() }}</h3>
                <p>Pending Requests</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-active">🟢</div>
            <div class="stat-info">
                <h3>{{ $active_count }}</h3>
                <p>Active Now</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-maintenance">⚠️</div>
            <div class="stat-info">
                <h3>{{ $broken_resources->count() }}</h3>
                <p>Maintenance Issues</p>
            </div>
        </div>
    </div>

    <h3 class="section-title">Reservation Requests</h3>

    @if($pending_reservations->isEmpty())
        <div class="empty-message">
            <p>✅ All caught up! No pending requests.</p>
        </div>
    @else
        <div class="table-container">
            <table class="table-dark">
                <thead>
                    <tr>
                        <th>Resource</th>
                        <th>Student</th>
                        <th>Date/Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pending_reservations as $res)
                    <tr>
                        <td>
                            <strong class="text-accent">{{ $res->resource->name }}</strong>
                        </td>
                        <td>
                            {{ $res->user->name }}<br>
                            <small class="text-muted">{{ $res->user->email }}</small>
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($res->start_time)->format('M d, H:i') }}
                        </td>
                        <td>
                            <div class="action-buttons">
                                <form action="{{ route('reservation.approve', $res->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-approve">Approve</button>
                                </form>
                                <form action="{{ route('reservation.reject', $res->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-reject">Reject</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

@endsection