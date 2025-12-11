<?php
session_start();
if (!isset($_SESSION['id_empleado'])) {
    header("Location: login_empleado.php?error=Debes iniciar sesión.");
    exit();
}

require_once("../include/db_connect.php");

// 3. Incluir la clase SimpleXLSXGen
// Ajusta la ruta según dónde guardaste SimpleXLSXGen.php
require_once __DIR__ . "/../simplexlsxgen/SimpleXLSXGen.php";

use Shuchkin\SimpleXLSXGen;

// 4. Definir tablas y nombres de hojas (aquí puedes renombrar como quieras)
$tablas = [
    'almacen'           => 'Almacenes',
    'categoria'         => 'Categorias',
    'cliente'           => 'Clientes',
    'cliente_direccion' => 'Clientes_Direcciones',
    'detalle_compra'    => 'Detalle_Compra',
    'detalle_pedido'    => 'Detalle_Pedido',
    'direccion'         => 'Direcciones',
    'empleado'          => 'Empleados',
    'entrada'           => 'Entradas',
    'inventario'        => 'Inventario',
    'orden_compra'      => 'Ordenes_Compra',
    'pedido'            => 'Pedidos',
    'producto'          => 'Productos',
    'proveedor'         => 'Proveedores',
    'salida'            => 'Salidas',
    'tipo_cargo'        => 'Tipos_Cargo',
    'ajuste'            => 'Ajustes'
];

// 5. Armar los datos de cada hoja
$sheets = [];

foreach ($tablas as $tabla => $nombreHoja) {

    $rows = [];

    // Consulta todos los datos de la tabla
    $sql = "SELECT * FROM `$tabla`";
    $resultado = $conn->query($sql);

    if ($resultado === false) {
        // Si hubo error en la consulta, dejamos un mensaje en la hoja
        $rows[] = ['Error al consultar la tabla ' . $tabla];
    } else {

        if ($resultado->num_rows > 0) {
            // Tomamos una fila para obtener nombres de columnas
            $filaEjemplo = $resultado->fetch_assoc();
            $columnas = array_keys($filaEjemplo);

            // Encabezados
            $rows[] = $columnas;

            // Volver al inicio del resultado
            $resultado->data_seek(0);

            // Filas de datos
            while ($fila = $resultado->fetch_assoc()) {
                $filaData = [];
                foreach ($columnas as $col) {
                    $filaData[] = $fila[$col];
                }
                $rows[] = $filaData;
            }
        } else {
            // Tabla sin registros: al menos mostramos las columnas
            $sqlCols = "SHOW COLUMNS FROM `$tabla`";
            $resCols = $conn->query($sqlCols);

            if ($resCols && $resCols->num_rows > 0) {
                $cols = [];
                while ($col = $resCols->fetch_assoc()) {
                    $cols[] = $col['Field'];
                }
                $rows[] = $cols;
                $rows[] = ['(Sin registros en esta tabla)'];
            } else {
                $rows[] = ['Tabla sin columnas visibles o error al obtener columnas'];
            }
        }
    }

    // Aseguramos nombre de hoja válido (máx 31 caracteres, sin caracteres raros)
    $nombreSeguro = preg_replace('/[:\\\\\\/\\?\\*\\[\\]]/', ' ', $nombreHoja);
    $nombreSeguro = mb_substr($nombreSeguro, 0, 31);

    $sheets[] = [
        'nombre' => $nombreSeguro,
        'data'   => $rows
    ];
}

// 6. Crear el Excel con varias hojas
$xlsx = new SimpleXLSXGen();

// Agregamos cada hoja al libro
foreach ($sheets as $sheet) {
    $xlsx->addSheet($sheet['data'], $sheet['nombre']);
}

// 7. Descargar el archivo
$nombreArchivo = 'totorashop_' . date('Ymd_His') . '.xlsx';

// SimpleXLSXGen ya se encarga de las cabeceras HTTP
$xlsx->downloadAs($nombreArchivo);
exit;
