<?php

$headers = apache_request_headers();

// Получаем значение заголовка X-Forwarded-For, если он существует, иначе используем запасной текст
$xForwardedFor = isset($headers['X-Forwarded-For']) ? $headers['X-Forwarded-For'] : 'No X-Forwarded-For header';
// Получаем значение заголовка X-Real-IP, если он существует, иначе используем запасной текст
$xRealIp = isset($headers['X-Real-IP']) ? $headers['X-Real-IP'] : 'No X-Real-IP header';

// Разделяем заголовок X-Forwarded-For на отдельные IP адреса
$ips = explode(', ', $xForwardedFor);

// Фильтруем IP адреса, удаляя те, которые не содержат '+'
$filteredIps = array_filter($ips, function($ip) {
    return strpos($ip, '+') !== false;
});

// Удаляем '+буквенное выражение' из IP адресов
$cleanedIps = array_map(function($ip) {
    $pos = strpos($ip, '+');
    return $pos !== false ? substr($ip, 0, $pos) : $ip;
}, $filteredIps);

// Собираем обратно строку с IP адресами
$xForwardedForCleaned = implode(', ', $cleanedIps);

// Выводим значения заголовков, экранируя их для безопасности
echo "X-Forwarded-For: " . htmlspecialchars($xForwardedForCleaned) . "\n";
echo "X-Real-IP: " . htmlspecialchars($xRealIp);

?>