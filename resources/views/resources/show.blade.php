<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserve {{ $resource->name }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    <style>
        .cal-widget { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px; user-select: none; }
        .cal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; font-weight: bold; color: #1e293b; }
        .cal-nav { cursor: pointer; padding: 5px 10px; color: #64748b; }
        .cal-nav:hover { color: #0f172a; background: #e2e8f0; border-radius: 4px; }
        .cal-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 5px; text-align: center; }
        .cal-day-name { font-size: 12px; color: #94a3b8; font-weight: 600; padding-bottom: 5px; }
        
        .cal-day { padding: 8px; font-size: 14px; border-radius: 4px; cursor: pointer; color: #334155; transition: 0.2s; }
        
        /* Interactive States */
        .cal-day:hover:not(.booked-day) { background: #e0f2fe; color: #0284c7; }
        
        /* Start & End Dates */
        .cal-day.selected-endpoint { background: #22c55e !important; color: white !important; font-weight: bold; }
        
        /* Days In Between */
        .cal-day.in-range { background: #dcfce7 !important; color: #166534 !important; }
        
        /* Disabled Dates */
        .cal-day.booked-day { background-color: #f1f5f9; color: #cbd5e1; cursor: not-allowed; text-decoration: line-through; }
        .cal-day.empty { cursor: default; background: transparent !important; }
    </style>
</head>
<body>

    @php
        $hasPending = false;
        if(Auth::check()) {
            $hasPending = \App\Models\Reservation::where('user_id', Auth::id())
                ->where('resource_id', $resource->id)
                ->where('status', 'pending')
                ->exists();
        }
    @endphp

    <nav class="pro-navbar">
        <a href="/" class="pro-logo"><img src="{{ asset('images/logo1NBG.png') }}" alt="DataCenter Logo" class="logo-image" style="height:100px; vertical-align:middle;"></a>
        <div class="pro-menu">
            <a href="/" class="nav-btn"><i class="fa-solid fa-house"></i> Home</a>
            @auth
                <span style="margin-right: 15px; font-weight: bold; color: #333;">{{ Auth::user()->name }}</span>
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

    {{-- TOP BANNERS --}}
    @auth
        @if(!Auth::user()->fresh()->is_active)
            <div style="background-color: #fff3cd; color: #856404; padding: 15px; text-align: center; border-bottom: 1px solid #ffeeba; font-weight: bold; width: 100%;">
                <i class="fa-solid fa-lock"></i> Account Pending Approval
            </div>
        @elseif($hasPending)
            <div style="background-color: #e0f2fe; color: #0369a1; padding: 15px; text-align: center; border-bottom: 1px solid #bae6fd; font-weight: bold; width: 100%;">
                <i class="fa-solid fa-hourglass-half"></i> You have a pending request for this item.
            </div>
        @endif
        
        <div id="js-error-banner" style="display:none; background-color: #fee2e2; color: #b91c1c; padding: 15px; text-align: center; border-bottom: 1px solid #fecaca; font-weight: bold;">
            <i class="fa-solid fa-circle-exclamation"></i> <span id="js-error-text"></span>
        </div>

        @if(session('error'))
            <div style="background-color: #fee2e2; color: #b91c1c; padding: 15px; text-align: center; border-bottom: 1px solid #fecaca; font-weight: bold;">
                <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
            </div>
        @endif
    @endauth

    <div class="app-container" style="display: block; max-width: 1100px; margin: 40px auto; padding: 0 20px;">
        <a href="{{ route('catalogue') }}" style="display: inline-block; margin-bottom: 20px; color: #64748b; text-decoration: none; font-weight: 600;">
            <i class="fa-solid fa-arrow-left"></i> Back to Catalogue
        </a>

        <div class="pro-card" style="display: flex; flex-direction: row; overflow: hidden; min-height: 500px;">
            
            <div style="flex: 0.8; padding: 30px; border-right: 1px solid #e2e8f0; background: #f8fafc;">
                <div style="text-align: center; margin-bottom: 20px;">
                    @if($resource->image)
                        <img src="{{ asset('storage/' . $resource->image) }}" alt="{{ $resource->name }}" style="max-width: 100%; height: auto; border-radius: 8px;">
                    @else
                        <div style="font-size: 80px; color: #cbd5e1; margin: 40px 0;"><i class="fa-solid fa-server"></i></div>
                    @endif
                </div>
                <h1 style="font-size: 24px; color: #1e293b; margin-bottom: 10px;">{{ $resource->name }}</h1>
                <span class="status-badge {{ $resource->state == 'available' ? 'green' : 'orange' }}" style="display: inline-block; margin-bottom: 20px;">
                    {{ $resource->state == 'available' ? 'Available' : 'Maintenance' }}
                </span>
                <div style="margin-top: 20px;">
                    <h3 style="font-size: 16px; color: #64748b; text-transform: uppercase; font-weight: 700;">Specifications</h3>
                    <p style="color: #334155; margin-top: 5px;">{{ $resource->specifications ?? 'Standard Configuration' }}</p>
                </div>
            </div>

            <div style="flex: 1.2; padding: 40px; background: white; display: flex; flex-direction: column;">
                <h2 style="margin-bottom: 20px; color: #0f172a;">Select Date Range</h2>

                @auth
                    {{-- 1. SUCCESS STATE (RESTORED!) --}}
                    @if(session('success'))
                        <div style="padding: 30px; background: #dcfce7; border: 1px solid #86efac; border-radius: 8px; text-align: center;">
                            <i class="fa-solid fa-circle-check" style="font-size: 40px; color: #16a34a; margin-bottom: 15px;"></i>
                            <h3 style="color: #15803d; margin-bottom: 10px;">Reservation Sent!</h3>
                            <p style="color: #14532d;">{{ session('success') }}</p>
                            <a href="{{ route('dashboard') }}" class="card-btn" style="background: #16a34a; color: white; display: inline-block; width: auto; padding: 10px 20px; margin-top: 15px;">
                                Go to My Reservations
                            </a>
                        </div>
                    
                    {{-- 2. PENDING STATE --}}
                    @elseif($hasPending)
                        <div style="padding: 30px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; text-align: center;">
                            <i class="fa-solid fa-clock-rotate-left" style="font-size: 30px; color: #3b82f6; margin-bottom: 15px;"></i>
                            <h3 style="color: #1e40af; margin-bottom: 10px;">Request Pending</h3>
                            <p style="color: #1e3a8a;">You already have a pending request for this item.</p>
                            <a href="{{ route('dashboard') }}" class="card-btn" style="background: #3b82f6; color: white; display: inline-block; width: auto; padding: 10px 20px;">Check Status</a>
                        </div>
                    
                    {{-- 3. UNAVAILABLE STATES --}}
                    @elseif(!Auth::user()->fresh()->is_active || $resource->state != 'available')
                        <div style="padding: 20px; background: #f1f5f9; border-radius: 8px; text-align: center; color: #64748b;">
                            Booking is currently unavailable for this account or resource.
                        </div>

                    {{-- 4. BOOKING FORM (Range Calendar) --}}
                    @else
                        
                        <form action="{{ route('reservation.store') }}" method="POST" id="bookingForm">
                            @csrf
                            <input type="hidden" name="resource_id" value="{{ $resource->id }}">
                            
                            <input type="hidden" id="real_start_datetime" name="start_time">
                            <input type="hidden" id="real_end_datetime" name="end_time">

                            <div style="display:flex; gap: 20px; margin-bottom: 20px;">
                                <div style="flex: 1;">
                                    <div class="cal-widget">
                                        <div class="cal-header">
                                            <div class="cal-nav" onclick="changeMonth(-1)">❮</div>
                                            <div id="calMonthYear"></div>
                                            <div class="cal-nav" onclick="changeMonth(1)">❯</div>
                                        </div>
                                        <div class="cal-grid">
                                            <div class="cal-day-name">Mo</div><div class="cal-day-name">Tu</div><div class="cal-day-name">We</div>
                                            <div class="cal-day-name">Th</div><div class="cal-day-name">Fr</div><div class="cal-day-name">Sa</div>
                                            <div class="cal-day-name">Su</div>
                                        </div>
                                        <div class="cal-grid" id="calDays"></div>
                                    </div>
                                    <p style="font-size: 12px; color: #64748b; margin-top: 5px; text-align: center;">
                                        Click start date, then end date.
                                    </p>
                                </div>

                                <div style="flex: 0.8; display: flex; flex-direction: column; gap: 15px;">
                                    
                                    <div style="background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0;">
                                        <div style="font-size: 12px; color: #64748b; font-weight: bold;">FROM</div>
                                        <div id="dispStart" style="font-weight: bold; color: #1e293b; margin-bottom: 5px;">Select Date</div>
                                        <select id="time_start" class="card-btn" style="background:white; border:1px solid #cbd5e1; padding: 5px; font-size: 14px;">
                                            @for($i=8; $i<=18; $i++)
                                                <option value="{{ sprintf('%02d:00', $i) }}">{{ sprintf('%02d:00', $i) }}</option>
                                                <option value="{{ sprintf('%02d:30', $i) }}">{{ sprintf('%02d:30', $i) }}</option>
                                            @endfor
                                        </select>
                                    </div>

                                    <div style="background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0;">
                                        <div style="font-size: 12px; color: #64748b; font-weight: bold;">TO</div>
                                        <div id="dispEnd" style="font-weight: bold; color: #1e293b; margin-bottom: 5px;">Select Date</div>
                                        <select id="time_end" class="card-btn" style="background:white; border:1px solid #cbd5e1; padding: 5px; font-size: 14px;">
                                            @for($i=9; $i<=19; $i++)
                                                <option value="{{ sprintf('%02d:00', $i) }}">{{ sprintf('%02d:00', $i) }}</option>
                                                <option value="{{ sprintf('%02d:30', $i) }}">{{ sprintf('%02d:30', $i) }}</option>
                                            @endfor
                                        </select>
                                    </div>

                                </div>
                            </div>

                            <button type="button" onclick="submitReservation()" id="submitBtn" class="card-btn green" style="width: 100%; font-size: 16px; padding: 15px;">
                                Confirm Reservation <i class="fa-solid fa-arrow-right" style="margin-left: 5px;"></i>
                            </button>
                        </form>
                    @endif
                @else
                    <div style="text-align: center; padding: 40px 0;">
                        <i class="fa-solid fa-user-lock" style="font-size: 40px; color: #cbd5e1; margin-bottom: 20px;"></i>
                        <p style="margin-bottom: 20px; color: #64748b;">You must be logged in to reserve resources.</p>
                        <a href="{{ route('login') }}" class="card-btn" style="background: #0f172a; color: white; display: inline-block; width: auto; padding: 10px 30px;">Login / Register</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <footer class="pro-footer">
        <div class="footer-grid">
            <div class="footer-col brand-col">
                <a href="/" class="brand-logo" style="color:white;"><img src="{{ asset('images/logoBLK.png') }}" alt="DigitalCenter" style="height:100px;"></a>
                <p>Platform for allocation and tracking of Data Center IT resources.</p>
            </div>
            <div class="footer-col">
                <h5>Navigation</h5>
                <a href="{{ url('/catalogue') }}">Catalogue</a>
            </div>
            <div class="footer-col">
                <h5>Contact Us</h5>
                <a href="#">IT Support</a>
            </div>
        </div>
        <div class="footer-bottom">&copy; 2026 DigitalCenter. All rights reserved.</div>
    </footer>

    <script>
        const bookedSlots = @json($bookedSlots ?? []);
        let currentDate = new Date();
        let rangeStart = null;
        let rangeEnd = null;

        function renderCalendar() {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();
            
            document.getElementById('calMonthYear').innerText = new Intl.DateTimeFormat('en-US', { month: 'long', year: 'numeric' }).format(currentDate);

            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const calDays = document.getElementById('calDays');
            calDays.innerHTML = '';

            let startDay = firstDay === 0 ? 6 : firstDay - 1;

            // Empty slots
            for (let i = 0; i < startDay; i++) {
                const div = document.createElement('div');
                div.className = 'cal-day empty';
                calDays.appendChild(div);
            }

            // Days
            for (let d = 1; d <= daysInMonth; d++) {
                const dayDiv = document.createElement('div');
                dayDiv.className = 'cal-day';
                dayDiv.innerText = d;
                
                const checkDate = `${year}-${String(month+1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
                
                if (isDateBooked(checkDate)) {
                    dayDiv.classList.add('booked-day');
                    dayDiv.title = "Unavailable";
                } else {
                    dayDiv.onclick = () => selectDate(checkDate);
                }

                if (rangeStart === checkDate || rangeEnd === checkDate) {
                    dayDiv.classList.add('selected-endpoint');
                }

                if (rangeStart && rangeEnd) {
                    if (checkDate > rangeStart && checkDate < rangeEnd) {
                        dayDiv.classList.add('in-range');
                    }
                }

                calDays.appendChild(dayDiv);
            }
        }

        function isDateBooked(dateStr) {
            for (let slot of bookedSlots) {
                let s = slot.start_time.split(' ')[0];
                let e = slot.end_time.split(' ')[0];
                if (dateStr >= s && dateStr <= e) return true;
            }
            return false;
        }

        function selectDate(dateStr) {
            const banner = document.getElementById('js-error-banner');
            banner.style.display = 'none';

            if (!rangeStart || (rangeStart && rangeEnd)) {
                rangeStart = dateStr;
                rangeEnd = null;
            } else if (dateStr > rangeStart) {
                if (rangeIncludesBooking(rangeStart, dateStr)) {
                    banner.style.display = 'block';
                    document.getElementById('js-error-text').innerText = "Selection includes already booked dates!";
                    return; 
                }
                rangeEnd = dateStr;
            } else {
                rangeStart = dateStr;
                rangeEnd = null;
            }

            updateUI();
            renderCalendar();
        }

        function rangeIncludesBooking(start, end) {
            let curr = new Date(start);
            let last = new Date(end);
            while(curr <= last) {
                let d = curr.toISOString().split('T')[0];
                if (isDateBooked(d)) return true;
                curr.setDate(curr.getDate() + 1);
            }
            return false;
        }

        function updateUI() {
            document.getElementById('dispStart').innerText = rangeStart ? rangeStart : 'Select Date';
            document.getElementById('dispEnd').innerText = rangeEnd ? rangeEnd : (rangeStart ? rangeStart : 'Select Date');
        }

        function changeMonth(dir) {
            currentDate.setMonth(currentDate.getMonth() + dir);
            renderCalendar();
        }

        function submitReservation() {
            const timeStart = document.getElementById('time_start').value;
            const timeEnd = document.getElementById('time_end').value;
            const banner = document.getElementById('js-error-banner');

            if (!rangeStart) {
                banner.style.display = 'block';
                document.getElementById('js-error-text').innerText = "Please select at least a date.";
                return;
            }

            const finalStartInfo = rangeStart;
            const finalEndInfo = rangeEnd ? rangeEnd : rangeStart;
            const finalStart = `${finalStartInfo} ${timeStart}:00`;
            const finalEnd = `${finalEndInfo} ${timeEnd}:00`;

            if (finalStartInfo === finalEndInfo && timeEnd <= timeStart) {
                banner.style.display = 'block';
                document.getElementById('js-error-text').innerText = "End time must be after start time.";
                return;
            }

            document.getElementById('real_start_datetime').value = finalStart;
            document.getElementById('real_end_datetime').value = finalEnd;
            document.getElementById('bookingForm').submit();
        }

        renderCalendar();
    </script>

</body>
</html>