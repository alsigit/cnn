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
            <input type="file" id="upload-file" class="hide" accept="image/jpeg,image/png">
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
          <input type="text" name="result-kunci" id="kunci" value="" placeholder="Kunci Jawaban" style="width:100%">
          <textarea id="text-hasil" style="width:100%;height:calc(100% - 35px);resize:none;padding:10px;box-sizing:border-box"></textarea>
        </fieldset>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $("#proses-testing").on("click",function(){
    if ($("#testing-upload").attr("sts")==1) {
      ctx = document.getElementById("testing-upload").getContext("2d");
      pixel = ctx.getImageData(0,0,ctx.canvas.width,ctx.canvas.height);

      //grayscale, biner, dan mean filtering;
      var pre = new preprocessing();
      pre.send_to_testing(ctx,pixel);
      // m_pixel = pre.grayscale(pixel,ctx.canvas.width,ctx.canvas.height);
      //
      // m_pixel = pre.smoothing(3,3,m_pixel,0);
      // alfabet = pre.segmentasi(m_pixel,ctx,1);
      //
      // biner = [];
      // for (var i=0;i<alfabet.length;i++) {
      //   biner[i] = [];
      //   biner[i][0] = alfabet[i];
      // }
      //
      // generate_token();
      // ajax_with_data(biner,'send-testing-data','temp-hasil-segmen');
    }else{
      pesan_alert("Info","Tidak ada gambar untuk diproses","info");
    }
  });
</script>
