@if(session()->has('message'))
  @php
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
  @endphp
  <script>
    $.confirm({
      theme: 'modern', animation: 'zoom',
      closeAnimation: 'zoom',
      animateFromElement: false,
      useBootstrap:false,boxWidth: "20%",
      closeIcon: 'fa fa-close',icon:"{{$icon}}",
      type: "{{$color}}", closeIcon: true,
      title: "<span style='font-size:24px' class='font-content'>"+"{{session()->get("title")}}"+"</span>",
      content: "<span class='font-content'>"+"{{session()->get("message")}}"+"</span>",
      buttons:{
        ya: function(){
        }
      }
    });
  </script>
  <?php session()->forget('message');?>
@endif
