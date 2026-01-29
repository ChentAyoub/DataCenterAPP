<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Edit Resource</title>
	<link rel="stylesheet" href="{{ asset('css/home.css') }}">
	<link rel="stylesheet" href="{{ asset('css/style.css') }}">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<style>
		.edit-container { max-width: 860px; margin: 40px auto; background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 30px; }
		.edit-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
		.edit-header h2 { font-size: 20px; color: #0f172a; }
		.edit-form { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
		.edit-form .full { grid-column: 1 / -1; }
		.edit-form label { font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 6px; display: block; }
		.edit-form input, .edit-form select, .edit-form textarea {
			width: 100%; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 14px; background: #f8fafc;
		}
		.edit-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px; }
		.thumb { width: 120px; height: 80px; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0; }
		.muted { color: #94a3b8; font-size: 12px; }
	</style>
</head>
<body class="pro-body">

	<nav class="pro-navbar">
		<a href="/" class="pro-logo"><img src="{{ asset('images/logo1NBG.png') }}" alt="DataCenter Logo" class="logo-image" style="height:100px; vertical-align:middle;"></a>
		<div class="pro-menu">
			<a href="/" class="nav-btn"><i class="fa-solid fa-house"></i> Home</a>
			@auth
				@if(Auth::user()->role === 'admin')
					<a href="{{ route('dashboard') }}" class="nav-btn primary"><i class="fa-solid fa-wrench"></i> Admin Panel</a>
				@elseif(Auth::user()->role === 'manager')
					<a href="{{ route('dashboard') }}" class="nav-btn primary"><i class="fa-solid fa-bolt"></i> Manager Dash</a>
				@else
					<a href="{{ route('dashboard') }}" class="nav-btn primary">
						<i class="fa-solid fa-calendar-check"></i> My Reservations
					</a>
				@endif
				<form action="{{ route('logout') }}" method="POST" style="display:inline;">
					@csrf 
					<button class="nav-btn logout-btn"><i class="fa-solid fa-right-from-bracket"></i> Log Out</button>
				</form>
			@else
				<a href="{{ route('login') }}" class="nav-btn">Login</a>
				<a href="{{ route('register') }}" class="nav-btn primary">Sign Up</a>
			@endauth
		</div>
	</nav>

	<div class="edit-container">
		<div class="edit-header">
			<h2>Edit Resource</h2>
			<a href="{{ url('catalogue') }}" class="nav-btn"><i class="fa-solid fa-arrow-left"></i> Back</a>
		</div>

		@if($errors->any())
			<div class="flash error" style="margin-bottom:16px;">
				<strong>Error</strong>
				<span>{{ $errors->first() }}</span>
			</div>
		@endif

		@if(session('success'))
			<div class="flash-success" style="margin-bottom:16px;">
				<i class="fa-solid fa-circle-check"></i>
				<span>{{ session('success') }}</span>
			</div>
		@endif

		<form class="edit-form" method="POST" action="{{ route('resources.update', $resource->id) }}" enctype="multipart/form-data">
			@csrf
			@method('PUT')

			<div>
				<label for="name">Resource Name</label>
				<input id="name" name="name" type="text" value="{{ old('name', $resource->name) }}" required>
			</div>

			<div>
				<label for="category_id">Category</label>
				<select id="category_id" name="category_id" required>
					@foreach($categories as $cat)
						<option value="{{ $cat->id }}" {{ (int) old('category_id', $resource->category_id) === (int) $cat->id ? 'selected' : '' }}>
							{{ $cat->name }}
						</option>
					@endforeach
				</select>
			</div>

			<div class="full">
				<label for="specifications">Specifications</label>
				<textarea id="specifications" name="specifications" rows="3" required>{{ old('specifications', $resource->specifications) }}</textarea>
			</div>

			<div class="full">
				<label for="description">Description</label>
				<textarea id="description" name="description" rows="4">{{ old('description', $resource->description) }}</textarea>
			</div>

			<div>
				<label for="image">Image</label>
				<input id="image" name="image" type="file" accept="image/*">
				<div class="muted">Leave empty to keep existing image.</div>
			</div>

			<div>
				<label>Current Image</label>
				@php
					$imageUrl = $resource->image
						? (\Illuminate\Support\Str::startsWith($resource->image, ['http://','https://','images/'])
							? asset($resource->image)
							: asset('storage/' . $resource->image))
						: asset('images/rege1.png');
				@endphp
				<img class="thumb" src="{{ $imageUrl }}" alt="{{ $resource->name }}">
			</div>

			<div class="full edit-actions">
				<a href="{{ route('resources.manage') }}" class="btn ghost">Cancel</a>
				<button type="submit" class="btn primary">Save Changes</button>
			</div>
		</form>
	</div>

	<footer class="pro-footer">
		<div class="container footer-grid">
			<div class="footer-col brand-col">
				<a href="/" class="brand-logo" style="color:white;">
					<img src="{{ asset('images/logoBLK.png') }}" alt="DigitalCenter Logo" class="logo-image">
				</a>
				<p>Platform for allocation and tracking of Data Center IT resources.</p>
			</div>
            
			<div class="footer-col">
				<h5>Navigation</h5>
				<a href="{{ url('/catalogue') }}">Catalogue</a>
				<a href="#">My Reservations</a>
			</div>
            
			<div class="footer-col">
				<h5>Legal</h5>
				<a href="{{ route('usage-rules') }}">Usage Rules</a>
				<a href="{{ route('privacy-policy') }}">Privacy Policy</a>
			</div>
            
			<div class="footer-col">
				<h5>Contact Us</h5>
				<a href="#">IT Support</a>
			</div>
		</div>
		<div class="footer-bottom">
			&copy; 2026 DigitalCenter. All rights reserved.
		</div>
	</footer>

</body>
</html>
