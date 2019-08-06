<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\alfabet;

class cnn_controller extends Controller
{
  private $b=[[]], $w=[[]], $momentw=[[[]]],$momentb=[[]], $result='';
  private $layer_data = [], $ep=1, $tempep=1, $jumkelas = 62;
  private $a = 0.001, $beta = [0.9,0.999], $epsilon = 0.00000001;

  private function convolution($mpx,$konvolusi,$bias,$stride,$loop,$fa){
    $row_padding = intval(count($konvolusi[0][0])/2);
    $col_padding = intval(count($konvolusi[0][0][0])/2);
    for ($iloop=0;$iloop<$loop;$iloop++) {
      $jumlah_mpx = count($mpx);
      for ($n2=0;$n2<count($konvolusi[0]);$n2++) {
        //proses konvolusi
        for ($n1=0;$n1<$jumlah_mpx;$n1++) {
          $filter_matrix = [];
          $filter_baris = 0;
          for ($baris=$row_padding;$baris<(count($mpx[$n1])-$row_padding);$baris+=$stride) {
            $filter_matrix[$filter_baris] = [];
            $filter_kolom = 0;
            for ($kolom=$col_padding;$kolom<(count($mpx[$n1][$baris])-$col_padding);$kolom+=$stride) {
              $hitung = 0;
              for ($i=0;$i<count($konvolusi[$n1][$n2]);$i++) {
                $ccc = 0;
                for ($j=0;$j<count($konvolusi[$n1][$n2][$i]);$j++) {
                  $hitung += $konvolusi[$n1][$n2][$i][$j]*$mpx[$n1][($baris+($i-$row_padding))][($kolom+($j-$col_padding))]+$bias[$n2];
                  $ccc += $konvolusi[$n1][$n2][$i][$j]*$mpx[$n1][($baris+($i-$row_padding))][($kolom+($j-$col_padding))]+$bias[$n2];
                }//end j
              }//end i
              $filter_matrix[$filter_baris][$filter_kolom++] = $hitung;
            }//endkolom
            $filter_baris++;
          }//endbaris
          array_push($mpx,$filter_matrix);
        }//endn1
      }//endn2
      for ($n1=0;$n1<$jumlah_mpx;$n1++) {
        array_shift($mpx);
      }
      if ($jumlah_mpx>1) {
        $nkonv = count($konvolusi[0]);
        $filter_matrix = [];
        $position = 0;
        for ($i=0;$i<$nkonv;$i++) {
          array_push($filter_matrix,$mpx[$position]);
          for ($j=$position+1;$j<$position+$jumlah_mpx;$j++) {
            for ($k=0;$k<count($mpx[$j]);$k++) {
              for ($l=0;$l<count($mpx[$j][$k]);$l++) {
                $filter_matrix[$i][$k][$l] += $mpx[$j][$k][$l];
              }
            }
          }
          $position += $jumlah_mpx;
        }
        $mpx = $filter_matrix;
      }
      array_push($this->layer_data,$mpx);
      for ($i=0;$i<count($mpx);$i++) {
        for ($j=0;$j<count($mpx[$i]);$j++) {
          for ($k=0;$k<count($mpx[$i][$j]);$k++) {
            if ($fa=='sigmoid') {
              $mpx[$i][$j][$k] = 1/(1+exp(-$mpx[$i][$j][$k]));
            }else{
              if ($mpx[$i][$j][$k]<0) {
                switch ($fa) {
                  case 'RReLU':
                    do {
                      $l = rand(3,8);
                      $u = rand(3,8);
                    } while ($l>=$u);
                    $mpx[$i][$j][$k] = ($mpx[$i][$j][$k]*2)/($l+$u);
                  break;
                  case 'LReLU':
                    $mpx[$i][$j][$k] = $mpx[$i][$j][$k]/5.5;
                  break;
                  case 'ReLU':
                    $mpx[$i][$j][$k] = 0;
                  break;
                }
              }
            }
          }
        }
      }
      array_push($this->layer_data,$mpx);
    }
    return $mpx;
  }

  private function avgpooling($mpx,$step){
    $pooling = [];
    for ($i=0;$i<count($mpx);$i++) {
      $pooling[$i] = [];
      $batas = ceil(count($mpx[$i])/$step);
      $lebih = $step-1;
      for ($j=0;$j<$batas;$j++) {
        $pooling[$i][$j] = [];
        for ($k=0;$k<$batas;$k++) {
          $hitung = 0;
          for ($l=0;$l<$step;$l++) {
            for ($m=0;$m<$step;$m++) {
              $hitung += $mpx[$i][ceil($step*$j-$l)+$lebih][ceil($step*$k-$m)+$lebih];
            }
          }
          $pooling[$i][$j][$k] = $hitung/pow($step,2);
        }
      }
    }
    array_push($this->layer_data,$pooling);
    return $pooling;
  }

  private function flatten($mpx){
    $flat = [];
    for ($i=0;$i<count($mpx);$i++) {
      for ($j=0;$j<count($mpx[$i]);$j++) {
        for ($k=0;$k<count($mpx[$i][$j]);$k++) {
          array_push($flat,$mpx[$i][$j][$k]);
        }
      }
    }
    array_push($this->layer_data,$flat);
    return $flat;
  }

  private function one_hot_label($kelas){
    switch ($kelas) {
      case '0':return 0;break;
      case '1':return 1;break;
      case '2':return 2;break;
      case '3':return 3;break;
      case '4':return 4;break;
      case '5':return 5;break;
      case '6':return 6;break;
      case '7':return 7;break;
      case '8':return 8;break;
      case '9':return 9;break;
      case 'A':return 10;break;
      case 'B':return 11;break;
      case 'C':return 12;break;
      case 'D':return 13;break;
      case 'E':return 14;break;
      case 'F':return 15;break;
      case 'G':return 16;break;
      case 'H':return 17;break;
      case 'I':return 18;break;
      case 'J':return 19;break;
      case 'K':return 20;break;
      case 'L':return 21;break;
      case 'M':return 22;break;
      case 'N':return 23;break;
      case 'O':return 24;break;
      case 'P':return 25;break;
      case 'Q':return 26;break;
      case 'R':return 27;break;
      case 'S':return 28;break;
      case 'T':return 29;break;
      case 'U':return 30;break;
      case 'V':return 31;break;
      case 'W':return 32;break;
      case 'X':return 33;break;
      case 'Y':return 34;break;
      case 'Z':return 35;break;
      case 'a':return 36;break;
      case 'b':return 37;break;
      case 'c':return 38;break;
      case 'd':return 39;break;
      case 'e':return 40;break;
      case 'f':return 41;break;
      case 'g':return 42;break;
      case 'h':return 43;break;
      case 'i':return 44;break;
      case 'j':return 45;break;
      case 'k':return 46;break;
      case 'l':return 47;break;
      case 'm':return 48;break;
      case 'n':return 49;break;
      case 'o':return 50;break;
      case 'p':return 51;break;
      case 'q':return 52;break;
      case 'r':return 53;break;
      case 's':return 54;break;
      case 't':return 55;break;
      case 'u':return 56;break;
      case 'v':return 57;break;
      case 'w':return 58;break;
      case 'x':return 59;break;
      case 'y':return 60;break;
      case 'z':return 61;break;
    }
  }

  private function softmax($mpx){
    $sumexp = 0;
    $o = [];
    for ($i=0;$i<count($mpx);$i++) {
      $sumexp += exp($mpx[$i]);
    }
    for ($i=0;$i<count($mpx);$i++) {
      $o[$i] = exp($mpx[$i])/$sumexp;
    }
    array_push($this->layer_data,$o);
    return $o;
  }

  private function decision($o_out){
    $max = 0;
    for ($i=0;$i<count($o_out);$i++) {
      if ($o_out[$i]>=0 && $max<$o_out[$i]) {
        $max = $o_out[$i];
        $urutan = $i;
      }
    }
    switch ($urutan) {
      case 0:$this->result.='0';break;
      case 1:$this->result.='1';break;
      case 2:$this->result.='2';break;
      case 3:$this->result.='3';break;
      case 4:$this->result.='4';break;
      case 5:$this->result.='5';break;
      case 6:$this->result.='6';break;
      case 7:$this->result.='7';break;
      case 8:$this->result.='8';break;
      case 9:$this->result.='9';break;
      case 10:$this->result.='A';break;
      case 11:$this->result.='B';break;
      case 12:$this->result.='C';break;
      case 13:$this->result.='D';break;
      case 14:$this->result.='E';break;
      case 15:$this->result.='F';break;
      case 16:$this->result.='G';break;
      case 17:$this->result.='H';break;
      case 18:$this->result.='I';break;
      case 19:$this->result.='J';break;
      case 20:$this->result.='K';break;
      case 21:$this->result.='L';break;
      case 22:$this->result.='M';break;
      case 23:$this->result.='N';break;
      case 24:$this->result.='O';break;
      case 25:$this->result.='P';break;
      case 26:$this->result.='Q';break;
      case 27:$this->result.='R';break;
      case 28:$this->result.='S';break;
      case 29:$this->result.='T';break;
      case 30:$this->result.='U';break;
      case 31:$this->result.='V';break;
      case 32:$this->result.='W';break;
      case 33:$this->result.='X';break;
      case 34:$this->result.='Y';break;
      case 35:$this->result.='Z';break;
      case 36:$this->result.='a';break;
      case 37:$this->result.='b';break;
      case 38:$this->result.='c';break;
      case 39:$this->result.='d';break;
      case 40:$this->result.='e';break;
      case 41:$this->result.='f';break;
      case 42:$this->result.='g';break;
      case 43:$this->result.='h';break;
      case 44:$this->result.='i';break;
      case 45:$this->result.='j';break;
      case 46:$this->result.='k';break;
      case 47:$this->result.='l';break;
      case 48:$this->result.='m';break;
      case 49:$this->result.='n';break;
      case 50:$this->result.='o';break;
      case 51:$this->result.='p';break;
      case 52:$this->result.='q';break;
      case 53:$this->result.='r';break;
      case 54:$this->result.='s';break;
      case 55:$this->result.='t';break;
      case 56:$this->result.='u';break;
      case 57:$this->result.='v';break;
      case 58:$this->result.='w';break;
      case 59:$this->result.='x';break;
      case 60:$this->result.='y';break;
      case 61:$this->result.='z';break;
    }
  }

  private function initialize($size_w,$n_wb,$n_wbo,$nflatten){
    //inisialisasi bobot weight 0
    for ($nw=0;$nw<count($size_w);$nw++) {
      //loop jumlah neuron
      for ($i=0;$i<$n_wb[$nw][0];$i++) {
        //loop baris
        $this->w[$nw][$i] = [];
        $this->momentw[$nw][$i] = [];
        for ($j=0;$j<$n_wb[$nw][1];$j++) {
          $this->w[$nw][$i][$j] = [];
          $this->momentw[$nw][$i][$j] = [];
          for ($k=0;$k<$size_w[$nw];$k++) {
            $this->w[$nw][$i][$j][$k] = [];
            $this->momentw[$nw][$i][$j][$k] = [];
            for ($l=0;$l<$size_w[$nw];$l++) {
              $this->w[$nw][$i][$j][$k][$l] = rand(1,9)/100;
              $this->momentw[$nw][$i][$j][$k][$l] = [0,0];
            }
          }
        }
      }
      for ($j=0;$j<$n_wb[$nw][1];$j++) {
        $this->b[$nw][$j] = 0;
        $this->momentb[$nw][$j] = [0,0];
      }
    }
    for ($i=0;$i<$nflatten;$i++) {
      $this->w[count($n_wb)][$i] = [];
      $this->momentw[count($n_wb)][$i] = [];
      for ($j=0;$j<$n_wbo;$j++) {
        // $this->w[count($n_wb)][$i][$j] = 0.1;
        $this->w[count($n_wb)][$i][$j] = rand(1,9)/100;
        $this->momentw[count($n_wb)][$i][$j] = [0,0];
      }
    }
    for ($i=0;$i<$n_wbo;$i++) {
      $this->b[count($n_wb)][$i] = 0;
      $this->momentb[count($n_wb)][$i] = [0,0];
    }
    file_put_contents(public_path('w.txt'),json_encode($this->w));
    file_put_contents(public_path('b.txt'),json_encode($this->b));
    file_put_contents(public_path('mw.txt'),json_encode($this->momentw));
    file_put_contents(public_path('mb.txt'),json_encode($this->momentb));
  }

  private function fully_connection($mpx,$nsize){
    $fully_matrix = [];
    $last = count($this->w)-1;
    for ($i=0;$i<$nsize;$i++) {
      $fully_matrix[$i] = 0;
      for ($j=0;$j<count($mpx);$j++) {
        $fully_matrix[$i] += $this->w[$last][$j][$i]*$mpx[$j]+$this->b[$last][$i];
      }
    }
    array_push($this->layer_data,$fully_matrix);
    return $fully_matrix;
  }

  private function dLoss_CE_SMX_O_in($P,$noutput,$kelas){
    $dE_o_in = [];
    for ($i=0;$i<$noutput;$i++) {
      if ($i!=$kelas) {
        $target = 0;
      }else{
        $target = 1;
      }
      $dE_o_in[$i] = round(($P[$i]-$target),6);
    }
    return $dE_o_in;
  }

  private function dLoss_input_mtx($citra_input,$citra_output,$w){
    //rotating
    $baris = count($w[0][0])-1;
    $kolom = count($w[0][0][0])-1;
    $temp_w = [];
    for ($asal=0;$asal<count($w);$asal++) {
      $temp_w[$asal] = [];
      for ($target=0;$target<count($w[$asal]);$target++) {
        $temp_w[$asal][$target] = [];
        for ($loopx=0;$loopx<=$baris;$loopx++) {
          $temp_w[$asal][$target][$loopx] = [];
          for ($loopy=0;$loopy<=$kolom;$loopy++) {
            $temp_w[$asal][$target][$loopx][$loopy] = $w[$asal][$target][$baris-$loopx][$kolom-$loopy];
          }
        }
      }
    }

    $dE_in = [];
    $padding = count($w[0][0])-1;
    for ($loopI=0;$loopI<count($citra_input);$loopI++) {
      $dE_in[$loopI] = [];
      for ($ix=0;$ix<count($citra_input[$loopI]);$ix++) {
        $dE_in[$loopI][$ix] = [];
        for ($iy=0;$iy<count($citra_input[$loopI][$ix]);$iy++) {
          $dE_in[$loopI][$ix][$iy] = 0;
          for ($loopO=0;$loopO<count($citra_output);$loopO++) {
            for ($loopWx=0;$loopWx<count($w[$loopI][$loopO]);$loopWx++) {
              $baris = $loopWx-$padding+$ix;
              if ($baris>=0 && $baris<count($citra_output[$loopO])) {
                for ($loopWy=0;$loopWy<count($w[$loopI][$loopO][$loopWx]);$loopWy++) {
                  $kolom = $loopWy-$padding+$iy;
                  if ($kolom>=0 && $kolom<count($citra_output[$loopO][0])) {
                    $dE_in[$loopI][$ix][$iy] += $citra_output[$loopO][$baris][$kolom]*$temp_w[$loopI][$loopO][$loopWx][$loopWy];
                  }
                }
              }
            }
          }
        }
      }
    }
    return $dE_in;
  }

  private function dLoss_conv_avg($dE_pool,$dFa,$fa,$ukuranpool){
    $dE_conv = [];
    $npx = count($dFa[0]);
    $pembagi = $ukuranpool;
    for ($i=0;$i<count($dFa);$i++) {
      $dE_conv[$i] = [];
      for ($j=0;$j<$npx;$j++) {
        $dE_conv[$i][$j] = [];
        for ($k=0;$k<$npx;$k++) {
          switch ($fa) {
            case 'sigmoid':
              $dE_conv[$i][$j][$k] = ($dE_pool[$i][intval($j/$pembagi)][intval($k/$pembagi)]/pow($pembagi,2)) * $dFa[$i][$j][$k] * (1-$dFa[$i][$j][$k]);
            break;
            case 'ReLU':
              if ($dFa[$i][$j][$k]>0) {
                $val = 1;
              }else{
                $val = 0;
              }
              $dE_conv[$i][$j][$k] = ($dE_pool[$i][intval($j/$pembagi)][intval($k/$pembagi)]/pow($pembagi,2)) * $val;
            break;
            case 'LReLU':
              if ($dFa[$i][$j][$k]>0) {
                $val = 1;
              }else{
                $val = 1/5.5;
              }
              $dE_conv[$i][$j][$k] = ($dE_pool[$i][intval($j/$pembagi)][intval($k/$pembagi)]/pow($pembagi,2)) * $val;
            break;
          }
        }
      }
    }
    return $dE_conv;
  }

  private function update_weight_convolution($no,$dE_conv,$pool,$epoch){
    $dE_w = [];
    for ($i=0;$i<count($this->w[$no]);$i++) { //loop asal
      $dE_w[$i] = [];
      for ($j=0;$j<count($this->w[$no][$i]);$j++) { //loop tujuan
        $dE_w[$i][$j] = [];
        $shiftx = 0;
        for ($k=0;$k<count($this->w[$no][$i][$j]);$k++) { //loop baris
          $dE_w[$i][$j][$k] = [];
          $shifty = 0;
          for ($l=0;$l<count($this->w[$no][$i][$j][$k]);$l++) { //loop kolom
            $dE_w[$i][$j][$k][$l] = 0;
            for ($m=0;$m<count($dE_conv[$j]);$m++) {
              $coba = 0;
              for ($n=0;$n<count($dE_conv[$j][$m]);$n++) {
                $dE_w[$i][$j][$k][$l] += $dE_conv[$j][$m][$n]*$pool[$i][$m+$shiftx][$n+$shifty];
              }
            }
            $shifty++;
            $this->w[$no][$i][$j][$k][$l] -= $this->a*$dE_w[$i][$j][$k][$l];
            // $this->w[$no][$i][$j][$k][$l] -= $this->adam_optimizer($dE_w[$i][$j][$k][$l],[2,$no,$i,$j,$k,$l]);
          }
          $shiftx++;
        }//end baris
      } //end tujuan
    }//end asal
  }

  private function update_bias_convolution($no,$dE_conv,$epoch){
    for ($i=0;$i<count($dE_conv);$i++) {
      $hitung = 0;
      for ($j=0;$j<count($dE_conv[$i]);$j++) {
        for ($k=0;$k<count($dE_conv[$i][$j]);$k++) {
          $hitung += $dE_conv[$i][$j][$k];
        }
      }
      $this->b[$no][$i] -= $this->a*$hitung;
      // $this->b[$no][$i] -= $this->adam_optimizer($hitung,[1,$no,$i]);
    }
  }


  //gak dipakai
  private function adam_optimizer($g,$sts){
    if ($sts[0]==1) {
      $this->momentb[$sts[1]][$sts[2]][0] = $this->beta[0]*$this->momentb[$sts[1]][$sts[2]][0]+(1-$this->beta[0])*$g;
      $this->momentb[$sts[1]][$sts[2]][1] = $this->beta[1]*$this->momentb[$sts[1]][$sts[2]][1]+(1-$this->beta[1])*pow($g,2);
      $at = $this->a*sqrt(1-pow($this->beta[1],$this->ep))/(1-pow($this->beta[0],$this->ep));
      return $at*$this->momentb[$sts[1]][$sts[2]][0]/(sqrt($this->momentb[$sts[1]][$sts[2]][1])+$this->epsilon);
    }else if($sts[0]==2){
      $this->momentw[$sts[1]][$sts[2]][$sts[3]][$sts[4]][$sts[5]][0] = $this->beta[0]*$this->momentw[$sts[1]][$sts[2]][$sts[3]][$sts[4]][$sts[5]][0]+(1-$this->beta[0])*$g;
      $this->momentw[$sts[1]][$sts[2]][$sts[3]][$sts[4]][$sts[5]][1] = $this->beta[1]*$this->momentw[$sts[1]][$sts[2]][$sts[3]][$sts[4]][$sts[5]][1]+(1-$this->beta[1])*pow($g,2);
      $at = $this->a*sqrt(1-pow($this->beta[1],$this->ep))/(1-pow($this->beta[0],$this->ep));
      return $at*$this->momentw[$sts[1]][$sts[2]][$sts[3]][$sts[4]][$sts[5]][0]/(sqrt($this->momentw[$sts[1]][$sts[2]][$sts[3]][$sts[4]][$sts[5]][1])+$this->epsilon);
    }else{
      $this->momentw[$sts[1]][$sts[2]][$sts[3]][0] = $this->beta[0]*$this->momentw[$sts[1]][$sts[2]][$sts[3]][0]+(1-$this->beta[0])*$g;
      $this->momentw[$sts[1]][$sts[2]][$sts[3]][1] = $this->beta[1]*$this->momentw[$sts[1]][$sts[2]][$sts[3]][1]+(1-$this->beta[1])*pow($g,2);
      $at = $this->a*sqrt(1-pow($this->beta[1],$this->ep))/(1-pow($this->beta[0],$this->ep));
      return $at*$this->momentw[$sts[1]][$sts[2]][$sts[3]][0]/(sqrt($this->momentw[$sts[1]][$sts[2]][$sts[3]][1])+$this->epsilon);
    }
  }

  private function cross_entropy_error($noclass){
    $err = 0;
    for ($i=0;$i<$jumkelas;$i++) {
      if ($i!=$noclass) {
        $err += log(1-$this->layer_data[9][$i]);
      }else{
        $err += log($this->layer_data[9][$i]);
      }
    }
    //SESUAIKAN DENGAN JUMLAH KELAS
    return $err/($jumkelas*-1);
  }

  public function deep_convolution($biner,$status){
    if (!isset($this->b[0][0])) {
      if (filesize("b.txt")>0 || filesize("w.txt")>0) {
      // if (filesize("b.txt")>0 || filesize("w.txt")>0 || filesize("mb.txt")>0 || filesize("mw.txt")>0) {
        $file = fopen("w.txt", "r") or die("Unable to open file!");
        $this->w = json_decode(fread($file,filesize("w.txt")));
        fclose($file);
        $file = fopen("b.txt", "r") or die("Unable to open file!");
        $this->b = json_decode(fread($file,filesize("b.txt")));
        fclose($file);
        //Kalau pake adam optimizer
        //ADAM
        $file = fopen("mw.txt", "r") or die("Unable to open file!");
        $this->momentw = json_decode(fread($file,filesize("mw.txt")));
        fclose($file);
        $file = fopen("mb.txt", "r") or die("Unable to open file!");
        $this->momentb = json_decode(fread($file,filesize("mb.txt")));
        fclose($file);
        $file = fopen("epoch.txt", "r") or die("Unable to open file!");
        $this->ep = json_decode(fread($file,filesize("epoch.txt")));
        fclose($file);
        $this->ep = intval($this->ep);
        $this->tempep = $this->ep;
        //END ADAM
      }else{
        //INISIALISASI NILAI BOBOT DAN BIAS, MOMENT BOBOT DAN MOMENT BIAS JIKA PAKE ADAM
        //PARAMETER PERTAMA UKURAN FITUR ATAU BOBOT [M X N], PARAMETER KEDUA UNTUK JUMLAH NEURON PADA HIDDEN LAYER 1 DAN 2 [[0,j],[j,k]]
        //PARAMETER KETIGA ADALAH JUMLAH NEURON PADA OUTPUT LAYER DISEBUT VARIABEL m
        //PARAMETER KE EMPAT ADALAH JUMLAH NEURON PADA TAHAP FLATTEN, TERGANTUNG DENGAN TOTAL VECTOR YANG DIHASILKAN
        //KETIKA TAHAP AKHIR LAYER KONVOLUSI. DISINI HASIL AKHIR DARI POOLING 4 X 4 = 16, DENGAN JUMLAH NEURON SEBANYAK 12
        //JADI 12 X 16 = 192
        $this->initialize([5,5],[[1,6],[6,12]],$jumkelas,192);
      }
    }else{
      //KALAU PAKE ADAM
      $this->ep = $this->tempep;
    }
    if ($status==0) {
      $error = [];
      $target_error = 0.00000001;
      $stop = 0;
      for ($epoch=1;$epoch<=1;$epoch++) {
        for ($ndata=0;$ndata<count($biner);$ndata++) {
          $this->layer_data = [];
          $this->layer_data[0] = $biner[$ndata][0]; //0
          //KALAU MAU NAMBAH ATAU NGURANGIN LAYER, BISA DENGAN COPY PASTE FUNGSI2 INI
          //SETIAP HASIL FUNGSI DI BAWAH INI DISIMPAN KE DALAM VAR $layer_data, JADI SESUAIKAN JUMLAH ARRAYNYA
          //PERHATIKAN ANGKA DI SETIAP FUNGSI DI BAWAH INI, ITU UNTUK KASIH KETERANGAN SUDAH BERAPA BANYAJ ARRAY MATRIKS PADA $layer_data
          $biner[$ndata][0] = $this->convolution($biner[$ndata][0],$this->w[0],$this->b[0],1,1,'LReLU');//1,2
          $biner[$ndata][0] = $this->avgpooling($biner[$ndata][0],2);//3
          $biner[$ndata][0] = $this->convolution($biner[$ndata][0],$this->w[1],$this->b[1],1,1,'LReLU');//4,5
          $biner[$ndata][0] = $this->avgpooling($biner[$ndata][0],2);//6
          $biner[$ndata][0] = $this->flatten($biner[$ndata][0]);//7
          $biner[$ndata][0] = $this->fully_connection($biner[$ndata][0],$jumkelas); //8
          $biner[$ndata][0] = $this->softmax($biner[$ndata][0]); //9
          $biner[$ndata][0] = $this->layer_data[0];
          $error[$ndata] = $this->cross_entropy_error($biner[$ndata][1]);
          if ($error[$ndata]>$target_error) {
            //parameter = probabilitas/softmax
            //62 DISITU, TERGANTUNG NEURON OUTPUT LAYER ATAU JUMLAH KELAS YANG AKAN DIKENALI
            $dE_o_in = $this->dLoss_CE_SMX_O_in($this->layer_data[9],$jumkelas,$biner[$ndata][1]);

            //PERHITUNGAN TURUNAN ERROR TERHADAP BOBOT DI FULLY CONNECTED
            $dE_w2 = [];
            for ($i=0;$i<count($this->layer_data[7]);$i++) {
              $dE_w2[$i] = [];
              $changeFully = 0;
              for ($j=0;$j<$jumkelas;$j++) {
                $dE_w2[$i][$j] = $dE_o_in[$j] * $this->layer_data[7][$i];
                $changeFully += $dE_o_in[$j] * $this->w[2][$i][$j];
                $this->w[2][$i][$j] -= $this->a*$dE_w2[$i][$j];
                // $this->w[2][$i][$j] -= $this->adam_optimizer($dE_w2[$i][$j],[3,2,$i,$j]);
              }
              $this->layer_data[7][$i] = $changeFully;
            }

            //PERHITUNGAN TURUNAN ERROR TERHADAP BIAS DI FULLY CONNECTED
            for ($i=0;$i<$jumkelas;$i++) {
              $this->b[2][$i] -= $this->a*$dE_o_in[$i];
              // $this->b[2][$i] -= $this->adam_optimizer($dE_o_in[$i],[1,2,$i]);
            }

            //reverse process from fully to pooling disingkat dengan langsung menghitung dE_pool
            //flatten to pool1
            $dE_pool1 = [];
            $npx = 0;
            for ($i=0;$i<count($this->layer_data[6]);$i++) {
              $dE_pool1[$i] = [];
              for ($j=0;$j<count($this->layer_data[6][0]);$j++) {
                $dE_pool1[$i][$j] = [];
                for ($k=0;$k<count($this->layer_data[6][0]);$k++) {
                  $dE_pool1[$i][$j][$k] = $this->layer_data[7][$npx++];
                }
              }
            }

            $dE_conv1 = $this->dLoss_conv_avg($dE_pool1,$this->layer_data[4],'LReLU',2);
            $dE_pool0 = $this->dLoss_input_mtx($this->layer_data[3],$dE_conv1,$this->w[1]);
            $this->update_weight_convolution(1,$dE_conv1,$this->layer_data[3],$epoch);
            $this->update_bias_convolution(1,$dE_conv1,$epoch);
            $dE_conv0 = $this->dLoss_conv_avg($dE_pool0,$this->layer_data[1],'LReLU',2);
            $this->update_weight_convolution(0,$dE_conv0,$this->layer_data[0],$epoch);
            $this->update_bias_convolution(0,$dE_conv0,$epoch);
          }
        }
        if ($stop==1) {
          break;
        }
        $mean = 0;
        for ($i=0;$i<count($biner);$i++) {
         $mean += $error[$i];
        }
        $mean/=count($biner);
        if ($mean<=$target_error) {
         break;
         $stop = 1;
        }
        $this->ep++;
      }
      $file = fopen(public_path('w.txt'), "w") or die("Unable to open file!");
      fwrite($file,json_encode($this->w));
      fclose($file);
      $file = fopen(public_path('b.txt'), "w") or die("Unable to open file!");
      fwrite($file,json_encode($this->b));
      fclose($file);
      $file = fopen(public_path('mw.txt'), "w") or die("Unable to open file!");
      fwrite($file,json_encode($this->momentw));
      fclose($file);
      $file = fopen(public_path('mb.txt'), "w") or die("Unable to open file!");
      fwrite($file,json_encode($this->momentb));
      fclose($file);
    }else{
      for ($ndata=0;$ndata<count($biner);$ndata++) {
        $this->layer_data = [[]];
        $this->layer_data[0] = $biner[$ndata];
        $biner[$ndata] = $this->convolution($biner[$ndata],$this->w[0],$this->b[0],1,1,'LReLU');//1,2
        $biner[$ndata] = $this->avgpooling($biner[$ndata],2);//3
        $biner[$ndata] = $this->convolution($biner[$ndata],$this->w[1],$this->b[1],1,1,'LReLU');//4,5
        $biner[$ndata] = $this->avgpooling($biner[$ndata],2);//6
        $biner[$ndata] = $this->flatten($biner[$ndata]);//7
        $biner[$ndata] = $this->fully_connection($biner[$ndata],$jumkelas); //8
        $biner[$ndata] = $this->softmax($biner[$ndata]); //9
        $this->decision($biner[$ndata]);
      }
      return $this->result;
    }
  }


  public function training(){
    $huruf = new data_huruf_controller;
    $loop = alfabet::whereNULL("train")->count();
    if ($loop>0) {
      //PERHATIKAN SINTAK DI BAWAH INI, TUJUANNYA ADALAH NGAMBIL SEBANYAK 62 DATA DARI DATABASE, DENGAN TOTAL DATA YANG ADA DIBAGI 62 JADI ADA BERAPA KALI PENGAMBILAN, ITU TUJUANNYA
      //PENYIMPANAN DATA DI DATABASE USAHAKAN BERURUTAN, SUPAYA SISTEM BELAJAR SECARA MERATA, TIDAK TUMPANG TINDIH, ANGKA 62 ITU BISA DIGANTI ASAL MERUPAKAN KELIPATAN 62, EX 124
      $loop = ceil($loop/62);
      for ($i=0;$i<$loop;$i++) {
        $biner = [];
        $nbiner = 0;
        $biners = $huruf->train_null();
        $idata = 0;
        foreach ($biners as $data) {
          $biner[$nbiner] = [];
          $biner[$nbiner][0] = [];
          $biner[$nbiner][0][0] = explode("|",$data->biner);
          for ($j=0;$j<count($biner[$nbiner][0][0]);$j++) {
            $biner[$nbiner][0][0][$j] = str_split($biner[$nbiner][0][0][$j]);
            for ($k=0;$k<count($biner[$nbiner][0][0][$j]);$k++) {
              $biner[$nbiner][0][0][$j][$k] = intval($biner[$nbiner][0][0][$j][$k]);
            }
          }
          $biner[$nbiner][1] = $this->one_hot_label($data->huruf);
          $nbiner++;
          DB::beginTransaction();
          try {
            alfabet::where("no",$data->no)->update(["train"=>1]);
            DB::commit();
          } catch (\Exception $e) {
            DB::rollback();
          }
        }
        $this->deep_convolution($biner,0);
      }
    }
  }
}
