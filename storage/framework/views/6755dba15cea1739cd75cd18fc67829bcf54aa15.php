<div class="panel-judul" target="pengolahan-data">
  <i class="fa fa-briefcase"></i><div>Pengolahan Data</div>
</div>
<div id="pengolahan-data" class="container slide">
  <div class="table">
    <div class="row">
      <div id="panel-upload" class="col2">
        <fieldset>
          <legend>Form Upload Gambar</legend>
          <div style="padding:10px;box-sizing:border-box;width:100%;background-color:#7a1212">
            <input style="width:200px" type="text" id="img-url" placeholder="Alamat gambar" readonly>
            <button type="button" name="button" title="Upload Gambar" id="upload" class="orange icon"><i class="fa fa-upload"></i></button>
            <button type="button" name="button" title="Proses Pengolahan Citra" id="proses" class="green icon"><i class=" fa fa-cogs"></i></button>
            <input type="number" id="nomorproses" value="">
            <input type="file" id="upload-file" class="hide">
          </div>
          <div style="width:100%;position:relative">
            <div id="panel-tbl-crop">
              <button type="button" name="button" class="icon" onclick="cut_image('img-upload','coordmap')"><i class="fa fa-scissors"></i></button>
              <button type="button" name="button" class="icon" onclick="clear_nodes('coordmap')"><i class="fa fa-times"></i></button>
            </div>
            <svg id="coordmap" class="noselect"></svg>
            <canvas id="img-upload" width="300px" height="200px" sts="0"></canvas>
          </div>
        </fieldset>
      </div>
      <div id="panel-hasil" class="col2">
        <form id="form-hasil-segmen" method="post">
          <fieldset style="overflow-y:auto">
            <legend>Segmentasi</legend>
            <?php echo e(csrf_field()); ?>

            <div id="hasil-segmen" class="table" style="width:100%;position:relative;">

            </div>
            <script type="text/javascript">
            document.getElementById("form-hasil-segmen").setAttribute("action",window.location.href);
            </script>
          </fieldset>
          <div id="panel-proses">
            <button class="orange icon" type="submit" id="simpan_huruf" form="form-hasil-segmen" name="button" title="Simpan seluruh huruf"><i class="fa fa-save"></i></button>
            <button class="cadetblue icon" type="button" form="form-hasil-segmen" id="delete_huruf" name="button" title="Hapus seluruh huruf"><i class="fa fa-trash"></i></button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<br>


<div class="panel-judul" target="library">
  <i class="fa fa-book"></i><div>Library Data</div>
</div>
<div id="library" class="container slide hide">
  <div id="data-huruf">

  </div>
</div>
<div id="message" class="hide">
</div>
<script type="text/javascript">
  $(document).ready(function(){
    $("#proses").on("click",function(){
        // ss = parseInt(document.getElementById("nomorproses").value);
        // document.getElementById("nomorproses").value = ss+1;
        // switch (ss) {
        //   case 1:shuruf='0';break;
        //   case 2:shuruf='1';break;
        //   case 3:shuruf='2';break;
        //   case 4:shuruf='3';break;
        //   case 5:shuruf='4';break;
        //   case 6:shuruf='5';break;
        //   case 7:shuruf='6';break;
        //   case 8:shuruf='7';break;
        //   case 9:shuruf='8';break;
        //   case 10:shuruf='9';break;
        //   case 11:shuruf='A';break;
        //   case 12:shuruf='B';break;
        //   case 13:shuruf='C';break;
        //   case 14:shuruf='D';break;
        //   case 15:shuruf='E';break;
        //   case 16:shuruf='F';break;
        //   case 17:shuruf='G';break;
        //   case 18:shuruf='H';break;
        //   case 19:shuruf='I';break;
        //   case 20:shuruf='J';break;
        //   case 21:shuruf='K';break;
        //   case 22:shuruf='L';break;
        //   case 23:shuruf='M';break;
        //   case 24:shuruf='N';break;
        //   case 25:shuruf='O';break;
        //   case 26:shuruf='P';break;
        //   case 27:shuruf='Q';break;
        //   case 28:shuruf='R';break;
        //   case 29:shuruf='S';break;
        //   case 30:shuruf='T';break;
        //   case 31:shuruf='U';break;
        //   case 32:shuruf='V';break;
        //   case 33:shuruf='W';break;
        //   case 34:shuruf='X';break;
        //   case 35:shuruf='Y';break;
        //   case 36:shuruf='Z';break;
        //   case 37:shuruf='a';break;
        //   case 38:shuruf='b';break;
        //   case 39:shuruf='c';break;
        //   case 40:shuruf='d';break;
        //   case 41:shuruf='e';break;
        //   case 42:shuruf='f';break;
        //   case 43:shuruf='g';break;
        //   case 44:shuruf='h';break;
        //   case 45:shuruf='i';break;
        //   case 46:shuruf='j';break;
        //   case 47:shuruf='k';break;
        //   case 48:shuruf='l';break;
        //   case 49:shuruf='m';break;
        //   case 50:shuruf='n';break;
        //   case 51:shuruf='o';break;
        //   case 52:shuruf='p';break;
        //   case 53:shuruf='q';break;
        //   case 54:shuruf='r';break;
        //   case 55:shuruf='s';break;
        //   case 56:shuruf='t';break;
        //   case 57:shuruf='u';break;
        //   case 58:shuruf='v';break;
        //   case 59:shuruf='w';break;
        //   case 60:shuruf='x';break;
        //   case 61:shuruf='y';break;
        //   case 62:shuruf='z';break;
        // }
        // tttt = ('00'+ss).slice(-3);
        // // for (loop=1;loop<=50;loop++) {
        //
        // for (loop=31;loop<=55;loop++) {
        //   // for (loop=11;loop<=20;loop++) {
        //   // for (loop=21;loop<=30;loop++) {
        //   // for (loop=31;loop<=40;loop++) {
        //   // for (loop=41;loop<=55;loop++) {
        //   img = document.createElement("img");
        //   nomor = ('00'+loop).slice(-3);
        //   img.src = "/img/Sample"+tttt+"/img"+tttt+"-"+nomor+".png";
        //   img.onload = function(){
        //     ctx = document.getElementById("img-upload").getContext("2d");
        //     ctx.canvas.width = this.width;
        //     ctx.canvas.height = this.height;
        //     ctx.drawImage(this,0,0,this.width,this.height);
        //     pixel = ctx.getImageData(0,0,ctx.canvas.width,ctx.canvas.height);
        //
        //     //grayscale, biner, dan mean filtering;
        //     starttime = new Date();
        //     pre = new preprocessing();
        //     m_pixel = pre.grayscale(pixel,ctx.canvas.width,ctx.canvas.height,0,0);
        //     // m_pixel = pre.smoothing(3,3,m_pixel,0);
        //     m_pixel = pre.sisip_matrix(m_pixel);
        //     // m_pixel = pre.thinning(m_pixel,0);
        //     pre.segmentasi(m_pixel,ctx,0);
        //     $("#rowid div input").val(shuruf);
        //   };
        // }

        // ss = parseInt(document.getElementById("nomorproses").value);
        // document.getElementById("nomorproses").value = ss+1;
        // switch (ss) {
        //   case 0:shuruf='0';foldername='0';break;
        //   case 1:shuruf='1';foldername='1';break;
        //   case 2:shuruf='2';foldername='2';break;
        //   case 3:shuruf='3';foldername='3';break;
        //   case 4:shuruf='4';foldername='4';break;
        //   case 5:shuruf='5';foldername='5';break;
        //   case 6:shuruf='6';foldername='6';break;
        //   case 7:shuruf='7';foldername='7';break;
        //   case 8:shuruf='8';foldername='8';break;
        //   case 9:shuruf='9';foldername='9';break;
        //   case 10:shuruf='A';foldername='A0';break;
        //   case 11:shuruf='B';foldername='B0';break;
        //   case 12:shuruf='C';foldername='C0';break;
        //   case 13:shuruf='D';foldername='D0';break;
        //   case 15:shuruf='F';foldername='F0';break;
        //   case 14:shuruf='E';foldername='E0';break;
        //   case 16:shuruf='G';foldername='G0';break;
        //   case 17:shuruf='H';foldername='H0';break;
        //   case 18:shuruf='I';foldername='I0';break;
        //   case 19:shuruf='J';foldername='J0';break;
        //   case 20:shuruf='K';foldername='K0';break;
        //   case 21:shuruf='L';foldername='L0';break;
        //   case 22:shuruf='M';foldername='M0';break;
        //   case 23:shuruf='N';foldername='N0';break;
        //   case 24:shuruf='O';foldername='O0';break;
        //   case 25:shuruf='P';foldername='P0';break;
        //   case 26:shuruf='Q';foldername='Q0';break;
        //   case 27:shuruf='R';foldername='R0';break;
        //   case 28:shuruf='S';foldername='S0';break;
        //   case 29:shuruf='T';foldername='T0';break;
        //   case 30:shuruf='U';foldername='U0';break;
        //   case 31:shuruf='V';foldername='V0';break;
        //   case 32:shuruf='W';foldername='W0';break;
        //   case 33:shuruf='X';foldername='X0';break;
        //   case 34:shuruf='Y';foldername='Y0';break;
        //   case 35:shuruf='Z';foldername='Z0';break;
        //   case 36:shuruf='a';foldername='a1';break;
        //   case 37:shuruf='b';foldername='b1';break;
        //   case 38:shuruf='c';foldername='c1';break;
        //   case 39:shuruf='d';foldername='d1';break;
        //   case 40:shuruf='e';foldername='e1';break;
        //   case 41:shuruf='f';foldername='f1';break;
        //   case 42:shuruf='g';foldername='g1';break;
        //   case 43:shuruf='h';foldername='h1';break;
        //   case 44:shuruf='i';foldername='i1';break;
        //   case 45:shuruf='j';foldername='j1';break;
        //   case 46:shuruf='k';foldername='k1';break;
        //   case 47:shuruf='l';foldername='l1';break;
        //   case 48:shuruf='m';foldername='m1';break;
        //   case 49:shuruf='n';foldername='n1';break;
        //   case 50:shuruf='o';foldername='o1';break;
        //   case 51:shuruf='p';foldername='p1';break;
        //   case 52:shuruf='q';foldername='q1';break;
        //   case 53:shuruf='r';foldername='r1';break;
        //   case 54:shuruf='s';foldername='s1';break;
        //   case 55:shuruf='t';foldername='t1';break;
        //   case 56:shuruf='u';foldername='u1';break;
        //   case 57:shuruf='v';foldername='v1';break;
        //   case 58:shuruf='w';foldername='w1';break;
        //   case 59:shuruf='x';foldername='x1';break;
        //   case 60:shuruf='y';foldername='y1';break;
        //   case 61:shuruf='z';foldername='z1';break;
        // }
        // // for (loop=1;loop<=50;loop++) {
        //
        // for (loop=41;loop<=60;loop++) {
        //   // for (loop=11;loop<=20;loop++) {
        //   // for (loop=21;loop<=30;loop++) {
        //   // for (loop=31;loop<=40;loop++) {
        //   // for (loop=41;loop<=55;loop++) {
        //   img = document.createElement("img");
        //   nomor = ('0000'+loop).slice(-5);
        //   img.src = "/IAM/"+foldername+"/hsf_0/hsf_0_"+nomor+".png";
        //   img.onload = function(){
        //     ctx = document.getElementById("img-upload").getContext("2d");
        //     ctx.canvas.width = this.width;
        //     ctx.canvas.height = this.height;
        //     ctx.drawImage(this,0,0,this.width,this.height);
        //     pixel = ctx.getImageData(0,0,ctx.canvas.width,ctx.canvas.height);
        //
        //     //grayscale, biner, dan mean filtering;
        //     starttime = new Date();
        //     pre = new preprocessing();
        //     m_pixel = pre.grayscale(pixel,ctx.canvas.width,ctx.canvas.height,3,3);
        //     m_pixel = pre.smoothing(5,5,m_pixel,0);
        //     // m_pixel = pre.sisip_matrix(m_pixel);
        //     // m_pixel = pre.thinning(m_pixel,0);
        //     pre.segmentasi(m_pixel,ctx,0);
        //     $("#rowid div input").val(shuruf);
        //   };
        // }

        if (document.getElementById("img-upload").getAttribute("sts")!="0") {
          content = "<div style='display:inline-block;width:50%;text-align:left'><span style='text-align:center'>Jumlah baris</span><br>"+
          "<input style='width:100%;display:inline-block' type='number' onkeyup='just_number(this);' value='3' min='0' id='rbaris'></div>"+
          "<div style='display:inline-block;width:50%;text-align:left'><span style='text-align:center'>Jumlah kolom</span><br>"+
          "<input style='width:100%;display:inline-block' type='number' onkeyup='just_number(this);' value='3' min='0' id='rkolom'></div><br><br>";
          alert_field(content,0);
        }else{
          pesan_alert("Info","Tidak ada gambar untuk diproses","info");
        }
    });

    $("#form-hasil-segmen").submit(function(e){
      e.preventDefault();
      if($("#hasil-segmen").children().length>0){
        ajax_with_form("form-hasil-segmen","/simpan-huruf","data-huruf");
      }else{
        pesan_alert("Info","Tidak ada data untuk disimpan","info");
      }
    });

    $("#upload").on("click",function(){
      $("#upload-file").click();
    });

    $("#upload-file").on("change",function(){
      validate_input(this);
    });

    $("#delete_huruf").on("click",function(){
      if ($("#hasil-segmen").children().length>0) {
        $.confirm({
          theme: 'modern', animation: 'zoom',
          animateFromElement:false,
          closeAnimation: 'zoom',
          useBootstrap:false,boxWidth: "20%",
          closeIcon: 'fa fa-close',icon:"fa fa-warning",
          type: "orange", closeIcon: true,
          title: "<span style='font-size:24px' class='font-content'>Hapus Hasil Segmentasi</span>",
          content: "<span class='font-content'>Seluruh hasil segmentasi akan dihapus</span>",
          buttons:{
            Ya: function(){
              $("#hasil-segmen").empty();
            },
            Tidak: function(){}
          }
        });
      }else{
        pesan_alert("Info","Tidak ada data untuk dihapus","info");
      }
    });

  });
</script>
