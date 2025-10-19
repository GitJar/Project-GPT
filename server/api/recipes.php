<?php
require_once __DIR__ . '/../functions.php';
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'GET') {
  if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $mysqli->prepare('SELECT * FROM recipes WHERE id=?');
    $stmt->bind_param('i',$id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    json_resp(['recipe'=>$row]);
  }
  $page = max(1,intval($_GET['page'] ?? 1)); $limit = intval($_GET['limit'] ?? 12);
  $q = trim($_GET['q'] ?? ''); $region = $_GET['region'] ?? '';
  $offset = ($page-1)*$limit;
  $params = [];$where = ' WHERE 1=1 ';
  if ($region!=='') { $where .= ' AND region = ?'; $params[] = $region; }
  if ($q!=='') { $where .= ' AND (title LIKE ? OR ingredients LIKE ? OR steps LIKE ?)'; $params[]='%'.$q.'%'; $params[]='%'.$q.'%'; $params[]='%'.$q.'%'; }
  $types = str_repeat('s', count($params));
  $count_sql = "SELECT COUNT(*) AS c FROM recipes {$where}";
  $stmt = $mysqli->prepare($count_sql);
  if ($params) $stmt->bind_param($types, ...$params);
  $stmt->execute(); $c = $stmt->get_result()->fetch_assoc()['c'] ?? 0; $stmt->close();
  $sql = "SELECT id, title, region, excerpt, image_local, image_url, slug, featured FROM recipes {$where} ORDER BY featured DESC, created_at DESC LIMIT ? OFFSET ?";
  $stmt = $mysqli->prepare($sql);
  if ($params) {
    $bind_types = $types . 'ii';
    $stmt->bind_param($bind_types, ...array_merge($params, [$limit, $offset]));
  } else {
    $stmt->bind_param('ii', $limit, $offset);
  }
  $stmt->execute(); $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC); $stmt->close();
  json_resp(['total'=>intval($c),'items'=>$rows]);
}
if ($method === 'POST') {
  require_role(['admin','editor']);
  if (!empty($_FILES['image'])) {
    $url = handle_image_upload_api('image');
  } else {
    $body = json_decode(file_get_contents('php://input'), true);
    $url = $body['image_local'] ?? null;
  }
  $title = $_POST['title'] ?? $body['title'] ?? '';
  $region = $_POST['region'] ?? $body['region'] ?? '';
  $ingredients = $_POST['ingredients'] ?? $body['ingredients'] ?? '';
  $steps = $_POST['steps'] ?? $body['steps'] ?? '';
  $excerpt = $_POST['excerpt'] ?? $body['excerpt'] ?? '';
  $featured = isset($_POST['featured']) ? 1 : ($body['featured'] ?? 0);
  $slug = slugify($title);
  $stmt = $mysqli->prepare('INSERT INTO recipes (title, region, ingredients, steps, excerpt, featured, slug, image_local) VALUES (?,?,?,?,?,?,?,?)');
  $stmt->bind_param('sssssiis', $title, $region, $ingredients, $steps, $excerpt, $featured, $slug, $url);
  $stmt->execute();
  json_resp(['ok'=>true,'id'=>$stmt->insert_id]);
}
if ($method === 'PUT') {
  require_role(['admin','editor']);
  parse_str(file_get_contents('php://input'), $put);
  $id = intval($put['id'] ?? 0);
  if (!$id) json_resp(['error'=>'invalid'],400);
  $title = $put['title'] ?? '';
  $region = $put['region'] ?? '';
  $ingredients = $put['ingredients'] ?? '';
  $steps = $put['steps'] ?? '';
  $excerpt = $put['excerpt'] ?? '';
  $featured = intval($put['featured'] ?? 0);
  $slug = slugify($title);
  $stmt = $mysqli->prepare('UPDATE recipes SET title=?, region=?, ingredients=?, steps=?, excerpt=?, featured=?, slug=? WHERE id=?');
  $stmt->bind_param('sssssisi', $title, $region, $ingredients, $steps, $excerpt, $featured, $slug, $id);
  $stmt->execute(); json_resp(['ok'=>true]);
}
if ($method === 'DELETE') {
  require_role(['admin']);
  parse_str(file_get_contents('php://input'), $del);
  $id = intval($del['id'] ?? 0);
  if (!$id) json_resp(['error'=>'invalid'],400);
  $stmt = $mysqli->prepare('DELETE FROM recipes WHERE id=?'); $stmt->bind_param('i',$id); $stmt->execute(); json_resp(['ok'=>true]);
}
json_resp(['error'=>'bad_request'],400);
