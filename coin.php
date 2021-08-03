<?php
$url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest';
$parameters = [
  'symbol' => 'BTC,ETH,USDT,BNB,ADA,XRP,USDC,DOGE,DOT,UNI,BUSD,BCH'
];

$headers = [
  'Accepts: application/json',
  'X-CMC_PRO_API_KEY: 02e73a45-839b-4918-814f-baebb032c206'
];
$qs = http_build_query($parameters); // query string encode the parameters
$request = "{$url}?{$qs}"; // create the request URL


$curl = curl_init(); // Get cURL resource
// Set cURL options
curl_setopt_array($curl, array(
  CURLOPT_URL => $request,            // set the request URL
  CURLOPT_HTTPHEADER => $headers,     // set the headers 
  CURLOPT_RETURNTRANSFER => 1         // ask for raw response instead of bool
));

$response = curl_exec($curl); // Send the request, save the response
$lista = json_decode($response, true);
//print_r(json_decode($response)); // print json decoded response
//print_r(json_encode($lista['data'], JSON_FORCE_OBJECT));
curl_close($curl); // Close request


$precioBTC = $lista['data']['BTC']['quote']['USD']['price'];
$precioETH = $lista['data']['ETH']['quote']['USD']['price'];
$precioUSDT = $lista['data']['USDT']['quote']['USD']['price'];
$precioBNB = $lista['data']['BNB']['quote']['USD']['price'];
$precioADA = $lista['data']['ADA']['quote']['USD']['price'];
$precioXRP = $lista['data']['XRP']['quote']['USD']['price'];
$precioUSDC = $lista['data']['USDC']['quote']['USD']['price'];
$precioDOGE = $lista['data']['DOGE']['quote']['USD']['price'];
$precioDOT = $lista['data']['DOT']['quote']['USD']['price'];
$precioUNI = $lista['data']['UNI']['quote']['USD']['price'];
$precioBUSD = $lista['data']['BUSD']['quote']['USD']['price'];
$precioBCH = $lista['data']['BCH']['quote']['USD']['price'];


?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Calculadora Monedas</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
	<style>
		label{padding: 1rem 0 0 0; font-size: 90%; font-weight: bold; color:#1d233b}
		#divResultado{height: 50%; background-color: #363636; color:white; font-weight: bold; font-size: 2rem;}
		
		.form-control, .form-select{border: 1px solid #dddddd;}
		.form-control:hover, .form-select:hover{box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;}
		#divMiniCabecera{background-color: #424141; color:white; }
		#txtMonto, #sltMonedas{color:#3e4045;font-weight: 500;}
		#bolita{width: 40px; height: 40px; color:#dddddd; background-color:white; border:1px solid #dddddd; border-radius: 25px;}
		#bolita:hover{background-color: #dddddd; cursor: pointer;}
		#parteUd{color:#5a5d66;}
		#parteDigitos{color:#0f1f39; font-weight: bold; font-size: 2rem;}
		#spanMoneda{color:#0f1f39; font-weight: bold; font-size: 1.4rem;}
	</style>
	<div class="container p-3" id="app">
		<div class="row">
			<div class="col-12 col-md-6">
				<label v-if="cambiar=='monedas'" for="">Monto a vender ($)</label>
				<label v-else for="">Monto a comprar ($)</label>
				<input type="number" id="txtMonto" class="form-control form-control-lg text-center" placeholder="Min. 10 - Max 900" @change="cambiarMoneda()" v-on:keyup="cambiarMoneda()" v-model="monto" @focusout="cambiarMoneda(); salir()">
			</div>
			
				
			</div>
			<div class="row">
				<div class="col-12 col-md-5">
					<label v-if="cambiar=='monedas'" for="">Criptomoneda a vender</label>
					<label v-else for="">Pagar con:</label>
					<div v-if="cambiar=='monedas'">
						<select name="" id="sltMonedas" class="form-select form-select-lg" v-model='monedaSelect' @change="cambiarMoneda()" placeholder='SS'>
							<option value="-1" disabled>Elija una moneda</option>
							<option v-for="moneda in monedas" :value="moneda.signo">{{moneda.nombre}}</option>
						</select>
					</div>

					
					<div v-if="cambiar=='soles'">
						<select name="" id="sltMonedas" class="form-select form-select-lg">
							<option  value="1">PEN - Sol Peruano</option>
						</select>
					</div>
				</div>
				<div class="col col-md-2 d-flex justify-content-center align-items-end pt-4 pt-lg-0">
					<div class=" d-flex align-items-center justify-content-center" id="bolita" @click="girarTextos()">
						<img src="images/arrow-left-right.svg" alt="">
					</div>
				</div>
				<div class="col-12 col-md-5">
					<label v-if="cambiar=='monedas'" for="">Recibir en</label>
					<label v-else for="">Recibir</label>
					<div v-if="cambiar=='monedas'">
						<select name="" id="sltMonedas" class="form-select form-select-lg">
							<option  value="1">PEN - Sol Peruano</option>
						</select>
					</div>

					<div v-if="cambiar=='soles'">
						<select name="" id="sltMonedas" class="form-select form-select-lg" v-model='monedaSelect' @change="cambiarMoneda()">
							<option value="-1" disabled>Elija una moneda</option>
							<option v-for="moneda in monedas" :value="moneda.signo">{{moneda.nombre}}</option>
						</select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col d-flex justify-content-end">
					<div  v-if="cambiar=='monedas'">
						<label id="parteUd" for="">Ud. puede VENDER {{montoFormateado()}}{{simboloMoneda}} de {{queMoneda}}  = {{digitosCrito}} {{simboloCripto}} a</label>
						<div  id="" class="d-flex align-items-center justify-content-center py-0" > <span class="mx-2" id="parteDigitos">  <span>{{simboloRecepcion}}</span> {{conversion}}</span> <span id="spanMoneda">  </span></div>
					</div>
					<div  v-if="cambiar=='soles'">
						<label id="parteUd" for="">Ud. puede COMPRAR {{montoFormateado()}}{{simboloMoneda}} de {{queMoneda}} = {{digitosCrito}} {{simboloCripto}} por:</label>
						<div  id="" class="d-flex align-items-center justify-content-center py-0" > <span class="mx-2" id="parteDigitos"><span>{{simboloRecepcion}}</span> {{conversion}}</span> <span id="spanMoneda"> </span></div>
					</div>
				</div>
			</div>
		</div>

	</div>
	<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>

	<script>
		//var sltMonedas = document.getElementById('sltMonedas')
		var app = new Vue({
			el: "#app",
			data:{
				monto:'',
				precioDolar: 4.01,
				monedas:[
					{id: 1, nombre:'Bitcoin', signo: 'BTC', precio: <?= $precioBTC; ?> },
					{id: 2, nombre:'Ethereum', signo: 'ETH', precio: <?= $precioETH; ?> },
					{id: 3, nombre:'Tether', signo: 'USDT', precio: <?= $precioUSDT; ?> },
					{id: 4, nombre:'Binance Coin', signo: 'BNB', precio: <?= $precioBNB; ?> },
					{id: 5, nombre:'Cardano', signo: 'ADA', precio: <?= $precioADA; ?> },
					{id: 6, nombre:'XRP', signo: 'XRP', precio: <?= $precioXRP; ?> },
					{id: 7, nombre:'USD Coin', signo: 'USDC', precio: <?= $precioUSDC; ?> },
					{id: 8, nombre:'Dogecoin', signo: 'DOGE', precio: <?= $precioDOGE; ?> },
					{id: 9, nombre:'Polkadot', signo: 'DOT', precio: <?= $precioDOT; ?> },
					{id: 10, nombre:'Uniswap', signo: 'UNI', precio: <?= $precioUNI; ?> },
					{id: 11, nombre:'Binance USD', signo: 'BUSD', precio: <?= $precioBUSD; ?> },
					{id: 12, nombre:'Bitcoin Cash', signo: 'BCH', precio: <?= $precioBCH; ?> },
				],
				cambiar: 'monedas',
				monedaSelect:-1,
				queMoneda:'',
				conversion:'0.00',
				simboloMoneda:'$',
				simboloRecepcion: 'S/',
				quePorcentaje: 0.25,
				digitosCrito: 0, simboloCripto:''
			},
			computed:{
				montoFormato:{
					get(){
						return parseFloat(this.monto).toFixed(2)
					},
					set(newValue){
						if(newValue==''){
							this.monto=0;
						}else{
							this.monto = parseFloat(newValue);
						}
					}
				},
				
				
			},
			methods:{
				cambiarMoneda(){
					if( isNaN(this.monto)){ this.monto=0; this.conversion=0;}else{
						this.monto = parseFloat(this.monto);
						
						let indice = this.monedas.map(recurso => recurso.signo).indexOf( this.monedaSelect );
						let precioMoneda = parseFloat(this.monedas[indice].precio);
						//console.log( 'precio moneda ' + precioMoneda );
						
						//var quePorcentaje = 0.25;

						this.queMoneda = this.monedas[indice].nombre;
							
						if(this.cambiar=='monedas'){
							this.conversion = parseFloat((this.monto / precioMoneda) * precioMoneda * this.precioDolar * (1+this.quePorcentaje)).toFixed(2);
							//this.conversion = parseFloat( (this.monto/(this.precioDolar * precioMoneda ))*(1 + quePorcentaje) ).toFixed(6);
						}else{
							this.conversion = parseFloat((this.monto / precioMoneda) * precioMoneda * this.precioDolar * (1-this.quePorcentaje)).toFixed(2);
							//this.conversion = parseFloat(this.monto * this.precioDolar * (1 + this.quePorcentaje)).toFixed(2)
							
						}
						this.digitosCrito = parseFloat(this.monto / precioMoneda).toFixed(6);
						this.simboloCripto = this.monedas[indice].signo;
					}
				},
				girarTextos(){
					if(this.cambiar=='monedas'){
						this.cambiar = 'soles';
					/* 	this.simboloMoneda = 'S/';
						this.simboloRecepcion = '$'; */

					}else{
						this.cambiar = 'monedas';
						/* this.simboloMoneda = '$';
						this.simboloRecepcion = 'S/'; */
					}
					this.cambiarMoneda();
				},
				salir(){
					this.monto = parseFloat(this.monto).toFixed(2);
				},
				montoFormateado(){
					if(this.monto=='' || isNaN(this.monto)){
						return '0.00';
					}else{
						return parseFloat(this.monto).toFixed(2);
					}
				},
				retornarPrecioMoneda(){
					return 1;
				}
				
			},
			mounted(){
				/* fetch('monedas.json')
				.then(respuesta=> respuesta.json().then(data=>{
					console.log( data );
					this.monedas = data;

				}))
				.catch(error => console.log( error )) */
			}
		});
	</script>
</body>
</html>