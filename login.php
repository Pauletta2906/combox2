
<?php
session_start();

$url = 'https://wgtmiojqpplblcevumhe.supabase.co/auth/v1/token?grant_type=password';
$key = 'TU_CLAVE_PUBLICABLE';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.html');
    exit();
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    header('Location: login.html?error=1');
    exit();
}

$data = json_encode([
    'email' => $email,
    'password' => $password
]);

$ch = curl_init($url);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $data,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'apikey: ' . $key
    ],
    CURLOPT_TIMEOUT => 20
]);

$response = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if ($response === false) {
    error_log('Error cURL: ' . curl_error($ch));
    curl_close($ch);

    header('Location: login.html?error=conexion');
    exit();
}

curl_close($ch);

$result = json_decode($response, true);

if (
    $status === 200 &&
    isset($result['access_token']) &&
    isset($result['user']['id'])
) {
    session_regenerate_id(true);

    $_SESSION['supabase_access_token'] = $result['access_token'];
    $_SESSION['supabase_user_id'] = $result['user']['id'];

    header('Location: index.php');
    exit();
}

// Registrar el error real para revisarlo en PHP
error_log('Supabase login error HTTP ' . $status . ': ' . $response);

header('Location: login.html?error=1');
exit();
