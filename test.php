<!DOCTYPE html>
<html>
<head>
    <title>Capture Source</title>
    <script>
        function sendClientInfo() {
            var clientInfo = {
                url: window.location.href,
                referrer: document.referrer,
                userAgent: navigator.userAgent,
                platform: navigator.platform,
                cookiesEnabled: navigator.cookieEnabled
            };

 
            alert(clientInfo);
            console.log(clientInfo);
        }

        // Enviar la información al cargar la página
        window.onload = sendClientInfo;
    </script>
</head>
<body>
    <h1>Capture Source Example</h1>
</body>
</html>

<?php
function getDeviceType($user_agent) {
    if (preg_match('/mobile/i', $user_agent)) {
        return 'Mobile';
    } elseif (preg_match('/tablet/i', $user_agent)) {
        return 'Tablet';
    } else {
        return 'Desktop';
    }
}

// Capturando la dirección IP del cliente
$ip_address = $_SERVER['REMOTE_ADDR'];

// Capturando el referer
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'Direct Access';

// Capturando el user agent
$user_agent = $_SERVER['HTTP_USER_AGENT'];

// Determinando el tipo de dispositivo
$device_type = getDeviceType($user_agent);

// Imprimiendo los resultados
echo "IP Address: " . $ip_address . "<br>";
echo "Referer: " . $referer . "<br>";
echo "Device Type: " . $device_type;
?>