<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="pro-body">

    <header class="pro-header">
        <div class="container nav-row">
            <a href="/" class="brand-logo">
                <img src="{{ asset('images/logo1NBG.png') }}" alt="DataCenter Logo" class="logo-image">
            </a>
            
            <nav class="nav-links">
                @auth
                    <span class="user-greeting">Welcome, {{ Auth::user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button class="nav-item btn-text">Logout</button>
                    </form>
                    <a href="{{ url('/catalogue') }}" class="nav-item btn-primary">Browse Catalogue</a>
                @else
                    <a href="{{ route('login') }}" class="nav-item btn-text">Log In</a>
                    <a href="{{ route('register') }}" class="nav-item btn-primary">Get Started</a>
                @endauth
            </nav>
        </div>
    </header>

    <section class="pro-hero">
        <div class="container hero-container">
            
            <div class="hero-content">
                <span class="badge-caps">Internal Lab Resources</span>
                <h1 class="hero-heading">High-Performance <br> Infrastructure Access.</h1>
                <p class="hero-sub">
                    Securely reserve and manage enterprise-grade hardware for your research and development projects.
                </p>
                
                <div class="hero-actions">
                    <a href="{{ url('/catalogue') }}" class="btn-hero-main">
                        Browse Catalogue
                    </a>
                    <div class="hero-stat-row">
                        <div class="tiny-stat">
                            <strong>500+</strong> Units
                        </div>
                        <div class="vr"></div>
                        <div class="tiny-stat">
                            <strong>99.9%</strong> Uptime
                        </div>
                    </div>
                </div>
            </div>

            <div class="hero-image-wrapper">
                <img src="{{ asset('images/rege1.png') }}" alt="Server Rack">
                <div class="image-card">
                    <i class="fa-solid fa-shield-halved"></i>
                    <div>
                        <span class="d-block">System Status</span>
                        <strong class="text-green">All Systems Operational</strong>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section class="brand-section">

        <div class="container">
            <p class="brand-label">POWERED BY INDUSTRY LEADERS</p>
            <div class="brand-track">
                <img src="https://upload.wikimedia.org/wikipedia/commons/6/64/Cisco_logo.svg" alt="Cisco" class="brand-icon">
                <img src="https://upload.wikimedia.org/wikipedia/commons/1/18/Dell_logo_2016.svg" alt="Dell" class="brand-icon">
                <!--<img src="https://upload.wikimedia.org/wikipedia/commons/b/b8/Juniper_Networks_logo.svg" alt="Juniper" class="brand-icon">-->
                <img src="https://upload.wikimedia.org/wikipedia/commons/4/46/Hewlett_Packard_Enterprise_logo.svg" alt="HPE" class="brand-icon">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/51/IBM_logo.svg" alt="IBM" class="brand-icon" style="padding: 5px;">
                <img src="https://upload.wikimedia.org/wikipedia/commons/2/21/Nvidia_logo.svg" alt="Nvidia" class="brand-icon">
                <!--<img src="https://upload.wikimedia.org/wikipedia/commons/3/3a/Fortinet_logo.svg" alt="Fortinet" class="brand-icon">-->
                <img src="https://upload.wikimedia.org/wikipedia/commons/7/7d/Intel_logo_%282006-2020%29.svg" alt="Intel" class="brand-icon">
                <img src="https://upload.wikimedia.org/wikipedia/commons/7/7c/AMD_Logo.svg" alt="AMD" class="brand-icon" style="padding: 5px;">
                <img src="https://upload.wikimedia.org/wikipedia/commons/9/96/Microsoft_logo_%282012%29.svg" alt="Microsoft" class="brand-icon">
                <img src="https://upload.wikimedia.org/wikipedia/commons/d/d8/Red_Hat_logo.svg" alt="Red Hat" class="brand-icon">
                <img src="https://upload.wikimedia.org/wikipedia/commons/9/9a/Vmware.svg" alt="VMware" class="brand-icon">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/50/Oracle_logo.svg" alt="Oracle" class="brand-icon">
                <img src="https://upload.wikimedia.org/wikipedia/commons/6/64/Cisco_logo.svg" alt="Cisco" class="brand-icon">
                <img src="https://upload.wikimedia.org/wikipedia/commons/1/18/Dell_logo_2016.svg" alt="Dell" class="brand-icon">
                <!--<img src="https://upload.wikimedia.org/wikipedia/commons/b/b8/Juniper_Networks_logo.svg" alt="Juniper" class="brand-icon">-->
                <img src="https://upload.wikimedia.org/wikipedia/commons/4/46/Hewlett_Packard_Enterprise_logo.svg" alt="HPE" class="brand-icon">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/51/IBM_logo.svg" alt="IBM" class="brand-icon" style="padding: 5px;">
                <img src="https://upload.wikimedia.org/wikipedia/commons/2/21/Nvidia_logo.svg" alt="Nvidia" class="brand-icon">
                <!--<img src="https://upload.wikimedia.org/wikipedia/commons/3/3a/Fortinet_logo.svg" alt="Fortinet" class="brand-icon">-->
                <img src="https://upload.wikimedia.org/wikipedia/commons/7/7d/Intel_logo_%282006-2020%29.svg" alt="Intel" class="brand-icon">
                <img src="https://upload.wikimedia.org/wikipedia/commons/7/7c/AMD_Logo.svg" alt="AMD" class="brand-icon" style="padding: 5px;">
                <img src="https://upload.wikimedia.org/wikipedia/commons/9/96/Microsoft_logo_%282012%29.svg" alt="Microsoft" class="brand-icon">
                <img src="https://upload.wikimedia.org/wikipedia/commons/d/d8/Red_Hat_logo.svg" alt="Red Hat" class="brand-icon">
                <img src="https://upload.wikimedia.org/wikipedia/commons/9/9a/Vmware.svg" alt="VMware" class="brand-icon">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/50/Oracle_logo.svg" alt="Oracle" class="brand-icon">
            </div>
        </div>
    </section>

    <section class="pro-features">
        <div class="container">
            <div class="feature-grid">
                <div class="feature-card">
                    <div class="icon-circle"><i class="fa-solid fa-network-wired"></i></div>
                    <h3>Network Topology</h3>
                    <p>Access complex Cisco routing and switching configurations.</p>
                </div>
                <div class="feature-card">
                    <div class="icon-circle"><i class="fa-solid fa-microchip"></i></div>
                    <h3>Compute Power</h3>
                    <p>Reserve high-core PowerEdge servers for processing.</p>
                </div>
                <div class="feature-card">
                    <div class="icon-circle"><i class="fa-regular fa-calendar-check"></i></div>
                    <h3>Guaranteed Access</h3>
                    <p>Book your time slot in seconds. Your hardware is locked and ready when you are.</p>
                </div>
            </div>
        </div>
    </section>
    <section class="how-it-works">
        <div class="container">
            <div class="section-header">
                <h2>Reservation Workflow</h2>
                <p>Three simple steps to access enterprise-grade resources.</p>
            </div>

            <div class="steps-row">
                <div class="step-item">
                    <div class="step-number">01</div>
                    <h4>Request</h4>
                    <p>Select resource, define period, and submit justification.</p>
                </div>
                <div class="step-line"></div>
                
                <div class="step-item">
                    <div class="step-number">02</div>
                    <h4>Validation</h4>
                    <p>Technical Manager reviews and approves the request.</p>
                </div>
                <div class="step-line"></div>

                <div class="step-item">
                    <div class="step-number">03</div>
                    <h4>Notification</h4>
                    <p>Receive alerts for status changes (Approved/Refused).</p>
                </div>
            </div>
        </div>
    </section>

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
                <a href="{{route('usage-rules')}}">Usage Rules</a>
                <a href="{{route('privacy-policy')}}">Privacy Policy</a>
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

</body>
</html>