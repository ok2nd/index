function saveCookie(arg1,arg2,arg3,arg4){ //arg1=dataname arg2=data arg3=expiration days
	if(arg1&&arg2){
		if(arg3){
/*
			xDay = new Date;
			xDay.setDate(xDay.getDate() + eval(arg3)*60*60*24);
			xDay = xDay.toGMTString();
			_exp = ";expires=" + xDay;
*/
			var nowtime = new Date().getTime();
			var clear_time = new Date(nowtime + (60 * 60 * 24 * 1000 * arg3));
			var expires = clear_time.toGMTString();
			_exp = ";expires=" + expires;
		}
		else _exp ="";
		if(arg4){
			_path = ";path=" + arg4;
		}
		else _path= "";
		document.cookie = escape(arg1) + "=" + escape(arg2) + _exp + _path +";";
	}
}
function loadCookie(arg){ //arg=dataname
	if(arg){
		cookieData = document.cookie + ";" ;
		arg = escape(arg);
		startPoint1 = cookieData.indexOf(arg);
		startPoint2 = cookieData.indexOf("=",startPoint1) +1;
		endPoint = cookieData.indexOf(";",startPoint1);
		if(startPoint2 < endPoint && startPoint1 > -1 &&startPoint2-startPoint1 == arg.length+1){
			cookieData = cookieData.substring(startPoint2,endPoint);
			cookieData = unescape(cookieData);
			return cookieData
		}
	}
	return false
}
function deleteCookie(arg){ //arg=dataname
	if(arg){
		arg = escape(arg);
		yDay = new Date;
		yDay.setHours(yDay.getHours() - 1); 
		yDay = yDay.toGMTString(); 
		document.cookie = arg + "=xxx" + ";expires=" + yDay;
	}
}
