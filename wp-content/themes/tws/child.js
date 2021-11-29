//点击随机更换图片
function btn(){                 
  var img=document.getElementById("img");
  var ran=Math.floor((Math.random()*images.length));;                       
  img.src="https://tvax1.sinaimg.cn/mw690/"+images[ran]+".jpg";
}
//定时器
window.onload =function(){
  setInterval(btn,100000);//设置多久执行一次,1000毫秒=1秒
}
window.onload()
