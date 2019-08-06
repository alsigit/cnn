<?php if(session()->has('message')): ?>
  <?php
    switch (strtolower(session()->get("type"))) {
      case 'success':
        $icon = "fa fa-smile-o";
        $color = "green";
      break;
      case 'error':
        $icon = "fa fa-close";
        $color = "red";
      break;
      case 'info':
        $icon = "fa fa-exclamation-circle";
        $color = "blue";
      break;
    }
  ?>
  <script>
    $.confirm({
      theme: 'modern', animation: 'zoom',
      closeAnimation: 'zoom',
      animateFromElement: false,
      useBootstrap:false,boxWidth: "20%",
      closeIcon: 'fa fa-close',icon:"<?php echo e($icon); ?>",
      type: "<?php echo e($color); ?>", closeIcon: true,
      title: "<span style='font-size:24px' class='font-content'>"+"<?php echo e(session()->get("title")); ?>"+"</span>",
      content: "<span class='font-content'>"+"<?php echo e(session()->get("message")); ?>"+"</span>",
      buttons:{
        ya: function(){
        }
      }
    });
  </script>
  <?php session()->forget('message');?>
<?php endif; ?>
