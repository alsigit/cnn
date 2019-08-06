<fieldset style="background-color:#f0ffdb">
  <legend>Info Proses</legend>
  <div id="info">

  </div>
</fieldset>
<script type="text/javascript">
  info = "<table class='tbl-info'>"+
            "<caption>Layer Konvolusi</caption>"+
            "<tr><th>Fitur Konvolusi Pertama</th><th>:</th><td>6 buah</td></tr>"+
            "<tr><th>Fitur Konvolusi Kedua</th><th>:</th><td>12 buah</td></tr>"+
            "<tr><th>Penggunaan Leaky ReLU (LReLU)</th><th>:</th><td>&#10004;</td></tr>"+
            "<tr><th>Penggunaan Average Pooling</th><th>:</th><td>&#10004;</td></tr>"+
            "<tr><th>Set Layer Konvolusi</th><th>:</th><td><?php echo e($setting->nlayer); ?> set</td></tr>"+
         "</table><br>"+
         "<table class='tbl-info'>"+
            "<caption>Layer Koneksi Penuh</caption>"+
            "<tr><th>Jumlah Data</th><th>:</th><td>"+<?php echo e($setting->jumdata); ?>+" buah</td></tr>"+
         "</table>";
  document.getElementById("info").innerHTML += info;
  $("#info-training").slideDown(400);
</script>
