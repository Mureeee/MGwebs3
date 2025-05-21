<?php

require_once __DIR__ . '/../models/UserModel.php';

class RegisterController {
    private $userModel;

    public function __construct() {
        // Asegúrate de que la sesión ya esté iniciada en index.php o al principio
        // session_start() debe llamarse antes de usar $_SESSION
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->userModel = new UserModel();
    }

    public function index() {
        // Esto cargará la vista del formulario de registro
        // Pasamos las variables necesarias para el navbar/header si es preciso
        $isLoggedIn = isset($_SESSION['usuario_id']);
        $primeraLetra = $isLoggedIn ? strtoupper(substr($_SESSION['usuario_nombre'], 0, 1)) : '';
        
        // Puedes inicializar variables de mensaje aquí si es necesario, aunque el JS las maneja por ahora
        $message = '';
        $messageClass = '';

        require __DIR__ . '/../views/register.php';
    }

    public function process() {
        error_log("Inicio de RegisterController::process()"); // Log al inicio
        ob_start(); // Iniciar el buffer de salida

        // Este método maneja la lógica de procesamiento del formulario POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Sanitizar y obtener datos del POST
                // FILTER_SANITIZE_STRING está obsoleto, leer directamente y sanear al mostrar si es necesario
                $nombre = $_POST['nombre'];
                $correo = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                $contraseña = $_POST['password']; // La contraseña se hashea después
                // FILTER_SANITIZE_STRING está obsoleto, leer directamente y sanear al mostrar si es necesario
                $direccion_envio = $_POST['direccion'];
                
                // Validaciones básicas
                if (empty($nombre) || empty($correo) || empty($contraseña)) {
                     ob_clean(); // Limpiar el buffer
                     error_log("Validación fallida: campos obligatorios vacíos."); // Log de validación fallida
                     echo json_encode(['success' => false, 'message' => 'Nombre, correo y contraseña son obligatorios.']);
                     error_log("Respuesta JSON de validación fallida enviada."); // Nuevo log
                     exit;
                }

                // Usar el Modelo para interactuar con la DB
                // $userModel = new UserModel(); // Instanciar dentro del método si no se hizo en el constructor
                
                // Verificar si el correo ya existe
                if ($this->userModel->getUserByCorreo($correo)) {
                    ob_clean(); // Limpiar el buffer
                    error_log("Registro fallido: correo ya registrado."); // Log de correo existente
                    echo json_encode(['success' => false, 'message' => 'El correo ya está registrado.']);
                     error_log("Respuesta JSON de correo existente enviada."); // Nuevo log
                    exit;
                }
                
                // Hash de la contraseña
                $contraseñaHash = password_hash($contraseña, PASSWORD_DEFAULT);
                
                // Insertar nuevo usuario
                $userId = $this->userModel->createUser($nombre, $correo, $contraseñaHash, $direccion_envio);
                
                if ($userId) {
                    // *** Lógica para iniciar sesión automáticamente ***
                    $_SESSION['usuario_id'] = $userId;
                    $_SESSION['usuario_nombre'] = $nombre;
                    $_SESSION['usuario_correo'] = $correo; // Usar 'correo' como en la base de datos y modelo
                    $_SESSION['usuario_rol'] = 'cliente'; // Asignar rol por defecto al registrarse
                    
                    // Respuesta de éxito para el JavaScript
                    ob_clean(); // Limpiar el buffer
                    error_log("Registro exitoso. Enviando respuesta JSON de éxito."); // Log de éxito
                    echo json_encode(['success' => true, 'message' => 'Registro exitoso!']);
                    error_log("Respuesta JSON de éxito enviada."); // Nuevo log
                    exit;
                } else {
                    // Error al insertar en la base de datos
                    error_log("Error al crear usuario en la base de datos."); // Registrar el error en logs del servidor
                    ob_clean(); // Limpiar el buffer
                    error_log("Registro fallido: error al crear usuario en DB."); // Log de error DB
                    echo json_encode(['success' => false, 'message' => 'Error al registrar el usuario. Por favor, intente de nuevo.']);
                    error_log("Respuesta JSON de error DB enviada."); // Nuevo log
                }
                
            } catch (Exception $e) {
                // Capturar cualquier excepción no manejada y devolver un error JSON
                ob_clean(); // Limpiar el buffer
                error_log("Excepción en RegisterController::process(): " . $e->getMessage()); // Log del error de excepción
                echo json_encode(['success' => false, 'message' => 'Error interno del servidor al procesar el registro.']);
                 error_log("Respuesta JSON de excepción enviada."); // Nuevo log
            }
            
        } else {
            // Si no es una solicitud POST
            header("HTTP/1.0 405 Method Not Allowed");
            error_log("Método no permitido en /registrarse/process."); // Log de método no permitido
            echo "Método no permitido.";
        }

        ob_end_clean(); // Finalizar y limpiar el buffer si no se salió antes
        error_log("Fin de RegisterController::process()"); // Log al final
    }
}

?> 