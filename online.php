<?php
// --- အပိုင်း ၁: PHP Proxy ---
// JavaScript က 'fetch_url' ဆိုတဲ့ parameter နဲ့ ဒီ file ကို ပြန်ခေါ်တဲ့အခါ အလုပ်လုပ်ပါမယ်။
if (isset($_GET['fetch_url'])) {
    
    // လုံခြုံရေးအတွက်၊ ခွင့်ပြုထားတဲ့ domain တွေကိုပဲ request လုပ်ခိုင်းပါမယ်။
    $allowed_domains = [
        'jp1.sksvpn.shop',
        'free1dec.sksvpn.shop',
        'free2dec.sksvpn.shop',
        'free3dec.sksvpn.shop',
        'free4dec.sksvpn.shop',
        'free5dec.sksvpn.shop',
        'free6nov.sksvpn.shop',
        'free7nov.sksvpn.shop',
        'free8nov.sksvpn.shop',
        'free9nov.sksvpn.shop',
        'free10nov.sksvpn.shop',
        'free11nov.sksvpn.shop',
        'free12nov.sksvpn.shop',
        'free13nov.sksvpn.shop',
        'free14nov.sksvpn.shop',
        'free15nov.sksvpn.shop',
        'free16dec.sksvpn.shop',
        'free17dec.sksvpn.shop',
        'free18dec.sksvpn.shop',
        'free19dec.sksvpn.shop',
        'free20dec.sksvpn.shop'
        
    ];
    
    $url_to_fetch = $_GET['fetch_url'];
    $domain = parse_url($url_to_fetch, PHP_URL_HOST);

    // Domain စစ်ဆေးခြင်း
    if (in_array($domain, $allowed_domains)) {
        
        // 5 စက္ကန့်ထက်ကြာရင် timeout ဖြစ်အောင် သတ်မှတ်ပါ
        $context = stream_context_create([
            'http' => [
                'timeout' => 5,  // 5 seconds
                'ignore_errors' => true // error တွေရလည်း ဆက်လုပ်မယ်
            ]
        ]);
        
        $response = @file_get_contents($url_to_fetch, false, $context);
        
        if ($response !== false && is_numeric(trim($response))) {
            // အောင်မြင်ရင် JSON format နဲ့ data ပြန်ပို့မယ်
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success', 'online' => intval(trim($response))]);
        } else {
            // မအောင်မြင်ရင် error JSON ပြန်ပို့မယ်
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error']);
        }
        
    } else {
        // ခွင့်မပြုတဲ့ domain ဆိုရင်
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Domain not allowed']);
    }
    
    // HTML ပိုင်းကို ဆက်မ run စေတော့ဘဲ ဒီမှာတင် ရပ်လိုက်မယ်
    exit;
}

// --- အပိုင်း ၂: HTML + JavaScript Page ---
// ပုံမှန် page load (အပေါ်က 'fetch_url' parameter မပါရင် ဒီကို ရောက်လာမယ်)

// CSP header (allow all domains)
header("Content-Security-Policy: frame-ancestors *;");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SVPN Online Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            /* Light Mode Colors (Deep) */
            --bg-color-light: linear-gradient(135deg, #e0e7ff 0%, #ffffff 100%);
            --nav-bg-light: #ffffff;
            --table-bg-light: #ffffff;
            --text-color-light: #212529;
            --border-color-light: #ececec;
            --th-bg-light: #f2f2f2;
            
            /* === [ပြင်ဆင်မှု ၁] Green Color ကို #006400 အစား #198754 (Bootstrap Green) သို့ ပြောင်းထားပါသည် === */
            --color-green-light: #198754;  /* Deep but clear Green */
            --color-yellow-light: #FFA000; /* Deep Yellow (Amber) */
            --color-red-light: #B71C1C;    /* Deep Red */
            --color-grey-light: #888888;
            --color-offline-light: #B71C1C;

            /* Dark Mode Colors */
            --bg-color-dark: linear-gradient(135deg, #0a192f 0%, #172a45 100%);
            --nav-bg-dark: #0a192f;
            --table-bg-dark: #172a45;
            --text-color-dark: #ccd6f6;
            --border-color-dark: #303c55;
            --th-bg-dark: #0a192f;

            --color-green-dark: #00C853;  /* Bright Green */
            --color-yellow-dark: #FFD600; /* Bright Yellow */
            --color-red-dark: #F44336;    /* Bright Red */
            --color-grey-dark: #999999;
            --color-offline-dark: #F44336;
        }

        body {
            font-family: 'Roboto', Arial, sans-serif;
            background: var(--bg-color-light);
            color: var(--text-color-light);
            min-height: 100vh;
            transition: background 0.3s, color 0.3s;
        }
        body.dark-mode {
            background: var(--bg-color-dark);
            color: var(--text-color-dark);
        }

        .navbar {
            background-color: var(--nav-bg-light) !important;
            transition: background-color 0.3s;
        }
        body.dark-mode .navbar {
            background-color: var(--nav-bg-dark) !important;
        }
        .navbar .nav-link, .navbar .navbar-brand {
            color: var(--text-color-light) !important;
        }
        body.dark-mode .navbar .nav-link, 
        body.dark-mode .navbar .navbar-brand {
            color: var(--text-color-dark) !important;
        }

        /* === [ပြင်ဆင်မှု ၂] Dark Mode Toggler Button အတွက် CSS === */
        body.dark-mode .navbar-toggler {
            border-color: var(--border-color-dark);
        }
        body.dark-mode .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255, 255, 255, 0.55)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        /* ==================================================== */
        
        .theme-switch-wrapper {
            display: flex;
            align-items: center;
        }
        .theme-switch {
            display: inline-block;
            height: 24px;
            position: relative;
            width: 50px;
            margin: 0 10px;
        }
        .theme-switch input { display:none; }
        .slider {
            background-color: #ccc;
            bottom: 0;
            cursor: pointer;
            left: 0;
            position: absolute;
            right: 0;
            top: 0;
            transition: .4s;
        }
        .slider:before {
            background-color: #fff;
            bottom: 4px;
            content: "";
            height: 16px;
            left: 4px;
            position: absolute;
            transition: .4s;
            width: 16px;
        }
        input:checked + .slider {
            background-color: #66bb6a;
        }
        input:checked + .slider:before {
            transform: translateX(26px);
        }
        .slider.round { border-radius: 34px; }
        .slider.round:before { border-radius: 50%; }

        .table-container {
            width: 95%;
            max-width: 800px;
            margin: 32px auto 0 auto;
            background: var(--table-bg-light);
            box-shadow: 0 4px 6px rgba(0,0,0,0.08);
            border-radius: 8px;
            padding: 16px;
            transition: background 0.3s;
        }
        body.dark-mode .table-container {
            background: var(--table-bg-dark);
            box-shadow: 0 4px 6px rgba(0,0,0,0.2);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: var(--table-bg-light);
            transition: background 0.3s;
        }
        body.dark-mode table {
            background: var(--table-bg-dark);
        }

        th, td {
            border: 1px solid var(--border-color-light);
            padding: 10px;
            text-align: left;
            min-height: 50px; 
            transition: border-color 0.3s;
        }
        body.dark-mode th, body.dark-mode td {
            border: 1px solid var(--border-color-dark);
        }

        th {
            background-color: var(--th-bg-light);
            transition: background-color 0.3s;
        }
        body.dark-mode th {
            background-color: var(--th-bg-dark);
        }

        /* Status Colors */
        .online { color: var(--color-green-light); font-weight: bold; }
        .online-warning { color: var(--color-yellow-light); font-weight: bold; }
        .online-danger { color: var(--color-red-light); font-weight: bold; }
        .offline { color: var(--color-offline-light); font-weight: bold; }
        .loading { color: var(--color-grey-light); font-style: italic; }

        .dot-green { background: var(--color-green-light); }
        .dot-yellow { background: var(--color-yellow-light); }
        .dot-red { background: var(--color-red-light); }
        .dot-grey { background: var(--color-grey-light); }

        /* Dark Mode Status Colors */
        body.dark-mode .online { color: var(--color-green-dark); }
        body.dark-mode .online-warning { color: var(--color-yellow-dark); }
        body.dark-mode .online-danger { color: var(--color-red-dark); }
        body.dark-mode .offline { color: var(--color-offline-dark); }
        body.dark-mode .loading { color: var(--color-grey-dark); }

        body.dark-mode .dot-green { background: var(--color-green-dark); }
        body.dark-mode .dot-yellow { background: var(--color-yellow-dark); }
        body.dark-mode .dot-red { background: var(--color-red-dark); }
        body.dark-mode .dot-grey { background: var(--color-grey-dark); }

        .total-users {
            text-align: center;
            margin-top: 16px;
            margin-bottom: 20px;
            font-weight: bold;
            font-size: 1.1rem;
        }
        .status-dot {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 6px;
            vertical-align: middle;
        }

        @media (max-width: 600px) {
            .table-container {padding: 5px;}
            th, td {font-size: 0.91rem;}
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.html">
                <img src="https://raw.githubusercontent.com/sansoe2022/image_store/refs/heads/main/pnt_icon510.png" alt="SVPN Logo" style="width:36px; height:auto;">
                PNT VPN
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#svpnNavbar" aria-controls="svpnNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="svpnNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="https://pntvpn.netlify.app/guidesim">
                            <i class="bi bi-journal-code me-1"></i> VPN နဲ့ ချိတ်သုံးဖို့ လမ်းညွှန်
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://pntvpn.netlify.app/extendsim">
                            <i class="bi bi-phone me-1"></i> ဆင်းမ်ကဒ် သက်တမ်းတိုးနည်း
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://pntvpn.netlify.app/">
                            <i class="bi bi-house-door me-1"></i> Home
                        </a>
                    </li>
                    <li class="nav-item ms-lg-3 d-flex align-items-center">
                        <div class="theme-switch-wrapper">
                            <i class="bi bi-sun-fill"></i>
                            <label class="theme-switch mx-2">
                                <input type="checkbox" id="theme-toggle">
                                <span class="slider round"></span>
                            </label>
                            <i class="bi bi-moon-fill"></i>
                        </div>
                    </li>
                    </ul>
            </div>
        </div>
    </nav>
    
    <h3 class="mt-4 text-center">PNT VPN Server Status</h3>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Server Name</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="server-list-body">
                </tbody>
        </table>
        
        <div class='total-users'>Total online users: 
            <span id="total-users-display" class="loading">
                <span class='status-dot dot-grey'></span>Loading...
            </span>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Server list (သင့် server list အတိုင်း)
        const servers = {
            '🇯🇵 JP 1': [
                'http://jp1.sksvpn.shop:81/server/online',
                'http://jp1.sksvpn.shop:81/udpserver/online'
            ],
            '🇹🇭 Free 1': [
                'http://free1nov.sksvpn.shop:81/server/online',
                'http://free1nov.sksvpn.shop:81/udpserver/online'
            ],
            '🇹🇭 Free 2': [
                'http://free2nov.sksvpn.shop:81/server/online',
                'http://free2nov.sksvpn.shop:81/udpserver/online'
            ],
            '🇹🇭 Free 3': [
                'http://free3nov.sksvpn.shop:81/server/online',
                'http://free3nov.sksvpn.shop:81/udpserver/online'
            ],
            '🇹🇭 Free 4': [
                'http://free4nov.sksvpn.shop:81/server/online',
                'http://free4nov.sksvpn.shop:81/udpserver/online'
            ],
            '🇹🇭 Free 5': [
                'http://free5nov.sksvpn.shop:81/server/online',
                'http://free5nov.sksvpn.shop:81/udpserver/online'
            ],
            '🇹🇭 Free 6': [
                'http://free6nov.sksvpn.shop:81/server/online',
                'http://free6nov.sksvpn.shop/udpserver/online'
            ],
            '🇹🇭 Free 7': [
                'http://free7nov.sksvpn.shop:81/server/online',
                'http://free7nov.sksvpn.shop:81/udpserver/online'
            ],
            '🇹🇭 Free 8': [
                'http://free8nov.sksvpn.shop:81/server/online',
                'http://free8nov.sksvpn.shop:81/udpserver/online'
            ],
            '🇹🇭 Free 9': [
                'http://free9nov.sksvpn.shop:81/server/online',
                'http://free9nov.sksvpn.shop:81/udpserver/online'
            ],
            '🇹🇭 Free 10': [
                'http://free10nov.sksvpn.shop:81/server/online',
                'http://free10nov.sksvpn.shop:81/udpserver/online'
            ],
            '🇹🇭 Free 11': [
                'http://free11nov.sksvpn.shop:81/server/online',
                'http://free11nov.sksvpn.shop:81/udpserver/online'
            ],
            '🇹🇭 Free 12': [
                'http://free12nov.sksvpn.shop:81/server/online',
                'http://free12nov.sksvpn.shop:81/udpserver/online'
            ],
            '🇹🇭 Free 13': [
                'http://free13nov.sksvpn.shop:81/server/online',
                'http://free13nov.sksvpn.shop:81/udpserver/online'
            ],
            '🇹🇭 Free 14': [
                'http://free14nov.sksvpn.shop:81/server/online',
                'http://free14nov.sksvpn.shop:81/udpserver/online'
            ],
            '🇹🇭 Free 15': [
                'http://free15nov.sksvpn.shop:81/server/online',
                'http://free15nov.sksvpn.shop:81/udpserver/online'

            ],
            '🇹🇭 Free 16': [
                'http://free16dec.sksvpn.shop:81/server/online',
                'http://free16dec.sksvpn.shop:81/udpserver/online'
            ],
            '🇹🇭 Free 17': [
                'http://free17dec.sksvpn.shop:81/server/online',
                'http://free17dec.sksvpn.shop:81/udpserver/online'
            ],
            '🇹🇭 Free 18': [
                'http://free18dec.sksvpn.shop:81/server/online',
                'http://free18dec.sksvpn.shop:81/udpserver/online'
            ],
            '🇹🇭 Free 19': [
                'http://free19dec.sksvpn.shop:81/server/online',
                'http://free19dec.sksvpn.shop:81/udpserver/online'
            ],
            '🇹🇭 Free 20': [
                'http://free20dec.sksvpn.shop:81/server/online',
                'http://free20dec.sksvpn.shop:81/udpserver/online'

            ],
        };

        // --- Global Variables ---
        let totalOnlineCount = 0;
        let serversResponded = 0;
        const serverGroupCount = Object.keys(servers).length;

        // HTML element တွေကို ကြိုယူထားမယ်
        const tbody = document.getElementById('server-list-body');
        const totalDisplay = document.getElementById('total-users-display');
        const themeToggle = document.getElementById('theme-toggle');
        const body = document.body;

        
        // --- Theme Toggle Logic ---
        function setTheme(theme) {
            if (theme === 'dark') {
                body.classList.add('dark-mode');
                themeToggle.checked = true;
                localStorage.setItem('theme', 'dark');
            } else {
                body.classList.remove('dark-mode');
                themeToggle.checked = false;
                localStorage.setItem('theme', 'light');
            }
        }

        // Toggle change event
        themeToggle.addEventListener('change', () => {
            if (themeToggle.checked) {
                setTheme('dark');
            } else {
                setTheme('light');
            }
        });

        // Load saved theme from localStorage
        const savedTheme = localStorage.getItem('theme') || 'light';
        setTheme(savedTheme);
        

        // --- Data Fetching Logic ---

        // Main function (ဒါကို 30 စက္ကန့်တစ်ခါ ခေါ်မယ်)
        function fetchAllServerStatuses() {
            // Reset counters and table
            totalOnlineCount = 0;
            serversResponded = 0;
            tbody.innerHTML = ''; // Table ကို ရှင်းလင်းပါ
            totalDisplay.innerHTML = `<span class="loading"><span class='status-dot dot-grey'></span>Loading...</span>`;
            totalDisplay.className = 'total-users'; // class ကို reset လုပ်ပါ

            // Server list ကို loop ပတ်ပြီး "Loading..." row တွေ အသစ်ပြန်ဆောက်မယ်
            Object.entries(servers).forEach(([serverName, urlList]) => {
                const row = tbody.insertRow();
                const cellName = row.insertCell(0);
                const cellStatus = row.insertCell(1);
                
                cellName.textContent = serverName;
                cellStatus.innerHTML = `<span class="loading"><span class='status-dot dot-grey'></span>Loading...</span>`;

                // Data အသစ် သွား fetch ခိုင်းမယ်
                fetchServerGroupStatus(serverName, urlList, cellStatus);
            });
        }

        // URL list တစ်ခုလုံး (server group) ရဲ့ status ကို fetch လုပ်မယ့် function
        async function fetchServerGroupStatus(serverName, urlList, statusCell) {
            let groupOnlineCount = 0;
            let groupConnectFailed = true;

            const requests = urlList.map(url => {
                // cache မဖြစ်အောင် အချိန်ထည့်ပေးပါ
                const cacheBustUrl = `${url.includes('?') ? '&' : '?'}t=${new Date().getTime()}`;
                return fetch(`?fetch_url=${encodeURIComponent(url)}${cacheBustUrl.substring(url.length)}`)
                    .then(response => {
                        if (!response.ok) {
                            return {status: 'error'}; // Network error
                        }
                        return response.json();
                    })
                    .catch(() => {
                        return {status: 'error'}; // Fetch API error
                    });
            });

            const results = await Promise.allSettled(requests);

            results.forEach(result => {
                if (result.status === 'fulfilled' && result.value.status === 'success') {
                    groupOnlineCount += result.value.online;
                    groupConnectFailed = false;
                }
            });

            // ရလာတဲ့ data နဲ့ status cell ကို update လုပ်မယ်
            if (!groupConnectFailed) {
                let statusClass, dotClass, label;
                if (groupOnlineCount > 300) {
                    statusClass = "online-danger";
                    dotClass = "dot-red";
                    label = "High Load";
                } else if (groupOnlineCount > 200) {
                    statusClass = "online-warning";
                    dotClass = "dot-yellow";
                    label = "Busy";
                } else {
                    statusClass = "online";
                    dotClass = "dot-green";
                    label = "Normal";
                }

                statusCell.className = statusClass;
                // dark mode မှာ border အရောင်မတူလို့ CSS variable ကို သုံးထားပါတယ်
                statusCell.innerHTML = `
                    <span class='status-dot ${dotClass}'></span>
                    Online ${groupOnlineCount} people
                    <span class='badge rounded-pill ms-2 ${statusClass}' style='background: transparent; border: 1px solid var(--border-color-light); font-size: 0.85em;'>${label}</span>
                `;
                
                totalOnlineCount += groupOnlineCount;

            } else {
                statusCell.className = 'offline';
                statusCell.innerHTML = `<span class='status-dot dot-red'></span>Unable to connect`;
            }

            // server တိုင်းက ပြန်ဖြေပြီးပြီလား စစ်မယ်
            serversResponded++;
            if (serversResponded === serverGroupCount) {
                updateTotalDisplay();
            }
        }
        
        // Total online display ကို update လုပ်မယ့် function
        function updateTotalDisplay() {
            let totalClass, dotClass;
            
            if (totalOnlineCount > 400 * serverGroupCount) {
                totalClass = "online-danger";
                dotClass = "dot-red";
            } else if (totalOnlineCount > 300 * serverGroupCount) {
                totalClass = "online-warning";
                dotClass = "dot-yellow";
            } else {
                totalClass = "online";
                dotClass = "dot-green";
            }

            totalDisplay.className = `total-users ${totalClass}`; // class ကို update လုပ်ပါ
            totalDisplay.innerHTML = `<span class='status-dot ${dotClass}'></span>${totalOnlineCount} people`;
        }

        // --- Page Load & Auto-Refresh ---
        document.addEventListener('DOMContentLoaded', () => {
            // Page load တာနဲ့ တစ်ခါတည်း အရင် run မယ်
            fetchAllServerStatuses(); 
            
            // === [ပြင်ဆင်မှု ၃] 10000ms မှ 30000ms (30 စက္ကန့်) သို့ ပြောင်းထားပါသည် ===
            setInterval(fetchAllServerStatuses, 30000); 
        });

    </script>

</body>
</html>
