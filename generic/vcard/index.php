<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

</head>


<?php
use PKPass\PKPass;

require('../../vendor/autoload.php');


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
    

$mysqli->set_charset('utf8');


$consulta = "SELECT * FROM empleados";

if ($resultado = mysqli_query($mysqli, $consulta)) {

    while ($row = $resultado->fetch_assoc()) {      

        $notapersonal = '';


        $image = $row['imagen'];
        $imagevcard = $row['imagenvcard'];
        $id = $row['idcard'];
        $layout = $row['layout'];
        $has_logo = $row['has_logo'];
        $namealone = stripslashes($row['nombre']);
        $name = stripslashes($row['nombre']).' '.stripslashes($row['apellidos']);
        $company_name = stripslashes($row['empresa']);   
        $company_slug = $row['empresa_slug'];  
        $company_url_server = $row['empresa_url_server'];  
        $company_cif = $row['empresa_cif'];   
        $title = stripslashes($row['puesto']);
        $email = $row['email'];
        $movil = $row['telefono'];
        $linkedin = stripslashes($row['linkedin']);
        $notapersonal = stripslashes($row['notapersonal']);
        $address = stripslashes($row['direccion_empresa']);
        $web = stripslashes($row['web_empresa']);
        $telefono_empresa = stripslashes($row['telefono_empresa']);
        $emp_desc = stripslashes($row['descripcion_empresa']);
        $emp_instagram = stripslashes($row['instagram_empresa']);
        $emp_twitter = stripslashes($row['twitter_empresa']);
        $emp_linkedin = stripslashes($row['linkedin_empresa']);
        $emp_behance = stripslashes($row['behance_empresa']);
        $emp_youtube = stripslashes($row['youtube_empresa']);

        $vcardnameslugify = slugify($id);
        $vcardname = $id;

        mkdir('users/'.$vcardname);


        if($image!=""){ 
          $getPhoto               = file_get_contents($image);
          $b64vcard               = base64_encode($getPhoto);
          $b64mline               = chunk_split($b64vcard,74,"\n");
          $b64final               = preg_replace('/(.+)/', ' $1', $b64mline);
          $photo                  = $b64final;
        }

        if($image!=""){
	        $getPhotologo               = file_get_contents($imagevcard);
	        $b64vcardlogo               = base64_encode($getPhotologo);
	        $b64mlinelogo               = chunk_split($b64vcardlogo,74,"\n");
	        $b64finallogo               = preg_replace('/(.+)/', ' $1', $b64mlinelogo);
	        $photologo                  = $b64finallogo;
	    }

	    $logo_empresa = file_get_contents($row['logo_empresa']);

        file_put_contents('users/'.$vcardname.'/background.png', $getPhoto);

        file_put_contents('users/'.$vcardname.'/icon.png', $logo_empresa);
        file_put_contents('users/'.$vcardname.'/icon@2x.png', $logo_empresa);

        file_put_contents('users/'.$vcardname.'/logo.png', $logo_empresa);
        file_put_contents('users/'.$vcardname.'/logo@2x.png', $logo_empresa);

        file_put_contents('users/'.$vcardname.'/thumbnail.png', $logo_empresa);
        file_put_contents('users/'.$vcardname.'/thumbnail@2x.png', $logo_empresa);

        $vCard = "BEGIN:VCARD\r\n";
        $vCard .= "VERSION:3.0\r\n";
        $vCard .= "N:" . stripslashes($row['apellidos']) .";". stripslashes($row['nombre']). "\r\n";
        $vCard .= "FN:" . $name . "\r\n";
        $vCard .= "ORG:" . $company_name . "\r\n";
        $vCard .= "TITLE:" . $title . "\r\n";
        
        $vCard .= "ADR;TYPE=work:" . $address . "\r\n";
        $vCard .= "item1.URL;type=pref:" . $web . "\r\n";

        if($linkedin){
          $vCard .= "item2.URL;type=pref:" . $linkedin . "\r\n";
        }

        if($emp_instagram){
          $vCard .= "item3.URL;type=pref:" . $emp_instagram . "\r\n";
        }
        if($emp_twitter){
          $vCard .= "item4.URL;type=pref:" . $emp_twitter . "\r\n";
        }
        if($emp_linkedin){
          $vCard .= "item5.URL;type=pref:" . $emp_linkedin . "\r\n";
        }
        if($emp_behance){
          $vCard .= "item6.URL;type=pref:" . $emp_behance . "\r\n";
        }

        if($emp_youtube){
          $vCard .= "item7.URL;type=pref:" . $emp_youtube . "\r\n";
        }

        if($email){
          $vCard .= "EMAIL;TYPE=internet,pref:" . $email . "\r\n";
        }

        //if($getPhoto){
          $vCard .= "PHOTO;ENCODING=b;TYPE=JPEG:";
          $vCard .= $photologo . "\r\n";
        //}

        if($movil){
          $vCard .= "TEL;TYPE=cell,voice,pref:" . $movil . "\r\n"; 
        }

        if($telefono_empresa){
          $vCard .= "TEL;TYPE=work,voice,pref:" . $telefono_empresa . "\r\n"; 
        }

        $vCard .= "END:VCARD\r\n";

        file_put_contents("vcards/$vcardnameslugify.vcf", $vCard);

        $token = sha1(mt_rand(1, 90000) . 'SALT');


        if (file_exists('../certs/Certificados'.$company_slug.'.p12')) {
          $pass = new PKPass('../certs/Certificados'.$company_slug.'.p12', 'CLAVE_DEL_CERTIFICADO');
          $passTypeIdentifier = 'pass.com.helefante.'.$company_slug;
        }else{
          $pass = new PKPass('../certs/Certificadosgeneric.p12', 'CLAVE_DEL_CERTIFICADO');
          $passTypeIdentifier = 'pass.com.helefante.digitalcard';
        }
        

        $name_data = '';
        $title_data = '';
        $title_data_2 = '';
        $linkedin_data = '';
        $emp_instagram_data = '';
        $emp_twitter_data = '';
        $emp_linkedin_data = '';
        $emp_youtube_data = '';
        $company_name_data = '';
        $emp_desc_data = '';
        $notapersonal_data = '';
        $company_telefono = '';
        $company_cif_data = '';
        $barcode = '';

        if($company_url_server == ''){
          $company_url_server = 'https://zeliuk.xyz/pkpass-wallet-apple/generic/vcard/vcards/';
        }

        if($has_logo == 0){
          $logo_text = $company_name;
        }else{
          $logo_text = '';
        }


        //if($name == ' '){ $name = '';}
        if($name != ' '){
          $name_data = '{
                      "key": "trabajador",
                      "label": "Nombre",
                      "value": "' . $name . '"
                  },';
        }

        if($title != ''){
          $title_data = ',{
                      "key": "puesto",
                      "label": "PUESTO",
                      "value": "' . $title . '"
                  }';

          $title_data_2 = '{
                      "key": "puesto",
                      "label": "Puesto",
                      "value": "' . $title . '"
                  }';
        }

        if($linkedin != ''){
          $linkedin_data = ',{
                      "key": "linkedin",
                      "label": "LinkedIn",
                      "value": "' . $linkedin . '"
                  }';
        }

        if($emp_instagram != ''){
          $emp_instagram_data = ',{
                      "key": "instagram",
                      "label": "Instagram",
                      "value": "' . $emp_instagram . '"
                  }';
        }

        if($emp_twitter != ''){
          $emp_twitter_data = ',{
                      "key": "twitter",
                      "label": "Twitter",
                      "value": "' . $emp_twitter . '"
                  }';
        }

        if($emp_linkedin != ''){
          $emp_linkedin_data = ',{
                      "key": "linkedin",
                      "label": "LinkedIn",
                      "value": "' . $emp_linkedin . '"
                  }';
        }

        if($emp_youtube != ''){
          $emp_youtube_data = ',{
                      "key": "youtube",
                      "label": "YouTube",
                      "value": "' . $emp_youtube . '"
                  }';
        }

        if($company_name != ''){
          $company_name_data = ',{
                      "key": "empresa",
                      "label": "Empresa",
                      "value": "' . $company_name . '"
                  }';
        }

        if($company_cif != ''){
          $company_cif_data = ',{
                      "key": "cif",
                      "label": "CIF",
                      "value": "' . $company_cif . '"
                  }';
        }

        if($telefono_empresa != ''){
          $company_telefono = ',{
                      "key": "telefonoempresa",
                      "label": "Teléfono empresa",
                      "value": "' . $telefono_empresa . '"
                  }';
        }

        if($emp_desc != ''){
          $emp_desc_data = ',{
                      "key": "descripcionempresa",
                      "label": "Descripción empresa",
                      "value": "' . $emp_desc . '"
                  }';
        }

        if($notapersonal != ''){
          $notapersonal_data = ',{
                      "key": "notapersonal",
                      "label": "Nota personal",
                      "value": "' . $notapersonal . '"
                  }';
        }


        if($company_slug == 'thebestcityclub'){
          $barcode = '"barcode": {
              "format": "PKBarcodeFormatQR",
              "message": "https://qrco.de/bepJHI",
              "messageEncoding": "iso-8859-1",
            "altText": ""
          }';
        }else{
          $barcode = '"barcode": {
              "format": "PKBarcodeFormatQR",
              "message": "'.$company_url_server.$vcardnameslugify.'.vcf",
              "messageEncoding": "iso-8859-1",
            "altText": ""
          }';
        }

        /*"groupingIdentifier": "' . $company_name . '",*/

        $passdata = '{
        "passTypeIdentifier": "'.$passTypeIdentifier.'", 
        "formatVersion": 1,
        "organizationName": "' . $company_name . '",
        "teamIdentifier": "JMFXUMMX23",
        "serialNumber": "' . $vcardname . '",        
        "backgroundColor": "' . $row['color_background'] . '",
        "labelColor" : "' . $row['color_text_2'] . '",
        "foregroundColor" : "' . $row['color_text_1'] . '",
        "logoText": "' . $logo_text . '",
        "description": "' . $company_name . '",
        "webServiceURL" : "https://zeliuk.xyz/pkpass-wallet-apple/generic/webservice",
        "authenticationToken" : "'.$token.'",          
          "'.$layout.'": {
              "secondaryFields": [ 
                  {
                      "key" : "name",
                      "label" : "'.$title.'",
                      "value" : "'.$name.'"
                  }
              ],
              "backFields": [
                  '.$name_data.'
                  '.$title_data_2.',
                  {
                      "key": "email",
                      "label": "Email",
                      "value": "' . $email . '"
                  },
                  {
                      "key": "telefonopersonal",
                      "label": "Teléfono",
                      "value": "' . $movil . '"
                  }'.$notapersonal_data.'
                  '.$linkedin_data.'
                  '.$company_name_data.'
                  '.$company_cif_data.'
                  '.$company_telefono.'
                  '.$emp_desc_data.',
                  {
                      "key": "web",
                      "label": "Página web",
                      "value": "' . $web . '"
                  },
                  {
                      "key": "direccion",
                      "label": "Dirección",
                      "value": "' . $address . '"
                  }'.$emp_instagram_data.'
                  '.$emp_linkedin_data.'
                  '.$emp_twitter_data.'
                  '.$emp_youtube_data.'
              ]
          },
          '.$barcode.'
        }';

        echo '<pre>';
        //var_dump($passdata);
        echo $vcardname;
        echo '</pre>';

        $pass->setData($passdata);

        // add files to the PKPass package
        $pass->addFile('users/'.$vcardname.'/icon.png');
        $pass->addFile('users/'.$vcardname.'/icon@2x.png');


        if($has_logo == 1){
          $pass->addFile('users/'.$vcardname.'/logo.png');
          $pass->addFile('users/'.$vcardname.'/logo@2x.png');
        }

        /*$pass->addFile('users/'.$vcardname.'/thumbnail.png');
        $pass->addFile('users/'.$vcardname.'/thumbnail@2x.png');*/

        if($layout != 'generic'){
	        if($image!=""){
	          $pass->addFile('users/'.$vcardname.'/background.png', 'strip.png');
	        }else{
	        }
	    }
        file_put_contents("pkpass/$vcardname.pkpass", $pass->create(false));
    }

    $resultado->close();
}


function slugify($text, string $divider = '-')
{
  $text = preg_replace('~[^\pL\d]+~u', $divider, $text);
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
  $text = preg_replace('~[^-\w]+~', '', $text);
  $text = trim($text, $divider);
  $text = preg_replace('~-+~', $divider, $text);
  $text = strtolower($text);
  if (empty($text)) {
    return 'n-a';
  }

  return $text;
}
    

?>