<?php

namespace App\Http\Controllers;


use App\Wallet;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Edujugon\PushNotification\PushNotification;



class WebserviceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $slugNum = null)
    {

        $request = explode("/", $slugNum);

        $device = explode(" ", $_SERVER['HTTP_AUTHORIZATION']);
        $device = $device[0];

        if(strpos($_SERVER['HTTP_AUTHORIZATION'], 'ApplePass') === 0){
            $auth_key = str_replace('ApplePass ', '', $_SERVER['HTTP_AUTHORIZATION']);
        }

        if(strpos($_SERVER['HTTP_AUTHORIZATION'], 'AndroidPass') === 0){
            $auth_key = str_replace('AndroidPass ', '', $_SERVER['HTTP_AUTHORIZATION']);
        }



        //Registro Device
        if (strtoupper($_SERVER['REQUEST_METHOD']) === "POST"
            && isset($_SERVER['HTTP_AUTHORIZATION'])
            && $request[1] === "devices") {

            $device_library_id = $request[2];
            $pass_type_id = $request[4];
            $serial_number = $request[5];


            $dt = @file_get_contents('php://input');

            $device_token = json_decode($dt);
            $device_token = $device_token->pushToken;


            $wallet = Wallet::where('serial_number', $serial_number)->firstOrFail();

            $wallet->device = $device;
            $wallet->device_library_id = $device_library_id;
            $wallet->device_token = $device_token;
            $wallet->pass_type_id = $pass_type_id;
            $wallet->auth_key = $auth_key;
            $wallet->active = '1';

            $wallet->save();
        }


        //Pass Delivery
        if (strtoupper($_SERVER['REQUEST_METHOD']) === "GET"
            && isset($_SERVER['HTTP_AUTHORIZATION'])
            && $request[1] === "passes") {

            $pass_type_id = $request[2];
            $serial_number = $request[3];

            $wallet = Wallet::where('serial_number', $serial_number)
                                ->where('pass_type_id', $pass_type_id)
                                ->where('auth_key', $auth_key)
                                ->firstOrFail();

            /*$wallet->device = $device;
            $wallet->pass_type_id = $pass_type_id;
            $wallet->auth_key = $auth_key;
            $wallet->active = '1';

            $wallet->save();*/

            if($wallet){

                $file = base_path().'/web/walletpasses/selenta_'.$serial_number.'.pkpass';

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



                $myfile = fopen("tokens.txt", "a");

                $txt = '';
                if(isset($_SERVER['HTTP_USER_AGENT'])) $txt .= "\n\nHTTP_USER_AGENT: ".$_SERVER['HTTP_USER_AGENT'];
                if(isset($_SERVER['HTTP_AUTHORIZATION'])) $txt .= "\n\nHTTP_AUTHORIZATION: ".$_SERVER['HTTP_AUTHORIZATION'];
                if(isset($_SERVER['REQUEST_METHOD'])) $txt .= "\nREQUEST_METHOD: ".strtoupper($_SERVER['REQUEST_METHOD']);
                if(isset($_SERVER['REQUEST_URI'])) $txt .= "\nREQUEST_URI: ".$_SERVER['REQUEST_URI'];
                if(isset($request[0])) $txt .= "\nrequest0: ".$request[0];
                if(isset($request[1])) $txt .= "\nrequest1: ".$request[1];
                if(isset($request[2])) $txt .= "\nrequest2: ".$request[2];
                if(isset($request[3])) $txt .= "\nrequest3: ".$request[3];
                if(isset($request[4])) $txt .= "\nrequest4: ".$request[4];
                if(isset($request[5])) $txt .= "\nrequest5: ".$request[5];
                if(isset($request[6])) $txt .= "\nrequest6: ".$request[6];
                if(isset($request[7])) $txt .= "\nrequest7: ".$request[7];
                if(isset($request[8])) $txt .= "\nrequest8: ".$request[8];
                if(isset($wallet)) $txt .= "\nwallet: ".$wallet;
                if(isset($file)) $txt .= "\nwallet: ".$file;
                if(isset($_GET['passesUpdatedSince'])) $txt .= "n\passesUpdatedSince: ".$_GET['passesUpdatedSince'];


                fwrite($myfile, $txt);

                fclose($myfile);
            }

            

        }


        //Borrar Device
        if (strtoupper($_SERVER['REQUEST_METHOD']) === "DELETE"
            && isset($_SERVER['HTTP_AUTHORIZATION'])
            && $request[1] === "devices") {

            $device_library_id = $request[2];
            $pass_type_id = $request[4];
            $serial_number = $request[5];

            $wallet = Wallet::where('serial_number', $serial_number)->firstOrFail();

            $wallet->device = $device;
            $wallet->device_library_id = $device_library_id;
            $wallet->pass_type_id = $pass_type_id;
            $wallet->auth_key = $auth_key;
            $wallet->active = '0';           

            $wallet->save();            
        }


        //Log
        $description = '';

        if (strtoupper($_SERVER['REQUEST_METHOD']) === "POST"
            && $request[1] === "log") {
           
            $dt = @file_get_contents('php://input');

            $description = json_decode($dt);
            $description = $device_token->description;
        }






        $myfile = fopen("tokens.txt", "a");

        $txt = '';
        if(isset($_SERVER['HTTP_USER_AGENT'])) $txt .= "\n\nHTTP_USER_AGENT: ".$_SERVER['HTTP_USER_AGENT'];
        if(isset($_SERVER['HTTP_AUTHORIZATION'])) $txt .= "\n\nHTTP_AUTHORIZATION: ".$_SERVER['HTTP_AUTHORIZATION'];
        if(isset($_SERVER['REQUEST_METHOD'])) $txt .= "\nREQUEST_METHOD: ".strtoupper($_SERVER['REQUEST_METHOD']);
        if(isset($_SERVER['REQUEST_URI'])) $txt .= "\nREQUEST_URI: ".$_SERVER['REQUEST_URI'];
        if(isset($request[0])) $txt .= "\nrequest0: ".$request[0];
        if(isset($request[1])) $txt .= "\nrequest1: ".$request[1];
        if(isset($request[2])) $txt .= "\nrequest2: ".$request[2];
        if(isset($request[3])) $txt .= "\nrequest3: ".$request[3];
        if(isset($request[4])) $txt .= "\nrequest4: ".$request[4];
        if(isset($request[5])) $txt .= "\nrequest5: ".$request[5];
        if(isset($request[6])) $txt .= "\nrequest6: ".$request[6];
        if(isset($request[7])) $txt .= "\nrequest7: ".$request[7];
        if(isset($request[8])) $txt .= "\nrequest8: ".$request[8];
        if(isset($description)) $txt .= "n\description: ".$description;
        if(isset($_GET['passesUpdatedSince'])) $txt .= "n\passesUpdatedSince: ".$_GET['passesUpdatedSince'];


        fwrite($myfile, $txt);

        fclose($myfile);



        

        return [
            'response' => '200'
        ];
    }


    public function sendnotification(Request $request)
    {
        #API access key from Google API's Console
        define( 'API_ACCESS_KEY', '' );
        $registrationIds = $_GET['id'];

        #prep the bundle
        $msg = array
              (
            'body'  => 'Body  Of Notification',
            'title' => 'Title Of Notification',
                    'icon'  => 'myicon',
                    'sound' => 'mySound'
              );

        $fields = array
                (
                    'passTypeIdentifier'        => $request->passTypeIdentifier,
                    'pushTokens'    => $request->pushTokens
                );
        
        
        $headers = array
                (
                    'Authorization: ' . API_ACCESS_KEY,
                    'Content-Type: application/json'
                );

        #Send Reponse To FireBase Server    
        $ch = curl_init();
        //curl_setopt( $ch,CURLOPT_URL, 'https://walletpasses.appspot.com/api/v1/push' );
        curl_setopt( $ch,CURLOPT_URL, 'https://walletpasses.appspot.com/api/v1/push' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        curl_close( $ch );

        #Echo Result Of FireBase Server




        return $result;
    }
}
