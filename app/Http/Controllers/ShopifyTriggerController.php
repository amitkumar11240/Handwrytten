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

class ShopifyTriggerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $triggers = ShopifyTrigger::where('user_id', Auth::id())->latest()->get();
        $handwrytten = DB::table('handwrytten_apis')->where('user_id', '=', Auth::id())->first();

        if(is_null($handwrytten)){
            return view('handwrytten-app.login');
        } else{
          $responseStyle = Http::withToken($handwrytten->token)->get('https://api.handwrytten.com/v1/fonts/list');
            $responseInsert = Http::withToken($handwrytten->token)->get('https://api.handwrytten.com/v1/inserts/list');
            $responseGiftCard = Http::withToken($handwrytten->token)->get('https://api.handwrytten.com/v1/giftCards/list');
            $responseCategory = Http::withToken($handwrytten->token)->get('https://api.handwrytten.com/v1/categories/list');
            $singlesteporder = Http::withToken($handwrytten->token)->post('https://api.handwrytten.com/v1/orders/singleStepOrder');   
            $style = json_decode($responseStyle);
            $insertData = json_decode($responseInsert);
            $giftCard = json_decode($responseGiftCard);
            $category = json_decode($responseCategory);
         
   return view('triggers', compact('triggers', 'handwrytten', 'style', 'insertData', 'giftCard', 'category'));
        }
        // dd($triggers);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $formData = $request->validate([
            // 'trigger_name' => 'required|unique:shopify_triggers,user_id,'. Auth::id(),
            'trigger_name'      => 'required|string|unique:shopify_triggers,trigger_name,NULL,id,user_id,'.Auth::id(),
        ]);

        $addTrigger = new ShopifyTrigger();
        $addTrigger->user_id = Auth::id();
        $addTrigger->trigger_name = $request->trigger_name;

        $addTrigger->save();
        if($request->trigger_name == "First Order Placed"){
            $shop = Auth::user();
            $address = env('APP_URL')."api/shopifyOrders";    
            $topic = 'orders/create';
            $format = 'json';
            $api_version = '2020-10';
            
            $orders_create_webhook= $shop->api()->rest('post', '/admin/api/2020-10/webhooks.json',
            ['webhook' => 
                ['topic' => $topic,
                'address' =>  $address,
                'format' => $format
                ]
            ]);  
            $orders_create_webhook2= $shop->api()->rest('get', '/admin/api/2020-10/webhooks.json')['body']['webhooks'][0]->id;
           
            $addWebhook = new ShopifyWebhook();
            $addWebhook->user_id = Auth::id();
            $addWebhook->webhook_topic = $topic;
            $addWebhook->webhook_address = $address;
            $addWebhook->webhook_formate = $format;
            $addWebhook->webhook_id = $orders_create_webhook2;
            $addWebhook->save();
             
       
        }
        return back()->with('success', 'Trigger and webhook has been added');


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ShopifyTrigger  $shopifyTrigger
     * @return \Illuminate\Http\Response
     */
    public function show(ShopifyTrigger $shopifyTrigger)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ShopifyTrigger  $shopifyTrigger
     * @return \Illuminate\Http\Response
     */
    public function edit(ShopifyTrigger $shopifyTrigger)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ShopifyTrigger  $shopifyTrigger
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $formData = $request->validate([
            'trigger_card' => '',
            'trigger_message' => 'required|string',
            'trigger_signoff' => 'required|string',
            'trigger_handwriting_style' => 'required|string',
            'trigger_insert' => 'required|string',
            'trigger_gift_card' => 'required|string',
            'trigger_status' => 'string'
        ]);

        $formData['user_id'] = Auth::id();

        

        if($request->trigger_card){

            $formData['trigger_card'] = $request->trigger_card;

        } else{

            $formData['trigger_card'] = $request->old_trigger_card;
        }


        ShopifyTrigger::whereId($id)->update($formData);
        return back()->with('update', 'Trigger has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ShopifyTrigger  $shopifyTrigger
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $userid =  Auth::id();
        $trigger = DB::table('shopify_triggers')->where('id', $id)->delete();
        $webhookdetails= DB::table('shopifywebhook')->where('webhook_topic','orders/create')->first();

     
        $webhook_details= DB::table('shopifywebhook')->where([   
            ['user_id', '=', $userid],
            ['webhook_topic', '=', 'orders/create']
        ])->first();
        $deleteid =   $webhook_details->webhook_id;
        $shop = Auth::user();
        $orders_create_webhook2= $shop->api()->rest('delete', '/admin/api/2020-10/webhooks/'.$deleteid.'.json');
        
        $trigger = DB::table('shopifywebhook')->where([   
            ['user_id', '=', $userid],
            ['webhook_topic', '=', 'orders/create']
        ])->delete();
        return back()->with('delete', 'Trigger has been deleted');
    }
}
