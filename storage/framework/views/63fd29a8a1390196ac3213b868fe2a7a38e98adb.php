<script type="text/javascript">
matrix = [];
jumbiner = 0;

//Proses CNN
nlayer = 2;
stride = 1;
<?php if(count($biners)>0): ?>
  <?php $__currentLoopData = $biners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $biner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  //Pembentukan matrix
  matrix[jumbiner] = [];
  matrix[jumbiner][1] = "<?php echo e($biner->huruf); ?>";
  biner = "<?php echo e($biner->biner); ?>";
  biner = biner.split("|");
  length = biner.length;
  for (i=0;i<length;i++) {
    biner[i] = biner[i].split(",");
    for (j=0;j<biner[i].length;j++) {
      biner[i][j] = parseInt(biner[i][j]);
    }
  }

  matrix[jumbiner][0] = deep_convolution(biner,nlayer,stride);

  textarea = document.createElement("textarea");
  textarea.name = "hasil_cnn"+jumbiner;
  textarea.value = matrix[jumbiner][0];
  inp = document.createElement("input");
  inp.type = "text";
  inp.name = "no"+jumbiner;
  inp.value = "<?php echo e($biner->no); ?>";
  document.getElementById("form-data-baru").appendChild(inp);
  document.getElementById("form-data-baru").appendChild(textarea);
  matrix[jumbiner][0] = matrix[jumbiner][0].split(",");
  jumbiner++;
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  //Proses penyimpanan
  var form = $('#form-data-baru')[0];
  var frdata = new FormData(form);
  $.ajax({
    type: "POST",
    data: frdata,
    url: window.location.href+'simpan-hasil-CNN',
    contentType: false,
    processData: false,
    cache:false,
    beforeSend  : function(){
      $("#load").show();
    },
    success: function(data){
      $("#load").hide();
      $("#form-data-baru").html(data);
      generate_token();
      ajax_operation("post",window.location.href+"hapus-berkala/<?php echo e($r1+50); ?>/<?php echo e($r2+50); ?>","message");
    },
    error: function(req,sts,err){
      $("#load").hide();
      console.log(req.responseText);
    }
  });

<?php endif; ?>
</script>
