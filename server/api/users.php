<?php
require_once __DIR__ . '/../functions.php';
require_role(['admin']);
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'GET') {
  $res = $mysqli->query('SELECT id, username, display_name, email, role, created_at FROM users ORDER BY created_at DESC');
  $rows = $res->fetch_all(MYSQLI_ASSOC);
  json_resp(['users'=>$rows]);
}
if ($method === 'POST') {
  $data = json_decode(file_get_contents('php://input'), true);
  $username = trim($data['username'] ?? '');
  $password = $data['password'] ?? '';
  $display = $data['display_name'] ?? '';
  $email = $data['email'] ?? '';
  $role = in_array($data['role'] ?? '', ['admin','editor']) ? $data['role'] : 'editor';
  if (!$username || !$password) json_resp(['error'=>'invalid'],400);
  $hash = password_hash($password, PASSWORD_DEFAULT);
  $stmt = $mysqli->prepare('INSERT INTO users (username, password_hash, display_name, email, role) VALUES (?,?,?,?,?)');
  $stmt->bind_param('sssss', $username, $hash, $display, $email, $role);
  if (!$stmt->execute()) json_resp(['error'=>'db'],500);
  json_resp(['ok'=>true,'id'=>$stmt->insert_id]);
}
if ($method === 'PUT') {
  parse_str(file_get_contents('php://input'), $put);
  $id = intval($put['id'] ?? 0);
  if (!$id) json_resp(['error'=>'invalid'],400);
  $display = $put['display_name'] ?? '';
  $email = $put['email'] ?? '';
  $role = in_array($put['role'] ?? '', ['admin','editor']) ? $put['role'] : 'editor';
  $stmt = $mysqli->prepare('UPDATE users SET display_name=?, email=?, role=? WHERE id=?');
  $stmt->bind_param('sssi', $display, $email, $role, $id);
  $stmt->execute();
  json_resp(['ok'=>true]);
}
if ($method === 'DELETE') {
  parse_str(file_get_contents('php://input'), $del);
  $id = intval($del['id'] ?? 0);
  if (!$id) json_resp(['error'=>'invalid'],400);
  $stmt = $mysqli->prepare('DELETE FROM users WHERE id = ?');
  $stmt->bind_param('i', $id);
  $stmt->execute();
  json_resp(['ok'=>true]);
}
json_resp(['error'=>'bad_request'],400);
