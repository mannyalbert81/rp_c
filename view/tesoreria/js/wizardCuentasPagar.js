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
				$('<button></button>').text('Distribucion')
						      .addClass('btn btn-default')
						      .attr({ 
						    	  type:"button",id:"btn_distribucion",name:"btn_distribucion",'data-toggle':"modal",
						    	  'data-target':"#mod_distribucion",disabled:true
						    	  })
						      .append("<i class=\"fa \" aria-hidden=\"true\" ></i>"),						      
		        $('<button></button>').text('Aplicar')
						      .addClass('btn btn-success')
						      .attr({ 
						    	  id:"aplicar",name:"aplicar",type:"submit", form:"frm_cuentas_pagar",
						    	  disabled:true
						    	  })						    	  
						      .append("<i class=\"fa \" aria-hidden=\"true\" ></i>"),
				$('<button></button>').text('Cancelar')
						      .addClass('btn btn-danger')
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
		
		//console.log(stepDirection);
		if(stepNumber==0){
			
			return validaPaso1();
		}
		if(stepNumber==1){
			
			return validaPaso2();
			
		}
    });
	
	formulario.on("showStep", function(e, anchorObject, stepNumber) {
		
		if(stepNumber==2){
			$("#btn_distribucion").attr({disabled:false});
			$("#aplicar").attr({disabled:false});
			
			if( typeof resultadosCompra !== 'undefined' && jQuery.isFunction( resultadosCompra ) ) {
			    
				resultadosCompra();
			}
		}
	});

    
   function validaPaso1(){
	   
	   let lote_id = $("#id_lote").val();
	   let tipo_documento_id = $("#id_tipo_documento").val();
	   let descripcion = $("#descripcion_cuentas_pagar").val();
	   
	   if(lote_id == '' || lote_id == 0){
		   $("#nombre_lote").notify("Lote No Generado",{ position:"buttom left", autoHideDelay: 2000});
			return false;
	   }
	   if(tipo_documento_id == 0){
		   $("#id_tipo_documento").notify("Seleccione Tipo Documento",{ position:"buttom left", autoHideDelay: 2000});
			return false;
	   }
	   if(descripcion.length == 0 || descripcion == ''){
		   $("#descripcion_cuentas_pagar").notify("Ingrese una descripcion",{ position:"buttom left", autoHideDelay: 2000});
			return false;
	   }
	   
	   return true;
   }
   
   function validaPaso2(){
	   
	  let provedor_id = $("#id_proveedor").val(); 
	  let banco_id = $("#id_bancos").val();
	  let numero_documento = $("#numero_documento").val();
	  
	  if( provedor_id == '' || provedor_id.length == 0  || provedor_id == 0 ){
		  $("#cedula_proveedor").notify("Digite Ruc proveedor",{ position:"buttom left", autoHideDelay: 2000});	 
		  return false; 
		 }
	  if( banco_id == 0 ){
		  $("#id_bancos").notify("Selecione Banco",{ position:"buttom left", autoHideDelay: 2000});	 
		  return false; 
		 }
	  if( numero_documento == '' || numero_documento.length == 0 ){
		  $("#numero_documento").notify("Ingrese número documento",{ position:"buttom left", autoHideDelay: 2000});	 
		  return false; 
		 }
	  
	   return true;
   }
   
	
})

