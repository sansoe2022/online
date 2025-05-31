<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        h3 {
            text-align: center;
            color: #333;
        }
        .table-container {
            width: 95%;
            max-width: 800px;
            margin: 20px auto;
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
    <h3>SVPN Server Status</h3>
    <div class="table-container">
    <?php
        // Allow iframe embedding
        header('X-Frame-Options: ALLOWALL');
        header("Content-Security-Policy: frame-ancestors *");

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
</body>
</html>
