<fieldset style="background-color:#f0ffdb">
  <legend>Info Proses</legend>
  <div id="info">

  </div>
</fieldset>
<script type="text/javascript">
  info = "<table class='tbl-info'>"+
            "<caption>Layer Konvolusi</caption>"+
            "<tr><th>Fitur Konvolusi</th><th>:</th><td>"+<?php echo e($setting->nkonv); ?>+" buah</td></tr>"+
            "<tr><th>Stride</th><th>:</th><td>"+<?php echo e($setting->stride); ?>+" langkah</td></tr>"+
            "<tr><th>Penggunaan Randomized Leaky ReLU (RReLU)</th><th>:</th><td>&#10004;</td></tr>"+
            "<tr><th>Penggunaan Max Pooling</th><th>:</th><td>&#10004;</td></tr>"+
            "<tr><th>Set Layer Konvolusi</th><th>:</th><td>"+<?php echo e($setting->nlayer); ?>+" set</td></tr>"+
         "</table><br>"+
         "<table class='tbl-info'>"+
            "<caption>Layer Koneksi Penuh</caption>"+
            "<tr><th>Jumlah Data</th><th>:</th><td>"+<?php echo e($setting->jumdata); ?>+" buah</td></tr>"+
         "</table>";
  document.getElementById("info").innerHTML += info;
  $("#info-training").slideDown(400);
</script>
