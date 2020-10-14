<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use ShopModel;

class OrdersWebhookController extends Controller
{
    public function create(Request $request)
    {
        $user= Auth::user();
        // dd($user->name);
        $token = $request->session()->get('shopify_session_token');
        dd($token);
        $response = Http::withHeaders([
            'X-Shopify-Topic' => 'orders/create',
            'X-Shopify-Hmac-Sha256' => $token,
            'X-Shopify-Shop-Domain' => 'cloud1212.myshopify.com',
            'X-Shopify-API-Version' => '2020-07',
        ])->get('https://cloud1212.myshopify.com/admin/api/2020-07/webhooks.json');

        return $response;
    }

    public function registerOrdersWebhook()
    {
        $user= Auth::user();
        $accessToken = $request->session()->get('shopify_session_token');
        Shopify::setShopUrl($user->name)->setAccessToken($accessToken)->post("admin/webhooks.json", ['webhook' => 
            ['topic' => 'orders/create',
            'address' => 'https://2aea92beb016.ngrok.io/handwritten/webhook/orders-create',
            'format' => 'json'
            ]
        ]);
    }

    public function ordersCreated()
    {
        if (Shopify::verifyWebHook($data, $hmac)) {
            // do your stuffs here in background
            return response('Hello World', 200)
            ->header('Content-Type', 'text/plain');
        } else {
        return response('UnAuthorized', 401)
            ->header('Content-Type', 'text/plain');
        }
    }
}
