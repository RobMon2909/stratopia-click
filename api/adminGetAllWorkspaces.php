<?php
require 'db.php';
require 'vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

// Lógica de token y permisos de Admin (la misma que en getUsers.php)
$authHeader = null; if (isset($_SERVER['Authorization'])) { $authHeader = $_SERVER['Authorization']; } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { $authHeader = $_SERVER['HTTP_AUTHORIZATION']; } if (!$authHeader) { http_response_code(401); die(json_encode(["message"=>"Encabezado no encontrado."]));} $arr = explode(" ", $authHeader); $jwt = $arr[1] ?? '';
try {
    $decoded = JWT::decode($jwt, new Key("UNA_CLAVE_SECRETA_PARA_STRATOPIA", 'HS256'));
    $admin_id = $decoded->data->id;
    $stmt_role = $conn->prepare("SELECT role FROM users WHERE id = ?"); $stmt_role->bind_param("s", $admin_id); $stmt_role->execute(); $result_role = $stmt_role->get_result()->fetch_assoc();
    if (!$result_role || $result_role['role'] !== 'ADMIN') { http_response_code(403); die(json_encode(["message" => "Acceso denegado. Solo para administradores."]));}
} catch (Exception $e) { http_response_code(401); die(json_encode(["message" => "Token inválido."]));}

// Si es admin, obtenemos todos los workspaces
$result = $conn->query("SELECT id, name FROM workspaces ORDER BY createdAt DESC");
$workspaces = $result->fetch_all(MYSQLI_ASSOC);

http_response_code(200);
echo json_encode($workspaces);

$conn->close();
?>