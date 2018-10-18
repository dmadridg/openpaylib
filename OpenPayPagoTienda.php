<?php
try {
    $inputJSON = file_get_contents('php://input');    
    $data = json_decode($inputJSON, TRUE);
	$tarjetahabiente = $data['tarjetahabiente'];
	$cargo = $data['data'];	
	include "OpenPay/Openpay.php";
	$openpay = Openpay::getInstance('mm43pdsrnno1oi0asnmp', 'sk_cac20f82dc45499f8071c96a4d0676fe');
    Openpay::setSandboxMode(true);
	 
   $customer = array(
         'name' => $tarjetahabiente['nombre'],
         'last_name' =>$tarjetahabiente['apellidos'],
         'phone_number' => $tarjetahabiente['IdTarjetaHabiente'],
         'email' => $tarjetahabiente['email']
    	 );
		 
    $chargeData = array(
                    'method' => 'store',
                    'amount' =>  number_format((float)$cargo["total"], 2, '.', ''),
                    'description' => 'Cargo a tienda',
                    'customer' => $customer
                  );
    $chargeTienda = $openpay->charges->create($chargeData);
    echo $chargeTienda->payment_method->reference;

} catch (OpenpayApiTransactionError $e) {
	switch ($e->getErrorCode()) {
        case 3001:
            $mensaje = "La tarjeta ha sido rechazada.";
            break;
        case 3002:
            $mensaje = "La tarjeta ha expirado.";
            break;
        case 3003:
            $mensaje = "La tarjeta no cuenta con los fondos suficientes.";
            break;
		case 3004:
            $mensaje = "La tarjeta ha sido identificada como robada.";
            break;
		case 3005:
            $mensaje = "La tarjeta ha sido identificada como robada.";
            break;
    }
	return  $mensaje;
} catch (OpenpayApiRequestError $e) {
	echo'ERROR en la petición: ' . $e->getMessage();
	#return 'ERROR en la petición: ' . $e->getMessage();

} catch (OpenpayApiConnectionError $e) {
	echo'ERROR en la conexión al API: ' . $e->getMessage();
	#return 'ERROR en la conexión al API: ' . $e->getMessage();

} catch (OpenpayApiAuthError $e) {
	echo 'ERROR en la autenticación: ' . $e->getMessage();
	#return 'ERROR en la autenticación: ' . $e->getMessage();
	
} catch (OpenpayApiError $e) {
	echo 'ERROR en el API: ' . $e->getMessage();
	#return 'ERROR en el API: ' . $e->getMessage();
	
} catch (Exception $e) {
	echo 'Error en el script: ' . $e->getMessage();
	#return 'Error en el script: ' . $e->getMessage();
}
				
	