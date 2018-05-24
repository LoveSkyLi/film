$(function(){
	var delParent;
	var defaults = {
		fileType         : ["jpg","png","bmp","jpeg"],   // 上传文件的类型
		fileSize         : 1024 * 1024 * 10                  // 上传文件的大小 10M
	};
		/*点击图片的文本框*/
	$(".file").change(function(){

		var idFile = $(this).attr("id");
		var file = document.getElementById(idFile);
		var imgContainer = $(this).parents(".z_photo"); //存放图片的父亲元素
		var fileList = file.files; //获取的图片文件
		var input = $(this).parent();//文本框的父亲元素
		var imgArr = [];
		//遍历得到的图片文件
		var numUp = imgContainer.find(".up-section").length;
		var totalNum = numUp + fileList.length;  //总的数量
		if(fileList.length > 5 || totalNum > 5 ){
			alert("上传图片数目不可以超过5个，请重新选择");  //一次选择上传超过5个 或者是已经上传和这次上传的到的总数也不可以超过5个
		}
		else if(numUp < 5){
			// console.log(fileList)
			fileList = validateUp(fileList);
			for(var i = 0;i<fileList.length;i++){
			 var imgUrl = window.URL.createObjectURL(fileList[i]);
			     imgArr.push(imgUrl);
		      
		   }
		}
	});

	$(".wsdel-ok").click(function(){
		$(".works-mask").hide();
		var numUp = delParent.siblings().length;
		if(numUp < 6){
			delParent.parent().find(".z_file").show();
		}
		 delParent.remove();
	});
	
	$(".wsdel-no").click(function(){
		$(".works-mask").hide();
	});
		
	function validateUp(files){
		var arrFiles = [];//替换的文件数组

		for(var i = 0, file; file = files[i]; i++){
			//获取文件上传的后缀名
			var newStr = file.name.split("").reverse().join("");
			// console.log(newStr)
			if(newStr.split(".")[0] != null){
					var type = newStr.split(".")[0].split("").reverse().join("");
					// console.log(type+"===type===");
					if(jQuery.inArray(type, defaults.fileType) > -1){
						ajax(file )	

						if (file.size >= defaults.fileSize) {
							alert(file.size);
							alert('您这个"'+ file.name +'"文件大小过大');	
						} else {
							// 在这里需要判断当前所有文件中
							
						}
					}else{
						alert('您这个"'+ file.name +'"上传类型不符合');	
					}
				}else{
					alert('您这个"'+ file.name +'"没有类型, 无法识别');	
				}
		}
		return arrFiles;
	}
    var img =''
    // var arrImg = []
    var arrimg =[]
	function ajax(arrFiles){
		var fd = new FormData();
    	fd.append('file', arrFiles);
		$.ajax({
			url: url + 'file/upload',
			type:'POST',
			 cache: false,
			contentType: false, //不可缺
			processData: false,
			data:fd,
			success:function(res){
				arrimg.push({'type':'pic','content':res.data.url})
				// console.log(arrimg)
				 img = `
					<img src="${res.data.url}" class='imgs' alt="">
				`
				// console.log(img)
				$('.body').append(img)
				$('.imgs').click(function(){
					var height = $('body').height()
					$('mask').height(height)
					$(".works-mask").show();
					delParent = $(this)
				})

			}
		})	
	}
		
	
	
})
