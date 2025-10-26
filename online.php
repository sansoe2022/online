<?php
// --- အပိုင်း ၁: PHP Proxy ---
// JavaScript က 'fetch_url' ဆိုတဲ့ parameter နဲ့ ဒီ file ကို ပြန်ခေါ်တဲ့အခါ အလုပ်လုပ်ပါမယ်။
if (isset($_GET['fetch_url'])) {
    
    // လုံခြုံရေးအတွက်၊ ခွင့်ပြုထားတဲ့ domain တွေကိုပဲ request လုပ်ခိုင်းပါမယ်။
    $allowed_domains = [
        'us1.sksvpn.shop',
        'jp1.sksvpn.shop',
        'sg1.sksvpn.shop',
        'free1.sksfree.shop',
        'free2.sksfree.shop',
        'free3.sksfree.shop',
        'free4.sksfree.shop',
        'free5.sksfree.shop',
        'free6.sksvpn.shop',
        'free7.sksvpn.shop',
        'free8.sksvpn.shop',
        'free9.sksvpn.shop',
        'free10.sksvpn.shop',
        'free11oct.sksvpn.shop',
        'free12oct.sksvpn.shop',
        'free13oct.sksvpn.shop',
        'free14oct.sksvpn.shop',
        'free15oct.sksvpn.shop'
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
        body {
            font-family: 'Roboto', Arial, sans-serif;
            background: linear-gradient(135deg, #e0e7ff 0%, #ffffff 100%);
            min-height: 100vh;
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .table-container {
            width: 95%;
            max-width: 800px;
            margin: 32px auto 0 auto;
            background: #fff;
            box-shadow: 0 4px 6px rgba(0,0,0,0.08);
            border-radius: 8px;
            padding: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
        }
        th, td {
            border: 1px solid #ececec;
            padding: 10px;
            text-align: left;
            /* JS နဲ့ ထည့်တဲ့အခါ style ပျက်မသွားအောင် Caching လုပ်ထားတာပါ */
            min-height: 50px; 
        }
        th {
            background-color: #f2f2f2;
        }
        .online { color: #00C853; font-weight: bold; }
        .online-warning { color: #FFD600; font-weight: bold; }
        .online-danger { color: #D32F2F; font-weight: bold; }
        .offline { color: #d32f2f; font-weight: bold; }
        .loading { color: #888888; font-style: italic; } /* Loading status အတွက် style အသစ် */
        
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
        .dot-green { background: #00C853; }
        .dot-yellow { background: #FFD600; }
        .dot-red { background: #D32F2F; }
        .dot-grey { background: #888888; } /* Loading status အတွက် dot */

        @media (max-width: 600px) {
            .table-container {padding: 5px;}
            th, td {font-size: 0.91rem;}
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
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
        // PHP array ကို JavaScript Object အဖြစ် ပြောင်းထားပါတယ်
        const servers = {
            '🇺🇲 US 1': [
                'http://us1.sksvpn.shop:81/server/online',
                'http://us1.sksvpn.shop:81/udpserver/online'
            ],
            '🇯🇵 JP 1': [
                'http://jp1.sksvpn.shop:81/server/online',
                'http://jp1.sksvpn.shop:81/udpserver/online'
            ],
            '🇸🇬 SG 1': [
                'http://sg1.sksvpn.shop:81/server/online',
                'http://sg1.sksvpn.shop:81/udpserver/online'
            ],
            '🇹🇭 Free 1': [
                'http://free1.sksfree.shop:81/server/online',
                'http://free1.sksfree.shop:81/udpserver/online'
            ],
            '🇹🇭 Free 2': [
                'http://free2.sksfree.shop:81/server/online',
                'http://free2.sksfree.shop:81/udpserver/online'
            ],
            '🇹🇭 Free 3': [
                'http://free3.sksfree.shop:81/server/online',
                'http://free3.sksfree.shop:81/udpserver/online'
            ],
            '🇹🇭 Free 4': [
                'http://free4.sksfree.shop:81/server/online',
                'http://free4.sksfree.shop:81/udpserver/online'
            ],
            '🇹🇭 Free 5': [
                'http://free5.sksfree.shop:81/server/online',
                'http://free5.sksfree.shop:81/udpserver/online'
            ],
            '🇹🇭 Free 6': [
                'http://free6.sksvpn.shop:81/server/online',
                'http://free6.sksvpn.shop/udpserver/online'
            ],
            '🇹🇭 Free 7': [
                'http://free7.sksvpn.shop:81/server/online',
                'http://free7.sksvpn.shop:81/udpserver/online'
            ],
            '🇹🇭 Free 8': [
                'http://free8.sksvpn.shop:81/server/online',
                'http://free8.sksvpn.shop:81/udpserver/online'
            ],
            '🇹🇭 Free 9': [
                'http://free9.sksvpn.shop:81/server/online',
                'http://free9.sksvpn.shop:81/udpserver/online'
            ],
            '🇹🇭 Free 10': [
                'http://free10.sksvpn.shop:81/server/online',
                'http://free10.sksvpn.shop:81/udpserver/online'
            ],
            '🇹🇭 Free 11': [
                'http://free11oct.sksvpn.shop:81/server/online',
                'http://free11oct.sksvpn.shop:81/udpserver/online'
            ],
            '🇹🇭 Free 12': [
                'http://free12oct.sksvpn.shop:81/server/online',
                'http://free12oct.sksvpn.shop:81/udpserver/online'
            ],
            '🇹🇭 Free 13': [
                'http://free13oct.sksvpn.shop:81/server/online',
                'http://free13oct.sksvpn.shop:81/udpserver/online'
            ],
            '🇹🇭 Free 14': [
                'http://free14oct.sksvpn.shop:81/server/online',
                'http://free14oct.sksvpn.shop:81/udpserver/online'
            ],
            '🇹🇭 Free 15': [
                'http://free15oct.sksvpn.shop:81/server/online',
                'http://free15oct.sksvpn.shop:81/udpserver/online'
            ],
        };

        // Total တွက်ဖို့ variable တွေ
        let totalOnlineCount = 0;
        let serversResponded = 0;
        const serverGroupCount = Object.keys(servers).length;

        // HTML element တွေကို ကြိုယူထားမယ်
        const tbody = document.getElementById('server-list-body');
        const totalDisplay = document.getElementById('total-users-display');

        // Page load တာနဲ့ ဒီ function ကို run မယ်
        document.addEventListener('DOMContentLoaded', () => {
            // Server list ကို loop ပတ်မယ်
            Object.entries(servers).forEach(([serverName, urlList]) => {
                
                // 1. "Loading..." placeholder row ကို အရင်ဖန်တီးမယ်
                const row = tbody.insertRow();
                const cellName = row.insertCell(0);
                const cellStatus = row.insertCell(1);
                
                cellName.textContent = serverName;
                cellStatus.innerHTML = `<span class="loading"><span class='status-dot dot-grey'></span>Loading...</span>`;

                // 2. ဒီ server group အတွက် data ကို သွား fetch ခိုင်းမယ်
                fetchServerGroupStatus(serverName, urlList, cellStatus);
            });
        });

        // URL list တစ်ခုလုံး (server group) ရဲ့ status ကို fetch လုပ်မယ့် function
        async function fetchServerGroupStatus(serverName, urlList, statusCell) {
            let groupOnlineCount = 0;
            let groupConnectFailed = true;

            // URL list ထဲက link တွေကို တစ်ပြိုင်တည်း request ပို့မယ်
            const requests = urlList.map(url => {
                // ဒီ file ကိုယ်တိုင်ကို 'fetch_url' parameter နဲ့ ပြန်ခေါ်တာ (PHP proxy ကို ခေါ်တာပါ)
                return fetch(`?fetch_url=${encodeURIComponent(url)}`)
                    .then(response => response.json());
            });

            // request အားလုံး ပြီးဆုံးတာကို စောင့်မယ် (အောင်မြင်သည်ဖြစ်စေ၊ ကျရှုံးသည်ဖြစ်စေ)
            const results = await Promise.allSettled(requests);

            results.forEach(result => {
                if (result.status === 'fulfilled' && result.value.status === 'success') {
                    groupOnlineCount += result.value.online;
                    groupConnectFailed = false; // တစ်ခုမအောင်မြင်ရင်တောင် online ပြမယ်
                }
            });

            // 3. ရလာတဲ့ data နဲ့ status cell ကို update လုပ်မယ်
            if (!groupConnectFailed) {
                // Color logic
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
                statusCell.innerHTML = `
                    <span class='status-dot ${dotClass}'></span>
                    Online ${groupOnlineCount} people
                    <span class='badge rounded-pill ms-2 ${statusClass}' style='background: transparent; border: 1px solid #ececec; font-size: 0.85em;'>${label}</span>
                `;
                
                // Total ကို update လုပ်မယ်
                totalOnlineCount += groupOnlineCount;

            } else {
                // လုံးဝမရရင် offline ပြမယ်
                statusCell.className = 'offline';
                statusCell.innerHTML = `<span class='status-dot dot-red'></span>Unable to connect`;
            }

            // 4. Total display ကို update လုပ်ဖို့ server တိုင်းက ပြန်ဖြေပြီးပြီလား စစ်မယ်
            serversResponded++;
            if (serversResponded === serverGroupCount) {
                // server အားလုံး response ပြန်ပြီဆိုမှ total ကို နောက်ဆုံး update လုပ်မယ်
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

            totalDisplay.className = totalClass;
            totalDisplay.innerHTML = `<span class='status-dot ${dotClass}'></span>${totalOnlineCount}</span> people`;
        }

    </script>

</body>
</html>
