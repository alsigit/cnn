@if (count($hasilcnns)>0)
  @foreach ($hasilcnns as $hasil)
    <script type="text/javascript">
      bobot[jumbiner] = [];
      bobot[jumbiner][0] = "{{$hasil->hasil_cnn}}";
      bobot[jumbiner][0] = bobot[jumbiner][0].split(",");
      bobot[jumbiner++][1] = "{{$hasil->huruf}}";
    </script>
  @endforeach
@endif
<script type="text/javascript">
  console.log(bobot);
  alert("s");
</script>
