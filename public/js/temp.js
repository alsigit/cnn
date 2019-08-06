function clears(){
  $("#grayscale tr td,#integral tr td").removeClass("yellow");
  document.getElementById('cari').value='';
  document.getElementById("hasil_cari").innerHTML='';
}

function clears_gray(){
  $("#gray tr td,#matriks tr td").removeClass("yellow");
  document.getElementById('cari').value='';
  document.getElementById("hasil_cari").innerHTML='';
}

function clears_smooth(){
  $("#integral tr td,#smooth tr td").removeClass("yellow");
  document.getElementById('cari').value='';
  document.getElementById("hasil_cari").innerHTML='';
}

function clears_threshold(){
  $("#threshold tr td,#smooth tr td").removeClass("yellow");
  document.getElementById('cari').value='';
  document.getElementById("hasil_cari").innerHTML='';
}

function cari_perhitungan_threshold(){
  val = document.getElementById("cari").value;
  sval = val.split(",");
  str = "<b>Diketahui</b><br>dx = "+document.getElementById("dx").innerHTML+"<br>"+
        "dy = "+document.getElementById("dy").innerHTML+"<br>"+
        "nr = "+document.getElementById("nr").innerHTML+"<br>"+
        "nc = "+document.getElementById("nc").innerHTML+"<br>";
  str += document.getElementById("divmean"+sval[0]+","+sval[1]).innerHTML;
  $("#threshold tr td,#smooth tr td").removeClass("yellow");
  document.getElementById("hasil_cari").innerHTML = str;
  document.getElementById("I"+sval[0]+","+sval[1]).classList.add("yellow");
  document.getElementById("thresholdI"+sval[0]+","+sval[1]).classList.add("yellow");
}

function cari_perhitungan_smooth(){
  val = document.getElementById("cari").value;
  sval = val.split(",");
  str = "<b>Diketahui</b><br>dx = "+document.getElementById("dx").innerHTML+"<br>"+
        "dy = "+document.getElementById("dy").innerHTML+"<br>"+
        "nr = "+document.getElementById("nr").innerHTML+"<br>"+
        "nc = "+document.getElementById("nc").innerHTML+"<br>";
  str += document.getElementById("divmean"+sval[0]+","+sval[1]).innerHTML;
  $("#integral tr td,#smooth tr td").removeClass("yellow");
  document.getElementById("hasil_cari").innerHTML = str;
  document.getElementById("g"+sval[0]+","+sval[1]).classList.add("yellow");
  document.getElementById("hasilI"+sval[0]+","+sval[1]).classList.add("yellow");
}

function cari_hitung_gray(){
  val = document.getElementById("cari").value;
  sval = val.split(",");
  sval[0] = parseInt(sval[0]);
  sval[1] = parseInt(sval[1]);
  $("#grayscale tr td,#integral tr td").removeClass("yellow");
  document.getElementById("mpx"+sval[0]+","+sval[1]).classList.add("yellow");
  document.getElementById("g"+sval[0]+","+sval[1]).classList.add("yellow");
  str="<b>Matriks RGB mpx("+sval[0]+","+sval[1]+")</b><br>";
  str+="<table><tr><th>mpx(x,y)</th><th>"+sval[1]+"</th></tr>";
  str+="<tr><th>"+sval[0]+"</th><td>"+document.getElementById("mpx"+sval[0]+","+sval[1]).innerHTML+"</td></tr></table><br>";
  str+=document.getElementById("I("+sval[0]+","+sval[1]+")c").innerHTML;
  document.getElementById("hasil_cari").innerHTML = str;
}

function cari_perhitungan(){
  val = document.getElementById("cari").value;
  sval = val.split(",");
  sval[0] = parseInt(sval[0]);
  sval[1] = parseInt(sval[1]);
  $("#grayscale tr td,#integral tr td").removeClass("yellow");
  document.getElementById("i"+sval[0]+","+sval[1]).classList.add("yellow");
  document.getElementById("hasilg"+sval[0]+","+sval[1]).classList.add("yellow");
  str = "<b><i>I("+sval[0]+","+sval[1]+")</i> = </b>"+document.getElementById("i"+sval[0]+","+sval[1]).innerHTML+"<br>";
  str+= document.getElementById("g"+sval[0]+","+sval[1]+"c").innerHTML;
  str += "<b>Matriks Citra Integral</b><table>";
  switch (true) {
    case (sval[0]==0 && sval[1]==0):
      str+="<tr><th>g(x,y)</th><th>0</th></tr>"+
           "<tr><th>0</th><td>"+document.getElementById("i0,0").innerHTML+"</td></tr>";
    break;
    case (sval[0]==0 && sval[1]>=1):
      str+="<tr><th>g(x,y)</th><th>"+(sval[1]-1)+"</th><th>"+sval[1]+"</th></tr>"+
           "<tr><th>0</th><td>"+document.getElementById("hasilg0,"+(sval[1]-1)).innerHTML+"</td><td></td></tr>";
    break;
    case (sval[1]==0):
      str+="<tr><th>g(x,y)</th><th>0</th></tr>"+
           "<tr><th>"+(sval[0]-1)+"</th><td>"+document.getElementById("hasilg"+(sval[0]-1)+",0").innerHTML+"</td></tr>"+
           "<tr><th>"+sval[0]+"</th><td></td></tr>";
    break;
    default :
      str+= "<tr><th>g(x,y)</th><th>"+(sval[1]-1)+"</th><th>"+(sval[1])+"</th></tr>"+
          "<tr>"+
            "<th>"+(sval[0]-1)+"</th>"+
            "<td>"+document.getElementById("hasilg"+(sval[0]-1)+","+(sval[1]-1)).innerHTML+"</td>"+
            "<td>"+document.getElementById("hasilg"+(sval[0]-1)+","+sval[1]).innerHTML+"</td>"+
          "<tr>"+
            "<th>"+sval[0]+"</th>"+
            "<td>"+document.getElementById("hasilg"+sval[0]+","+(sval[1]-1)).innerHTML+"</td>"+
            "<td></td></tr>"+
           "</tr>";
  }
  str += "</table><br>";
  document.getElementById("hasil_cari").innerHTML = str;
}
