<?php
session_start();

// Configuración de la base de datos
$host = 'localhost';
$dbname = 'ticket_system';
$user = 'root';
$pass = '';

// Conexión a la base de datos
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Procesar el formulario de registro
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Validar que los campos no estén vacíos
    if (empty($username) || empty($password) || empty($email)) {
        $error_message = "Todos los campos son obligatorios.";
    } else {
        // Hash de la contraseña
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insertar el nuevo usuario en la base de datos
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (:username, :password, :email)");
            $stmt->execute([
                'username' => $username,
                'password' => $hashed_password,
                'email' => $email
            ]);
            $success_message = "Registro exitoso. Ahora puedes iniciar sesión.";
        } catch (PDOException $e) {
            $error_message = "Error al registrar el usuario: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Sistema de Tickets</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilologin.css"> <!-- Reutilizamos el mismo CSS -->
    <style>
        :root {
            --bs-primary: #3498db;
            --bs-success: #2ecc71;
            --bs-danger: #e74c3c;
        }
        /* Ajustes específicos para registro */
        .register-box {
            max-width: 500px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container">
        <header class="d-flex justify-content-between align-items-center py-3 border-bottom border-primary">
            <div class="logo">
                <img src="https://camaradesevilla.com/wp-content/uploads/2024/07/S00-logo-Grupo-Solutia-v01-1.png" alt="Logo del Sistema" class="img-fluid" style="max-width: 150px;">
            </div>
            <div class="theme-toggle">
                <button id="theme-button" class="btn btn-primary">Modo Oscuro</button>
            </div>
        </header>

        <main class="main-content d-flex justify-content-center align-items-center min-vh-80">
            <div class="login-box register-box bg-white p-4 rounded shadow-sm text-center w-100">
                <h1 class="text-primary mb-4">Registro de Usuario</h1>
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
                <?php endif; ?>
                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
                <?php endif; ?>
                <form class="login-form" action="register.php" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Usuario:</label>
                        <input type="text" id="username" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña:</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico:</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Registrarse</button>
                </form>
                <div class="mt-3">
                    <a href="login.php" class="text-primary text-decoration-none">¿Ya tienes cuenta? Inicia sesión</a>
                </div>
            </div>
        </main>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Script para cambiar entre modo oscuro y modo claro
        const themeButton = document.getElementById('theme-button');
        const body = document.body;

        themeButton.addEventListener('click', () => {
            body.classList.toggle('dark-mode');
            if (body.classList.contains('dark-mode')) {
                themeButton.textContent = 'Modo Claro';
                themeButton.classList.remove('btn-primary');
                themeButton.classList.add('btn-warning');
            } else {
                themeButton.textContent = 'Modo Oscuro';
                themeButton.classList.remove('btn-warning');
                themeButton.classList.add('btn-primary');
            }
        });

        // Cargar preferencia de tema al inicio si existe
        if (localStorage.getItem('darkMode') === 'true') {
            body.classList.add('dark-mode');
            themeButton.textContent = 'Modo Claro';
            themeButton.classList.remove('btn-primary');
            themeButton.classList.add('btn-warning');
        }
    </script>
</body>
</html>