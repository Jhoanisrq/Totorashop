<?php
session_start();
require_once "../include/db_connect.php";

$cat        = $_POST['categoria'];
$nombre     = $_POST['nombre'];
$desc       = $_POST['descripcion'];
$perecible  = $_POST['perecible'];
$fecha_v    = $_POST['fecha_v'] ?: null;
$garantia   = $_POST['garantia'] ?: null;

/*=============================
  MANEJO DE IMAGEN
==============================*/
$folder = "../assets/img/productos/";
if (!file_exists($folder)) {
    mkdir($folder, 0777, true);
}

$imagen = null;
if (!empty($_FILES['imagen']['name'])) {
    $img_name = time() . "_" . basename($_FILES['imagen']['name']);
    $ruta_guardar = $folder . $img_name;

    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_guardar)) {
        $imagen = "assets/img/productos/" . $img_name; // ruta para la BD
    }
}

/*=============================
  INSERT BD
==============================*/
$stmt = $conn->prepare("
    INSERT INTO producto(nombre, descripcion, imagen, id_catgria, perecible, fch_vncmnto, grntia_meses, fecha_rgstro)
    VALUES(?,?,?,?,?,?,?, NOW())
");
$stmt->bind_param("sssiiis", $nombre, $desc, $imagen, $cat, $perecible, $fecha_v, $garantia);
$stmt->execute();

$id = $stmt->insert_id;

echo json_encode([
    "success" => true,
    "id" => $id,
    "nombre" => $nombre
]);
