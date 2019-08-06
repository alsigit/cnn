<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\alfabet;
use Illuminate\Support\Facades\DB;

class data_huruf_controller extends Controller
{
  public function alphabet_data(){
    return DB::table("view_data_huruf")->select("no","huruf","biner","imgname")
    ->paginate(100);
  }

  public function train_null(){
    //YANG INI DISESUAIKAN DENGAN YANG ADA DI FUNGSI proses_pelatihan, KALAU JUMLAH DATA YANG DIAMBIL 62, LIAT DI DB, HARUS 62 JUGA, LIAT DI VIEWNYA
    return DB::table('view_train_null_62')->inRandomOrder()->get();
  }

  public function train_notnull($offset){
    return alfabet::select("huruf")
    ->whereNotNULL("train")->offset($offset)->limit(500)->get();
  }

  public function delete_alphabet($no){
    DB::beginTransaction();
    try {
      $data = DB::table("view_data_huruf")->select("imgname")->where("no",$no)->first();
      alfabet::where("no",$no)->delete();
      unlink(public_path('huruf/'.$data->imgname.".png"));
      DB::commit();
      session()->flash('type','success');
      session()->flash('title','Berhasil');
      session()->flash('message','Data huruf berhasil dihapus');
    } catch (\Exception $e) {
      DB::rollback();
      session()->flash('type','error');
      session()->flash('title','Error');
      session()->flash('message','Data huruf gagal dihapus');
    }
  }

  public function save_changes($no,$huruf,$imgname){
    $huruf = strip_tags($huruf);
    if ($huruf!="") {
      DB::beginTransaction();
      try {
        DB::select("CALL simpan_perubahan_huruf(?,?)",array($no,$huruf));
        $data = DB::table("view_data_huruf")->select("imgname")->where("no",$no)->first();
        rename(public_path("huruf/$imgname.png"),public_path("huruf/$data->imgname.png"));
        DB::commit();
        session()->flash('type','success');
        session()->flash('title','Berhasil');
        session()->flash('message','Data huruf berhasil diubah');
      } catch (\Exception $e) {
        DB::rollback();
        session()->flash('type','error');
        session()->flash('title','Error');
        session()->flash('message','Data huruf gagal diubah');
      }
    }
  }
}
