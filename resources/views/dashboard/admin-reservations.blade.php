@extends('layouts.dashboard')

@section('content')
<div class="topbar">
  <div>
    <h1>Admin Reservations</h1>
    <p>Review, approve, or reject all reservations.</p>
  </div>
</div>

<div class="section">
  <h2>All Reservations</h2>
  <p class="sub">Filter and manage reservation requests.</p>

  <form method="GET" class="filters" style="margin-bottom:16px; display:flex; gap:12px; flex-wrap:wrap;">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search user or resource" class="input" />
    <select name="status" class="input">
      <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All status</option>
      <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
      <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
      <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
    </select>
    <button class="btn primary" type="submit"><i class="fa-solid fa-filter"></i> Filter</button>
    <a href="{{ route('admin.reservations') }}" class="btn danger" style="text-decoration:none;">
      <i class="fa-solid fa-xmark"></i> Clear
    </a>
  </form>

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
      @forelse($reservations as $r)
        <tr>
          <td>{{ optional($r->user)->name ?? '—' }} <small>({{ optional($r->user)->email ?? '' }})</small></td>
          <td>{{ optional($r->resource)->name ?? '—' }}</td>
          <td>
            <small>
              From: {{ $r->start_time ? \Illuminate\Support\Carbon::parse($r->start_time)->format('Y-m-d H:i') : '—' }}
              <br>
              To: {{ $r->end_time ? \Illuminate\Support\Carbon::parse($r->end_time)->format('Y-m-d H:i') : '—' }}
            </small>
          </td>
          <td>
            @if($r->status === 'approved')
              <span class="badge approved">approved</span>
            @elseif($r->status === 'rejected')
              <span class="badge rejected">rejected</span>
            @else
              <span class="badge pending">pending</span>
            @endif
          </td>
          <td>
            <div class="actions">
              @if($r->status !== 'approved')
                <form method="POST" action="{{ route('reservation.approve', $r->id) }}">
                  @csrf @method('PATCH')
                  <button class="btn accent" type="submit"><i class="fa-solid fa-check"></i> Approve</button>
                </form>
              @endif
              @if($r->status !== 'rejected')
                <form method="POST" action="{{ route('reservation.reject', $r->id) }}">
                  @csrf @method('PATCH')
                  <button class="btn warning" type="submit"><i class="fa-solid fa-xmark"></i> Reject</button>
                </form>
              @endif
            </div>
          </td>
        </tr>
      @empty
        <tr><td colspan="5"><small>No reservations found.</small></td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
