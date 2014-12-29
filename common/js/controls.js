function LinkControl(title,url,hint){
	// Create a div to hold the control.
	controlDiv = document.createElement('DIV');
	controlDiv.style.padding = '5px';
	
	// Set CSS for the control border
	var controlUI = document.createElement('DIV');
	controlUI.style.backgroundColor = 'white';
	controlUI.style.borderStyle = 'solid';
	controlUI.style.borderWidth = '1px';
	controlUI.style.padding = '2px';
	controlUI.style.cursor = 'pointer';
	controlUI.style.textAlign = 'center';
	controlUI.title = hint;
	controlDiv.appendChild(controlUI);
	
	// Set CSS for the control interior
	var controlText = document.createElement('DIV');
	controlText.style.fontFamily = 'Arial,sans-serif';
	controlText.style.fontSize = '12px';
	controlText.style.paddingLeft = '4px';
	controlText.style.paddingRight = '4px';
	controlText.innerHTML = '<a href="'+url+'">'+title+'</a>';
	controlUI.appendChild(controlText);
	return controlDiv;
}
//Define a property to hold the Home state.
CheckBoxControl.prototype.checked_ = false;

// Define setters and getters for this property.
CheckBoxControl.prototype.getCheckedState = function() {
  return this.checked_;
}

CheckBoxControl.prototype.setCheckedState = function(checked) {
	this.checked_ = checked;
}

function CheckBoxControl(controlDiv,title,state,color,hint){

	var control = this;

	//control.checked_ = state;
	control.setCheckedState(state);
	
	// Create a div to hold the control.
	//var controlDiv = document.createElement('DIV');
	controlDiv.style.padding = '5px';
	//controlDiv.checked=state;
	controlDiv.style.display='block';
	controlDiv.style.width='66px';
	
	// Set CSS for the control border
	var controlUI = document.createElement('DIV');
	controlUI.style.backgroundColor = 'white';
	controlUI.style.borderStyle = 'solid';
	controlUI.style.borderWidth = '1px';
	controlUI.style.padding = '2px';
	controlUI.style.cursor = 'pointer';
	controlUI.style.textAlign = 'center';
	controlUI.title = hint;
	controlUI.style.display='block';
	controlDiv.appendChild(controlUI);
	
	// Set CSS for the control interior
	var controlCheck = document.createElement('DIV');
	controlCheck.style.backgroundColor = color;	
	controlCheck.style.fontFamily = 'Arial,sans-serif';
	controlCheck.style.fontSize = '12px';
	//controlCheck.style.fontWeight= 'bold';	
	controlCheck.style.paddingLeft = '4px';
	controlCheck.style.paddingRight = '4px';
	controlCheck.style.verticalAlign= 'middle';
	controlCheck.style.borderStyle = 'solid';
	controlCheck.style.borderWidth = '1px';	
	controlCheck.style.float='left';
	controlCheck.style.position='relative';
	controlCheck.style.display='block';
	controlCheck.style.width='7px';
	
	// Set CSS for the control interior
	var controlText = document.createElement('DIV');
	controlText.style.fontFamily = 'Arial,sans-serif';
	controlText.style.fontSize = '12px';
	controlText.style.fontWeight= 'bold';
	controlText.style.paddingLeft = '4px';
	controlText.style.paddingRight = '4px';
	controlText.style.verticalAlign= 'middle';
	controlText.style.float='left';
	controlText.style.position='relative';
	controlText.style.display='block';
	controlText.innerHTML=title;	
	
	// Set CSS for the control interior
	var controlClear = document.createElement('DIV');
	controlClear.style.clear = 'both';
	
	
	// image
	//var controlImage = document.createElement('img');
	//controlImage.src=imageurl;
	
	// checkbox
	//var controlCheckbox = document.createElement('input');
	//controlCheckbox.type='checkbox';
	//controlCheckbox.checked=true;
	
	//var html='<img src="'+imageurl+'">';
	//var html='';
	//if (state=='checked'){
	//	html = html+'<input type="checkbox" style="width:auto;" id="'+name+'" name="'+name+'" value="'+title+'" checked>'+title;
	//} else {
	//	html = html+'<input type="checkbox" style="width:auto;" id="'+name+'" name="'+name+'" value="'+title+'">'+title;
	//}
	
	
	//controlText.appendChild(controlImage);
	//controlText.appendChild(controlCheckbox);
	controlUI.appendChild(controlCheck);
	controlUI.appendChild(controlText);
	controlUI.appendChild(controlClear);
	
	draw();
	
	google.maps.event.addDomListener(controlDiv, 'click', function() {
		//controlCheckbox.checked=true;
		if (!control.getCheckedState()){
				control.setCheckedState(true);
				//controlUI.style.borderWidth = '2px';
			}else{
				control.setCheckedState(false);
				//controlUI.style.borderWidth = '1px';
			}
		draw();
		  });	

	function draw(){
		if (control.getCheckedState()){
			//controlUI.style.borderWidth = '2px';
			controlCheck.innerHTML='+';
		}else{
			//controlUI.style.borderWidth = '1px';
			controlCheck.innerHTML='-';
		}		
	}
	//return controlDiv;
}