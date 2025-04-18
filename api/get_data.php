<?php
namespace Api;

header('Content-Type: application/json');

// Если данных нет, возвращаем пустой список
$data = [];

echo json_encode($data);
?>
