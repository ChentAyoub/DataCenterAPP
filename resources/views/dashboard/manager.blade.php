@extends('layouts.dashboard')

@section('content')
<div class="topbar">
  <div>
    <h1>Manager Dashboard</h1>
    <p>Approve reservations, manage availability, and monitor issues.</p>
  </div>
</div>

<div class="stats-grid">
  <div class="stat-card">
    <h3>Active Reservations</h3>
    <div class="value">{{ $active_count ?? 0 }}</div>
    <div class="hint">Approved and currently running</div>
  </div>
  <div class="stat-card">
    <h3>Pending Requests</h3>
    <div class="value">{{ isset($pending_reservations) ? $pending_reservations->count() : 0 }}</div>
    <div class="hint">Need your decision</div>
  </div>
  <div class="stat-card">
    <h3>Maintenance Resources</h3>
    <div class="value">{{ isset($broken_resources) ? $broken_resources->count() : 0 }}</div>
    <div class="hint">Unavailable right now</div>
  </div>
</div>

<div class="section">
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
  <h2>Availability & Maintenance</h2>
  <p class="sub">Toggle a resource between Available and Maintenance.</p>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Resource</th>
          <th>State</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      @forelse($broken_resources as $res)
        <tr>
          <td>{{ $res->name }}</td>
          <td><span class="badge maintenance">{{ $res->state }}</span></td>
          <td>
            <form method="POST" action="{{ route('resources.toggle', $res->id) }}">
              @csrf @method('PATCH')
              <button class="btn primary" type="submit"><i class="fa-solid fa-arrows-rotate"></i> Toggle</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="3"><small>No resources in maintenance.</small></td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
