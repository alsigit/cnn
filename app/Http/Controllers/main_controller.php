<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\alfabet;

error_reporting(E_ALL & ~E_NOTICE);
session_start();
date_default_timezone_set('Asia/Jakarta');

class main_controller extends Controller
{
  public function index(){
    return view("index");
  }

  public function get_training_data(){
    $huruf = new data_huruf_controller;
    $datas = $huruf->alphabet_data();
    return view("Container.data-latih",compact("datas"));
  }

  public function get_alphabet_data(){
    $huruf = new data_huruf_controller;
    $datas = $huruf->alphabet_data();
    return view("Container.set-data-huruf",compact("datas"));
  }

  public function save_alphabet(Request $req){
    $i = 0;
    DB::beginTransaction();
    while (isset($req["huruf$i"])) {
      try {
        DB::select('CALL simpan_huruf(?,?)',array($req["huruf$i"],$req["biner$i"]));
        $j = alfabet::select("no_gambar as no")->where("huruf",$req["huruf$i"])->orderBy("no","desc")->first();
        $data = $req["imgbase64$i"];
        list($type, $data) = explode(';', $data);
        list(, $data)      = explode(',', $data);
        $data = base64_decode($data);
        file_put_contents(public_path("huruf/".$req["huruf$i"]."$j->no.png"), $data);
      } catch (\Exception $e) {
        DB::rollback();
        session()->flash('type','error');
        session()->flash('title','Error');
        session()->flash('message','Data huruf gagal disimpan');
        break;
        return view("message");
      }
      $i++;
    }
    session()->flash('type','success');
    session()->flash('title','Berhasil');
    session()->flash('message','Data huruf berhasil disimpan');
    DB::commit();
    echo "<script>$('#hasil-segmen').empty();</script>";
    return $this->get_alphabet_data();
  }

  public function testing(Request $req){
    $biner = json_decode($req->testing);
    $cnn = new cnn_controller;
    $hasil = $cnn->deep_convolution($biner,1);
    return view("Container.hasil-testing",compact("hasil"));
  }

  public function training(){
    $cnn = new cnn_controller;
    $cnn->training();
    $jumdata = alfabet::whereNotNULL("train")->count();
    $setting = (Object)array("nlayer"=>2,"jumdata"=>$jumdata);
    return view("Container.proses-training",compact("setting"));
  }

  public function save_changes($no,$imgname,$huruf,$hal){
    $objhuruf = new data_huruf_controller;
    $objhuruf->save_changes($no,$huruf,$imgname);
    return redirect('/more-data?page='.$hal);
  }

  public function delete_alphabet($no,$hal){
    $no = strip_tags($no);
    $huruf = new data_huruf_controller;
    $huruf->delete_alphabet($no);
    return redirect('/more-data?page='.$hal);
  }
}
