<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SVPN Online Users</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
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
        }
        th {
            background-color: #f2f2f2;
        }
        .online {
            color: #00C853; font-weight: bold;
        }
        .offline {
            color: #d32f2f; font-weight: bold;
        }
        .total-users {
            text-align: center;
            margin-top: 16px;
            font-weight: bold;
            font-size: 1.1rem;
        }
        @media (max-width: 600px) {
            .table-container {padding: 5px;}
            th, td {font-size: 0.91rem;}
        }
    </style>
</head>
<body>
    <!-- Navbar (same as index.html) -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.html">
                <img src="https://raw.githubusercontent.com/sansoe2022/image_store/refs/heads/main/icon.png" alt="SVPN Logo" style="width:36px; height:auto;">
                SVPN
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#svpnNavbar" aria-controls="svpnNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="svpnNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="guidesim.html">
                            <i class="bi bi-journal-code me-1"></i> VPN နဲ့ ချိတ်သုံးဖို့ လမ်းညွှန်
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="extendsim.html">
                            <i class="bi bi-phone me-1"></i> ဆင်းမ်ကဒ် သက်တမ်းတိုးနည်း
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.html">
                            <i class="bi bi-house-door me-1"></i> Home
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <h3 class="mt-4 text-center">SVPN Server Status</h3>
    <div class="table-container">
    <?php
        // Allow iframe embedding if you want to show in iframe (optional)
        // header('X-Frame-Options: ALLOWALL');
        // header("Content-Security-Policy: frame-ancestors *");

        $servers = [
            '🇹🇭 FREE 1' => 'http://tmvh.co.free1.sksfree.shop:81/server/online',
            '🇹🇭 FREE 2' => 'http://tmvh.co.free6.sksfree.shop:81/server/online',
            '🇹🇭 FREE 3' => 'http://tmvh.co.free1new.sksfree.shop:81/server/online',
            '🇹🇭 FREE 4' => 'http://tmvh.co.free4.sksfree.shop:81/server/online',
            '🇹🇭 FREE 5' => 'http://tmvh.co.free5.sksfree.shop:81/server/online',
            '🇹🇭 FREE 6' => 'http://tmvh.co.free2.sksfree.shop:81/server/online',
            '🇹🇭 FREE 7' => 'http://tmvh.co.free3.sksfree.shop:81/server/online',
            '🇹🇭 FREE 8' => 'http://tmvh.co.free8.sksfree.shop:81/server/online',
            '🇹🇭 FREE 9' => 'http://tmvh.co.free9.sksfree.shop:81/server/online',
        ];

        $totalOnlineCount = 0;

        echo '<table>';
        echo '<tr>
                <th>Server Name</th>
                <th>Status</th>
              </tr>';

        foreach ($servers as $serverName => $serverURL) {
            $response = @file_get_contents($serverURL);
            if ($response !== false && is_numeric(trim($response))) {
                $onlineCount = intval($response);
                echo "<tr><td>$serverName</td><td class='online'>Online $onlineCount people</td></tr>";
                $totalOnlineCount += $onlineCount;
            } else {
                echo "<tr><td>$serverName</td><td class='offline'>Unable to connect</td></tr>";
            }
        }

        echo '</table>';
        echo "<div class='total-users'>Total online users: <span class='online'>$totalOnlineCount</span> people</div>";
    ?>
    </div>
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
