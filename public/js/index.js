function just_number(obj){
  obj.value = obj.value.replace(/[^0-9]/g,'');
  return true;
}

function make_tabel(label,mpx,color){
  if (color) {
    str = "<table><tr><th>"+label+"</th>";
    for (var i = 0; i < mpx[0].length; i++) {
      str+="<th>"+i+"</th>";
    }
    str+="</tr>";
    for (var i = 0; i < mpx.length; i++) {
      str+="<tr><th>"+i+"</th>";
      for (var j = 0; j < mpx[i].length; j++) {
        if (mpx[i][j]==1) {
          str+="<td bgcolor='green'>1</td>";
        }else{
          str+="<td>0</td>";
        }
      }
      str+="</tr>";
    }
    str+= "</table>";
  }else{
    str = "<table><tr><th>"+label+"</th>";
    for (var i = 0; i < mpx[0].length; i++) {
      str+="<th>"+i+"</th>";
    }
    str+="</tr>";
    for (var i = 0; i < mpx.length; i++) {
      str+="<tr><th>"+i+"</th>";
      for (var j = 0; j < mpx[i].length; j++) {
        str+="<td>"+mpx[i][j]+"</td>";
      }
      str+="</tr>";
    }
    str+= "</table>";
  }
  document.write(str);
}

function preprocessing(){
  this.grayscale = function(px,width,height){
    mpx = [];
    mpx[0] = [];
    baris = 0;
    kolom = 0;
    rgb = [];
    height -= 1;
    pre = new preprocessing();
    for (var i=0;i<px.data.length;i+=4) {
      //GRAYSCALE
      r = px.data[i];
      g = px.data[(i+1)];
      b = px.data[(i+2)];
      temp = Math.round(0.299*r+0.587*g+0.114*b);
      //end GRAYSCALE

      //PEMBENTUKAN MATRIKS
      mpx[baris][kolom++] = temp;
      if(kolom == width && baris<height){
        baris++;
        kolom = 0;
        mpx[baris] = [];
      }
    }
    return mpx;
  }
  var integral_image = function(mpx){
    g=[];
    for(i=0;i<mpx.length;i++){
      g[i] = [];
      for(j=0;j<mpx[i].length;j++){
        switch (true) {
          case (i==0 && j==0):
            g[0][0] = mpx[i][j];
          break;
          case (i==0 && j>=1):
            g[0][j] = mpx[i][j]+g[0][(j-1)];
          break;
          case (j==0):
            g[i][0] = mpx[i][j]+g[(i-1)][0];
          break;
          default :
            g[i][j] = mpx[i][j]+g[(i-1)][j]+g[i][(j-1)]-g[(i-1)][(j-1)];
        }
      }
    }
    return g;
  }
  var mean_integral_image = function(x,y,g,M,N,wx,wy,dx,dy,warna){
    hitung = 0;
    //cek apakah ada mask yang memiliki nelai negatif
    a = 0;
    if (warna==255) {
      nc = Math.trunc(wx/2);
      nr = Math.trunc(wy/2);
      switch (true) {
        case ((x-nr)<0 && (y-nc)<0):
          //jumlah sel kosong maksudnya adalah jumlah nilai 255
          rp = Math.abs(x-nr);
          a = (((rp*wy)+(Math.abs(y-nc)*(wx-rp)))*255)/(wx*wy);
        break;
        case ((x-nr)<0 && (y+nc)>N):
          rp = Math.abs(x-nr);
          a = (((rp*wy)+(((y+nc)-N)*(wx-rp)))*255)/(wx*wy);
        break;
        case ((x+nr)>M && (y-nc)<0):
          rp = (x+nr)-M;
          a = (((rp*wy)+(Math.abs(y-nc)*(wx-rp)))*255)/(wx*wy);
        break;
        case ((x+nr)>M && (y+nc)>N):
          rp = (x+nr)-M;
          a = (((rp*wy)+(((y+nc)-N)*(wx-rp)))*255)/(wx*wy);
        break;
        case ((x-nr)<0):
        a = ((Math.abs(x-nr)*wy)*255)/(wx*wy);
        break;
        case ((x+nr)>M):
          a = ((((x+nr)-M)*wy)*255)/(wx*wy);
        break;
        case ((y-nc)<0):
          a = ((Math.abs(y-nc)*wx)*255)/(wx*wy);
        break;
        case ((y+nc)>N):
          a = ((((y+nc)-N)*wy)*255)/(wx*wy);
        break;
      }
    }
    for(iterasi=0;iterasi<=3;iterasi++){
      if (iterasi<2) {
        kons = 1;
      }else{
        kons = -1;
      }
      switch (iterasi) {
        case 0:
          hitungx = x+dx-1;
          hitungy = y+dy-1;
        break;
        case 1:
          hitungx = x-dx;
          hitungy = y-dy;
        break;
        case 2:
          hitungx = x-dx;
          hitungy = y+dy-1;
        break;
        case 3:
          hitungx = x+dx-1;
          hitungy = y-dy;
        break;
      }

      switch (true) {
        case (hitungx<0 || hitungy<0):
          // hitung+= kons*warna;
          continue;
        break;
        case (hitungx>M && hitungy<=N):
          hitung+= kons*g[M][hitungy];
        break;
        case (hitungy>N && hitungx<=M):
          hitung+= kons*g[hitungx][N];
        break;
        case (hitungy>N && hitungx>M):
          hitung+= kons*g[M][N];
        break;
        default:
          hitung+= kons*g[hitungx][hitungy];
      }
    }
    return (hitung/(wx*wy))+a;
  }
  var insert_integral_image = function(i,j,g,mpx){
    switch (true) {
      case (i==0 && j==0):
        return mpx;
      break;
      case (i==0 && j>=1):
        return mpx+g[0][(j-1)];
      break;
      case (j==0):
        return mpx+g[(i-1)][0];
      break;
      default :
        return mpx+g[(i-1)][j]+g[i][(j-1)]-g[(i-1)][(j-1)];
    }
  }
  var sauvola = function(mpx,temp_mpx){
    k = 0.1;
    sd = mpx-temp_mpx;
    if (sd==1) {
      sd=-1;
    }
    threshold = temp_mpx*(1+k*((sd/(1-sd))-1));
    if (mpx>threshold) {
      return 0;
    }else{
      return 1;
    }
  }
  this.smoothing = function(range_baris,range_kolom,mpx,stats,imgdata){
    // this = new preprocessing();
    temp_mpx = [];
    g = integral_image(mpx);
    dx = Math.round(range_baris/2);
    dy = Math.round(range_kolom/2);
    switch (stats) {
      case 0:
      case 1:
        M = mpx.length-1;
        N = mpx[0].length-1;
        if (stats==0) {
          new_g = [];
          for(i=0;i<mpx.length;i++){
            temp_mpx[i] = [];
            new_g[i]=[];
            for(j=0;j<mpx[i].length;j++){
              temp_mpx[i][j] = Math.round(mean_integral_image(i,j,g,M,N,parseInt(range_baris),parseInt(range_kolom),dx,dy,255));
              new_g[i][j] = insert_integral_image(i,j,new_g,temp_mpx[i][j]);
            }
          }
          for(i=0;i<temp_mpx.length;i++){
            for(j=0;j<temp_mpx[i].length;j++){
              // mean = Math.round(mean_integral_image(i,j,new_g,M,N,24,24,12,12,255));
              mean = Math.round(mean_integral_image(i,j,new_g,M,N,5,5,3,3,255));
              temp_mpx[i][j] = sauvola(temp_mpx[i][j],mean);
            }
          }
        }else{
          for(i=0;i<mpx.length;i++){
            temp_mpx[i] = [];
            for(j=0;j<mpx[i].length;j++){
              temp_mpx[i][j] = Math.round(mean_integral_image(i,j,g,M,N,parseInt(range_baris),parseInt(range_kolom),dx,dy,0));
              if (temp_mpx[i][j]>0) {
                temp_mpx[i][j] = 1;
              }else{
                temp_mpx[i][j] = 0;
              }
            }
          }
        }
        return temp_mpx;
      break;
      case 2:
        M = mpx.length-1;
        N = mpx[0].length-1;
        m = 0;
        for(i=0;i<mpx.length;i++){
          temp_mpx[i] = [];
          for(j=0;j<mpx[i].length;j++){
            temp_mpx[i][j] = mean_integral_image(i,j,g,M,N,parseInt(range_baris),parseInt(range_kolom),dx,dy,0);
            if (temp_mpx[i][j]>0) {
              temp_mpx[i][j]=1;
              imgdata.data[m++] = 255;
              imgdata.data[m++] = 255;
              imgdata.data[m++] = 255;
              imgdata.data[m++] = 255;
            }else{
              temp_mpx[i][j]=0;
              imgdata.data[m++] = 0;
              imgdata.data[m++] = 0;
              imgdata.data[m++] = 0;
              imgdata.data[m++] = 255;
            }
          }
        }
        return temp_mpx;
      break;
    }
  }
  var insert_matrix = function(m_pixel){
    for (i=0;i<m_pixel.length;i++) {
      m_pixel[i].unshift(0);
      m_pixel[i].push(0);
    }
    temp = [];
    for (i=0;i<m_pixel[0].length;i++) {
      temp[i] = 0;
    }
    m_pixel.unshift(temp);
    m_pixel.push(temp);
    return m_pixel;
  }
  this.segmentation = function(mpx,ctx,sts){
    var make_col = function(k,batas,temp_matrix){
      ukuran = 28;
      string = "";

      col = document.createElement("div");
      col.className = "inblock panel-huruf";
      col.id = "div"+k;
      cvs = document.createElement("canvas");
      var img = document.createElement("img");
      img.width = ukuran;
      img.height = ukuran;
      ctx = cvs.getContext("2d");
      ctx.canvas.width = ukuran;
      ctx.canvas.height = ukuran;
      ctx.fillStyle = "rgba(0,0,0,255)";
      ctx.fillRect(0,0,cvs.width,cvs.height);
      ctx.fillStyle = "rgba(255,255,255,255)";

      //scaling cvs
      width = ((batas[1] - batas[3])+1);
      height = ((batas[2] - batas[0])+1);
      if (width>=height) {
        s = ukuran/width;
        jum_y = parseInt(ukuran/2)-parseInt((s*height)/2);
        jum_x = 0;
      }else{
        s = ukuran/height;
        jum_x = parseInt(ukuran/2)-parseInt((s*width)/2);
        jum_y = 0;
      }

      mpx = [];
      for (p=0;p<ukuran;p++) {
        mpx[p] = [];
        for (q=0;q<ukuran;q++) {
          mpx[p][q] = 0;
        }
      }

      for (var p=0;p<temp_matrix.length;p++) {
        temp = temp_matrix[p].split(",");
        koor_x = ((parseInt(temp[1])-batas[3])*s)+jum_x;
        if(koor_x%1>0 && koor_x>=1){
          x = 2;
        }else{
          x = 1;
        }
        koor_x = Math.trunc(koor_x);
        koor_y = ((parseInt(temp[0])-batas[0])*s)+jum_y;
        if(koor_y%1>0 && koor_y>=1){
          y = 2;
        }else{
          y = 1;
        }
        koor_y = Math.trunc(koor_y);
        ctx.fillRect(koor_x,koor_y,x,y);
        for (q=0;q<y;q++) {
          for (r=0;r<x;r++) {
            if (mpx[(koor_y+q)]!=null && mpx[(koor_y+q)][(koor_x+r)]!=null) {
              mpx[(koor_y+q)][(koor_x+r)] = 1;
            }
          }
        }
      }

      //bebaskan memori
      delete width;delete koor_x;delete koor_y;delete temp;delete s;
      delete height;delete jum_x;delete jum_y;

      imgdata = ctx.getImageData(0,0,ukuran,ukuran);
      var pre = new preprocessing();
      // if (temp_matrix.length<=240) {
      //   mpx = pre.smoothing(3,3,mpx,2,imgdata);
      // }
      // mpx = pre.insert_matrix(mpx);
      // pre.thinning(mpx,1,imgdata);
      // make_tabel('<i>tob<sub>0,x,y</sub></i>',mpx,true);
      // alert();
      for (p=0;p<mpx.length;p++) {
        for (q=0;q<mpx[p].length;q++) {
          if (mpx[p][q]==1) {
            string+="1";
          }else{
            string+="0";
          }
        }
        if (p!=(mpx.length-1)) {
          string+="|";
        }
      }

      ctx.putImageData(imgdata,0,0);

      img.src = cvs.toDataURL("image/png");
      img.id = "img"+k;
      img.style = "display:block;width:50px;height:50px";
      col.appendChild(img);
      inp = document.createElement("input");
      inp.type = "text";
      inp.name = "huruf"+k;
      inp.id = "huruf"+k;
      inp.placeholder = "Isi";
      inp.setAttribute("required","true");
      inp.style = "display:block;height:20;border-radius:0px;width:50px";
      inp.maxLength = 1;
      inp.onkeydown = function(ev){
        if (ev.shiftKey && ev.keyCode == 9) {
          ev.preventDefault();
          this.parentElement.previousSibling.childNodes[1].focus();
        }else if (ev.keyCode == 9) {
          ev.preventDefault();
          this.parentElement.nextSibling.childNodes[1].focus();
        }
      }
      col.appendChild(inp);
      textarea = document.createElement("textarea");
      textarea.id = "biner"+k;
      textarea.name = "biner"+k;
      textarea.value = string;
      textarea.style = "display:none";
      col.appendChild(textarea);
      textarea = document.createElement("textarea");
      textarea.id = "imgbase64"+k;
      textarea.name = "imgbase64"+k;
      textarea.value = cvs.toDataURL("image/png");
      textarea.style = "display:none";
      col.appendChild(textarea);
      btn = document.createElement("button");
      btn.innerHTML = "X";
      btn.type="button";
      btn.setAttribute("target",k);
      btn.onclick = function(){
        document.getElementById("div"+this.getAttribute("target")).remove();
        divcol = document.getElementsByClassName("panel-huruf");
        length = divcol.length;
        for (var x=0;x<length;x++) {
          divcol[x].id = "div"+x;
          divcol[x].childNodes[0].id = "img"+x;
          divcol[x].childNodes[1].id = "huruf"+x;
          divcol[x].childNodes[2].id = "biner"+x
          divcol[x].childNodes[3].id = "imgbase64"+x;
          divcol[x].childNodes[4].setAttribute("target",x);
          divcol[x].childNodes[0].name = "img"+x;
          divcol[x].childNodes[1].name = "huruf"+x;
          divcol[x].childNodes[2].name = "biner"+x
          divcol[x].childNodes[3].name = "imgbase64"+x;
        }
      }
      col.appendChild(btn);
      return col;
    }
    var matrix_alphabet = function(batas,temp_matrix){
        ukuran = 28;
        //scaling cvs
        width = ((batas[1] - batas[3])+1);
        height = ((batas[2] - batas[0])+1);
        if (width>=height) {
          s = ukuran/width;
          jum_y = parseInt(ukuran/2)-parseInt((s*height)/2);
          jum_x = 0;
        }else{
          s = ukuran/height;
          jum_x = parseInt(ukuran/2)-parseInt((s*width)/2);
          jum_y = 0;
        }
        //membuat matrix
        mpx = [];
        for (p=0;p<ukuran;p++) {
          mpx[p] = [];
          for (q=0;q<ukuran;q++) {
            mpx[p][q] = 0;
          }
        }

        for (var p=0;p<temp_matrix.length;p++) {
          temp = temp_matrix[p].split(",");
          koor_x = ((parseInt(temp[1])-batas[3])*s)+jum_x;
          if((koor_x%1)>0 && koor_x>=1){
            x = 2;
          }else{
            x = 1;
          }
          koor_x = Math.trunc(koor_x);
          koor_y = ((parseInt(temp[0])-batas[0])*s)+jum_y;
          if((koor_y%1)>0 && koor_y>=1){
            y = 2;
          }else{
            y = 1;
          }
          koor_y = Math.trunc(koor_y);
          for (q=0;q<y;q++) {
            for (r=0;r<x;r++) {
              if (mpx[(koor_y+q)]!=null && mpx[(koor_y+q)][(koor_x+r)]!=null) {
                mpx[(koor_y+q)][(koor_x+r)] = 1;
              }
            }
          }
        }

        //bebaskan memori
        delete width;delete koor_x;delete koor_y;delete temp;delete s;
        delete height;delete jum_x;delete jum_y;

        // var pre = new preprocessing();
        // if (temp_matrix.length<=300) {
        //   mpx = pre.smoothing(3,3,mpx,1);
        // }
        // mpx = pre.insert_matrix(mpx);
        // mpx = pre.thinning(mpx,0);
        // temp_mpx = [];
        // for (p=0;p<mpx.length;p++) {
        //   temp_mpx[p] = [];
        //   for (q=0;q<mpx[p].length;q++) {
        //     temp_mpx[p][q] = mpx[p][q];
        //   }
        // }
        // make_tabel('<i>I<sub>0,x,y</sub></i>',temp_mpx,true);
        // return temp_mpx;
        return mpx;
      }
    // Connected Component Labeling
    temp_index = 0;
    temp_matrix = [];
    temp_matrix[temp_index] = [];
    batas = [];
    batas[temp_index] = [];
    threshold = 20;
    baris = mpx.length;
    kolom = mpx[0].length;
    for (var i=0;i<baris;i++) {
      for (var j=0;j<kolom;j++) {
        if (mpx[i][j]==1) {
          if(temp_matrix[temp_index].length != 0){
            if (temp_matrix[temp_index].length>threshold) {
              temp_index++;
            }
            batas[temp_index] = [];
            temp_matrix[temp_index] = [];
          }
          mpx[i][j] = 0;
          koor = [];
          temp_matrix[temp_index].push(i+","+j);
          batas[temp_index][0] = i;
          batas[temp_index][1] = j;
          batas[temp_index][2] = i;
          batas[temp_index][3] = j;
          cacah = 0;
          do {
            if (cacah>0) {
              koor = temp_matrix[temp_index][cacah++].split(",");
              koor[0] = parseInt(koor[0]);
              koor[1] = parseInt(koor[1]);
              if (batas[temp_index][0] > koor[0]) {
                batas[temp_index][0] = koor[0];
              }
              if (batas[temp_index][1] < koor[1]) {
                batas[temp_index][1] = koor[1];
              }
              if (batas[temp_index][2] < koor[0]) {
                batas[temp_index][2] = koor[0];
              }
              if (batas[temp_index][3] > koor[1]) {
                batas[temp_index][3] = koor[1];
              }
            }else{
              koor[0] = i;
              koor[1] = j;
              cacah++;
            }
            for (k=-5;k<=5;k++) {
              temp_baris = koor[0] + k;
              if (temp_baris>=0 && temp_baris<baris) {
                for (l=-5;l<=5;l++) {
                  temp_kolom = koor[1] + l;
                  if ((temp_kolom>=0 && temp_kolom<kolom) && mpx[temp_baris][temp_kolom]==1) {
                    mpx[temp_baris][temp_kolom] = 0;
                    temp_matrix[temp_index].push(temp_baris+","+temp_kolom);
                  }
                }//endfor l
              }//endif
            }//endfor k
          } while(cacah<temp_matrix[temp_index].length);
        }
      }
    }
    if (temp_matrix[temp_index].length<=threshold) {
      batas.pop();
      temp_matrix.pop();
    }


    // str = "<table><tr><th colspan='2'><i>border<sub>1,j,x,y</sub></i></th></tr>";
    // for (var i=0;i<batas[1].length;i++) {
    //   str+="<tr><th>"+i+"</th><td>"+batas[1][i]+"</td></tr>";
    // }
    // str +="</table>";
    // document.write(str);
    // return false;

    if (sts==0) {
      if (document.getElementById("rowid")==null){
        row = document.createElement("div");
        row.className = "row";
        row.id = "rowid";
        k = 0;
      }else{
        row = document.getElementById("rowid");
        k = row.children.length;
      }
      urutan = [];
      urutan[0] = batas[0][3];
      threshold = batas[0][2];
      firstnode = 0;
      col = make_col(k++,batas[0],temp_matrix[0]);
      row.appendChild(col);
      for (var i=1;i<temp_matrix.length;i++) {
        col = make_col(k,batas[i],temp_matrix[i]);
        if (batas[i][0]>threshold) {
          firstnode = k;
          threshold = batas[i][2];
          urutan = [];
          urutan[0] = batas[i][3];
          row.appendChild(col);
        }else{
          if (batas[i][3]<urutan[0]) {
            row.insertBefore(col,row.childNodes[firstnode]);
            urutan.unshift(batas[i][3]);
          }else if (batas[i][3]>urutan[(urutan.length-1)]) {
            row.appendChild(col);
            urutan.push(batas[i][3]);
          }else{
            for (var j=1;j<urutan.length;j++) {
              if (batas[i][3]<urutan[j]) {
                row.insertBefore(col,row.childNodes[(firstnode+j)]);
                urutan.splice(j,0,batas[i][3]);
                break;
              }
            }
          }
        }
        k++;
      }
      document.getElementById("hasil-segmen").appendChild(row);
    }else{
      urutan = [];
      urutan[0] = batas[0][3];
      threshold = batas[0][2];
      firstnode = 0;
      alfabet = [];
      alfabet[0] = matrix_alphabet(batas[0],temp_matrix[0]);
      k = 1;
      for (var i=1;i<temp_matrix.length;i++) {
        temp = matrix_alphabet(batas[i],temp_matrix[i]);
        if (batas[i][0]>threshold) {
          firstnode = k;
          threshold = batas[i][2];
          urutan = [];
          urutan[0] = batas[i][3];
          alfabet.push(temp);
        }else{
          if (batas[i][3]<urutan[0]) {
            alfabet.splice(firstnode,0,temp);
            urutan.unshift(batas[i][3]);
          }else if (batas[i][3]>urutan[(urutan.length-1)]) {
            alfabet.push(temp);
            urutan.push(batas[i][3]);
          }else{
            for (var j=1;j<urutan.length;j++) {
              if (batas[i][3]<urutan[j]) {
                alfabet.splice((firstnode+j),0,temp);
                urutan.splice(j,0,batas[i][3]);
                break;
              }
            }
          }
        }
        k++;
      }
      return alfabet;
    }

  }
  this.save_data = function(){
    ln = $("#hasil-segmen").children().length;
    if(ln>0){
      ajax_with_form("form-hasil-segmen","/simpan-huruf","data-huruf");
    }else{
      pesan_alert("Info","Tidak ada data untuk disimpan","info");
    }
  }
  this.edit_data = function(val,no,imgname){
    if (val!="") {
      var hal = $(".pagination .active span").text();
      generate_token();
      ajax_operation("post",window.location.href+"/simpan-perubahan/"+no+"/"+imgname+"/"+val+"/"+hal,"panel-data-latih");
    }
  }
  this.delete_data = function(no,target,src){
    if (no!="") {
      title = "Peringatan";
      content = "<img src='"+src+"'><br>Hapus data ini?";
      method = "post";
      if (target=="panel-data-latih") {
        hal = $(".pagination .active span").text();
      }else{
        hal = 0;
      }
      url = window.location.href+"hapus-huruf/"+no+"/"+hal;
      konfirmasi(title,content,method,url,target);
    }
  }
  this.send_to_testing = function(ctx,pixel){
    m_pixel = this.grayscale(pixel,ctx.canvas.width,ctx.canvas.height);
    m_pixel = this.smoothing(3,3,m_pixel,0);
    alfabet = this.segmentation(m_pixel,ctx,1);
    biner = [];
    for (var i=0;i<alfabet.length;i++) {
      biner[i] = [];
      biner[i][0] = alfabet[i];
    }
    generate_token();
    ajax_with_data(biner,'send-testing-data','temp-hasil-segmen');
  }
}

function resize_fieldset(){
  if ($("#olah-data-btn").hasClass("active")) {
    parent = "#olah-data";
  }else{
    parent = "#testing";
  }
  if ($(parent+" #panel-upload fieldset").height()<400) {
    $(parent+" #panel-hasil fieldset").height(400);
  }else{
    $(parent+" #panel-hasil fieldset").height($(parent+" #panel-upload fieldset").height());
  }
}

function draggable_node(event,obj,coordwidth,coordheight,coordid){
  var pos_x = Math.ceil(event.pageX - $("#"+coordid).offset().left);
  var pos_y = Math.ceil(event.pageY - $("#"+coordid).offset().top);
  if(pos_x >= 0 && pos_y >= 0 && pos_x <= coordwidth && pos_y <= coordheight){
    event.target.setAttribute('cx', pos_x);
    event.target.setAttribute('cy', pos_y);
    if ($(obj).attr("no-circle")==1) {
      var x = new Array(pos_x,parseInt($("#"+coordid+" .node").eq(0).attr("cx")));
      var y = new Array(pos_y,parseInt($("#"+coordid+" .node").eq(0).attr("cy")));
    }else{
      var x = new Array(parseInt($("#"+coordid+" .node").eq(0).attr("cx")),pos_x);
      var y = new Array(parseInt($("#"+coordid+" .node").eq(0).attr("cy")),pos_y);
    }
    switch (true) {
      case (x[0]<=x[1] && y[0]<=y[1]):
        var cx = x[0];
        var cy = y[0];
      break;
      case (x[0]<=x[1] && y[0]>y[1]):
        var cx = x[0];
        var cy = y[1];
      break;
      case (x[0]>x[1] && y[0]<=y[1]):
        var cx = x[1];
        var cy = y[0];
      break;
      case (x[0]>x[1] && y[0]>y[1]):
        var cx = x[1];
        var cy = y[1];
      break;
    }
    $("#"+coordid+' .rect').attr("x",cx);
    $("#"+coordid+' .rect').attr("y",cy);
    $("#"+coordid+' .rect').attr("width",Math.abs(x[1]-x[0]));
    $("#"+coordid+' .rect').attr("height",Math.abs(y[1]-y[0]));
    move_panel_tbl(coordid);
  }
}

function move_panel_tbl(coordid){
  topc = [(parseInt($("#"+coordid+" circle[no-circle=1]").attr("cy"))-20),(parseInt($("#"+coordid+" circle[no-circle=2]").attr("cy"))-20)];
  leftc = [(parseInt($("#"+coordid+" circle[no-circle=1]").attr("cx"))-68),(parseInt($("#"+coordid+" circle[no-circle=2]").attr("cx"))-68)];
  if(topc[0]<topc[1]){
    temptop = topc[0];
  }else{
    temptop = topc[1];
  }
  if (leftc[0]>leftc[1]) {
    templeft = leftc[0];
  }else{
    templeft = leftc[1];
  }
  if (coordid == "coordmap") {
    $("#panel-tbl-crop").css("top",temptop);
    $("#panel-tbl-crop").css("left",templeft);
  }else{
    $("#testing-tbl-crop").css("top",temptop);
    $("#testing-tbl-crop").css("left",templeft);
  }
}

function clear_nodes(coordid){
  $("#"+coordid+" circle,#"+coordid+" rect").remove();
  if (coordid=="coordmap") {
    $("#panel-tbl-crop").hide();
  }else{
    $("#testing-tbl-crop").hide();
  }
}

function clear_node(event,obj,coordid){
  event.preventDefault();
  if(event.which==3){
    var length = $("#"+coordid).find(".node").length;
    if(length==2){
      $("#"+coordid+" .rect").remove();
    }
    $(obj).remove();
    for (var i=1;i<=(length-1); i++) {
      $("#"+coordid+" .node").eq(i-1).attr("no-circle",i);
    }
  }
  if (coordid == "coordmap") {
    $("#panel-tbl-crop").hide();
  }else{
    $("#testing-tbl-crop").hide();
  }
}

function cut_image(cvsid,coordid){
  var ctx = document.getElementById(cvsid).getContext("2d");
  seper_w = ctx.canvas.width/parseInt($("#"+cvsid).width());
  seper_h = ctx.canvas.height/parseInt($("#"+cvsid).height());
  var imgdata = ctx.getImageData((parseInt($("#"+coordid+" rect").attr("x"))*seper_w),(parseInt($("#"+coordid+" rect").attr("y"))*seper_h),
                (parseInt($("#"+coordid+" rect").width())*seper_w),(parseInt($("#"+coordid+" rect").height())*seper_h));
  ctx.canvas.width = parseInt($("#"+coordid+" rect").width())*seper_w;
  ctx.canvas.height = parseInt($("#"+coordid+" rect").height())*seper_h;
  ctx.putImageData(imgdata,0,0);
  clear_nodes(coordid);
  resize_fieldset();
}

function make_area(pageX,pageY,coordid){
    if ($("#"+coordid+" circle").length<2) {
      var pos_x = Math.ceil(pageX) - $("#"+coordid).offset().left;
      var pos_y = Math.ceil(pageY) - $("#"+coordid).offset().top;
      var node = document.createElement("circle");
      var existing = $("#"+coordid).html();
      var length = $("#"+coordid+" circle").length;
      var insert = '<circle milik="'+coordid+'" bentuk="rect" r="5" cx="'+pos_x+'" cy="'+pos_y+'" no-circle="'+(length+1)+'" draggable="true" class="node"></circle>'
      if (length==1) {
        var x = new Array(parseInt($("#"+coordid+" circle").eq(0).attr("cx")),pos_x);
        var y = new Array(parseInt($("#"+coordid+" circle").eq(0).attr("cy")),pos_y);
        switch (true) {
          case (x[0]<=x[1] && y[0]<=y[1]):
            var rangeshape = "<rect milik='"+coordid+"' x='"+x[0]+"' y='"+y[0]+"' width='"+Math.abs(x[1]-x[0])+"' height='"+Math.abs(y[1]-y[0])+"' class='rect'></rect>";
          break;
          case (x[0]<=x[1] && y[0]>y[1]):
            var rangeshape = "<rect milik='"+coordid+"' x='"+x[0]+"' y='"+y[1]+"' width='"+Math.abs(x[1]-x[0])+"' height='"+Math.abs(y[1]-y[0])+"' class='rect'></rect>";
          break;
          case (x[0]>x[1] && y[0]<=y[1]):
            var rangeshape = "<rect milik='"+coordid+"' x='"+x[1]+"' y='"+y[0]+"' width='"+Math.abs(x[1]-x[0])+"' height='"+Math.abs(y[1]-y[0])+"' class='rect'></rect>";
          break;
          case (x[0]>x[1] && y[0]>y[1]):
            var rangeshape = "<rect milik='"+coordid+"' x='"+x[1]+"' y='"+y[1]+"' width='"+Math.abs(x[1]-x[0])+"' height='"+Math.abs(y[1]-y[0])+"' class='rect'></rect>";
          break;
        }
        $("#"+coordid).html(rangeshape+existing+insert);
        move_panel_tbl(coordid);
        if (coordid == "coordmap") {
          $("#panel-tbl-crop").show();
        }else{
          $("#testing-tbl-crop").show();
        }
      }else{
        $("#"+coordid).html(existing+insert);
      }
    }
    // $("#clearbtn").prop("disabled",false);
    // $("#loadscript").html($("#loadscript").html());
}

function default_img(ctx,url){
  var img = document.createElement("img");
  img.width = 50;
  img.height = 50;
  img.src = url;
  ctx.canvas.width = 800;
  ctx.canvas.height = 600;
  ctx.drawImage(img,350,240,100,120);
}

function previewImage(data){
  var oFReader = new FileReader();
  oFReader.readAsDataURL(data);
  oFReader.onload = function (oFREvent) {
    var img = document.createElement("img");
    img.setAttribute("src",oFREvent.target.result);
    img.onload = function(){
      if ($("#olah-data-btn").hasClass("active")) {
        idcvs = "img-upload";
        document.getElementById("img-url").value = document.getElementById("upload-file").value;
      }else{
        idcvs = "testing-upload";
        document.getElementById("testing-url").value = document.getElementById("upload-file").value;
      }
      document.getElementById(idcvs).setAttribute("sts",1);
      ctx = document.getElementById(idcvs).getContext("2d");
      ctx.canvas.width = img.width;
      ctx.canvas.height = img.height;
      ctx.drawImage(img,0,0,img.width,img.height);
      var width = $("#"+idcvs).width();
      var height = (((width/img.width)*img.height)/img.height)*100;
      $("#"+idcvs).css({"width":"100%","height":height+"%"});
      $("#"+idcvs).attr("sts","1");
      resize_fieldset();
    }
  }
}

function validate_input(oInput) {
  var _validFileExtensions = [".jpg",".jpeg",".png"];
  if (oInput.type == "file") {
    console.log(oInput.value.split("\\"));
    var sFileName = oInput.value.split("\\");
    sFileName = sFileName[sFileName.length-1];
    var namaFile = sFileName.substring(sFileName.lastIndexOf('\\')+1, sFileName.length);
    var extensiFile = sFileName.split('.');
    var indexTerakhir = sFileName.lastIndexOf('\\');
    if (sFileName.length > 0) {
      var blnValid = false;
      for (var j = 0; j < _validFileExtensions.length; j++) {
        var sCurExtension = _validFileExtensions[j];
          if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
            blnValid = true;
            break;
          }
      }
      if (!blnValid) {
        $.confirm({
          theme: 'modern', animation: 'zoom',
          closeAnimation: 'zoom',
          animateFromElement: false,
          useBootstrap:false,boxWidth: "20%",
          closeIcon: 'fa fa-close',icon:"fa fa-times-circle",
          type: "red", closeIcon: true,
          title: "Error",
          content: "File '"+sFileName+"' tidak diizinkan,\nberikut ekstensi file yang digunakan ("+_validFileExtensions.join(", ")+")",
          buttons:{
            ya: function(){}
          }
        });
        oInput.value = "";
        return false;
      }else{
        previewImage(oInput.files[0]);
      }
    }
  }
  return true;
}

function generate_token(){
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
}

function konfirmasi(title,content,method,url,target){
  $.confirm({
    theme: 'modern', animation: 'zoom',
    animateFromElement:false,
    closeAnimation: 'zoom',
    useBootstrap:false,boxWidth: "20%",
    closeIcon: 'fa fa-close',icon:"fa fa-warning",
    type: "orange", closeIcon: true,
    title: "<span style='font-size:24px' class='font-content'>"+title+"</span>",
    content: "<span class='font-content'>"+content+"</span>",
    buttons:{
      Ya: function(){
        if(method == "post"){
          generate_token();
        }
        ajax_operation(method,url,target);
      },
      Tidak: function(){}
    }
  });
}

function ajax_operation(tipe,url,content){
  $.ajax({
    type: tipe,
    url: url,
    cache:false,
    beforeSend  : function(){
      $("#load").show();
    },
    success: function(data){
      $("#load").hide();
      if((content!="")&&(content!=1)){
        $("#"+content).html(data);
      }else if(content==1){
        location.reload(true);
      }
    },
    error: function(req,sts,err){
      $("#load").hide();
      console.log(req.responseText);
    }
  });
}

function ajax_with_data(data,url,content){
  data = JSON.stringify(data);
  $.ajax({
    type: "post",
    data: {testing:data},
    url: url,
    beforeSend  : function(){
        $("#load").show();
      },
    success: function(data){
      $("#load").hide();
      if((content!="")&&(content!=1)){
        $("#"+content).html(data);
      }else if(content==1){
        location.reload(true);
      }
    },
    error: function(req,sts,err){
      $("#load").hide();
      console.log(req.responseText);
    }
  });
}

function ajax_with_form(formname,url,content){
  var form = $('#'+formname)[0];
  var frdata = new FormData(form);
  $.ajax({
    type: "POST",
    data: frdata,
    url: url,
    contentType: false,
    processData: false,
    cache:false,
    async:false,
    timeout: 100000,
    beforeSend  : function(){
        $("#load").show();
      },
    success: function(data){
      $("#load").hide();
      if((content!="")&&(content!=1)){
        $("#"+content).html(data);
      }else if(content==1){
        location.reload(true);
      }
    },
    error: function(req,sts,err){
      $("#load").hide();
      console.log(req.responseText);
    }
  });
}

function clear_panel_upload(ctx,url){
  resize_fieldset();
  default_img(ctx,url);
}

function delete_huruf(no,target,src){
  var pre = new preprocessing();
  pre.delete_data(no,target,src);
}

function pesan_alert(title,content,sts){
    switch (sts) {
      case 'info':
        icon = "fa-exclamation";
        color = "blue";
      break;
      case 'error':
        icon = "fa-times-circle";
        color = "red";
      break;
    }
   $.confirm({
     theme: 'modern', animation: 'zoom',
     animateFromElement:false,
     closeAnimation: 'zoom',
     useBootstrap:false,boxWidth: "20%",
     closeIcon: 'fa fa-close',icon:"fa "+icon,
     type: color, closeIcon: true,
     title: "<span style='font-size:24px' class='font-content'>"+title+"</span>",
     content: "<span class='font-content'>"+content+"</span>",
     buttons:{
       ok: function(){
       }
     }
   });
}
