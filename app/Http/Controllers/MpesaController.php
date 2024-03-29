<?php

namespace App\Http\Controllers;

use Mpesa;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

use App\Models\MpesaTransaction;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Dotenv\Dotenv;


class MpesaController extends Controller
{

    public function lipaNaMpesaPassword()
    {
        //timestamps
        $timestamp=Carbon::rawParse('now')->format('YmdHms');
        //lipaNaMpesa_Passkey
        $passKey= "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919";
        //BusinessShortCode
        $businessShortCode=174379;

        //generate password

        $mpesapassword=base64_encode($businessShortCode.$passKey.$timestamp);

        return $mpesapassword;
    }

    public function generateAccessToken()
    {
        //initialize consumer key and consumer secret

            $consumer_key = env("MPESA_CONSUMER_KEY");
            $consumer_secret = env("MPESA_CONSUMER_SECRET");


        if(!isset($consumer_key)||!isset($consumer_secret)){
            die("please declare the consumer key and consumer secret as defined in the documentation");
        }

        $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        $credentials = base64_encode($consumer_key.':'.$consumer_secret);

        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$credentials,'Content-Type: application/json')); //setting a custom header
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

        $curl_response = curl_exec($curl);

        $access_token=json_decode($curl_response);

        return $access_token->access_token;


    }

    public function stkPush(Request $request)
    {
     $mpesa=new \Safaricom\Mpesa\Mpesa();

         $BusinessShortCode=174379;
         $LipaNaMpesaPasskey="bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919";
         $TransactionType="CustomerPayBillOnline";
         $Amount="1";
         $PartyA= "254712135643";
         $PartyB= 174379;
         $PhoneNumber= 254712135643;
         $CallBackURL= "https://8e3f-197-232-61-238.ngrok.io/api/mpesa_callback_url";
         $AccountReference ="Alvine Project Demo";
         $TransactionDesc="Alvine Web Company";
         $Remarks="Thank you for transacting with Alvine";


     $stkPushSimulation=$mpesa->STKPushSimulation($BusinessShortCode,
         $LipaNaMpesaPasskey,
         $TransactionType,
         $Amount,
         $PartyA,
         $PartyB,
         $PhoneNumber,
         $CallBackURL,
         $AccountReference,
         $TransactionDesc,
         $Remarks
     );

     return $stkPushSimulation;

   }

   public function mpesaResponse(Request $request)
   {
     $response=json_decode($request->getContent());
     Log::info(json_encode($response));

     $resCode=$response->Body->stkCallback->ResultCode;
     $resMessage=$response->Body->stkCallback->ResultDesc;
     $resData=$response->Body->stkCallback->CallbackMetadata;
     $amountPaid = $resData->Item[0]->Value;
     $mpesaTransactionId = $resData->Item[1]->Value;
     $mpesatransactiontime= $resData->Item[3]->Value;
     $paymentPhoneNumber=$resData->Item[4]->Value;

     $formatedPhone=str_replace("254","0",$paymentPhoneNumber);

     $transaction= new MpesaTransaction;
     $transaction->Amount=$amountPaid;
     $transaction->resultCode=$resCode;
     $transaction->resultDesc=$resMessage;
     $transaction->TransactionDate=$mpesatransactiontime;
     $transaction->ReceiptNumber=$mpesaTransactionId;
     $transaction->PhoneNumber=$formatedPhone;     
     $transaction->save();
     
   }

}
