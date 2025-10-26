Ok ครับ นี่คือ code ฉบับสมบูรณ์ที่แก้ไขตามที่คุณต้องการครับ
การเปลี่ยนแปลงที่สำคัญคือ ผมได้ปรับโครงสร้างของ array $servers ใหม่ครับ
แทนที่จะใส่ชื่อ server ที่ซ้ำกัน (ซึ่งจะทำให้ PHP เขียนทับค่าเก่าไป) ผมได้จัดกลุ่ม URL ทั้งหมดที่อยู่ภายใต้ชื่อ server เดียวกัน ให้อยู่ใน array เดียวกัน (เช่น '🇹🇭 Free 6' => [ url1, url2 ])
จากนั้น code จะวน loop ไปดึงข้อมูลจาก URL ทั้งสอง (หรือมากกว่า) ของ server ชื่อนั้นๆ แล้วนำค่า online ที่ได้ทั้งหมดมาบวกกัน ก่อนที่จะแสดงผลลัพธ์เป็นแถวเดียวครับ
Complete Code (HTML + PHP المعدل)
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
        }
        th {
            background-color: #f2f2f2;
        }
        .online {
            color: #00C853; font-weight: bold;
        }
        .online-warning {
            color: #FFD600; font-weight: bold; /* Yellow */
        }
        .online-danger {
            color: #D32F2F; font-weight: bold; /* Red */
        }
        .offline {
            color: #d32f2f; font-weight: bold;
        }
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
    <?php
        
        // CSP header (allow all domains)
        header("Content-Security-Policy: frame-ancestors *;");

        // --- นี่คือส่วนที่แก้ไข ---
        // เราเปลี่ยนโครงสร้าง array ให้ server name เป็น key
        // และ value เป็น array ของ URL ที่เกี่ยวข้อง
        $servers = [
            '🇺🇲 US 1' => ['http://us1.sksvpn.shop:81/server/online'],
            '🇯🇵 JP 1' => ['http://jp1.sksvpn.shop:81/server/online'],
            '🇸🇬 SG 1' => ['http://sg1.sksvpn.shop:81/server/online'],
            '🇹🇭 Free 1' => ['http://free1.sksfree.shop:81/server/online'],
            '🇹🇭 Free 2' => ['http://free2.sksfree.shop:81/server/online'],
            '🇹🇭 Free 3' => ['http://free3.sksfree.shop:81/server/online'],
            '🇹🇭 Free 4' => ['http://free4.sksfree.shop:81/server/online'],
            '🇹🇭 Free 5' => ['http://free5.sksfree.shop:81/server/online'],
            '🇹🇭 Free 6' => [
                'http://free6.sksvpn.shop:81/server/online',
                'http://free6.sksvpn.shop/udpserver/online'
            ],
            '🇹🇭 Free 7' => [
                'http://free7.sksvpn.shop:81/server/online',
                'http://free7.sksvpn.shop:81/udpserver/online'
            ],
            '🇹🇭 Free 8' => [
                'http://free8.sksvpn.shop:81/server/online',
                'http://free8.sksvpn.shop:81/udpserver/online'
            ],
            '🇹🇭 Free 9' => [
                'http://free9.sksvpn.shop:81/server/online',
                'http://free9.sksvpn.shop:81/udpserver/online'
            ],
            '🇹🇭 Free 10' => [
                'http://free10.sksvpn.shop:81/server/online',
                'http://free10.sksvpn.shop:81/udpserver/online'
            ],
            '🇹🇭 Free 11' => [
                'http://free11oct.sksvpn.shop:81/server/online',
                'http://free11oct.sksvpn.shop:81/udpserver/online'
            ],
            '🇹🇭 Free 12' => [
                'http://free12oct.sksvpn.shop:81/server/online',
                'http://free12oct.sksvpn.shop:81/udpserver/online'
            ],
            '🇹🇭 Free 13' => [
                'http://free13oct.sksvpn.shop:81/server/online',
                'http://free13oct.sksvpn.shop:81/udpserver/online'
            ],
            '🇹🇭 Free 14' => [
                'http://free14oct.sksvpn.shop:81/server/online',
                'http://free14oct.sksvpn.shop:81/udpserver/online'
            ],
            '🇹🇭 Free 15' => [
                'http://free15oct.sksvpn.shop:81/server/online',
                'http://free15oct.sksvpn.shop:81/udpserver/online'
            ],
        ];

        $totalOnlineCount = 0;

        echo '<table>';
        echo '<tr>
                <th>Server Name</th>
                <th>Status</th>
              </tr>';

        // วน loop ตามกลุ่ม server (server name)
        foreach ($servers as $serverName => $urlList) {
            
            $groupOnlineCount = 0; // ตัวแปรสำหรับนับผลรวมของกลุ่มนี้
            $groupConnectFailed = true; // ตั้งสมมติฐานว่าล้มเหลวไว้ก่อน
            
            // วน loop URL ภายในกลุ่ม
            foreach ($urlList as $serverURL) {
                $response = @file_get_contents($serverURL);
                
                if ($response !== false && is_numeric(trim($response))) {
                    $groupOnlineCount += intval($response); // บวกค่า online ที่ได้
                    $groupConnectFailed = false; // มีอย่างน้อย 1 URL ที่เชื่อมต่อสำเร็จ
                }
            }

            // หลังจากวน loop URL ในกลุ่มครบแล้ว ให้แสดงผล
            if (!$groupConnectFailed) {
                // $groupOnlineCount คือผลรวมของ server ชื่อนี้
                $totalOnlineCount += $groupOnlineCount; // เพิ่มเข้ายอดรวมทั้งหมด

                // Color logic (ใช้ $groupOnlineCount)
                if ($groupOnlineCount > 300) {
                    $statusClass = "online-danger";
                    $dotClass = "dot-red";
                    $label = "High Load";
                } elseif ($groupOnlineCount > 200) {
                    $statusClass = "online-warning";
                    $dotClass = "dot-yellow";
                    $label = "Busy";
                } else {
                    $statusClass = "online";
                    $dotClass = "dot-green";
                    $label = "Normal";
                }

                echo "<tr>
                        <td>$serverName</td>
                        <td class='$statusClass'>
                            <span class='status-dot $dotClass'></span>
                            Online $groupOnlineCount people
                            <span class='badge rounded-pill ms-2 $statusClass' style='background: transparent; border: 1px solid #ececec; font-size: 0.85em;'>$label</span>
                        </td>
                      </tr>";
            } else {
                // ถ้า URL ทั้งหมดในกลุ่มนี้ล้มเหลว
                echo "<tr><td>$serverName</td><td class='offline'><span class='status-dot dot-red'></span>Unable to connect</td></tr>";
            }
        }

        // Total users color
        // เราจะนับจำนวน "กลุ่ม" server ไม่ใช่จำนวน URL ทั้งหมด
        $serverGroupCount = count($servers); 
        
        if ($totalOnlineCount > 400 * $serverGroupCount) { // ปรับ logic การคำนวณตามจำนวนกลุ่ม
            $totalClass = "online-danger";
            $dotClass = "dot-red";
        } elseif ($totalOnlineCount > 300 * $serverGroupCount) { // ปรับ logic
            $totalClass = "online-warning";
            $dotClass = "dot-yellow";
        } else {
            $totalClass = "online";
            $dotClass = "dot-green";
        }

        echo '</table>';
        echo "<div class='total-users'>Total online users: <span class='$totalClass'><span class='status-dot $dotClass'></span>$totalOnlineCount</span> people</div>";
    ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

