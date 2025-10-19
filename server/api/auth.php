<?php
require_once __DIR__ . '/../functions.php';
$action = $_GET['action'] ?? '';
if ($action === 'login' && $_SERVER['REQUEST_METHOD']==='POST') {
  $data = json_decode(file_get_contents('php://input'), true);
  $username = $data['username'] ?? '';
  $password = $data['password'] ?? '';
  $stmt = $mysqli->prepare('SELECT id, password_hash FROM users WHERE username = ?');
  $stmt->bind_param('s', $username);
  $stmt->execute();
  $row = $stmt->get_result()->fetch_assoc();
  $stmt->close();
  if (!$row || !password_verify($password, $row['password_hash'])) json_resp(['error'=>'invalid_credentials'],401);
  session_regenerate_id(true);
  $_SESSION['user_id'] = $row['id'];
  json_resp(['ok'=>true]);
}
if ($action === 'me' && $_SERVER['REQUEST_METHOD']==='GET') {
  if (empty($_SESSION['user_id'])) json_resp(['user'=>null]);
  $u = get_user_by_id($_SESSION['user_id']);
  json_resp(['user'=>$u]);
}
if ($action === 'logout' && $_SERVER['REQUEST_METHOD']==='POST') {
  session_unset(); session_destroy(); json_resp(['ok'=>true]);
}
json_resp(['error'=>'bad_request'],400);
