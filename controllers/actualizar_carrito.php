<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['id']) && isset($data['cambio'])) {
        $id = $data['id'];
        $cambio = $data['cambio'];

        // Verificar si el producto está en el carrito
        if (isset($_SESSION['carrito'][$id])) {
            // Actualizar la cantidad
            $_SESSION['carrito'][$id] += $cambio;

            // Asegurarse de que la cantidad no sea menor que 1
            if ($_SESSION['carrito'][$id] < 1) {
                $_SESSION['carrito'][$id] = 1; // Mantener al menos 1
            }

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Producto no encontrado en el carrito.']);
        }
    } elseif (isset($data['id']) && isset($data['accion']) && $data['accion'] === 'eliminar') {
        $id = $data['id'];
        if (isset($_SESSION['carrito'][$id])) {
            unset($_SESSION['carrito'][$id]);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Producto no encontrado en el carrito.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
?> 