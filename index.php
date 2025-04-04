<?php
session_start();

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
    <title>Inicio - Sistema de Tickets</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilologin.css">
    <style>
        .features {
            text-align: left;
            margin: 20px 0;
        }
        .features ul {
            padding-left: 20px;
        }
        .action-buttons {
            margin-top: 30px;
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
            <div class="login-box bg-white p-4 rounded shadow-sm" style="max-width: 600px;">
                <h1 class="text-center text-primary mb-4">Sistema de Gestión de Tickets</h1>
                
                <div class="welcome-message">
                    <p class="text-center">Bienvenido al sistema de tickets de soporte técnico. Gestiona y realiza seguimiento de todos tus problemas técnicos en un solo lugar.</p>
                    
                    <div class="features">
                        <h3>Características principales:</h3>
                        <ul>
                            <li>Creación y seguimiento de tickets</li>
                            <li>Notificaciones en tiempo real</li>
                            <li>Soporte para múltiples categorías</li>
                            <li>Sistema de prioridades</li>
                            <li>Gestión de usuarios (para administradores)</li>
                        </ul>
                    </div>
                    
                    <div class="action-buttons d-flex justify-content-center gap-3">
                        <a href="login.php" class="btn btn-primary" style="padding: 10px 20px;">Iniciar Sesión</a>
                        <a href="register.php" class="btn btn-primary" style="padding: 10px 20px; background-color: #3498db;">Registrarse</a>
                    </div>
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