<?php
require 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $room_type = $_POST['room_type'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $breakfast = isset($_POST['breakfast']) ? 1 : 0;
    $parking = isset($_POST['parking']) ? 1 : 0;

    $sql = "SELECT id_quarto, preco FROM quartos WHERE tipo_quarto = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $room_type);
    $stmt->execute();
    $result = $stmt->get_result();
    $room = $result->fetch_assoc();

    if ($room) {
        $room_id = $room['id_quarto'];
        $price_per_night = $room['preco'];
        $num_nights = (strtotime($check_out) - strtotime($check_in)) / (60 * 60 * 24);
        $total_price = $num_nights * $price_per_night;

        if ($breakfast) {
            $total_price += $num_nights * 20;
        }

        if ($parking) {
            $total_price += $num_nights * 10; 
        }

        $sql_insert = "INSERT INTO reservas (nome_cliente, email_cliente, id_quarto, check_in, check_out, cafe_manha, estacionamento, preco_total) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("ssisssid", $name, $email, $room_id, $check_in, $check_out, $breakfast, $parking, $total_price);

        if ($stmt_insert->execute()) {
            echo "Reserva realizada com sucesso!";
        } else {
            echo "Erro ao realizar reserva: " . $stmt_insert->error;
        }
    } else {
        echo "Tipo de quarto não encontrado.";
    }
    $stmt->close();
    $stmt_insert->close();
    $conn->close();
}