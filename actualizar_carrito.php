<?php
session_start();

// Recibir datos
$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? null;
$accion = $data['accion'] ?? null;

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

if ($id && $accion) {
    switch ($accion) {
        case 'sumar':
            if (isset($_SESSION['carrito'][$id])) {
                $_SESSION['carrito'][$id]++;
            } else {
                $_SESSION['carrito'][$id] = 1;
            }
            break;
            
        case 'restar':
            if (isset($_SESSION['carrito'][$id])) {
                if ($_SESSION['carrito'][$id] > 1) {
                    $_SESSION['carrito'][$id]--;
                } else {
                    unset($_SESSION['carrito'][$id]);
                }
            }
            break;
            
        case 'eliminar':
            if (isset($_SESSION['carrito'][$id])) {
                unset($_SESSION['carrito'][$id]);
            }
            break;
    }
    
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
}
?> 