<div class="panel-judul" style="cursor:default">
  <i class="fa fa-briefcase"></i><div>Pelatihan</div>
</div>
<div class="container">
  <div class="table">
    <div class="row">
      <div id="panel-upload" class="col2">
        <fieldset>
          <legend>Form Upload Gambar</legend>
          <div style="padding:10px;box-sizing:border-box;width:100%;background-color:#7a1212">
            <input style="width:200px" type="text" id="testing-url" placeholder="Alamat gambar" readonly>
            <button type="button" name="button" onclick="$('#upload-file').click();" title="Upload Gambar" id="upload" class="orange icon"><i class="fa fa-upload"></i></button>
            <button type="button" name="button" title="Proses Pengolahan Citra" id="proses-testing" class="green icon"><i class=" fa fa-cogs"></i></button>
            <input type="file" id="upload-file" class="hide">
          </div>
          <div style="width:100%;position:relative">
            <div id="testing-tbl-crop">
              <button type="button" name="button" class="icon" onclick="cut_image('testing-upload','coordmap-testing')"><i class="fa fa-scissors"></i></button>
              <button type="button" name="button" class="icon" onclick="clear_nodes('coordmap-testing')"><i class="fa fa-times"></i></button>
            </div>
            <svg id="coordmap-testing" class="noselect"></svg>
            <canvas id="testing-upload" width="300px" height="200px" sts="0"></canvas>
          </div>
        </fieldset>
      </div>
      <div id="panel-hasil" class="col2">
        <fieldset style="overflow-y:auto">
          <legend>Hasil Proses</legend>
          <textarea id="text-hasil" style="width:100%;height:100%;resize:none;padding:10px;box-sizing:border-box"></textarea>
        </fieldset>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $("#proses-testing").on("click",function(){
    // if (typeof w !== 'undefined') {
      if ($("#testing-upload").attr("sts")==1) {
        content = "<div style='display:inline-block;width:50%;text-align:left'><span style='text-align:center'>Jumlah baris</span><br>"+
        "<input style='width:100%;display:inline-block' type='number' onkeyup='just_number(this);' value='3' min='0' id='rbaris'></div>"+
        "<div style='display:inline-block;width:50%;text-align:left'><span style='text-align:center'>Jumlah kolom</span><br>"+
        "<input style='width:100%;display:inline-block' type='number' onkeyup='just_number(this);' value='3' min='0' id='rkolom'></div><br><br>";
        alert_field(content,1);
      }else{
        pesan_alert("Info","Tidak ada gambar untuk diproses","info");
      }
    // }else{
    //   pesan_alert("Info","Anda belum melakukan <i>training</i>","info");
    // }
  });
</script>
