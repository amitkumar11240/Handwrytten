<?php

namespace App\Http\Controllers;

use App\HandwryttenApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class HandwryttenApiController extends Controller
{
    public function config()
    {
        return view('handwrytten-app.login');
    }

    public function login(Request $request)
    {
        $formData = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

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
        CURLOPT_POSTFIELDS => array('login' => $request->email,'password' => $request->password),
        CURLOPT_HTTPHEADER => array(
            "Accept: application/json",
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $data = json_decode($response);

        if($data->httpCode == '200'){
            $handwrytten = new HandwryttenApi();
            $handwrytten->user_id = Auth::id();
            $handwrytten->handwrytten_user_id = $data->user_id;
            $handwrytten->uid = $data->uid;
            $handwrytten->token = $data->token;
           
            $handwrytten->name = $request->name;
            $handwrytten->fullname = $data->fullname;
            $handwrytten->email = $data->email;
            $handwrytten->password = $request->password;
            
            $handwrytten->save();

            return redirect('/')->with('success', 'Successfully Login');
        } else{
            return back()->with('delete', 'Wrong Credentials');
        }


    }

    public function logout($id)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.handwrytten.com/v1/auth/logout",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_HTTPHEADER => array(
            "Accept: application/json",
            "Cookie: AWSELB=25F985570A460D9BBEA38280DDA53847DE6F34E7E66EA566814DDCE4DEB6BD89AFDDF7C082E2D610227F53ABEF03A16E51FED794EB2206DD8D99848C15BC0DE417C5A1043D; AWSELBCORS=25F985570A460D9BBEA38280DDA53847DE6F34E7E66EA566814DDCE4DEB6BD89AFDDF7C082E2D610227F53ABEF03A16E51FED794EB2206DD8D99848C15BC0DE417C5A1043D; PHPSESSID=v7me2ek64pv0c9uirn89854qv4"
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $handwrytten = DB::table('handwrytten_apis')->where('id', $id)->delete();
        // $affected = DB::table('handwrytten_apis')->where('user_id', $id)->update(['token' => '']);

        return back();

    }

    public function cardData(Request $request)
    {
        // return $request->id;

        $handwrytten = DB::table('handwrytten_apis')->where('user_id', '=', Auth::id())->first();
        $responseCategory = Http::withToken($handwrytten->token)->get("https://api.handwrytten.com/v1/cards/list?category_id=".$request->id."&with_images=&page=&lowres=");
        // $card = json_decode($responseCategory);
        return $responseCategory;
        // print_r($card);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\HandwryttenApi  $handwryttenApi
     * @return \Illuminate\Http\Response
     */
    public function show(HandwryttenApi $handwryttenApi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\HandwryttenApi  $handwryttenApi
     * @return \Illuminate\Http\Response
     */
    public function edit(HandwryttenApi $handwryttenApi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\HandwryttenApi  $handwryttenApi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HandwryttenApi $handwryttenApi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\HandwryttenApi  $handwryttenApi
     * @return \Illuminate\Http\Response
     */
    public function destroy(HandwryttenApi $handwryttenApi)
    {
        //
    }
}