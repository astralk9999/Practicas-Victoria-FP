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

// Procesar el formulario de login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Buscar el usuario en la base de datos
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        
        // Redirigir según el rol
        switch ($user['role']) {
            case 'admin':
                header('Location: index_admin.php');
                break;
            case 'tech':
                header('Location: index_tech.php');
                break;
            default:
                header('Location: index_usuario.php');
        }
        exit();
    } else {
        $error_message = "Usuario o contraseña incorrectos";
    }
}

// Redirigir si ya está logueado
if (isset($_SESSION['user_id'])) {
    switch ($_SESSION['role']) {
        case 'admin':
            header('Location: index_admin.php');
            break;
        case 'tech':
            header('Location: index_tech.php');
            break;
        default:
            header('Location: index_usuario.php');
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Tickets</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilologin.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .main-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-box {
            width: 100%;
            max-width: 400px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container">
        <header class="d-flex justify-content-between align-items-center py-3 border-bottom border-primary">
            <div class="logo">
                <img src="https://camaradesevilla.com/wp-content/uploads/2024/07/S00-logo-Grupo-Solutia-v01-1.png" 
                     alt="Logo del Sistema" class="img-fluid" style="max-width: 150px;">
            </div>
            <div class="theme-toggle">
                <button id="theme-button" class="btn" style="background-color: #3498db; color: white; padding: 8px 16px;">
                    Modo Oscuro
                </button>
            </div>
        </header>

        <main class="main-content py-5">
            <div class="login-box bg-white p-4 rounded shadow-sm">
                <h1 class="text-center text-primary mb-4">Sistema de Tickets</h1>
                
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
                <?php endif; ?>
                
                <form method="POST" action="login.php">
                    <div class="mb-3">
                        <label for="username" class="form-label">Usuario:</label>
                        <input type="text" id="username" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña:</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
                </form>
                
                <div class="mt-3 text-center">
                    <a href="register.php" class="text-decoration-none">Registrarse</a>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script para cambiar entre modo oscuro y modo claro
        const themeButton = document.getElementById('theme-button');
        const body = document.body;

        function applyDarkMode(isDark) {
            if (isDark) {
                body.classList.add('dark-mode');
                themeButton.textContent = 'Modo Claro';
                themeButton.style.backgroundColor = '#ff8c42';
            } else {
                body.classList.remove('dark-mode');
                themeButton.textContent = 'Modo Oscuro';
                themeButton.style.backgroundColor = '#3498db';
            }
        }

        // Cargar preferencia al inicio
        if (localStorage.getItem('darkMode') === 'true') {
            applyDarkMode(true);
        }

        themeButton.addEventListener('click', () => {
            const isDark = !body.classList.contains('dark-mode');
            applyDarkMode(isDark);
            localStorage.setItem('darkMode', isDark);
        });
    </script>
</body>
</html>