<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usage Rules - DigitalCenter</title>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="pro-body">

    <header class="pro-header">
        <div class="container nav-row">
            <a href="/" class="brand-logo">
                <img src="{{ asset('images/logo1NBG.png') }}" alt="DigitalCenter Logo" style="height:100px; margin-right:8px;">
            </a>
            <nav class="nav-links">
                <a href="/" class="nav-item btn-text">Back to Home</a>
            </nav>
        </div>
    </header>

    <section class="legal-content" style="padding: 80px 0;">
        <div class="container" style="max-width: 800px;">
            <h1 style="font-size: 36px; margin-bottom: 40px; color: var(--text-dark);">Acceptable Use Policy</h1>
            
            <div class="rule-block" style="margin-bottom: 40px;">
                <h3 style="color: var(--primary); margin-bottom: 15px;">1. Allowed Activities</h3>
                <ul style="list-style: none; padding: 0;">
                    <li style="margin-bottom: 10px; color: var(--text-grey);"><i class="fa-solid fa-check" style="color:var(--accent-green); margin-right:10px;"></i> Academic research and coursework assignments.</li>
                    <li style="margin-bottom: 10px; color: var(--text-grey);"><i class="fa-solid fa-check" style="color:var(--accent-green); margin-right:10px;"></i> Hosting of approved web applications and databases.</li>
                    <li style="margin-bottom: 10px; color: var(--text-grey);"><i class="fa-solid fa-check" style="color:var(--accent-green); margin-right:10px;"></i> Network simulation and security testing (in isolated sandboxes only).</li>
                </ul>
            </div>

            <div class="rule-block" style="margin-bottom: 40px;">
                <h3 style="color: #dc2626; margin-bottom: 15px;">2. Strictly Prohibited</h3>
                <ul style="list-style: none; padding: 0;">
                    <li style="margin-bottom: 10px; color: var(--text-grey);"><i class="fa-solid fa-ban" style="color:#dc2626; margin-right:10px;"></i> <strong>Cryptocurrency Mining:</strong> Any use of GPU/CPU resources for mining is strictly forbidden.</li>
                    <li style="margin-bottom: 10px; color: var(--text-grey);"><i class="fa-solid fa-ban" style="color:#dc2626; margin-right:10px;"></i> <strong>Peer-to-Peer File Sharing:</strong> Torrenting or hosting copyrighted material.</li>
                    <li style="margin-bottom: 10px; color: var(--text-grey);"><i class="fa-solid fa-ban" style="color:#dc2626; margin-right:10px;"></i> <strong>Unauthorized Penetration Testing:</strong> Attacking internal infrastructure without written consent from the Admin.</li>
                </ul>
            </div>

            <div class="rule-block">
                <h3 style="color: var(--primary); margin-bottom: 15px;">3. Reservation Limits</h3>
                <p style="color: var(--text-grey); line-height: 1.6;">
                    To ensure fair access for all students, the following limits apply:
                    <br>• Max reservation duration: <strong>4 Hours</strong> per session.
                    <br>• Max concurrent active sessions: <strong>2</strong> per user.
                    <br>• Unused reservations must be cancelled at least 30 minutes in advance.
                </p>
            </div>
        </div>
    </section>

    <footer class="pro-footer">
        <div class="container footer-grid">
            <div class="footer-col brand-col">
                <a href="/" class="brand-logo" style="color:white;"><img src="{{ asset('images/logoBLK.png') }}" alt="DigitalCenter Logo" style="height:100px; margin-right:8px;"></a>
            </div>
            <div class="footer-bottom">&copy; 2026 DigitalCenter Management System.</div>
        </div>
    </footer>

</body>
</html>