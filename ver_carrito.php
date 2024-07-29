<?php
session_start();

// Generar un nuevo token CSRF
$_SESSION['token'] = bin2hex(random_bytes(32)); 

include 'conexion.php';

// Manejo de acciones del carrito (eliminar, vaciar)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ... (validar token CSRF)

    if (isset($_POST['eliminar'])) {
        // ... (eliminar producto del carrito)
    } elseif (isset($_POST['vaciar'])) {
        // ... (vaciar carrito)
    }
}

// Calcular el total del carrito
$total = 0;
if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $item) {
        $total += $item['precio'] * $item['cantidad'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Carrito de Compras</h1>

    <?php if (!empty($_SESSION['carrito'])): ?>
        <table>
            <tr>
                <th>Producto</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($_SESSION['carrito'] as $index => $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['nombre']); ?></td>
                    <td>$<?php echo number_format($item['precio'], 0, ',', '.'); ?></td>
                    <td>
                        <form action="actualizar_cantidad.php" method="post">
                            <input type="hidden" name="index" value="<?php echo $index; ?>">
                            <input type="number" name="cantidad" value="<?php echo $item['cantidad']; ?>" min="1">
                            <button type="submit">Actualizar</button>
                        </form>
                    </td>
                    <td>$<?php echo number_format($item['precio'] * $item['cantidad'], 0, ',', '.'); ?></td>
                    <td>
                        <form action="eliminar_del_carrito.php" method="post">
                            <input type="hidden" name="index" value="<?php echo $index; ?>">
                            <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <p>Total: $<?php echo number_format($total, 0, ',', '.'); ?></p>

        <form action="vaciar_carrito.php" method="post">
            <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
            <button type="submit">Vaciar Carrito</button>
        </form>

        <form action="procesar_pago.php" method="post">
            <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
            <button type="submit">Realizar Pago</button>
        </form>

    <?php else: ?>
        <p>El carrito está vacío.</p>
    <?php endif; ?>

    <a href="index.php">Volver a la tienda</a>
</body>
</html>

