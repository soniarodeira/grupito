<?php include "configuracion.php"; ?>
<?php
//Función para conectarnos a la BD
function conectarBD(){
	try{
		$con = new PDO("mysql:host=".HOST.";dbname=".DBNAME.";charset=utf8", USER, PASS);
		
		$con -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
	}catch(PDOException $e){
		echo "Error: Error al conectar la BD: ".$e->getMessage();
		
		file_put_contents("PDOErrors.txt", "\r\n".date('j F, Y, g:i a ').$e->getMessage(), FILE_APPEND);
		exit;
	}
	return $con;
}

//Función para Desconectar BD
function desconectarBD($con){
	$con = NULL;
	return $con;
}

function seleccionarOfertasPortada($numOfertas){
	
	$con = conectarBD();
	
	try{
		$sql = "SELECT * FROM productos LIMIT :numOfertas";
			
		$stmt = $con->prepare($sql);
		
		$stmt->bindParam(':numOfertas', $numOfertas, PDO::PARAM_INT);
		
		$stmt->execute();	
		
		$rows = $stmt -> fetchAll(PDO::FETCH_ASSOC);
		
		
	}catch(PDOException $e){
		echo "Error: Error al seleccionar una tareas: ".$e->getMessage();
		
		file_put_contents("PDOErrors.txt", "\r\n".date('j F, Y, g:i a ').$e->getMessage(), FILE_APPEND);
		exit;
	}
	
	return $rows;
}


/***************************/
function seleccionarTodasOfertas(){
	
	$con = conectarBD();
	
	try{
		$sql = "SELECT * FROM productos";
			
		$stmt = $con->prepare($sql);
		
		$stmt->execute();	
		
		$rows = $stmt -> fetchAll(PDO::FETCH_ASSOC);
		
		
	}catch(PDOException $e){
		echo "Error: Error al seleccionar una tareas: ".$e->getMessage();
		
		file_put_contents("PDOErrors.txt", "\r\n".date('j F, Y, g:i a ').$e->getMessage(), FILE_APPEND);
		exit;
	}
	
	return $rows;
}/* */
?>

<?php 
function seleccionarProducto($idProducto){
	
	$con = conectarBD();
	
	try{
		$sql = "SELECT * FROM productos WHERE idProducto= :idProducto";
			
		$stmt = $con->prepare($sql);
		
		$stmt->bindParam(':idProducto', $idProducto, PDO::PARAM_INT);
		
		$stmt->execute();	
		
		$row = $stmt -> fetch(PDO::FETCH_ASSOC);
		
		
	}catch(PDOException $e){
		echo "Error: Error al seleccionar una oferta: ".$e->getMessage();
		
		file_put_contents("PDOErrors.txt", "\r\n".date('j F, Y, g:i a ').$e->getMessage(), FILE_APPEND);
		exit;
	}
	
	return $row;
}

/* Function InsertarPedido  */
function insertarPedido($idUsuario, $detallePedido, $total){
	$conexion = conectarBD();
	try{
		$conexion -> beginTransaction();
		
		$sql = "INSERT INTO pedidos (idUsuario, total) VALUES (:idUsuario, :total)";
		
		$sentencia = $conexion -> prepare($sql);
		
		$sentencia -> bindparam(":idUsuario", $idUsuario);
		$sentencia -> bindparam(":total", $total);
		
		$sentencia -> execute();
		
		$idPedido = $conexion->lastInsertId();
		
		foreach($detallePedido as $idProducto => $cantidad){
			
			$producto = seleccionarProducto($idProducto);
			$precio = $producto['precioOferta'];
			
			$sql2 = "INSERT INTO detallePedido (idPedido, idProducto, cantidad, precio) VALUES (:idPedido, :idProducto, :cantidad, :precio)";
			$sentencia = $conexion -> prepare($sql2);
		
			$sentencia -> bindparam(":idPedido", $idPedido);
			$sentencia -> bindparam(":idProducto", $idProducto);
			$sentencia -> bindparam(":cantidad", $cantidad);
			$sentencia -> bindparam(":precio", $precio);
			
			$sentencia -> execute();			
		}
		$conexion -> commit();		
		
	}catch(PDOException $e){		
	
		$conexion -> rollback();
		
		echo "Error: Error al insertar un pedido: ".$e->getMessage();
		
		file_put_contents("PDOErrors.txt", "\r\n".date('j F, Y, g:i a ').$e->getMessage(), FILE_APPEND);
		exit;
	}
	
	return $idPedido;
} 























