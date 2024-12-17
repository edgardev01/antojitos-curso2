<?php
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'create':
            createPedido($conn);
            break;
        case 'read':
            readPedidos($conn);
            break;
        case 'update':
            updatePedido($conn);
            break;
        case 'delete':
            deletePedido($conn);
            break;
        default:
            echo json_encode(["error" => "Acción no válida"]);
            break;
    }
}

function createPedido($conn) {
    $nombre_producto = $_POST['nombre_producto'];
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['precio'];
    $categoria = $_POST['categoria'];
    $id_cliente = $_POST['id_cliente'];

    $stmt = $conn->prepare("INSERT INTO pedidos (nombre_producto, cantidad, precio, categoria, id_cliente) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sidsi", $nombre_producto, $cantidad, $precio, $categoria, $id_cliente);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Pedido creado exitosamente"]);
    } else {
        echo json_encode(["error" => "Error al crear el pedido"]);
    }
}

function readPedidos($conn) {
    $result = $conn->query("SELECT * FROM pedidos");
    $pedidos = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode(["success" => true, "pedidos" => $pedidos]);
}

function updatePedido($conn) {
    $id_pedido = $_POST['id_pedido'];
    $nombre_producto = $_POST['nombre_producto'];
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['precio'];
    $categoria = $_POST['categoria'];

    $stmt = $conn->prepare("UPDATE pedidos SET nombre_producto=?, cantidad=?, precio=?, categoria=? WHERE id_pedido=?");
    $stmt->bind_param("sidss", $nombre_producto, $cantidad, $precio, $categoria, $id_pedido);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Pedido actualizado exitosamente"]);
    } else {
        echo json_encode(["error" => "Error al actualizar el pedido"]);
    }
}

function deletePedido($conn) {
    $id_pedido = $_POST['id_pedido'];

    $stmt = $conn->prepare("DELETE FROM pedidos WHERE id_pedido = ?");
    $stmt->bind_param("i", $id_pedido);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Pedido eliminado exitosamente"]);
    } else {
        echo json_encode(["error" => "Error al eliminar el pedido"]);
    }
}
?>
