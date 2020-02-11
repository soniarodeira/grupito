<?php session_start(); ?>

<?php
	$pagina = "carrito";
	$titulo = "Tu compra";
	include_once("inc/encabezado.php");
	include_once("bbdd/bbdd.php");
	include_once("inc/funciones.php");
?>

<main role="main">
	<div class="jumbotron">
		<div class="container">
		  <h1 class="display-3">Tu carrito de la compra</h1>
		  <p><a class="btn btn-primary btn-lg" href="productos.php" role="button">Seguir comprando »</a></p>
		</div>
	</div>
	
	<?php
	if(empty($_SESSION['carrito'])){
		$mensaje = "Carrito vacío";
		mostrarMensaje($mensaje);
	}else{
	?>	
	
	<div class="container">
	
	<div class="row px-5">
		
		<table class="table table-bordered">
		<thead>
		<tr>
		  <th scope="col">Producto</th>
		  <th scope="col">Cantidad</th>
		  <th scope="col">Precio</th>
		  <th scope="col">Subtotal</th>
		</tr>
		</thead>
		<tbody>

<?php
	$total=0;
	foreach($_SESSION['carrito'] as $id => $cantidad){
		$producto = seleccionarProducto($id);
		
		$nombre = $producto['nombre'];
		$precio = $producto['precioOferta'];
		$subtotal = $precio*$cantidad;
		$total=$total+$subtotal;
	?>
		<tr>
		  <td scope="col"><?php echo $nombre; ?></td>
		  <td class="text-center">
			<a href="procesarCarrito.php?id=<?php echo "$id"; ?>&op=remove">
				<i class="fas fa-minus-circle"></i>
			</a> <?php echo "$cantidad"; ?> 
			<a href="procesarCarrito.php?id=<?php echo "$id"; ?>&op=add">
				<i class="fas fa-plus-circle"></i>
			</a>
		  </td>
		  <td scope="col"><?php echo $precio; ?></td>
		  <td scope="col"><?php echo $subtotal; ?></td>
		</tr>
	<?php
	}
	?>
	</tbody>
	<tfoot>
		<tr>
			<th scope="row" colspan="3" class="text-right">Total</th>
			<td><?php echo $total ?></td>
		</tr>
	</tfoot>
</table>	

	<a href="procesarCarrito.php?id=<?php echo "$id"; ?>&op=empty" class="btn btn-danger">Vaciar carrito</a>
	
	<a href="confirmarPedido.php" class="btn btn-success ml-3">Confirmar pedido</a>


</div>
	
<?php
	$_SESSION['total']=$total;
}//Lave Else
?>	



	</main>
<?php 
	include_once("inc/pie.php");
?>