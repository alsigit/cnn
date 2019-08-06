{{-- //perombakan database --}}
{{-- <script type="text/javascript">
  generate_token();
  ajax_operation("post",window.location.href+"hapus-berkala/"+1+"/"+50,"message");
</script> --}}

<script type="text/javascript">
  bobot = [];
  jumbiner = 0;
</script>

{{-- @if (count($biners)>0)
  <script>
    //Proses CNN
    nlayer = 2;
    stride = 1;
    @foreach ($biners as $biner)
      //Pembentukan bobot
      bobot[jumbiner] = [];
      bobot[jumbiner][1] = "{{$biner->huruf}}";
      biner = "{{$biner->biner}}";
      biner = biner.split("|");
      length = biner.length;
      for (i=0;i<length;i++) {
        biner[i] = biner[i].split(",");
        for (j=0;j<biner[i].length;j++) {
          biner[i][j] = parseInt(biner[i][j]);
        }
      }

      bobot[jumbiner][0] = deep_convolution(biner,nlayer,stride);
      textarea = document.createElement("textarea");
      textarea.name = "hasil_cnn"+jumbiner;
      textarea.value = bobot[jumbiner][0];
      inp = document.createElement("input");
      inp.type = "text";
      inp.name = "no"+jumbiner;
      inp.value = "{{$biner->no}}";
      document.getElementById("form-data-baru").appendChild(inp);
      document.getElementById("form-data-baru").appendChild(textarea);
      bobot[jumbiner][0] = bobot[jumbiner][0].split(",");
      jumbiner++;
    @endforeach
    ajax_with_form("form-data-baru",window.location.href+'simpan-hasil-CNN',"form-data-baru");
    //Proses penyimpanan
    info = "<table class='tbl-info'>"+
              "<caption>Layer Konvolusi</caption>"+
              "<tr><th>Fitur Konvolusi</th><th>:</th><td>"+konvolusi.length+" buah</td></tr>"+
              "<tr><th>Stride</th><th>:</th><td>"+stride+" langkah</td></tr>"+
              "<tr><th>Penggunaan Rectified Linear Unit (ReLU)</th><th>:</th><td>&#10004;</td></tr>"+
              "<tr><th>Penggunaan Max Pooling</th><th>:</th><td>&#10004;</td></tr>"+
              "<tr><th>Set Layer Konvolusi</th><th>:</th><td>"+nlayer+" set</td></tr>"+
              "<tr><th>Jumlah Data Baru</th><th>:</th><td>"+jumbiner+" buah</td></tr>"+
           "</table><br>";
    document.getElementById("info").innerHTML = info;
  </script>
@endif --}}

<fieldset style="background-color:#f0ffdb">
  <legend>Info Proses</legend>
  <div id="info">

  </div>
</fieldset>

@if (count($hasilcnns)>0)
  @php
    for ($i=0;$i<$loop;$i++) {
      foreach ($hasilcnns[$i] as $hasil) {
        @endphp
        <script type="text/javascript">
          bobot[jumbiner] = [];
          bobot[jumbiner][0] = "{{$hasil->hasil_cnn}}";
          bobot[jumbiner][0] = bobot[jumbiner][0].split(",");
          bobot[jumbiner++][1] = "{{$hasil->huruf}}";
        </script>
        @php
      }
    }
  @endphp
@endif

<script type="text/javascript">
  for (i=0;i<bobot.length;i++) {
    for (j=0;j<bobot[i][0].length;j++) {
      bobot[i][0][j] = parseFloat(bobot[i][0][j]);
    }
  }

  info = "<table class='tbl-info'>"+
            "<caption>Layer Konvolusi</caption>"+
            "<tr><th>Fitur Konvolusi</th><th>:</th><td>"+{{$setting->nkonv}}+" buah</td></tr>"+
            "<tr><th>Stride</th><th>:</th><td>"+{{$setting->stride}}+" langkah</td></tr>"+
            "<tr><th>Penggunaan Randomized Leaky ReLU (RReLU)</th><th>:</th><td>&#10004;</td></tr>"+
            "<tr><th>Penggunaan Max Pooling</th><th>:</th><td>&#10004;</td></tr>"+
            "<tr><th>Set Layer Konvolusi</th><th>:</th><td>"+{{$setting->nlayer}}+" set</td></tr>"+
         "</table><br>"+
         "<table class='tbl-info'>"+
            "<caption>Layer Koneksi Penuh</caption>"+
            "<tr><th>Jumlah Data</th><th>:</th><td>"+jumbiner+" buah</td></tr>"+
         "</table>";
  document.getElementById("info").innerHTML += info;
  $("#info-training").slideDown(400);
</script>

{{-- <script type="text/javascript">
  for (i=0;i<bobot.length;i++) {
    for (j=0;j<bobot[i][0].length;j++) {
      bobot[i][0][j] = parseFloat(bobot[i][0][j]);
    }
  }
  for (i=0;i<matrix.length;i++) {
    for (j=0;j<matrix[i][0].length;j++) {
      matrix[i][0][j] = parseFloat(matrix[i][0][j]);
    }
  }
  bobot = LVQ(bobot,matrix);
  info = "<table class='tbl-info'>"+
            "<caption>Layer Koneksi Penuh</caption>"+
            "<tr><th>Jumlah Data</th><th>:</th><td>"+jumbiner+" buah</td></tr>"+
         "</table>";
  document.getElementById("info").innerHTML += info;
  $("#info-training").slideDown(400);
</script> --}}
