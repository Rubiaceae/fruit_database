//註解，這是用來處理條碼輸入滿後，自動跳行的程式。
//條碼長度應為13，
//1碼格式
//6碼日期
//2碼天數
//4碼員工編號
var counter = 10;
var limit = 20;
function addInput(divName){
     if (counter == limit)  {
          alert("已達輸入上限" + counter + " 個");
     }
     else {
         var newdiv = document.createElement('div');
//	var theDiv = document.getElementById(divName);
//	theDiv.innerHTML +=  (counter + 1) + '. <input type="text" id= barcode'+counter+' name="barcode[]" size=13 onKeyup="autotab(this, \'barcode' + (counter+1) + '\')" maxlength=13><br><br>';
//	counter++;
          newdiv.innerHTML = (counter + 1) + '. <input type="text" id= barcode'+counter+' name="barcode[]" size=13 onKeyup="autotab(this, \'barcode' + (counter+1) + '\')" maxlength=13><br><br>';
          document.getElementById(divName).appendChild(newdiv);
          counter++;

     }
}
