
<?php

session_start();

require_once __DIR__ . '/config/supabase.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Método no permitido');
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
    exit('Ingresa un correo válido y una contraseña.');
}

$url = rtrim(SUPABASE_URL, '/') . '/auth/v1/token?grant_type=password';

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
        'apikey: ' . SUPABASE_KEY
    ],
    CURLOPT_TIMEOUT => 15
]);

$response = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);

curl_close($ch);

if ($response === false) {
    http_response_code(502);
    exit('No fue posible conectar con Supabase.');
}

$result = json_decode($response, true);

if ($status === 200 && isset($result['access_token'], $result['user']['id'])) {
    session_regenerate_id(true);

    $_SESSION['supabase_access_token'] = $result['access_token'];
    $_SESSION['supabase_user_id'] = $result['user']['id'];
    $_SESSION['supabase_email'] = $result['user']['email'] ?? $email;

    header('Location: index.html');
    exit;
}

http_response_code(401);
exit('Correo o contraseña incorrectos.');
