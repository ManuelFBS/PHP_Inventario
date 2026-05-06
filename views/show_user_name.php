<h2 class="subtitle">
        Bienvenido(a)
        <?php
        $nombre = $_SESSION['nombre'] ?? '';
        $apellido = $_SESSION['apellido'] ?? '';
        $usuario = $_SESSION['usuario'] ?? 'usuario';
        $fullName = trim($nombre . ' ' . $apellido);
        echo htmlspecialchars($fullName !== '' ? $fullName : $usuario, ENT_QUOTES, 'UTF-8');
        echo ' !';
        ?>
</h2>