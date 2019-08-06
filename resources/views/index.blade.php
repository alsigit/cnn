<html>
  <head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>Pengenalan Tulisan Tangan Metode CNN</title>
  </head>
  <link rel="stylesheet" href="{!! asset("/css/public.css") !!}">
  <link rel="stylesheet" href="{!! asset("/css/index.css") !!}">
  <link rel="stylesheet" href="{!! asset("/css/font-awesome.css") !!}">
  <link rel="stylesheet" href="{!! asset("/css/jquery-confirm.css") !!}">
  <link rel="stylesheet" href="{!! asset("/css/jquery-ui.css") !!}">
  <script type="text/javascript" src="{!! asset("/js/index.js") !!}"></script>
  <script type="text/javascript" src="{!! asset("/js/jquery.js") !!}"></script>
  <script type="text/javascript" src="{!! asset("/js/jquery-ui.js") !!}"></script>
  <script type="text/javascript" src="{!! asset("/js/jquery-confirm.js") !!}"></script>
  <body>
    <div id="load"></div>
    <header>
      Pengenalan Tulisan Tangan
    </header>
    <div id="container">
      <div id="menu-bar">
        <button id="olah-data-btn" class='active' type="button" name="button" target-id="olah-data">Pengolahan Data</button>
        <button id="training-btn" type="button" name="button" target-id="training">Training</button>
        <button id="testing-btn" type="button" name="button" target-id="testing">Testing</button>
      </div>
      <div id="olah-data">
        <div class="wrapper">
          @include("container.olah-data")
        </div>
      </div>
      <div id="training" class="hide">
        <div class="wrapper">
          @include("container.training")
        </div>
      </div>
      <div id="testing" class="hide">
        <div class="wrapper">
          @include("container.testing")
        </div>
      </div>
    </div>
  </body>
  <script type="text/javascript">
    var cvs = document.getElementById("img-upload");
    var ctx = cvs.getContext("2d");
    var url = "{!! asset("/image/camera.png") !!}";
    clear_panel_upload(ctx,url);
    $(document).ready(function(){
      $("#menu-bar button").on("click",function(){
        $("#"+$("#menu-bar .active").attr("target-id")).slideUp(200);
        $("#menu-bar button").removeClass("active");
        $(this).addClass("active");
        $("#"+$(this).attr("target-id")).slideDown(200);
        if ($(this).attr("id")=="olah-data-btn" || $(this).attr("id")=="testing-btn") {
          if ($(this).attr("id")=="olah-data-btn") {
            id = "img-upload";
          }else{
            id = "testing-upload";
          }
          if (document.getElementById(id).getAttribute("sts")==0) {
            var cvs = document.getElementById(id);
            var ctx = cvs.getContext("2d");
            var url = "{!! asset("/image/camera.png") !!}";
            clear_panel_upload(ctx,url);
          }
        }
      });
    });

    $(".panel-judul").on("click",function(){
      target = $(this).attr("target");
      if (target!=null) {
        $(".slide").slideUp(400);
        if (target=="library") {
          if ($("#data-huruf").children().length==0) {
            ajax_operation("get",window.location.href+"get-data-huruf","data-huruf");
          }
        }
        $("#"+target).slideDown(400);
      }
    });

    $("#coordmap,#coordmap-testing").on("click",function(ev){
      if ($(this).attr("id")=="coordmap") {
        imgid = "img-upload";
        coordid = "coordmap";
      }else{
        imgid = "testing-upload";
        coordid = "coordmap-testing";
      }
      if ($("#"+imgid).attr("sts")=="1") {
        var prevX;
        var prevY;
        make_area(ev.pageX,ev.pageY,coordid);
        $("#"+coordid+" .node").contextmenu(function(event){
          coordid = $(this).attr("milik");
          clear_node(event,this,coordid);
        });

        $("#"+coordid+' .node').draggable().bind('mousedown', function(event){
          $(event.target.parentElement).append(event.target);
        }).bind('drag', function(event){
          coordid = $(this).attr("milik");
          var coordwidth = $("#"+coordid).width();
          var coordheight = $("#"+coordid).height();
          draggable_node(event,this,coordwidth,coordheight,coordid);
        });

        $("#"+coordid+' .rect').draggable().bind('mousedown', function(event){
          $(event.target.parentElement).prepend(event.target);
          prevX = event.pageX;
          prevY = event.pageY;
        }).bind('drag', function(event){
          coordid = $(this).attr("milik");
          var pos_x = parseInt($("#"+coordid+' rect').attr("x"));
          var pos_y = parseInt($("#"+coordid+' rect').attr("y"));
          var width = $(this).width();
          var height = $(this).height();
          var coordwidth = $("#"+coordid).width();
          var coordheight = $("#"+coordid).height();
          var circ_x = new Array(parseInt($("#"+coordid+" .node").eq(0).attr("cx")),parseInt($("#"+coordid+" .node").eq(1).attr("cx")));
          var circ_y = new Array(parseInt($("#"+coordid+" .node").eq(0).attr("cy")),parseInt($("#"+coordid+" .node").eq(1).attr("cy")));
          switch (true) {
            case (prevX > event.pageX && prevY > event.pageY):
            pos_x = pos_x - Math.abs(prevX - event.pageX);
            pos_y = pos_y - Math.abs(prevY - event.pageY);
            if(pos_x >= 0 && pos_y >= 0 && (pos_x + width) <= coordwidth && (pos_y + height) <= coordheight){
              circ_x[0] = circ_x[0]-Math.abs(prevX - event.pageX);
              circ_x[1] = circ_x[1]-Math.abs(prevX - event.pageX);
              circ_y[0] = circ_y[0]-Math.abs(prevY - event.pageY);
              circ_y[1] = circ_y[1]-Math.abs(prevY - event.pageY);
            }
            break;
            case (prevX > event.pageX && prevY <= event.pageY):
            pos_x = pos_x - Math.abs(prevX - event.pageX);
            pos_y = pos_y + Math.abs(prevY - event.pageY);
            if(pos_x >= 0 && pos_y >= 0 && (pos_x + width) <= coordwidth && (pos_y + height) <= coordheight){
              circ_x[0] = circ_x[0]-Math.abs(prevX - event.pageX);
              circ_x[1] = circ_x[1]-Math.abs(prevX - event.pageX);
              circ_y[0] = circ_y[0]+Math.abs(prevY - event.pageY);
              circ_y[1] = circ_y[1]+Math.abs(prevY - event.pageY);
            }
            break;
            case (prevX <= event.pageX && prevY > event.pageY):
            pos_x = pos_x + Math.abs(prevX - event.pageX);
            pos_y = pos_y - Math.abs(prevY - event.pageY);
            if(pos_x >= 0 && pos_y >= 0 && (pos_x + width) <= coordwidth && (pos_y + height) <= coordheight){
              circ_x[0] = circ_x[0]+Math.abs(prevX - event.pageX);
              circ_x[1] = circ_x[1]+Math.abs(prevX - event.pageX);
              circ_y[0] = circ_y[0]-Math.abs(prevY - event.pageY);
              circ_y[1] = circ_y[1]-Math.abs(prevY - event.pageY);
            }
            break;
            case (prevX <= event.pageX && prevY <= event.pageY):
            pos_x = pos_x + Math.abs(prevX - event.pageX);
            pos_y = pos_y + Math.abs(prevY - event.pageY);
            if(pos_x >= 0 && pos_y >= 0 && (pos_x + width) <= coordwidth && (pos_y + height) <= coordheight){
              circ_x[0] = circ_x[0]+Math.abs(prevX - event.pageX);
              circ_x[1] = circ_x[1]+Math.abs(prevX - event.pageX);
              circ_y[0] = circ_y[0]+Math.abs(prevY - event.pageY);
              circ_y[1] = circ_y[1]+Math.abs(prevY - event.pageY);
            }
            break;
          }
          if(pos_x >= 0 && pos_y >= 0 && (pos_x + width) <= coordwidth && (pos_y + height) <= coordheight){
            $("#"+coordid+' .node').eq(0).attr("cx",circ_x[0]);
            $("#"+coordid+' .node').eq(1).attr("cx",circ_x[1]);
            $("#"+coordid+' .node').eq(0).attr("cy",circ_y[0]);
            $("#"+coordid+' .node').eq(1).attr("cy",circ_y[1]);
            $("#"+coordid+' .rect').attr("x",pos_x);
            $("#"+coordid+' .rect').attr("y",pos_y);
          }
          prevX = event.pageX;
          prevY = event.pageY;
          move_panel_tbl(coordid);
        });
      }
    });
  </script>
</html>
