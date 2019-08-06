<fieldset>
  <legend style="box-shadow:0px 0px 2px #000">Data Latih</legend>
  @php
  $i = 0;
  @endphp
  <!--
  @forelse ($datas as $data)
  --><div id="ddiv{{$i}}" class="inblock panel-huruf" style="box-shadow:0px 0px 6px #363636">
      <button onclick="delete_huruf({{$data->no}},'panel-data-latih','{!! asset("huruf/$data->imgname.png") !!}')" type="button" name="button">X</button>
      {{-- <img id="dataimg{{$i}}" src="{!! asset("huruf/$data->imgname.png") !!}" style="width:50px;height:50px;"> --}}
      <table style="width:50px;height:50px;border-collapse:collapse;">
      @php
        $data->biner = explode("|",$data->biner);
        for ($j=0;$j<count($data->biner);$j++) {
          echo "<tr>";
          $data->biner[$j] = str_split($data->biner[$j]);
          for ($k=0;$k<count($data->biner[$j]);$k++) {
            if ($data->biner[$j][$k] == "1") {
              echo "<td style='background-color:#fff'></td>";
            }else{
              echo "<td style='background-color:#000'></td>";
            }
          }
          echo "</tr>";
        }
      @endphp
      </table>
      <input style="width:50px;" required id="dinp{{$i}}" placeholder="Isi" type="text" name="" value="{{$data->huruf}}" maxlength="1">
    </div><!--
 --><script type="text/javascript">
      $("#dataimg{{$i}}").on("load",function(){
        document.getElementById("dinp{{$i}}").style.width = document.getElementById("dataimg{{$i}}").width;
      });

      $("#dinp{{$i}}").on("keydown",function(ev){
        if (ev.shiftKey && ev.keyCode == 9) {
          ev.preventDefault();
          this.parentElement.previousElementSibling.previousElementSibling.childNodes[5].focus();
        }else if(ev.keyCode == 9){
          ev.preventDefault();
          this.parentElement.nextElementSibling.nextElementSibling.childNodes[5].focus();
        }
      });

      $("#dinp{{$i++}}").on("change",function(){
        var pre = new preprocessing();
        pre.edit_data(this.value,"{{$data->no}}","{{$data->imgname}}");
      });
    </script><!--
  @empty
  --><div class="info-data-notfound">
      Data latih tidak ditemukan
    </div><!--
  @endforelse
  -->
  <div class="halaman">
    {{$datas->links()}}
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
@include('message')
