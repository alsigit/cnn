<script type="text/javascript">
  ta = document.getElementById("text-hasil");
  ta.value = "<?php echo e($hasil); ?>";
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

