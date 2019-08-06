<script type="text/javascript">
matrix = [];
jumbiner = 0;

//Proses CNN
nlayer = 2;
stride = 1;
@if (count($biners)>0)
  @foreach ($biners as $biner)
  //Pembentukan matrix
  matrix[jumbiner] = [];
  matrix[jumbiner][1] = "{{$biner->huruf}}";
  biner = "{{$biner->biner}}";
  biner = biner.split("|");
  length = biner.length;
  for (i=0;i<length;i++) {
    biner[i] = biner[i].split(",");
    for (j=0;j<biner[i].length;j++) {
      biner[i][j] = parseInt(biner[i][j]);
    }
  }

  matrix[jumbiner][0] = deep_convolution(biner,nlayer,stride);

  textarea = document.createElement("textarea");
  textarea.name = "hasil_cnn"+jumbiner;
  textarea.value = matrix[jumbiner][0];
  inp = document.createElement("input");
  inp.type = "text";
  inp.name = "no"+jumbiner;
  inp.value = "{{$biner->no}}";
  document.getElementById("form-data-baru").appendChild(inp);
  document.getElementById("form-data-baru").appendChild(textarea);
  matrix[jumbiner][0] = matrix[jumbiner][0].split(",");
  jumbiner++;
  @endforeach
  //Proses penyimpanan
  var form = $('#form-data-baru')[0];
  var frdata = new FormData(form);
  $.ajax({
    type: "POST",
    data: frdata,
    url: window.location.href+'simpan-hasil-CNN',
    contentType: false,
    processData: false,
    cache:false,
    beforeSend  : function(){
      $("#load").show();
    },
    success: function(data){
      $("#load").hide();
      $("#form-data-baru").html(data);
      generate_token();
      ajax_operation("post",window.location.href+"hapus-berkala/{{$r1+50}}/{{$r2+50}}","message");
    },
    error: function(req,sts,err){
      $("#load").hide();
      console.log(req.responseText);
    }
  });

@endif
</script>
