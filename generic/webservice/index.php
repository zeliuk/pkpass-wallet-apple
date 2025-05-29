<?php

$request_cut = str_replace('/pkpass-wallet-apple/generic/webservice', '', $_SERVER['REQUEST_URI']);
$request = explode("/", substr($request_cut, 1));
$device = '';


if(strpos($_SERVER['HTTP_AUTHORIZATION'], 'ApplePass') === 0){
    $authKey = str_replace('ApplePass ', '', $_SERVER['HTTP_AUTHORIZATION']);
    $device = 'Apple';
}

if(strpos($_SERVER['HTTP_AUTHORIZATION'], 'AndroidPass') === 0){
    $authKey = str_replace('AndroidPass ', '', $_SERVER['HTTP_AUTHORIZATION']);
    $device = 'Android';
}


/*var_dump($request);

echo $request[3];*/

/*$txt = "\n\nHTTP_AUTHORIZATION: ".$_SERVER['HTTP_AUTHORIZATION'];
$txt .= "\nREQUEST_METHOD: ".strtoupper($_SERVER['REQUEST_METHOD']);
$txt .= "\nREQUEST_URI: ".$_SERVER['REQUEST_URI'];
$txt .= "\nrequest1: ".$request[1];
$txt .= "\nrequest2: ".$request[2];
$txt .= "\nrequest3: ".$request[3];
$txt .= "\nrequest4: ".$request[4];
$txt .= "\nrequest5: ".$request[5];
$txt .= "\nrequest6: ".$request[6];
$txt .= "\nrequest7: ".$request[7];
$txt .= "\nrequest8: ".$request[8];*/

/*$txt = $request[0];

$myfile = fopen("tokens.txt", "a");
fwrite($myfile, $txt);

fclose($myfile);*/


$username = "";
$password = "";
$hostname = "";
$port = 3306;
$database = "";

$mysqli = mysqli_connect($hostname, $username, $password, $database, $port);
  if (!$mysqli) {
      echo "Error: No se pudo conectar a MySQL." . PHP_EOL;
      echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
      echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
      exit;
  }


//Registro Device
if (strtoupper($_SERVER['REQUEST_METHOD']) === "POST"
    && isset($_SERVER['HTTP_AUTHORIZATION'])
    && $request[1] === "devices") {

    $myfile = fopen("tokens.txt", "a");

    $deviceLibraryIdentifier = $request[2];
    $passTypeIdentifier = $request[4];
    $serialNumber = $request[5];


    $dt = @file_get_contents('php://input');

    $deviceToken = json_decode($dt);
    $deviceToken = $deviceToken->pushToken;



    $cardexists = 0;
    $sqlcardexists = "SELECT * FROM distribution WHERE idcard LIKE '$serialNumber' AND devicelibraryidentifier LIKE '$deviceLibraryIdentifier'";

    if ($resultcardexists = mysqli_query($mysqli, $sqlcardexists)) {
        $row_cnt = mysqli_num_rows($resultcardexists);

        if($row_cnt > 0){
            $cardexists = 1;
        }
    }


    if($cardexists == 0){

        $sqlcard = "SELECT id FROM empleados WHERE idcard LIKE '$serialNumber'";

        if ($result = mysqli_query($mysqli, $sqlcard)) {
            while ($row = mysqli_fetch_row($result)) {
                $sql = "INSERT INTO distribution (idpersona, idcard, device, authkey, devicelibraryidentifier, devicetoken)
                VALUES ('$row[0]', '$serialNumber', '$device', '$authKey', '$deviceLibraryIdentifier', '$deviceToken')";

                if (mysqli_query($mysqli, $sql)) {
                    
                }
            }
        }

        
    }


    $txt = "\n\nNuevo registro ----- ";
    $txt .= "\nHTTP_AUTHORIZATION: ".$_SERVER['HTTP_AUTHORIZATION'];
    $txt .= "\nREQUEST_METHOD: ".strtoupper($_SERVER['REQUEST_METHOD']);
    $txt .= "\nREQUEST_URI: ".$_SERVER['REQUEST_URI'];
    $txt .= "\nauthKey: ".$authKey;
    $txt .= "\ndeviceLibraryIdentifier: ".$deviceLibraryIdentifier;
    $txt .= "\npassTypeIdentifier: ".$passTypeIdentifier;
    $txt .= "\nserialNumber: ".$serialNumber;
    $txt .= "\ndeviceToken: ".$deviceToken;

    fwrite($myfile, $txt);
    fclose($myfile);

    exit;
}


//Actualiza Device
if (strtoupper($_SERVER['REQUEST_METHOD']) === "GET"
    && isset($_SERVER['HTTP_AUTHORIZATION'])
    && $request[1] === "passes") {

    $myfile = fopen("tokens.txt", "a");

    $passTypeIdentifier = $request[2];
    $serialNumber = $request[3];


    $dt = @file_get_contents('php://input');

    $deviceToken = json_decode($dt);
    $deviceToken = $deviceToken->pushToken;



    $file = '/homepages/9/d4299026183/htdocs/pkpass-wallet-apple/generic/vcard/pkpass/'.$serialNumber.'.pkpass';

    header('Content-Transfer-Encoding: binary');
    header('Content-Description: File Transfer');
    header('Content-Type: application/vnd.apple.pkpass');
    header('Content-Disposition: attachment; filename="pass.pkpass"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Connection: Keep-Alive');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Content-Length: ' . filesize($file));
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s T'));
    readfile($file);


    $sqlcardupdate = "UPDATE distribution SET authkey='$authKey' WHERE idcard LIKE '$serialNumber' AND authkey LIKE '$authKey'";

    if ($resultcardupdate = mysqli_query($mysqli, $sqlcardupdate)) {
        
    }

    

    $txt = "\n\nActualiza ----- ";
    $txt .= "\nHTTP_AUTHORIZATION: ".$_SERVER['HTTP_AUTHORIZATION'];
    $txt .= "\nREQUEST_METHOD: ".strtoupper($_SERVER['REQUEST_METHOD']);
    $txt .= "\nREQUEST_URI: ".$_SERVER['REQUEST_URI'];
    $txt .= "\nauthKey: ".$authKey;
    $txt .= "\npassTypeIdentifier: ".$passTypeIdentifier;
    $txt .= "\nserialNumber: ".$serialNumber;
    $txt .= "\nfile: ".$file;

    fwrite($myfile, $txt);
    fclose($myfile);

    exit;
}



//Borrar Device
if (strtoupper($_SERVER['REQUEST_METHOD']) === "DELETE"
    && isset($_SERVER['HTTP_AUTHORIZATION'])
    && $request[1] === "devices") {

    $myfile = fopen("tokens.txt", "a");

    $deviceLibraryIdentifier = $request[2];
    $passTypeIdentifier = $request[4];
    $serialNumber = $request[5];


    $dt = @file_get_contents('php://input');

    $deviceToken = json_decode($dt);
    $deviceToken = $deviceToken->pushToken;


    $sqlcardremove = "DELETE FROM distribution WHERE idcard LIKE '$serialNumber' AND devicelibraryidentifier LIKE '$deviceLibraryIdentifier'";

    if ($resultcardremove = mysqli_query($mysqli, $sqlcardremove)) {
      /*  $txt = "\n\nBorrar registro ----- ";
        $txt .= "\nHTTP_AUTHORIZATION: ".$_SERVER['HTTP_AUTHORIZATION'];
        $txt .= "\nREQUEST_METHOD: ".strtoupper($_SERVER['REQUEST_METHOD']);
        $txt .= "\nREQUEST_URI: ".$_SERVER['REQUEST_URI'];
        $txt .= "\nauthKey: ".$authKey;
        $txt .= "\ndeviceLibraryIdentifier: ".$deviceLibraryIdentifier;
        $txt .= "\npassTypeIdentifier: ".$passTypeIdentifier;
        $txt .= "\nserialNumber: ".$serialNumber;

        fwrite($myfile, $txt);
        fclose($myfile);*/
    }

    $txt = "\n\nBorrar registro ----- ";
    $txt .= "\nHTTP_AUTHORIZATION: ".$_SERVER['HTTP_AUTHORIZATION'];
    $txt .= "\nREQUEST_METHOD: ".strtoupper($_SERVER['REQUEST_METHOD']);
    $txt .= "\nREQUEST_URI: ".$_SERVER['REQUEST_URI'];
    $txt .= "\nauthKey: ".$authKey;
    $txt .= "\ndeviceLibraryIdentifier: ".$deviceLibraryIdentifier;
    $txt .= "\npassTypeIdentifier: ".$passTypeIdentifier;
    $txt .= "\nserialNumber: ".$serialNumber;

    fwrite($myfile, $txt);
    fclose($myfile);

    exit;
}


mysqli_close($conn);
