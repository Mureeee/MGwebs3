<?php
session_start();
header('Content-Type: application/json');

$isAdmin = isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'administrador';
echo json_encode(['isAdmin' => $isAdmin]);
?> 