<div class="resource-card">
    <div class="card-thumb">
        <span class="status-pill {{ $resource->state == 'available' ? 'st-green' : 'st-red' }}">
            {{ $resource->state == 'available' ? 'Available' : 'Maintenance' }}
        </span>

        @if($resource->image)
            <img src="{{ asset('storage/' . $resource->image) }}" alt="{{ $resource->name }}">
        @else
            <div class="icon-fallback">
                @if($resource->category->name == 'Server') <i class="fa-solid fa-server"></i>
                @elseif($resource->category->name == 'Router') <i class="fa-solid fa-wifi"></i>
                @else <i class="fa-solid fa-box"></i> @endif
            </div>
        @endif
    </div>

    <div class="card-body">
        <h4>{{ $resource->name }}</h4>
        <div class="spec-list">
            <span><i class="fa-solid fa-microchip"></i> {{ Str::limit($resource->specifications, 20) }}</span>
        </div>
        
        <div class="card-footer">
            <a href="{{ route('resources.show', $resource->id) }}" class="btn-block">
                {{ $resource->state == 'available' ? 'Reserve Now' : 'View Details' }}
            </a>
        </div>
    </div>
</div>