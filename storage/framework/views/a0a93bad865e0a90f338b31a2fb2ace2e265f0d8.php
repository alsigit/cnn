<fieldset>
  <legend style="box-shadow:0px 0px 2px #000">Data bobot</legend>
  <?php
  $i = 0;
  ?>
  <!--
  <?php $__empty_1 = true; $__currentLoopData = $bobots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bobot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
  --><div id="bobotdiv<?php echo e($i); ?>" class="inblock panel-huruf" style="box-shadow:0px 0px 6px #363636;background-color:#e4f7ff">
      <button onclick="delete_huruf(<?php echo e($bobot->no); ?>,'panel-data-bobot','<?php echo asset("huruf/$bobot->imgname.png"); ?>')" type="button" name="button">X</button>
      <img id="bobotimg<?php echo e($i); ?>" src="<?php echo asset("huruf/$bobot->imgname.png")."?".filemtime("huruf/$bobot->imgname.png"); ?>">
      <input required id="bobotinp<?php echo e($i); ?>" placeholder="Isi" type="text" name="" value="<?php echo e($bobot->huruf); ?>" maxlength="1">
    </div><!--
 --><script type="text/javascript">
      $("#bobotimg<?php echo e($i); ?>").on("load",function(){
        document.getElementById("bobotinp<?php echo e($i); ?>").style.width = document.getElementById("bobotimg<?php echo e($i); ?>").width;
      });

      $("#bobotinp<?php echo e($i); ?>").on("keydown",function(ev){
        if (ev.shiftKey && ev.keyCode == 9) {
          ev.preventDefault();
          this.parentElement.previousElementSibling.previousElementSibling.childNodes[5].focus();
        }else if(ev.keyCode == 9){
          ev.preventDefault();
          this.parentElement.nextElementSibling.nextElementSibling.childNodes[5].focus();
        }
      });

      $("#bobotinp<?php echo e($i++); ?>").on("change",function(){
        if (this.value!="") {
          var hal = $(".pagination .active span").text();
          generate_token();
          ajax_operation("post",window.location.href+"/simpan-perubahan/<?php echo e($bobot->no); ?>/<?php echo e($bobot->imgname); ?>/"+this.value+"/"+0,"panel-data-bobot");
        }
      });
    </script><!--
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
  --><div class="info-data-notfound">
      Data bobot tidak ditemukan
    </div><!--
  <?php endif; ?>
  -->
</fieldset>
<?php echo $__env->make('message', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
