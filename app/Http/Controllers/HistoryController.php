<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\energy_history;
use Carbon\Carbon;
use Validator;
use Illuminate\Support\Facades\Auth;
use App\owl_setting;
use App\owl_info;

class HistoryController extends Controller
{
    //認証画面
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(Request $request){

        $owl_setting_count = owl_setting::where('email', Auth::user()->email)->count();
        if($owl_setting_count == 0){
            $input_addr = 0;
            $input_tarif_base = 0;
            $input_tarif_1 = 0;
            $input_tarif_2 = 0;
            $input_tarif_3 = 0;
            $input_fuel_adj = 0;
            
        }else{
            $owl_settings = owl_setting::where('email', Auth::user()->email)->get();
            foreach($owl_settings as $owl_setting){
                
            }        
            $input_addr = $owl_setting->addr;
            $input_tarif_base = $owl_setting->tarif_base;
            $input_tarif_1 = $owl_setting->tarif_1;
            $input_tarif_2 = $owl_setting->tarif_2;
            $input_tarif_3 = $owl_setting->tarif_3;
            $input_fuel_adj = $owl_setting->fuel_adj;
        }
        //$input_addr = config('owl_energy.addr');
        
        //情報パネル
        $owl_infos = owl_info::where('flg', 1)->orderby('inputdate','desc')->get();
        

             //バリデーション
            $validator = Validator::make($request->all(), [
            'item_date' => 'date',
            ]);

        $inputdate= $request->item_date;
        //echo $input_addr." ".$inputdate;   //デバッグ 
            //バリデーション： エラー
            if ($validator->fails()) {
                return redirect('/')
                    ->withInput()
                    ->withErrors($validator); 
            }
                $objinputdate = new Carbon($inputdate);
                $objinputdate = $objinputdate->addHour(config('owl_energy.TIMEZONE'));
                $input_year = $objinputdate->year;
                $input_month = $objinputdate->month;
                $input_day = $objinputdate->day;
                $inputdate = $input_year.'-'.$input_month.'-'.$input_day;
                $input_timestamp0 = $input_year.substr("0".$input_month, -2).substr("0".$input_day, -2);
                $input_timestamp1 = $input_year.substr("0".$input_month, -2);

                $yesterday = $objinputdate->subDay();
                $y_year = $yesterday->year;
                $y_month = $yesterday->month;
                $y_day = $yesterday->day;
                
                $y_timestamp0 = $y_year.substr("0".$y_month, -2).substr("0".$y_day, -2);
                
                $objinputdate = new Carbon($inputdate);
                $objinputdate = $objinputdate->addHour(config('owl_energy.TIMEZONE'));
                $yesterday1 = $objinputdate->subMonth();
                $y_year1 = $yesterday1->year;
                $y_month1 = $yesterday1->month;
                
                $y_timestamp1 = $y_year1.substr("0".$y_month1, -2);
                
                //消費電力量計算
                for($hour = 0; $hour < 24; $hour++){
                    $yday_kwh_sum = 0;
                    $tday_kwh_sum = 0;
                    for($min = 0; $min <= 59; $min++){
                        $y_timestamp = $y_timestamp0.substr("0".$hour, -2).substr("0".$min, -2);
                        $arrays = energy_history::where('addr', $input_addr)->where('timestamp', $y_timestamp)->where('email', Auth::user()->email)->get(['ch1_kwh']);
                        foreach($arrays as $array){
                            $yday_kwh = $array -> ch1_kwh;
                        }

                        $arrays_count = energy_history::where('addr', $input_addr)->where('timestamp', $y_timestamp)->where('email', Auth::user()->email)->count();
                        if($arrays_count == 0){
                                $yday_kwh = 0;
                        }

                        $input_timestamp = $input_timestamp0.substr("0".$hour, -2).substr("0".$min, -2);
                        $arrays = energy_history::where('addr', $input_addr)->where('timestamp', $input_timestamp)->where('email', Auth::user()->email)->get(['ch1_kwh']);
                        foreach($arrays as $array){
                            $tday_kwh = $array -> ch1_kwh;
                        }
                        $arrays_count = energy_history::where('addr', $input_addr)->where('timestamp', $input_timestamp)->where('email', Auth::user()->email)->count();
                        if($arrays_count == 0){
                                $tday_kwh = 0;
                        }
                        $graph_histories[] = array('hour' => substr('0'.$hour, -2), 'min' => substr('0'.$min, -2), 'yday_kwh' => $yday_kwh, 'tday_kwh' => $tday_kwh); //１分毎の消費電力量
                        $yday_kwh_sum = $yday_kwh_sum + $yday_kwh/60;
                        $tday_kwh_sum = $tday_kwh_sum + $tday_kwh/60;
                     }
                     $graph_histories_h[] = array('hour' => $hour, 'yday_kwh' => $yday_kwh_sum, 'tday_kwh' => $tday_kwh_sum); //１時間毎の消費電力量
                }
                
                $yday_kwh_sum_all = 0;
                $tday_kwh_sum_all = 0;
                for($day = 1; $day <= 31; $day++){
                    $yday_kwh_sum = 0;
                    $tday_kwh_sum = 0;
                    $arrays = energy_history::where('addr', $input_addr)->where('email', Auth::user()->email)->wherebetween('timestamp' ,array($y_timestamp1.substr("0".$day, -2)."0000", $y_timestamp1.substr("0".$day, -2)."2359"))->get(['ch1_kwh']);
                    foreach($arrays as $array){
                        $yday_kwh_sum = $yday_kwh_sum + $array -> ch1_kwh;
                    } 
                    //$arrays_count = energy_history::where('addr', $input_addr)->where('email', Auth::user()->email)->wherebetween('timestamp' ,array($y_timestamp1.substr("0".$day, -2)."0000", $y_timestamp1.substr("0".$day, -2)."2359"))->count();
                    //if($arrays_count == 0){
                    //    $yday_kwh_sum = 0;
                    //} 
                    
                    $arrays = energy_history::where('addr', $input_addr)->where('email', Auth::user()->email)->wherebetween('timestamp' ,array($input_timestamp1.substr("0".$day, -2)."0000", $input_timestamp1.substr("0".$day, -2)."2359"))->get(['ch1_kwh']);
                    foreach($arrays as $array){
                        $tday_kwh_sum = $tday_kwh_sum + $array -> ch1_kwh;
                    } 
                    //$arrays_count = energy_history::where('addr', $input_addr)->where('email', Auth::user()->email)->wherebetween('timestamp' ,array($input_timestamp1.substr("0".$day, -2)."0000", $input_timestamp1.substr("0".$day, -2)."2359"))->count();
                    //if($arrays_count == 0){
                    //    $tday_kwh_sum = 0;
                    //}
                    $graph_histories_m[] = array('day' => $day, 'yday_kwh' => $yday_kwh_sum/60, 'tday_kwh' => $tday_kwh_sum/60);
                    $yday_kwh_sum_all = $yday_kwh_sum_all + $yday_kwh_sum/60;
                    $tday_kwh_sum_all = $tday_kwh_sum_all + $tday_kwh_sum/60;
                } 
                
                $tarif_base = $input_tarif_base; //基本料金
                
                if($yday_kwh_sum_all <= 120){
                    $y_tarif1 = $yday_kwh_sum_all*$input_tarif_1; //1段料金
                    $y_tarif2 = 0;
                    $y_tarif3 = 0;
                }else{
                    if($yday_kwh_sum_all <= 300){
                        $y_tarif1 = 120*$input_tarif_1;
                        $y_tarif2 = ($yday_kwh_sum_all - 120)*$input_tarif_2; //2段料金
                        $y_tarif3 = 0;
                    }else{
                        $y_tarif1 = 120*$input_tarif_1;
                        $y_tarif2 = (300 - 120)*$input_tarif_2;
                        $y_tarif3 = ($yday_kwh_sum_all - 300)*$input_tarif_3 - 120*$input_tarif_2; //3段料金
                    }
                    
                }
                $y_tarif_fuel_adj = $yday_kwh_sum_all*$input_fuel_adj;//燃料調整費

                $y_tarif_total = $tarif_base + $y_tarif1 + $y_tarif2 + $y_tarif3 + $y_tarif_fuel_adj;

               if($tday_kwh_sum_all <= 120){
                    $t_tarif1 = $tday_kwh_sum_all*$input_tarif_1; //1段料金
                    $t_tarif2 = 0;
                    $t_tarif3 = 0;
                }else{
                    if($tday_kwh_sum_all <= 300){
                        $t_tarif1 = 120*$input_tarif_1;
                        $t_tarif2 = ($tday_kwh_sum_all - 120)*$input_tarif_2; //2段料金
                        $t_tarif3 = 0;
                    }else{
                        $t_tarif1 = 120*20.68;
                        $t_tarif2 = (300 - 120)*25.08;
                        $t_tarif3 = ($tday_kwh_sum_all - 300)*$input_tarif_3 - 120*$input_tarif_2; //3段料金
                    }
                }
                $t_tarif_fuel_adj = $tday_kwh_sum_all*$input_fuel_adj;//燃料費調整額

                $t_tarif_total = $tarif_base + $t_tarif1 + $t_tarif2 + $t_tarif3 + $t_tarif_fuel_adj;

                return view('history',['graph_histories' => $graph_histories , 
                'graph_histories_h' => $graph_histories_h, 
                'graph_histories_m' => $graph_histories_m, 
                'inputdate' => $inputdate, 
                'yday_kwh_sum_all' => $yday_kwh_sum_all, 
                'tday_kwh_sum_all'=> $tday_kwh_sum_all,
                'tarif_base' => $tarif_base,
                'y_tarif1' => $y_tarif1,
                'y_tarif2' => $y_tarif2,
                'y_tarif3' => $y_tarif3,
                'y_tarif_total' => $y_tarif_total,
                'y_tarif_fuel_adj' => $y_tarif_fuel_adj,
                't_tarif1' => $t_tarif1,
                't_tarif2' => $t_tarif2,
                't_tarif3' => $t_tarif3,
                't_tarif_total' => $t_tarif_total,
                't_tarif_fuel_adj' => $t_tarif_fuel_adj,
                'addr' => $input_addr,
                'owl_infos' => $owl_infos,
                ]);
   }
    

    public function user(){
        return view('user');
    }
    
    public function owl_setting(){
        $owl_setting_count = owl_setting::where('email', Auth::user()->email)->count();
        if($owl_setting_count ==0){
            $message = config('owl_energy.MSG003');
        }else{
            $message = "";
        }
        $owl_settings = owl_setting::where('email', Auth::user()->email)->get();
        return view('owl_setting', ['owl_settings' => $owl_settings, 'message' => $message]);
    }
    
   public function owl_setting_edit(Request $request){
        $owl_settings_count = owl_setting::where('email', Auth::user()->email)->count();
        if($owl_settings_count ==0){
                    $owl_settings = new owl_setting;
                    $owl_settings->email = Auth::user()->email;
                    $owl_settings->addr = $request->item_addr;
                    $owl_settings->tarif_base = $request->item_tarif_base;
                    $owl_settings->tarif_1 = $request->item_tarif_1;
                    $owl_settings->tarif_2 = $request->item_tarif_2;
                    $owl_settings->tarif_3 = $request->item_tarif_3;
                    $owl_settings->fuel_adj = $request->item_fuel_adj;
                    $owl_settings->save(); 
                    $message = config('owl_energy.MSG001');
        }else{
                    owl_setting::where('email', Auth::user()->email)
                                    ->update(['addr' => $request->item_addr, 'tarif_base' => $request->item_tarif_base, 'tarif_1' => $request->item_tarif_1, 'tarif_2' => $request->item_tarif_2, 'tarif_3' => $request->item_tarif_3, 'fuel_adj' => $request->item_fuel_adj]);
                    $message = config('owl_energy.MSG002');
        }
        $owl_settings = owl_setting::where('email', Auth::user()->email)->get();

         return view('owl_setting', ['owl_settings' => $owl_settings, 'message' => $message]);
    }

}