<?php
  require_once('../../../includes/loggedin.php');
  require_once('../../../includes/mysqli_connect_user.php');
  check_user_type('Addetto-Consegne');

  // inizio file, niente whitespace!
  header('Content-Type: application/json; charset=utf-8');

  // cattura eventuali errori in JSON
  set_error_handler(function($errno, $errstr) {
      http_response_code(500);
      echo json_encode(['success' => false, 'message' => "PHP Error: $errstr"]);
      exit;
  });

  // valida input
  $email     = $_POST['email']   ?? '';
  $data      = $_POST['date']    ?? '';
  $ora       = $_POST['time']    ?? '';
  $nomeProd  = $_POST['product'] ?? '';
  $operatore = $_SESSION['CodiceID'];

  // chiami la stored procedure
  $stmt = $dbc->prepare("CALL segna_ordine_consegnato(?, ?, ?, ?, ?, @msg);");
  $stmt->bind_param('ssssi', $data, $ora, $email, $nomeProd, $operatore);
  if (!$stmt->execute()) {
      http_response_code(500);
      echo json_encode(['success' => false, 'message' => $stmt->error]);
      exit;
  }

  // preleva OUT parameter
  $res = $dbc->query("SELECT @msg AS message");
  $msg = ($res->fetch_assoc())['message'] ?? '';

  // invia JSON di risposta
  echo json_encode(['success' => true, 'message' => $msg]);
  exit;

  ?>