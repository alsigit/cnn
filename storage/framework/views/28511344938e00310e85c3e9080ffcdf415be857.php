<fieldset>
  <legend style="box-shadow:0px 0px 2px #000">Data Latih</legend>
  <?php
  $i = 0;
  ?>
  <!--
  <?php $__empty_1 = true; $__currentLoopData = $datas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
  --><div id="ddiv<?php echo e($i); ?>" class="inblock panel-huruf" style="box-shadow:0px 0px 6px #363636">
      <button onclick="delete_huruf(<?php echo e($data->no); ?>,'panel-data-latih','<?php echo asset("huruf/$data->imgname.png"); ?>')" type="button" name="button">X</button>
      <img id="dataimg<?php echo e($i); ?>" src="<?php echo asset("huruf/$data->imgname.png"); ?>" style="width:50px;height:50px;">
      <input required id="dinp<?php echo e($i); ?>" placeholder="Isi" type="text" name="" value="<?php echo e($data->huruf); ?>" maxlength="1">
    </div><!--
 --><script type="text/javascript">
      $("#dataimg<?php echo e($i); ?>").on("load",function(){
        document.getElementById("dinp<?php echo e($i); ?>").style.width = document.getElementById("dataimg<?php echo e($i); ?>").width;
      });

      $("#dinp<?php echo e($i); ?>").on("keydown",function(ev){
        if (ev.shiftKey && ev.keyCode == 9) {
          ev.preventDefault();
          this.parentElement.previousElementSibling.previousElementSibling.childNodes[5].focus();
        }else if(ev.keyCode == 9){
          ev.preventDefault();
          this.parentElement.nextElementSibling.nextElementSibling.childNodes[5].focus();
        }
      });

      $("#dinp<?php echo e($i++); ?>").on("change",function(){
        if (this.value!="") {
          var hal = $(".pagination .active span").text();
          generate_token();
          ajax_operation("post",window.location.href+"/simpan-perubahan/<?php echo e($data->no); ?>/<?php echo e($data->imgname); ?>/"+this.value+"/"+hal,"panel-data-latih");
        }
      });
    </script><!--
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
  --><div class="info-data-notfound">
      Data latih tidak ditemukan
    </div><!--
  <?php endif; ?>
  -->
  <div class="halaman">
    <?php echo e($datas->links()); ?>

  </div>
  <script type="text/javascript">
    $("#panel-data-latih").slideDown(400);
    $(".pagination a, .pagination span").click(function(event) {
      event.preventDefault();
      var hal = $(this).attr('href').split('page=')[1];
      var url = window.location.href+"more-data/?page="+hal;
      ajax_operation("get",url,"panel-data-latih");
      $("#panel-data-latih").slideToggle(400);
    });
  </script>
</fieldset>
<?php echo $__env->make('message', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
