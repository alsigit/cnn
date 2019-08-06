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
            {{-- <input type="number" id="nomorproses" value=""> --}}
            <input type="file" id="upload-file" class="hide" accept="image/jpeg,image/png">
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
            {{csrf_field()}}
            <div id="hasil-segmen" class="table" style="width:100%;position:relative;">

            </div>
            <div id='temp-hasil-segmen' class='hide'>
            </div>
            <script type="text/javascript">
            document.getElementById("form-hasil-segmen").setAttribute("action",window.location.href);
            </script>
          </fieldset>
          <div id="panel-proses">
            {{-- <button type="button" name="button" id='setval'>set val</button> --}}
            <button class="orange icon" type="submit" id="simpan_huruf" form="form-hasil-segmen" name="button" title="Simpan seluruh huruf"><i class="fa fa-save"></i></button>
            <button class="cadetblue icon" type="button" form="form-hasil-segmen" id="delete_huruf" name="button" title="Hapus seluruh huruf"><i class="fa fa-trash"></i></button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<br>

{{-- library --}}
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
  $("#setval").on("click",function(){
    ln = $("#hasil-segmen div div").length;
    for (var i = 0; i < ln; i++) {
      switch (i) {
        case 0: v = '0';break;
        case 1: v = '1';break;
        case 2: v = '2';break;
        case 3: v = '3';break;
        case 4: v = '4';break;
        case 5: v = '5';break;
        case 6: v = '6';break;
        case 7: v = '7';break;
        case 8: v = '8';break;
        case 9: v = '9';break;
        case 10: v = 'A';break;
        case 11: v = 'B';break;
        case 12: v = 'C';break;
        case 13: v = 'D';break;
        case 14: v = 'E';break;
        case 15: v = 'F';break;
        case 16: v = 'G';break;
        case 17: v = 'H';break;
        case 18: v = 'I';break;
        case 19: v = 'J';break;
        case 20: v = 'K';break;
        case 21: v = 'L';break;
        case 22: v = 'M';break;
        case 23: v = 'N';break;
        case 24: v = 'O';break;
        case 25: v = 'P';break;
        case 26: v = 'Q';break;
        case 27: v = 'R';break;
        case 28: v = 'S';break;
        case 29: v = 'T';break;
        case 30: v = 'U';break;
        case 31: v = 'V';break;
        case 32: v = 'W';break;
        case 33: v = 'X';break;
        case 34: v = 'Y';break;
        case 35: v = 'Z';break;
        case 36: v = 'a';break;
        case 37: v = 'b';break;
        case 38: v = 'c';break;
        case 39: v = 'd';break;
        case 40: v = 'e';break;
        case 41: v = 'f';break;
        case 42: v = 'g';break;
        case 43: v = 'h';break;
        case 44: v = 'i';break;
        case 45: v = 'j';break;
        case 46: v = 'k';break;
        case 47: v = 'l';break;
        case 48: v = 'm';break;
        case 49: v = 'n';break;
        case 50: v = 'o';break;
        case 51: v = 'p';break;
        case 52: v = 'q';break;
        case 53: v = 'r';break;
        case 54: v = 's';break;
        case 55: v = 't';break;
        case 56: v = 'u';break;
        case 57: v = 'v';break;
        case 58: v = 'w';break;
        case 59: v = 'x';break;
        case 60: v = 'y';break;
        case 61: v = 'z';break;
      }
      $("#hasil-segmen div div").eq(i).children('input').val(v);
      // document.getElementById("huruf"+i).value = v;
    }
  });

  $(document).ready(function(){
    $("#proses").on("click",function(){
        if (document.getElementById("img-upload").getAttribute("sts")!="0") {
          ctx = document.getElementById("img-upload").getContext("2d");
          pixel = ctx.getImageData(0,0,ctx.canvas.width,ctx.canvas.height);

          var pre = new preprocessing();
          m_pixel = pre.grayscale(pixel,ctx.canvas.width,ctx.canvas.height);
          m_pixel = pre.smoothing(3,3,m_pixel,0);
          pre.segmentation(m_pixel,ctx,0);
        }else{
          pesan_alert("Info","Tidak ada gambar untuk diproses","info");
        }
    });

    $("#form-hasil-segmen").submit(function(e){
      e.preventDefault();
      var pre = new preprocessing();
      pre.save_data();
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
