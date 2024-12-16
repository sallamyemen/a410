

$(".btn").on("click", function() {
  $('#modal-pol').hide(); 
});

$("#modal-pol .close").on("click", function() {
  $('#modal-pol').hide();
    setdisc(1);   
});

function gettCookie(byname)	// возвращает по имени значение, здесь не используется
   {byname=byname+"=";
    nlen = byname.length;
    fromN = document.cookie.indexOf(byname)+0;
    if((fromN) != -1)
        {fromN +=nlen
         toN=document.cookie.indexOf(";",fromN)+0;
         if(toN == -1) {toN=document.cookie.length;}
         return unescape(document.cookie.substring(fromN,toN));
        }
    return null;
   }

 function parseCookie()   // Разделение cookie
   { var cookieList = document.cookie.split("; ");
   
   var cookieArray = new Array();
   for (var i = 0; i < cookieList.length; i++) {
       
       var name = cookieList[i].split("=");
      
       cookieArray[unescape(name[0])] = unescape(name[1]);
    }
   return cookieArray;
  }
 function settCookie(visits) {
    /* Счетчик числа посещений с указанием даты последнего посещения
       и определением срока хранения в 1 год. */
    var expireDate = new Date();
    var today = new Date();
    // Установка даты истечения срока хранения.
    expireDate.setDate(365 + expireDate.getDate());
    // Сохранение числа посещений.
    document.cookie = "visits=" + visits +
                      "; expires=" + expireDate.toGMTString() + ";";
    // Сохранение настоящей даты как времени последнего посещения.
    document.cookie = "LastVisit=" + escape(today.toGMTString()) +
                       "; expires=" + expireDate.toGMTString() + ";";
    }

function setdisc(disc) {    
    document.cookie = "Disclaimer=" + disc + ";";
    }

    if ("" == document.cookie)
	{ // Инициализация cookie.	 
		console.log("Поздравляю Вас с первым посещением нашего сайта.");        
	}    
    else 
    {
        var cookies = parseCookie();       
           
        if(cookies.visits != NaN) vcount = true;
        else if(cookies.visits === NaN) vcount = false;

        console.log('vcount ' + /*typeof*/ vcount);
        console.log(cookies.Disclaimer);

        if(vcount == true){
            if (cookies.Disclaimer == 1) 
                bval = 86400000;      
            else            
                bval = 1000;
        }        
            settCookie(isNaN(cookies.visits)?1:cookies.visits);	   
    }
