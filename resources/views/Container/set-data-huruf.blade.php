@if (count($datas)>0)
  <br>
  <div id="panel-data-latih">
    @include('Container.data-latih')
  </div>
@else
  <div class="info-data-notfound">
    Data huruf tidak ditemukan
  </div>
@endif
