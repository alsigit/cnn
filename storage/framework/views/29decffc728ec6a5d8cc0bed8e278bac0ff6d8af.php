<?php if(count($datas)>0): ?>
  <br>
  <div id="panel-data-latih">
    <?php echo $__env->make('Container.data-latih', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  </div>
<?php else: ?>
  <div class="info-data-notfound">
    Data huruf tidak ditemukan
  </div>
<?php endif; ?>
