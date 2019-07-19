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
            toolbarExtraButtons: [   ]
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
		if(stepNumber==2){
			
			return validaPaso3();
			
		}
    });
	
	

    
   function validaPaso1(){
	   
	   let id_entidad_patronal = $("#id_entidad_patronal").val();
	   let fecha_entrada_patronal_participes = $("#fecha_entrada_patronal_participes").val();
	   let cedula_participes = $("#cedula_participes").val();
	   let observacion_participes = $("#observacion_participes").val();
	   let codigo_alternativo_participes = $("#codigo_alternativo_participes").val();
	   let apellido_participes = $("#apellido_participes").val();
	   let nombre_participes = $("#nombre_participes").val();
	   let fecha_nacimiento_participes = $("#fecha_nacimiento_participes").val();
	   let id_genero_participes = $("#id_genero_participes").val();
	   let ocupacion_participes = $("#ocupacion_participes").val();
	   let id_tipo_instruccion_participes = $("#id_tipo_instruccion_participes").val();
	   let id_estado_civil_participes = $("#id_estado_civil_participes").val();
	   let correo_participes = $("#correo_participes").val();
	   
		   
	   if(id_entidad_patronal == '' || id_entidad_patronal == 0){
		   $("#id_entidad_patronal").notify("Seleccione Entidad",{ position:"buttom left", autoHideDelay: 2000});
			return false;
	   }
	   if(fecha_entrada_patronal_participes.length == 0 || fecha_entrada_patronal_participes == ''){
		   $("#fecha_entrada_patronal_participes").notify("Seleccione fecha",{ position:"buttom left", autoHideDelay: 2000});
		   return false;
	   }
	   if(cedula_participes.length == 0 || cedula_participes == ''){
		   $("#cedula_participes").notify("Ingrese Cedula",{ position:"buttom left", autoHideDelay: 2000});
		   return false;
	   }
	   if(observacion_participes.length == 0 || observacion_participes == ''){
		   $("#observacion_participes").notify("Ingrese una Observación",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	   }
	   if(codigo_alternativo_participes.length == 0 || codigo_alternativo_participes == ''){
		   $("#codigo_alternativo_participes").notify("Ingrese un Código",{ position:"buttom left", autoHideDelay: 2000});
			return false;
	   }
	   if(apellido_participes.length == 0 || apellido_participes == ''){
		   $("#apellido_participes").notify("Ingrese Apellido",{ position:"buttom left", autoHideDelay: 2000});
			return false;
	   }
	   if(nombre_participes.length == 0 || nombre_participes == ''){
		   $("#nombre_participes").notify("Ingrese Nombre",{ position:"buttom left", autoHideDelay: 2000});
			return false;
	   }
	   if(fecha_nacimiento_participes.length == 0 || fecha_nacimiento_participes == ''){
		   $("#fecha_nacimiento_participes").notify("Seleccione Fecha",{ position:"buttom left", autoHideDelay: 2000});
			return false;
	   }
	   if(id_genero_participes == '' || id_genero_participes == 0){
		   $("#id_genero_participes").notify("Selecione Genero",{ position:"buttom left", autoHideDelay: 2000});
			return false;
	   }
	   if(ocupacion_participes.length == 0 || ocupacion_participes == ''){
		   $("#ocupacion_participes").notify("Seleccione Ocupación",{ position:"buttom left", autoHideDelay: 2000});
			return false;
	   }
	   if(id_tipo_instruccion_participes.length == '' || id_tipo_instruccion_participes == 0){
		   $("#id_tipo_instruccion_participes").notify("Seleccione Tipo Instrucción",{ position:"buttom left", autoHideDelay: 2000});
			return false;
	   }
	   if(id_estado_civil_participes.length == '' || id_estado_civil_participes == 0){
		   $("#id_estado_civil_participes").notify("Seleccione estado Civil",{ position:"buttom left", autoHideDelay: 2000});
			return false;
	   }
	   if(correo_participes.length == 0 || correo_participes == ''){
		   $("#correo_participes").notify("Seleccione Correo",{ position:"buttom left", autoHideDelay: 2000});
			return false;
	   }
	
	   return true;
   }
   
   function validaPaso2(){
	   
	  let nombre_conyugue_participes = $("#nombre_conyugue_participes").val(); 
	  let apellido_esposa_participes = $("#apellido_esposa_participes").val();
	  let cedula_conyugue_participes = $("#cedula_conyugue_participes").val();
	  let numero_dependencias_participes = $("#numero_dependencias_participes").val();
	
	  
	   if(nombre_conyugue_participes.length == 0 || nombre_conyugue_participes == ''){
		   $("#nombre_conyugue_participes").notify("Ingrese Nombre",{ position:"buttom left", autoHideDelay: 2000});
			return false;
	   }
	   if(apellido_esposa_participes.length == 0 || apellido_esposa_participes == ''){
		   $("#apellido_esposa_participes").notify("Ingrese Apellido",{ position:"buttom left", autoHideDelay: 2000});
			return false;
	   }
	   if(cedula_conyugue_participes.length == 0 || cedula_conyugue_participes == ''){
		   $("#cedula_conyugue_participes").notify("Ingrese una Cedula",{ position:"buttom left", autoHideDelay: 2000});
			return false;
	   }
	   if(numero_dependencias_participes.length == 0 || numero_dependencias_participes == ''){
		   $("#numero_dependencias_participes").notify("Ingrese un Número",{ position:"buttom left", autoHideDelay: 2000});
			return false;
	   }
	
	   return true;
   }
   
   function validaPaso3(){
	   
		  let id_ciudades = $("#id_ciudades").val(); 
		  let direccion_participes = $("#direccion_participes").val();
		  let telefono_participes = $("#telefono_participes").val();
		  let celular_participes = $("#celular_participes").val();
		
		  
		   if(id_ciudades.length == '' || id_ciudades == 0){
			   $("#id_ciudades").notify("Ingrese una Ciudad",{ position:"buttom left", autoHideDelay: 2000});
				return false;
		   }
		   if(direccion_participes.length == 0 || direccion_participes == ''){
			   $("#direccion_participes").notify("Ingrese una Dirección",{ position:"buttom left", autoHideDelay: 2000});
				return false;
		   }
		   if(telefono_participes.length == 0 || telefono_participes == ''){
			   $("#telefono_participes").notify("Ingrese un Teléfono",{ position:"buttom left", autoHideDelay: 2000});
				return false;
		   }
		   if(celular_participes.length == 0 || celular_participes == ''){
			   $("#celular_participes").notify("Ingrese un Celular",{ position:"buttom left", autoHideDelay: 2000});
				return false;
		   }
		
		   return true;
	   }
   
   
	
})

