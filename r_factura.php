<!doctype html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Factura</title>

	<style type="text/css">
		* {
			font-family: Verdana, Arial, sans-serif;
		}

		table {
			font-size: x-small;
		}

		tfoot tr td {
			font-weight: bold;
			font-size: x-small;
		}

		.gray {
			background-color: lightgray
		}

		.cuadradito {
			vertical-align: bottom;
			display: inline-block;
			height: 30px;
			width: 30px;
			background: #cccccc;
			margin-bottom: 3px;
			text-align: center;
		}

		#cabecera1 {
			font-size: 18px;
			font-weight: bold;
		}

		#cabecera1 {
			font-size: 12px;
			font-weight: bold;
		}

		table,
		th,
		td {
			border: 1px solid black;
			border-collapse: collapse;
		}

		img {
			height: 100px;
		}
	</style>

</head>

<body>
	<table width="100%" class="tabla-borde">
		<thead>
			<tr>
				<td width="50%">
					<p id="cabecera1">${LOGO}</p>
					<p id="cabecera2"> Nombres y Apellidos/Razón Social: <b>GUILLERMO CARDOZO</b><br>
						R.U.C: <b>1234567-9</b><br>
						Dirección: <b>CHILE Y JEJUI</b><br>
						Telefóno: <b>0991433055</b><br></p>
				</td>
				<td width="50%">
					<p id="cabecera1">TIMBRADO: ${TIMBRADO} <br> FECHA VENCIMIENTO: ${FECHA_VENCIMIENTO} <br> R.U.C.: ${RUC} <br> FACTURA NRO.: ${NRO_FACTURA}</p>
				</td>
			</tr>
		</thead>
	</table>

	<table width="100%">
		<tr>
			<td width="50%">Fecha de Emisión: <b>${FECHA_EMISION}</b></td>
			<td align="right" width="50%">Condición de Venta: Contado: <div class="cuadradito">X</div> Crédito: <div class="cuadradito"></div>
			</td>
		</tr>
		<tr>
			<td width="50%">Nombres y Apellidos/Razón Social: <b>${CLIENTE}</b></td>
			<td width="50%">R.U.C.: <b>${CLIENTE_RUC}</b></td>
		</tr>

	</table>
	<table width="100%">
		<thead style="background-color: lightgray;">
			<tr>
				<th>#</th>
				<th>Descripción</th>
				<th>Cantidad</th>
				<th>Precio Unitario Gs.</th>
				<th>Total Gs.</th>
			</tr>
		</thead>
		<tbody>
			${PRODUCTOS}
		</tbody>

		<tfoot>
			<tr>
				<td colspan="3" class="gray"></td>
				<td align="right" class="gray">Subtotal Gs.</td>
				<td align="right" class="gray">${SUBTOTAL}</td>
			</tr>
			<!-- <tr>
				<td colspan="3"></td>
				<td align="right">IVA Gs.</td>
				<td align="right">294.3</td>
			</tr> -->
			<tr>
				<td colspan="3" class="gray"></td>
				<td align="right" class="gray">Total Gs.</td>
				<td align="right" class="gray">${TOTAL}</td>
			</tr>
		</tfoot>
	</table>

</body>

</html>