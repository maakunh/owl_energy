<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Reponse;
use App\energy_history;
use App\User;

class HistoryAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

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
        $user_count = User::where('email', $request->email)->where('password', $request->apikey)->count();
        
        if($user_count == 1){
                $histories_count  = energy_history::where('addr', $request->addr)->where('timestamp', $request->timestamp)->count();
                if($histories_count == 0){
                    //追加処理
                    $histories = new energy_history;
                    $histories->addr = $request->addr;
                    $histories->year = $request->year;
                    $histories->month = $request->month;
                    $histories->day = $request->day;
                    $histories->hour = $request->hour;
                    $histories->min = $request->min;
                    $histories->ch1_amps_avg = $request->ch1_amps_avg;
                    $histories->ch1_kw_avg = $request->ch1_kw_avg;
                    $histories->ch1_kwh = ($request->ch1_kw_avg)*60/1000;
                    $histories->ghg = $request->ghg;
                    $histories->cost = $request->cost;
                    $histories->ch1_amps_min = $request->ch1_amps_min;
                    $histories->ch1_amps_max = $request->ch1_amps_max;
                    $histories->ch1_kw_min = $request->ch1_kw_min;
                    $histories->ch1_kw_max = $request->ch1_kw_max;
                    $histories->dt = $request->dt;
                    $histories->timestamp = $request->timestamp;
                    $histories->email = $request->email;
                    $histories->save(); 
                    $response = response("OK_CRE", 201);
                }else{
                    //更新処理
                    energy_history::where('addr', $request->addr)->where('timestamp', $request->timestamp)->where('email', $request->email)
                                    ->update(['ch1_amps_avg' => $request->ch1_amps_avg, 'ch1_kw_avg' => $request->ch1_kw_avg,'ch1_kwh' => ($request->ch1_kw_avg)*60/1000,'ghg' => $request->ghg,'cost' => $request->cost,'ch1_amps_min' => $request->ch1_amps_min,'ch1_amps_max' => $request->ch1_amps_max,'ch1_kw_min' => $request->ch1_kw_min,'ch1_kw_max' => $request->ch1_kw_max]);
                    $response = response("OK_UPD", 201);    
                }       
                return $response;
        }else{
            return response("NG_User", 202);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
