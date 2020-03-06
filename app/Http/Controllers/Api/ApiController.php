<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Promo;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function create($sale_size,$max_count,$type){
        $status = false;
        $promo = null;
        if($sale_size && $max_count && $type && is_numeric($sale_size) && is_numeric($max_count)){
            $chars = '0123456789bcdfghjklmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ';
            $type1 = "";
            $type2 = "";
                for ($i = 0; $i < 8; $i++) {
                    $type2 .= $chars[mt_rand(0, strlen($chars)-1)];
                }
                for ($i = 0; $i < 8; $i++) {
                    $type1 .= rand(0,9);
                }
            if($type == '1'){
                $promo = $type1;
            }elseif($type == '2'){
                $promo = $type2;
            }
            $check_unique = Promo::where('promo',$promo)->first();
            if(!$check_unique && $promo != null){
                $status = true;
                Promo::create([
                   'sale_size'=> $sale_size,
                   'max_count'=> $max_count,
                   'format'=> $type,
                   'promo'=>$promo
                ]);
            }
        }else{
            $status = false;
        }

        return response()->json([
            'promo'=>$promo,
            'status'=>$status,
        ]);
    }
    public function use($promo){
        $find_promo = Promo::where('promo',$promo)->first();
        if($find_promo){
            if($find_promo->max_count > $find_promo->use_count){
                $find_promo->use_count = $find_promo->use_count + 1;
                $find_promo->save();
                return response()->json([
                    'result' => $find_promo->sale_size,
                    'info'=> 'In reserve '. ($find_promo->max_count - $find_promo->use_count)
                ]);
            }else{
                $result =  'Promo code expired';
            }
        }else{
                $result =  'Promo code is not found';
        }
        return response()->json([
            'result' => $result,
        ]);
    }
}
