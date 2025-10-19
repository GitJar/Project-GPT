<?php
require_once __DIR__ . '/config.php';

function json_resp($data, $code = 200) {
  http_response_code($code);
  header('Content-Type: application/json');
  echo json_encode($data);
  exit;
}

function get_user_by_id($id) {
  global $mysqli;
  $stmt = $mysqli->prepare('SELECT id, username, display_name, email, role FROM users WHERE id = ?');
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $u = $stmt->get_result()->fetch_assoc();
  $stmt->close();
  return $u;
}

function require_login_api() {
  if (empty($_SESSION['user_id'])) json_resp(['error'=>'not_authenticated'], 401);
}

function require_role($roles = []) {
  require_login_api();
  $user = get_user_by_id($_SESSION['user_id']);
  if (!$user || !in_array($user['role'], (array)$roles)) json_resp(['error'=>'forbidden'], 403);
}

function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    if (empty($text)) return bin2hex(random_bytes(6));
    return $text;
}

function handle_image_upload_api($field='file') {
  if (!isset($_FILES[$field]) || $_FILES[$field]['error']===UPLOAD_ERR_NO_FILE) return null;
  $file = $_FILES[$field];
  if ($file['error']!==UPLOAD_ERR_OK) return null;
  if ($file['size'] > 6*1024*1024) return null;
  $finfo = new finfo(FILEINFO_MIME_TYPE);
  $mime = $finfo->file($file['tmp_name']);
  $allowed = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
  if (!isset($allowed[$mime])) return null;
  $ext = $allowed[$mime];
  $dir = __DIR__ . '/uploads';
  if (!is_dir($dir)) mkdir($dir,0755,true);
  $name = bin2hex(random_bytes(10)) . '.' . $ext;
  $dest = $dir . '/' . $name;
  if (!move_uploaded_file($file['tmp_name'], $dest)) return null;
  return '/uploads/' . $name;
}
