<?php
require_once __DIR__ . '/../functions.php';
$action = $_GET['action'] ?? '';
if ($action === 'upload' && $_SERVER['REQUEST_METHOD']==='POST') {
  require_role(['admin','editor']);
  $url = handle_image_upload_api('file');
  if (!$url) json_resp(['error'=>'upload_failed'],500);
  json_resp(['ok'=>true,'url'=>$url]);
}
if ($action === 'list' && $_SERVER['REQUEST_METHOD']==='GET') {
  require_role(['admin','editor']);
  $dir = __DIR__ . '/../uploads';
  $files = [];
  if (is_dir($dir)) {
    foreach (scandir($dir) as $f) {
      if (in_array($f,['.','..'])) continue;
      $files[] = '/uploads/' . $f;
    }
  }
  json_resp(['files'=>$files]);
}
if ($action === 'delete' && $_SERVER['REQUEST_METHOD']==='POST') {
  require_role(['admin','editor']);
  $data = json_decode(file_get_contents('php://input'), true);
  $path = $data['path'] ?? '';
  if (!$path) json_resp(['error'=>'invalid'],400);
  $file = realpath(__DIR__ . '/../' . ltrim($path, '/'));
  if (!$file || strpos($file, realpath(__DIR__ . '/../uploads')) !== 0) json_resp(['error'=>'forbidden'],403);
  if (file_exists($file)) unlink($file);
  json_resp(['ok'=>true]);
}
json_resp(['error'=>'bad_request'],400);
