
<?php

$url = 'https://wgtmiojqpplblcevumhe.supabase.co/auth/v1/signup';
$key = 'TU_CLAVE_PUBLICABLE';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Método no permitido');
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
    exit('Correo inválido o contraseña demasiado corta.');
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
    ]
]);

$response = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if ($response === false) {
    curl_close($ch);
    http_response_code(500);
    exit('Error de conexión con Supabase.');
}

curl_close($ch);

$result = json_decode($response, true);

if ($status === 200 || $status === 201) {

    // No iniciamos sesión automáticamente.
    header('Location: login.html');
    exit();

} else {

    $error = $result['msg']
        ?? $result['message']
        ?? $result['error_description']
        ?? 'No se pudo registrar la cuenta.';

    echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8');
}
