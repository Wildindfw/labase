function Upload(){
    
    var form_data = new FormData();
    const LENGTH = 1024 * 1024;
    var start = 0;
    var end = start + LENGTH;
    var blob;
    var blob_num = 1;
    var is_stop = 0
    //对外方法，传入文件对象
    this.addFileAndSend = function(that,fun){

        var file = that.files[0];
        blob = cutFile(file);
        sendFile(blob,file,fun);
        blob_num  += 1;
    }
    //停止文件上传
    this.stop = function(){
      //  xhr.abort();
        is_stop = 1;
    }
    //切割文件
    function cutFile(file){
        var file_blob = file.slice(start,end);
        start = end;
        end = start + LENGTH;
        return file_blob;
    };
    //发送文件
    function sendFile(blob,file,fun){
        var total_blob_num = Math.ceil(file.size / LENGTH);
        var form_data = new FormData();
        form_data.append('file',blob);
        form_data.append('blob_num',blob_num);
        form_data.append('total_blob_num',total_blob_num);
        form_data.append('file_name',file.name);
		var xhr = new XMLHttpRequest();
        xhr.open('POST','/ajaxupload',true);
        xhr.onreadystatechange  = function () {
            if (xhr.readyState==4 && xhr.status==200)
            {
                var o = $.parseJSON(xhr.responseText);
                if(o.msg=='success'){
                    document.getElementById("upload_video_file").value =o.file_path;	
                    fun();
                }
				var progress;
				var progressObj = document.getElementById('finish');
				var pross_val = document.getElementById('pross');
				if(total_blob_num == 1){
					progress = '100%';
				}else{
					progress = Math.min(100,(blob_num/total_blob_num)* 100 ) +'%';
				}
				progressObj.style.width = progress;
				pross_val.innerHTML="上传进度"+parseInt(progress)+"%";
				
				var t = setTimeout(function(){
					if(start < file.size && is_stop === 0){
						blob = cutFile(file);
						sendFile(blob,file,fun);
						blob_num  += 1;
					}else{
						setTimeout(t);
					}
				},1000);
            }	
		
        }
        xhr.send(form_data);
    }
}
function getFileName(o){    var pos=o.lastIndexOf("\\");    return o.substring(pos+1);  }


$(document).ready(function(){
	 var fileForm = document.getElementById("file");
    var stopBtn = document.getElementById('stop');
    var upload = new Upload();
    fileForm.onchange = function(){
        $("#upload_video_file").val("");
        $("#pross").html(getFileName($("#file").val()))
    }
  /*  stopBtn.onclick = function(){
        this.value = "停止中";
        upload.stop();
        this.value = "已停止";
    }*/
});


   
