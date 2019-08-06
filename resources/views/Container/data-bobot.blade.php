<fieldset>
  <legend style="box-shadow:0px 0px 2px #000">Data bobot</legend>
  @php
  $i = 0;
  @endphp
  <!--
  @forelse ($bobots as $bobot)
  --><div id="bobotdiv{{$i}}" class="inblock panel-huruf" style="box-shadow:0px 0px 6px #363636;background-color:#e4f7ff">
      <button onclick="delete_huruf({{$bobot->no}},'panel-data-bobot','{!! asset("huruf/$bobot->imgname.png") !!}')" type="button" name="button">X</button>
      <img id="bobotimg{{$i}}" src="{!! asset("huruf/$bobot->imgname.png")."?".filemtime("huruf/$bobot->imgname.png") !!}">
      <input required id="bobotinp{{$i}}" placeholder="Isi" type="text" name="" value="{{$bobot->huruf}}" maxlength="1">
    </div><!--
 --><script type="text/javascript">
      $("#bobotimg{{$i}}").on("load",function(){
        document.getElementById("bobotinp{{$i}}").style.width = document.getElementById("bobotimg{{$i}}").width;
      });

      $("#bobotinp{{$i}}").on("keydown",function(ev){
        if (ev.shiftKey && ev.keyCode == 9) {
          ev.preventDefault();
          this.parentElement.previousElementSibling.previousElementSibling.childNodes[5].focus();
        }else if(ev.keyCode == 9){
          ev.preventDefault();
          this.parentElement.nextElementSibling.nextElementSibling.childNodes[5].focus();
        }
      });

      $("#bobotinp{{$i++}}").on("change",function(){
        if (this.value!="") {
          var hal = $(".pagination .active span").text();
          generate_token();
          ajax_operation("post",window.location.href+"/simpan-perubahan/{{$bobot->no}}/{{$bobot->imgname}}/"+this.value+"/"+0,"panel-data-bobot");
        }
      });
    </script><!--
  @empty
  --><div class="info-data-notfound">
      Data bobot tidak ditemukan
    </div><!--
  @endforelse
  -->
</fieldset>
@include('message')
