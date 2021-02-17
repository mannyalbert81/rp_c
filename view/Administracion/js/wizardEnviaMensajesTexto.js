$(document).ready(function(){
	 
	var formulario = $('#smartwizard').smartWizard({
        selected: 0,  // Initial selected step, 0 = first step 
        keyNavigation:true, // Enable/Disable keyboard navigation(left and right keys are used if enabled)
        autoAdjustHeight:true, // Automatically adjust content height
        cycleSteps: false, // Allows to cycle the navigation of steps
        backButtonSupport: true, // Enable the back button support
        useURLhash: true, // Enable selection of the step based on url hash
        lang: {  // Language variables
            next: 'Siguiente', 
            previous: 'Anterior'
        },
        toolbarSettings: {
            toolbarPosition: 'bottom', // none, top, bottom, both
            toolbarButtonPosition: 'right', // left, right
            showNextButton: true, // show/hide a Next button
            showPreviousButton: true, // show/hide a Previous button
            toolbarExtraButtons: [
									      
		        $('<button></button>').text('Procesar')
						      .addClass('btn btn-success')
						      .attr({ 
						    	  id:"aplicar",name:"aplicar",type:"submit", form:"frm_mensajes",
						    	  disabled:true
						    	  })						    	  
						      .append("<i class=\"fa \" aria-hidden=\"true\" ></i>"),
				$('<button></button>').text('Cancelar')
						      .addClass('btn btn-primary')
						      .attr({type:"button",id:"btn_cancelar",name:"btn_cancelar"})
						     
                  ]
        }, 
        anchorSettings: {
            anchorClickable: true, // Enable/Disable anchor navigation
            enableAllAnchors: false, // Activates all anchors clickable all times
            markDoneStep: true, // add done css
            enableAnchorOnDoneStep: true // Enable/Disable the done steps navigation
        },            
        contentURL: null, // content url, Enables Ajax content loading. can set as data data-content-url on anchor
        disabledSteps: [],    // Array Steps disabled
        errorSteps: [],    // Highlight step with errors
        theme: 'dots',
        transitionEffect: 'fade', // Effect on navigation, none/slide/fade
        transitionSpeed: '400'
  });
	
	
	formulario.on("leaveStep", function(e, anchorObject, stepNumber, stepDirection) {
	
		if(stepNumber==0){
			
			return validaPaso1();
		}
		
     
    });
	
	

    
   function validaPaso1(){
	   
	   let txt_var1 = $("#txt_var1").val();
	   let txt_var2 = $("#txt_var2").val();
	   let txt_var3 = $("#txt_var3").val();
	   let txt_var4 = $("#txt_var4").val();
		 
	   if(txt_var1 == '' || txt_var1 == 0){
		   $("#txt_var1").notify("Ingrese",{ position:"buttom left", autoHideDelay: 2000});
			return false;
	   }
	   
	   if(txt_var2 == '' || txt_var2 == 0){
		   $("#txt_var2").notify("Ingrese",{ position:"buttom left", autoHideDelay: 2000});
			return false;
	   }
	   if(txt_var3 == '' || txt_var3 == 0){
		   $("#txt_var3").notify("Ingrese",{ position:"buttom left", autoHideDelay: 2000});
			return false;
	   }
	   if(txt_var4 == '' || txt_var4 == 0){
		   $("#txt_var4").notify("Ingrese",{ position:"buttom left", autoHideDelay: 2000});
			return false;
	   }
	   
	   

	    var contador=0;
	    var textarea=$('#mensaje').val();
	    
	   	 if(textarea.length > 150){
	   		 
	   		 $("#mensaje").notify("Solo puede agregar 150 caracteres.",{ position:"buttom left", autoHideDelay: 2000});
	   			return false;
	   	 }
	   
		$("#aplicar").attr({disabled:false});
		
		   
	   return true;
   }
   
   
	
})

