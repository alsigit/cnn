<div class="panel-judul" style="cursor:default">
  <i class="fa fa-cogs"></i>
  <div>
    <button id="btn-training" type="button" class="orange" name="button">Training</button>
  </div>
</div>
<div class="container">
  <div class="table" style="display:block">
    <div class="row" style="display:block">
      <div id="info-training" class="hide">
      </div>
      <div id="temp-training">
      </div>
    </div>
  </div>
</div>
<form id="form-data-baru" name="form-data-baru" class="hide">
  {{csrf_field()}}
</form>
<script type="text/javascript">
  $("#btn-training").on("click",function(){
    content = "info-training";
    $.ajax({
      type: "get",
      url: window.location.href+"training",
      cache:true,
      beforeSend  : function(){
        $("#load").show();
        start_time = new Date();
      },
      success: function(data){
        $("#load").hide();
        $("#"+content).html(data);
        end_time = new Date();
        inf = "<table class='tbl-info'><tr><th>Durasi</th><th>:</th><td>"+((end_time.getTime()-start_time.getTime())/1000)+" detik</td>";
        document.getElementById("info").innerHTML += inf;
      },
      error: function(req,sts,err){
        $("#load").hide();
        console.log(req.responseText);
      }
    });
  });
</script>
