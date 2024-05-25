<?php
header('Content-Type: application/json');

$signed_request = $_POST['signed_request'];
$data = parse_signed_request($signed_request);
$user_id = $data['user_id'];

// Iniciar a exclusão de dados sobre o usuário
// Aqui você deve adicionar a lógica para excluir os dados do usuário do seu banco de dados ou sistema

// URL para acompanhar a exclusão
$status_url = 'https://www.seusite.com/deletion_status?id=' . $user_id; // Personalize esta URL
$confirmation_code = uniqid(); // Gera um código único para a solicitação de exclusão

$data = array(
  'url' => $status_url,
  'confirmation_code' => $confirmation_code
);
echo json_encode($data);

function parse_signed_request($signed_request) {
  list($encoded_sig, $payload) = explode('.', $signed_request, 2);

  $secret = "SEU_APP_SECRET"; // Use seu app secret aqui

  // Decodifica os dados
  $sig = base64_url_decode($encoded_sig);
  $data = json_decode(base64_url_decode($payload), true);

  // Confirma a assinatura
  $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
  if ($sig !== $expected_sig) {
    error_log('Assinatura JSON assinada inválida!');
    return null;
  }

  return $data;
}

function base64_url_decode($input) {
  return base64_decode(strtr($input, '-_', '+/'));
}
?>
