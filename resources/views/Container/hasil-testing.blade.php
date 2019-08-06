<script type="text/javascript">
  ta = document.getElementById("text-hasil");
  ta.value = "{{$hasil}}";
  kunci = $("#kunci").val();
  if (kunci.length!=0) {
    j = 0;
    res_length = 0;
    act_length = 0;
    for (var i = 0; i < kunci.length; i++) {
      if (kunci[i]!=" ") {
        act_length++;
        if (kunci[i] === ta.value[j]) {
          res_length++;
        }
        j++;
      }
    }
    pesan_alert("Info","Akurasi yang diperoleh adalah "+((res_length/act_length)*100).toFixed(2)+"%","info");
  }
</script>
{{-- @php
  echo "in";
  echo "<table bgcolor='gray'>";
  for ($i=0; $i < count($biner[0]); $i++) {
    echo "<tr>";
    for ($j=0; $j < count($biner[0][0]); $j++) {
      if ($biner[0][$i][$j]==1) {
        echo "<td bgcolor='green'>1</td>";
      }else{
        echo "<td>0</td>";
      }
    }
    echo "</tr>";
  }
  echo "</table>";
@endphp --}}
