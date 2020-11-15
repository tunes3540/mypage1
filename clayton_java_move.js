// JavaScript Document
// Determine browser and version.

function Browser() {

  var ua, s, i;

  this.isIE    = false;
  this.isNS    = false;
  this.isOP    = false;
  
  this.version = null;

  ua = navigator.userAgent;
  
  //window.status=ua;
  s = "MSIE";
  if ((i = ua.indexOf(s)) >= 0) {
    this.isIE = true;
    this.version = parseFloat(ua.substr(i + s.length));
    return;
  }

  s = "Netscape6/";
  if ((i = ua.indexOf(s)) >= 0) {
    this.isNS = true;
    this.version = parseFloat(ua.substr(i + s.length));
    return;
  }
  
  s = "Opera";
  if ((i = ua.indexOf(s)) >= 0) {
    this.isOP = true;
    this.version = parseFloat(ua.substr(i + s.length));
    return;
  }
  

  // Treat any other "Gecko" browser as NS 6.1.

  s = "Gecko";
  if ((i = ua.indexOf(s)) >= 0) {
    this.isNS = true;
    this.version = 6.1;
    return;
  }
  
 
}

var browser = new Browser();


// Global object to hold drag information.

var dragObj = new Object();
dragObj.zIndex = 0;

//------------------------------------------ dragStart -----------------------------------------------------------

function dragStart(event, cid, id) {
  document.move_page.id.value = cid;
  var el;
  var x, y;
  
  // If an element id was given, find it. Otherwise use the element being
  // clicked on.

  if (id){
  
    dragObj.elNode = document.getElementById(id);
    
    }
  else {
    if (browser.isIE)
      dragObj.elNode = window.event.srcElement;
      
    if (browser.isNS)
      dragObj.elNode = event.target;

    // If this is a text node, use its parent element.

    if (dragObj.elNode.nodeType == 3)
      dragObj.elNode = dragObj.elNode.parentNode;
  }


 // Get cursor position with respect to the page.
 if (browser.isIE) {
    x = window.event.clientX + document.documentElement.scrollLeft
      + document.body.scrollLeft;
    y = window.event.clientY + document.documentElement.scrollTop
      + document.body.scrollTop;
  }
  if (browser.isNS) {
    x = event.clientX + window.scrollX;
    y = event.clientY + window.scrollY;
  }
  if (browser.isOP ) {
    x = window.event.clientX + document.documentElement.scrollLeft
      + document.body.scrollLeft;
    y = window.event.clientY + document.documentElement.scrollTop
      + document.body.scrollTop;
  }

  


 // Save starting positions of cursor and element.

  dragObj.cursorStartX = x;
  dragObj.cursorStartY = y;
  dragObj.elStartLeft  = parseInt(dragObj.elNode.style.left, 10);
  dragObj.elStartTop   = parseInt(dragObj.elNode.style.top,  10);

  if (isNaN(dragObj.elStartLeft)) dragObj.elStartLeft = 0;
  if (isNaN(dragObj.elStartTop))  dragObj.elStartTop  = 0;


  // Update element's z-index.

  dragObj.elNode.style.zIndex = ++dragObj.zIndex;


 // Capture mousemove and mouseup events on the page.

  if (browser.isIE) {
    document.attachEvent("onmousemove", dragGo);
    document.attachEvent("onmouseup",   dragStop);
    window.event.cancelBubble = true;
    window.event.returnValue = false;
  }
  if (browser.isNS) {
    document.addEventListener("mousemove", dragGo,   true);
    document.addEventListener("mouseup",   dragStop, true);
    event.preventDefault();
  }
  if (browser.isOP ) {
    document.attachEvent("onmousemove", dragGo);
    document.attachEvent("onmouseup",   dragStop);
    window.event.cancelBubble = true;
    window.event.returnValue = false;
  }

}

//---------------------------------------------- dragGo ----------------------------------------------------
function dragGo(event) {

  var x, y;

  // Get cursor position with respect to the page.

  if (browser.isIE) {console.log("IE");
    x = window.event.clientX + document.documentElement.scrollLeft
      + document.body.scrollLeft;
    y = window.event.clientY + document.documentElement.scrollTop
      + document.body.scrollTop;
  }
  if (browser.isNS) {console.log("NS");
    x = event.clientX + window.scrollX;
    y = event.clientY + window.scrollY;
  }
  if (browser.isOP ) {console.log("OP");
    x = window.event.clientX + document.documentElement.scrollLeft
      + document.body.scrollLeft;
    y = window.event.clientY + document.documentElement.scrollTop
      + document.body.scrollTop;
  }


left1 = (dragObj.elStartLeft + x - dragObj.cursorStartX);
top1 = (dragObj.elStartTop  + y - dragObj.cursorStartY) ;

document.getElementById("move_info").style.left = left1 + 25;
document.getElementById("move_info").style.top = top1 + 25;
document.getElementById("move_info").style.display = "block";
document.getElementById("move_info").innerHTML = "Left  Top<br>" + left1 + " " + top1;


/*window.status = " top1 " + top1 + " left1 " + left1;*/


    // Move drag element by the same amount the cursor has moved.
    
      dragObj.elNode.style.left =   left1;
      dragObj.elNode.style.top  = top1;
        
      
     //------------------------ put the x and y in form elements on the page --------- 
      document.move_page.xpos.value = left1;
      document.move_page.ypos.value = top1;
      
    
     if (browser.isIE) {
        window.event.cancelBubble = true;
        window.event.returnValue = false;
      }
      if (browser.isNS)
        event.preventDefault();
      if (browser.isOP ) {
        window.event.cancelBubble = true;
        window.event.returnValue = false;
      }  

}
//-------------------------------------------- dragStop -------------------------------------------------------------

function dragStop(event) {

  // Stop capturing mousemove and mouseup events.

  if (browser.isIE) {
    document.detachEvent("onmousemove", dragGo);
    document.detachEvent("onmouseup",   dragStop);
    document.forms['move_page'].submit();
  }
  if (browser.isNS) {
    document.removeEventListener("mousemove", dragGo,   true);
    document.removeEventListener("mouseup",   dragStop, true);
    document.forms['move_page'].submit();
  }
  if (browser.isOP ){
    document.detachEvent("onmousemove", dragGo);
    document.detachEvent("onmouseup",   dragStop);
    document.forms['move_page'].submit();
  }
}


//--------------------------------------------------------------------------------------------------------------------

//------------------------------------------ resizeStart -----------------------------------------------------------

function resizeStart(event, cid, id, width, height) {
  document.move_page.id.value = cid;
  
  
  
  var el;
  var x, y;
  var oldwidth, oldheight;
  var oldxpos, oldypos;
  var newwidth, newheight;
  //document.getElementById('mainbox').style.display = "none";
  // If an element id was given, find it. Otherwise use the element being
  // clicked on.
  
  if (id){
  
    dragObj.elNode = document.getElementById(id);
    
    }
  else {
    if (browser.isIE)
      dragObj.elNode = window.event.srcElement;
      
    if (browser.isNS)
      dragObj.elNode = event.target;

    if (browser.isOP)
      dragObj.elNode = window.event.srcElement;
  
  }


 // Get cursor position with respect to the page.
 if (browser.isIE) {
    x = window.event.clientX + document.documentElement.scrollLeft
      + document.body.scrollLeft;
    y = window.event.clientY + document.documentElement.scrollTop
      + document.body.scrollTop;
  }
  if (browser.isNS) {
    x = event.clientX + window.scrollX;
    y = event.clientY + window.scrollY;
  }
  if (browser.isOP ) {
    x = window.event.clientX + document.documentElement.scrollLeft
      + document.body.scrollLeft;
    y = window.event.clientY + document.documentElement.scrollTop
      + document.body.scrollTop;
  }
   

  


 // Save starting positions of cursor and element.
    dragObj.cursorStartX = x;
    dragObj.cursorStartY =y;
    
    dragObj.width = width;
    dragObj.height = height;
  
  dragObj.elStartLeft  = parseInt(dragObj.elNode.style.left, 10);
  dragObj.elStartTop   = parseInt(dragObj.elNode.style.top,  10);

  if (isNaN(dragObj.elStartLeft)) dragObj.elStartLeft = 0;
  if (isNaN(dragObj.elStartTop))  dragObj.elStartTop  = 0;


  // Update element's z-index.

  dragObj.elNode.style.zIndex = ++dragObj.zIndex;


 // Capture mousemove and mouseup events on the page.

  if (browser.isIE)  {
    document.attachEvent("onmousemove", resizeGo);
    document.attachEvent("onmouseup",   resizeStop);
    window.event.cancelBubble = true;
    window.event.returnValue = false;
  }
  if (browser.isNS) {
    document.addEventListener("mousemove", resizeGo,   true);
    document.addEventListener("mouseup",   resizeStop, true);
    event.preventDefault();
  }
  if (browser.isOP)  {
    document.attachEvent("onmousemove", resizeGo);
    document.attachEvent("onmouseup",   resizeStop);
    window.event.cancelBubble = true;
    window.event.returnValue = false;
  }
}

//---------------------------------------------- resizeGo ----------------------------------------------------
function resizeGo(event) {
  
  var x, y;

  // Get cursor position with respect to the page.

  if (browser.isIE) {
    x = window.event.clientX + document.documentElement.scrollLeft
      + document.body.scrollLeft;
    y = window.event.clientY + document.documentElement.scrollTop
      + document.body.scrollTop;
  }
  if (browser.isNS) {
    x = event.clientX + window.scrollX;
    y = event.clientY + window.scrollY;
  }
  if (browser.isOP) {
    x = window.event.clientX + document.documentElement.scrollLeft
      + document.body.scrollLeft;
    y = window.event.clientY + document.documentElement.scrollTop
      + document.body.scrollTop;
  }
  ratio = document.move_page.ratio.value;
  x_movement = parseInt(x) - parseInt(dragObj.cursorStartX);
  y_movement = parseInt(y) - parseInt(dragObj.cursorStartY);

this.status = x_movement + ' ' +y_movement;

if (ratio != 0){
     /*y_movement != 0){newheight = (parseInt(dragObj.height) + (parseInt(y) - parseInt(dragObj.cursorStartY))); newwidth = newheight / ratio;*/
    
          newwidth = (parseInt(dragObj.width) + (parseInt(x) - parseInt(dragObj.cursorStartX))); 
          newheight = newwidth / ratio;
          }   
else {
    newwidth = (parseInt(dragObj.width) + (parseInt(x) - parseInt(dragObj.cursorStartX))); 
    newheight = (parseInt(dragObj.height) + (parseInt(y) - parseInt(dragObj.cursorStartY)));
    }

console.log(dragObj.elNode.style.left);
document.getElementById("move_info").style.left = parseInt(dragObj.elNode.style.left) + 25;
document.getElementById("move_info").style.top = dragObj.elNode.style.top + 25;
document.getElementById("move_info").style.display = "block";
document.getElementById("move_info").innerHTML = "Width  Height<br>" + "&nbsp;&nbsp; " + newwidth + "&nbsp;&nbsp; " + newheight;

//document.write(x); 

//if(window.event.keyCode == 13)
//document.write( parseInt(dragObj.cursorStartX) + parseInt(x) );




/* window.status = " newwidth " + newwidth + " newheight " + newheight;*/
    dragObj.elNode.style.width = newwidth ;
    dragObj.elNode.style.height  = newheight ;
    
    
      
      //------------------ put width and height into form elements on the page -----------------
      border = document.move_page.border.value;
      
      if (browser.isOP || browser.isNS) {
            window.status='border='+border;
          //document.move_page.width.value = newwidth+(2 * border) ;
          document.move_page.width.value = newwidth ;
          //document.move_page.height.value =  newheight+(2 * border) ;
          document.move_page.height.value =  newheight ;
          }
      else {
      
      document.move_page.width.value = newwidth ;
      document.move_page.height.value =  newheight ;
          }
      if (browser.isIE) {
        window.event.cancelBubble = true;
        window.event.returnValue = false;
      }
      if (browser.isNS)
        event.preventDefault();
      if (browser.isOP) {
        window.event.cancelBubble = true;
        window.event.returnValue = false;
      }


}
//-------------------------------------------- resizeStop -------------------------------------------------------------

function resizeStop(event) {

  // Stop capturing mousemove and mouseup events.

  if (browser.isIE) {
    document.detachEvent("onmousemove", resizeGo);
    document.detachEvent("onmouseup",   resizeStop);
    document.forms['move_page'].submit();
    
  }
  if (browser.isNS) {
    document.removeEventListener("mousemove", resizeGo,   true);
    document.removeEventListener("mouseup",   resizeStop, true);
    document.forms['move_page'].submit();
  }
  if (browser.isOP) {
    document.detachEvent("onmousemove", resizeGo);
    document.detachEvent("onmouseup",   resizeStop);
    document.forms['move_page'].submit();
    
  }
}
//------------------------------------------- makeActive -----------------------------------


function makeActive(event) {
 
  var el;
  
  
    if (browser.isIE)
      dragObj.elNode = window.event.srcElement;
      
    if (browser.isNS)
      dragObj.elNode = event.target;
      
    if (browser.isOP)
      dragObj.elNode = window.event.srcElement;

    // dragObj.elNode.style.zIndex = ++dragObj.zIndex;
    
      

}



