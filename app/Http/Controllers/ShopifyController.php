<?php

namespace App\Http\Controllers;


use App\ShopifyTrigger;
use App\ShopifyWebhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use App\HandwryttenApi;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Log;

use App\ShopifyOrder;

class ShopifyController extends Controller
{


  public function getorder(Request $request)
  {
    $count = DB::table('shopifyorders')->count();


    if ($count < 1) {

      $webhookdetails = DB::table('shopifywebhook')->where('webhook_topic', 'orders/create')->first();

      $userid =  $webhookdetails->user_id;

      $orderid =  $request->id;
      $ordernumber =  $request->number;
      $email =  $request->email;
      $order_status_url =  $request->order_status_url;
      $product_id =   $request['line_items'][0]['product_id'];
      $title =  $request['line_items'][0]['title'];
      $quantity =  $request['line_items'][0]['quantity'];
      $amount =   $request['total_line_items_price_set']['shop_money']['amount'];
      $currency_code =   $request['total_line_items_price_set']['shop_money']['currency_code'];
      $vendor =  $request['line_items'][0]['vendor'];
      $recipient_name = $request['shipping_address']['name'];
      $recipient_business_name = $request['shipping_address']['company'];
      $recipient_address1  = $request['shipping_address']['address1'];
      $recipient_address2  = $request['shipping_address']['address2'];
      $recipient_city = $request['shipping_address']['city'];
      $recipient_zip = $request['shipping_address']['zip'];
      $recipient_country = $request['shipping_address']['country'];
      $add0rder = new ShopifyOrder();
      $add0rder->user_id = $userid;
      $add0rder->order_id = $orderid;
      $add0rder->order_number = $ordernumber;
      $add0rder->email = $email;
      $add0rder->order_status_url = $order_status_url;
      $add0rder->product_id = $product_id;
      $add0rder->title = $title;
      $add0rder->quantity = $quantity;
      $add0rder->amount = $amount;
      $add0rder->currency_code = $currency_code;
      $add0rder->vendor = $vendor;
      $add0rder->recipient_name = $recipient_name;
      $add0rder->recipient_business_name = $recipient_business_name;
      $add0rder->recipient_address1 = $recipient_address1;
      $add0rder->recipient_city = $recipient_city;
      $add0rder->recipient_zip = $recipient_zip;
      $add0rder->recipient_country = $recipient_country;
      $add0rder->save();

      $user_details = DB::table('handwrytten_apis')->where([
        ['user_id', '=', $userid],
      ])->first();
      $useremail = $user_details->email;
      $pass = $user_details->password;

      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.handwrytten.com/v1/auth/authorization",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => array('login' => $useremail, 'password' => $pass),
        CURLOPT_HTTPHEADER => array(
          "Accept: application/json",
        ),
      ));

      $response = curl_exec($curl);
      $data = json_decode($response);

      $fullname = $data->fullname;
      $uidtt = $data->uid;

      $ordersget = DB::table('shopifyorders')->where([
        ['order_number', '=', '1'],
        ['user_id', '=', $userid],
      ])->first();

      $fetch_recipient_name =  $ordersget->recipient_name;
      $fetch_recipient_business_name =  $ordersget->recipient_business_name;
      $fetch_recipient_address1 =  $ordersget->recipient_address1;
      $fetch_recipient_city =   $ordersget->recipient_city;
      $fetch_recipient_zip =   $ordersget->recipient_zip;
      $fetch_recipient_country =   $ordersget->recipient_country;
      $triggerget = DB::table('shopify_triggers')->where([
        ['trigger_name', '=', 'First Order Placed'],
        ['user_id', '=', $userid],
      ])->first();

      $fetch_trigger_message = $triggerget->trigger_message;
      $fetch_trigger_signoff = $triggerget->trigger_signoff;
      $fetch_trigger_handwriting_style = $triggerget->trigger_handwriting_style;
      $fetch_trigger_insert = $triggerget->trigger_insert;
      $fetch_trigger_gift_card = $triggerget->trigger_gift_card;


      $user_details = DB::table('users')->where([
        ['id', '=', $userid],
      ])->first();
      $usernameus = $user_details->name;

      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://backend.handwrytten.com/api.php/api/orders/singleStepOrder",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\r\n\"uid\":\"$uidtt\",\r\n\"card_id\":\"101\",\r\n\"denomination_id\":\"2\",\r\n\"message\":\"$fetch_trigger_message\",\r\n\"font_label\":\"$fetch_trigger_handwriting_style\",\r\n\"sender_name\":\"$usernameus\",\r\n\"sender_business_name\":\"123456\",\r\n\"sender_address1\":\"2112 Manchester\",\r\n\"sender_address2\":\"\",\r\n\"sender_city\":\"Los Angeles\",\r\n\"sender_state\":\"CA\",\r\n\"sender_zip\":\"91111\",\r\n\"sender_country\":\"USA\",\r\n\"recipient_name\":\"$fetch_recipient_name\",\r\n\"recipient_business_name\":\"$fetch_recipient_business_name\",\r\n\"recipient_address1\":\"$fetch_recipient_address1\",\r\n\"recipient_city\":\"$fetch_recipient_city \",\r\n\"recipient_zip\":\"$fetch_recipient_zip\",\r\n\"recipient_country\":\"$fetch_recipient_country\",\r\n\"insert_id\":\"$fetch_trigger_insert\",\r\n\"credit_card_id\":\"\"\r\n}",
        CURLOPT_HTTPHEADER => array(
          "cache-control: no-cache",
          "content-type: application/json",
          "postman-token: 5100f8af-c0f2-2691-8872-53f00bbd5fb4"
        ),
      ));

      $responseyt = curl_exec($curl);
      $err = curl_error($curl);     
      curl_close($curl);
    }
  }
}
