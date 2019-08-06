<?php if(count($hasilcnns)>0): ?>
  <?php $__currentLoopData = $hasilcnns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hasil): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <script type="text/javascript">
      bobot[jumbiner] = [];
      bobot[jumbiner][0] = "<?php echo e($hasil->hasil_cnn); ?>";
      bobot[jumbiner][0] = bobot[jumbiner][0].split(",");
      bobot[jumbiner++][1] = "<?php echo e($hasil->huruf); ?>";
    </script>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>
<script type="text/javascript">
  console.log(bobot);
  alert("s");
</script>
