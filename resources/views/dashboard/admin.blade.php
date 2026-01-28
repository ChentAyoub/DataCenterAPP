@extends('layouts.dashboard')

@section('content')
<div class="topbar">
  <div>
    <h1>Admin Dashboard</h1>
    <p>Global overview: users, resources, and latest activity.</p>
  </div>
</div>

<div class="stats-grid">
  <div class="stat-card">
    <h3>Internal Users</h3>
    <div class="value">{{ $role_counts['internal users'] ?? 0 }}</div>
    <div class="hint">Accounts with booking access</div>
  </div>
  <div class="stat-card">
    <h3>Managers</h3>
    <div class="value">{{ $role_counts['managers'] ?? 0 }}</div>
    <div class="hint">Resource supervisors</div>
  </div>
  <div class="stat-card">
    <h3>Admins</h3>
    <div class="value">{{ $role_counts['admins'] ?? 0 }}</div>
    <div class="hint">Full access</div>
  </div>
  <div class="stat-card">
    <h3>Available Resources</h3>
    <div class="value">{{ $resource_stats['available'] ?? 0 }}</div>
    <div class="hint">Ready to reserve</div>
  </div>
</div>

<div class="section" id="users">
  <h2>Recent Users</h2>
  <p class="sub">Latest registered accounts.</p>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Role</th>
          <th>Approved</th>
          <th>Created</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      @forelse($recent_users as $u)
        <tr>
          <td>{{ $u->name }}</td>
          <td><small>{{ $u->email }}</small></td>
          <td><span class="badge">{{ $u->role }}</span></td>
          <td>
            @if(isset($u->is_active) && $u->is_active)
              <span class="badge approved">yes</span>
            @else
              <span class="badge pending">pending</span>
            @endif
          </td>
          <td><small>{{ $u->created_at->format('Y-m-d') }}</small></td>
          <td>
            <div class="actions">
              @if(isset($u->is_active) && !$u->is_active)
                <form method="POST" action="{{ route('users.approve', $u->id) }}">
                  @csrf @method('PATCH')
                  <button class="btn accent" type="submit"><i class="fa-solid fa-check"></i> Approve</button>
                </form>
                <form method="POST" action="{{ route('users.reject', $u->id) }}">
                  @csrf @method('PATCH')
                  <button class="btn warning" type="submit"><i class="fa-solid fa-xmark"></i> Reject</button>
                </form>
              @endif

              @if(auth()->id() !== $u->id)
                <form method="POST" action="{{ route('users.destroy', $u->id) }}">
                  @csrf @method('DELETE')
                  <button class="btn danger" type="submit" onclick="return confirm('Delete this user?')">
                    <i class="fa-solid fa-trash"></i> Delete
                  </button>
                </form>
              @endif
            </div>
          </td>
        </tr>
      @empty
        <tr><td colspan="6"><small>No users found.</small></td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="section" id="reservations">
  <h2>Pending Reservations</h2>
  <p class="sub">Approve or reject reservation requests.</p>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>User</th>
          <th>Resource</th>
          <th>Period</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      @forelse($pending_reservations as $r)
        <tr>
          <td>{{ $r->user->name ?? '—' }} <small>({{ $r->user->email ?? '' }})</small></td>
          <td>{{ $r->resource->name ?? '—' }}</td>
          <td><small>{{ optional($r->start_time)->format('Y-m-d H:i') }} → {{ optional($r->end_time)->format('Y-m-d H:i') }}</small></td>
          <td><span class="badge pending">pending</span></td>
          <td>
            <div class="actions">
              <form method="POST" action="{{ route('reservation.approve', $r->id) }}">
                @csrf @method('PATCH')
                <button class="btn accent" type="submit"><i class="fa-solid fa-check"></i> Approve</button>
              </form>
              <form method="POST" action="{{ route('reservation.reject', $r->id) }}">
                @csrf @method('PATCH')
                <button class="btn warning" type="submit"><i class="fa-solid fa-xmark"></i> Reject</button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr><td colspan="5"><small>No pending requests.</small></td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="section">
  <h2>Resource Health</h2>
  <p class="sub">Available vs maintenance.</p>

  <div class="stats-grid">
    <div class="stat-card">
      <h3>Maintenance</h3>
      <div class="value">{{ $resource_stats['maintenance'] ?? 0 }}</div>
      <div class="hint">Requires attention</div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
@if(isset($recent_users) && $recent_users->where('is_active', false)->count() > 0)
<script>
  window.addEventListener('load', function(){
    const toast = document.getElementById('toast');
    if(toast){ toast.style.display = 'block'; }
  });
</script>
@endif

<div class="toast" id="toast">
  <h4>Pending account approvals</h4>
  <p>Some new users are waiting for approval.</p>
  <div class="actions">
    <a class="btn primary" href="#users"><i class="fa-solid fa-arrow-right"></i> Review</a>
    <button class="btn ghost" type="button" onclick="document.getElementById('toast').style.display='none'">Dismiss</button>
  </div>
</div>
@endsection
