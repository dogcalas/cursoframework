/* Migracion Ext 4 
 * Silvio Carrandi Noa y Ricardo Enrique Suarez Riquenes
 *
 */

var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();
UCID.portal.cargarEtiquetas('gestservidor',cargarInterfaz);
		
////------------ Inicializo el singlenton QuickTips ------------////
Ext.QuickTips.init();
			
////------------ Declarar Variables ------------////
var winIns, winMod,regServ,openldap,auxauth,ldap, probarConForm;
var auxIns = false;
var auxMod = false;
var auxDel = false;
var auxMod1 = false;
var auxDel1 = false;
var auxIns2 = true;
var auxMod2 = true;
var auxDel2 = true;
var ventanaadicionar=false;
var vpServidorAut;
		
		
////------------ Area de Validaciones ------------////
var esDirIp, tipos, tipoServidor, keyAdd, keyMod, keyDel, tfpuerto;
tipos = /^([a-zA-Z��������]+[a-zA-Z��������\d\.\-\@\#\_ ]*)+$/;
esDirIp =  /(^(2([0-4][0-9])|2(5[0-5]))|^([0-1]?[0-9]?[0-9]))\.(((2([0-4][0-9])|2(5[0-5]))|([0-1]?[0-9]?[0-9]))\.){2}((2([0-4][0-9])|2(5[0-5]))|([0-1]?[0-9]?[0-9]))$/ ;
tipoServidor = /^(2([0-4][0-9])|2(5[0-5]))|^([0-1]?[0-9]?[0-9])\.(((2([0-4][0-9])|2(5[0-5]))|([0-1]?[0-9]?[0-9]))\.){2}((2([0-4][0-9])|2(5[0-5]))|([0-1]?[0-9]?[0-9]))$|^([a-zA-Z��������]+[a-zA-Z��������\d\.\-\@\#\_ ]*)+$/;
		
function cargarInterfaz()
{
    ////------------ Botones ------------////
    btnAdicionar = new Ext.Button({
        id:'btnAgrServ', 
        hidden:true, 
        icon:perfil.dirImg+'adicionar.png', 
        iconCls:'btn', 
        text:perfil.etiquetas.lbBtnAdd, 
        handler:function(){
            winForm('Ins');
            tfpassword.allowBlank = true;
        }
    });
btnModificar = new Ext.Button({
    disabled:true,
    id:'btnModServ', 
    hidden:true, 
    icon:perfil.dirImg+'modificar.png', 
    iconCls:'btn', 
    text:perfil.etiquetas.lbBtnModificar, 
    handler:function(){
        winForm('Mod');
        tfpassword.allowBlank = true;
    }
});
btnCambiarP = new Ext.Button({
    id:'btnCambServ', 
    hidden:true, 
    icon:perfil.dirImg+'modificar.png', 
    iconCls:'btn', 
    text:perfil.etiquetas.lbBtnCambia, 
    handler:function(){
        winForm('Camb');
    }
});
btnEliminar = new Ext.Button({
    disabled:true,
    id:'btnEliServ', 
    hidden:true, 
    icon:perfil.dirImg+'eliminar.png', 
    iconCls:'btn', 
    text:perfil.etiquetas.lbBtnEliminar,
    handler:function(){
        eliminarservidor();
    }
});
btnProbarConexAdd = new Ext.Button({
    icon:perfil.dirImg+'aprobar.png',
    //iconCls:'btn',
    text:perfil.etiquetas.lbBtnProbar,
    disabled: true,
    formBind: true,
    id: 'probarConAdd',
    handler:function(){
        winProbarCon.show();
    }
});

                        
/*btnAyuda = new Ext.Button({id:'btnAyuServ', hidden:true, icon:perfil.dirImg+'ayuda.png', iconCls:'btn', text:perfil.etiquetas.lbBtnAyuda,handler:function(){pepe();}});*/
UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
		   
////------------ Combo Box ------------////
var dataTmp = [
['bd', 'Servidor de base datos'],
['autenticaci&oacute;n', 'Servidor de autenticaci\xf3n'] 
];
			
var storeDataTmp = new Ext.data.SimpleStore({
    fields: ['tiposervidor','denominacion'],
    data : dataTmp
});
////------------ Store del Grid de Servidores ------------aquui////
var stGpServ =  new Ext.data.Store({
    fields:[
    {
        name:'idservidor',  
        mapping:'id'
    },

{
        name:'denominacion', 
        mapping:'text'
    },

{
        name:'tiposervidor', 
        mapping:'tiposervidor'
    },

{
        name:'descripcion', 
        mapping:'descripcion'
    },

{
        name:'ip',    
        mapping:'ip'
    },

{
        name:'type',  
        mapping:'type'
    },

{
        name:'user',  
        mapping:'user'
    },

{
        name:'baseDN',  
        mapping:'cadconexion'
    },

{
        name:'puerto',   
        mapping:'puerto'
    },

{
        name:'chekssl',  
        mapping:'ssl'
    },

{
        name:'chektsl',  
        mapping:'tsl'
    }
    ],                                        
    proxy:{
        type: 'ajax',                                                                            
        url: 'cargarservidores',
        actionMethods:{
            read:'POST'
        }, 
        reader:{
            totalProperty: 'cantidad_filas',
            root: 'datos',
            id: 'id'
        }
    }				
});
			
////------------ Establesco modo de seleccion de grid (single)------------////
sm = Ext.create('Ext.selection.RowModel',{
    mode:'SINGLE'
});

		
sm.on('beforeselect', function (smodel, rowIndex, keepExisting, record){
    btnModificar.enable();
    btnEliminar.enable();
}, this);
                        
sm.on('select', function (smodel, rowIndex, keepExisting, record){
    authaux = sm.getLastSelected().data.tiposervidor;
    if(sm.getLastSelected().data.type == 'openldap')
    {
        openldap = sm.getLastSelected().data.type;
        ldap = '';
        flagaux = sm.getLastSelected().data.type;
    }
    if(sm.getLastSelected().data.type == 'ldap')
    {
        ldap = sm.getLastSelected().data.type;
        openldap = '';
        flagaux = sm.getLastSelected().data.type
    }
    if(sm.getCount()!=1)
        btnModificar.disable();
    else
        btnModificar.enable();
}, this); 
        
sm.on('deselect', function (smodel, rowIndex, keepExisting, record){
    if(sm.getCount()!=1)
        btnModificar.disable();
    else
        btnModificar.enable();
}, this);
			
////------------ Defino el grid de Servidores ------------////
var gpServ = new Ext.grid.GridPanel({
    labelAlign: 'top',
    frame:true,
    header:false,
    region:'center',
    paginate:true,
    iconCls:'icon-grid',
    autoExpandColumn:'expandir',
    store:stGpServ,
    selModel:sm,
    columns: [
    {
        hidden: true, 
        hideable: false,  
        dataIndex: 'idservidor'
    },

    {
        header:perfil.etiquetas.lbServidor, 
        width:150, 
        dataIndex: 'denominacion',
        flex:1
    },

    {
        header: perfil.etiquetas.lbIP, 
        dataIndex: 'ip'
    },

    {
        header: perfil.etiquetas.lbTipo, 
        dataIndex: 'tiposervidor'
    },

    {
        id:'expandir',
        header: perfil.etiquetas.lbDes,
        width:350, 
        dataIndex: 'descripcion'
    }
    ],
						 
    loadMask:{
        store:stGpServ
    },
				
    bbar:new Ext.PagingToolbar({
        pageSize: 15,
        id:'ptbaux',
        store: stGpServ,
        displayInfo: true,
        displayMsg: perfil.etiquetas.lbMsgbbarI,
        emptyMsg: perfil.etiquetas.lbMsgbbarII
    })
});
////------------ Trabajo con el PagingToolbar ------------////
Ext.getCmp('ptbaux').on('change',function(){
    sm.select();
},this);
////------------ Renderiar el arbol ------------////
var panel = new Ext.Panel({
    layout:'border',
    title:perfil.etiquetas.lbTitTituloP,
    //renderTo:'panel',
    items:[gpServ],
    tbar:[btnAdicionar,btnModificar,btnEliminar/*,btnAyuda*/],
    keys: new Ext.KeyMap(document,[{
        key:Ext.EventObject.DELETE,
        fn: function(){
            if(auxDel && auxDel1 && auxDel2)
                eliminarservidor();
        }
    },
    {
        key:"i",
        alt:true,
        fn: function(){
            if(auxIns && auxIns2)
                winForm('Ins');
        }
    },
    {
        key:"m",
        alt:true,
        fn: function(){
            if(auxMod && auxMod1 && auxMod2)
                winForm('Mod');
        }
    }])
});
////------------ Eventos para hotkeys ------------////
btnAdicionar.on('show',function(){
    auxIns = true;
},this)
btnEliminar.on('show',function(){
    auxDel = true;
},this)
btnModificar.on('show',function(){
    auxMod = true;
},this)
stGpServ.on('load',function(){
    if(stGpServ.getCount() != 0)
    {
        auxMod1 = true;
        auxDel1 = true;
    }
    else
    {
        auxMod1 = false;
        auxDel1 = false;
    }
},this)
////------------ Viewport ------------////
                      
vpServidorAut = new Ext.Viewport({
    layout:'fit',      
    items:panel
})
                      
stGpServ.on("load2",function(){
    if(!vpServidorAut)
        crearViewport();
});                     

stGpServ.load({
    params:{
        start:0,
        limit:10
    }
});
			
////************ ITEMS DEL FORMULARIO ************////
////------------ Combo servidores del formulario ------------////
var cbservidores = new Ext.form.ComboBox({
    labelAlign: 'top',
    emptyText:perfil.etiquetas.lbMsgSelect,
    editable:false,
    fieldLabel:perfil.etiquetas.lbTiipoBD,
    store:storeDataTmp,
    tabIndex:3,
    id:'cbservidores',
    valueField:'tiposervidor',
    displayField:'denominacion',
    hiddenName:'tiposervidor',
    name:'tiposervidor',
    typeAhead: false,
    mode: 'local',
    width: 150,
    allowBlank:false,
    // blankText:perfil.etiquetas.lbMsgReq,
    triggerAction: 'all',
    selectOnFocus:false,
    anchor:'90%'
})
////------------ Textfield usuario del formulario ------------////
var tfusuario = new Ext.form.TextField({
    labelAlign: 'top',
    fieldLabel: perfil.etiquetas.lbTitUsuario,
    tabIndex:6,
    allowBlank:true,
    maxLength:50,
    width:335,                                                               
    id:'user',
    name:'user'
})

var tfpassword = new Ext.form.TextField({
    labelAlign: 'top',
    fieldLabel : perfil.etiquetas.lbContrasena,				
    inputType : 'password',
    tabIndex:7,
    maxLength:30,
    width:335,
    id:'passw',
    name:'passw'
});			
tfpuerto = new Ext.form.NumberField({
    labelAlign: 'top',
    tabIndex:11,
    maxLength:3,				
    anchor:'90%',				
    fieldLabel:perfil.etiquetas.lbPuerto,
    value: 389,				
    allowBlank:true,				
    id:'puerto',
    name:'puerto'
});

var panelpuerto = new Ext.Panel({
    labelAlign: 'top',
    hidden:'true',
    autoHeight:true,     
    layout:'form',                            
    border:0,
    items:[tfpuerto]
});
			
			
var chbssl = new Ext.form.Checkbox({
    labelAlign: 'top',
    boxLabel 	:  perfil.etiquetas.lbSSL,
    name  		: 'chbssl',
    id		: 'chekssl',
                               
    padding		: '0 0 10 0',
    margins		: '0 0 0 0',
    inputValue 	: true,
    handler:function(a,b){
        if(tfpuerto.getValue() != '636'){
            tfpuerto.setValue('636');                                       
        }
        else{
            tfpuerto.setValue('389');                                       
        }
    }
});

var chbtsl = new Ext.form.Checkbox({
    labelAlign: 'top',
    boxLabel 	: perfil.etiquetas.lbTSL,
    name  	: 'chbtsl',
    id		: 'chektsl',                    
    style       : 'padding: 10px',
    inputValue 	: true
});
var baseDN = new Ext.form.TextField({
    labelAlign: 'top',
    id                  : 'baseDN',
    name                : 'baseDN',
    width:160,
    tabIndex            : 2,
    maxLength           : 50,
    fieldLabel          : perfil.etiquetas.lbBaseDN,
    allowBlank          : false
})
var chbAgrupador = new Ext.form.CheckboxGroup({
    xtype           : 'checkboxgroup',
    labelSeparator  : '',
    id              : 'chbgp',
    name            : 'chbgp',
    //hidden:'true',                   
    style           : 'margins: 20px',
    columns         : 2,
    vertical        : true,
    items           : [chbssl, chbtsl]
});                       
var panel3= new Ext.Panel({                 
    hidden              : 'true',
    autoHeight          : true,
    border:0,
    layout              : 'form',
    autoWidth           : true,
    items               : [baseDN]
});
////------------ Fieldset usuario del formulario ------------////
var fsusuario = new Ext.form.FieldSet({
    labelAlign: 'top',
    title: perfil.etiquetas.lbTitConexion,
    autoHeight      : true,
    checkboxToggle  : true,			
    hidden:'true',
    collapsed:false,
    anchor:'100%',
    items:[tfusuario, tfpassword]
})
fsusuario.on("collapse",function(){
    if(chbldap.getValue()){                             
        if(tfpassword.getValue()==''){
            tfpassword.allowBlank = true;
            tfpassword.reset();
        }
        if(tfusuario.getValue()==''){
            tfusuario.reset();                           
            tfusuario.allowBlank = true;                                                         
        }
    }
    else{
        tfpassword.allowBlank = false;
        tfusuario.allowBlank = false;
    }

})
                        
fsusuario.on("expand",function(){
    if(chbldap.getValue()){
        if(tfpassword.getValue()==''){
            tfpassword.allowBlank = true;
            tfpassword.reset();
        }
        if(tfusuario.getValue()==''){
            tfusuario.reset();                                                                                      
            tfusuario.allowBlank = true;                            

        }
    }
    else{
        tfpassword.allowBlank = false;
        tfusuario.allowBlank = false;
    }

})


////------------ RadioButton LDAP y OpenLDAP ------------////
var chbldap = new Ext.form.Radio({
    labelAlign: 'top',
    hideLabel:'true',
    tabIndex:4,
    name:'chbauth',
    boxLabel:perfil.etiquetas.lbTitLDAP,
    id:'ldap',
    handler: function(a, b){                            
        if(chbldap.getValue() == true)                  
        {
                                    
            ldap = 'ldap';
            openldap = '';
            fsusuario.show();
            fsusuario.enable();
            tfusuario.enable();
            tfusuario.show();
            fsusuario.collapse();                                       
            tfpassword.allowBlank = true;
            tfusuario.allowBlank = true;
        }
    }                    
})
var chbopenldap = new Ext.form.Radio({
    labelAlign: 'top',
    hideLabel:'true',
    tabIndex:5,
    name:'chbauth',
    boxLabel:perfil.etiquetas.lbTitOpenLDAP,
    id:'openldap',
    handler: function(a,b){
        if(chbopenldap.getValue() == true)
        {	
                                   
            openldap = 'openldap';
            ldap = '';
            fsusuario.show();
            fsusuario.enable();
            tfusuario.enable();
            tfusuario.show();
            fsusuario.expand();
            tfpassword.allowBlank = false;
            tfusuario.allowBlank = false;
        }
                                    
    }
                                
                                
})
////------------ Fieldset tipos de autenticaci n del formulario ------------////
var fstiposaut = new Ext.form.FieldSet({
    labelAlign: 'top',
    title: perfil.etiquetas.lbTitTipoAuth,
    autoHeight:true,
                               
    collapsed:false,
    hidden:'true',
    anchor:'90%',
    id:'fstiposaut',
    name:'fstiposaut',
    items:[chbldap,chbopenldap]	
})			
			
////************ FIN ITEMS DEL FORMULARIO ************////		
////------------ Formulario ------------////
regServ = new Ext.FormPanel({ 
    labelAlign: 'top',
    autoHeight:true,	
    frame: true,
     //monitorValid:true,
    //id:1,
    //enableKeyEvents: true,
    //frame:true,
    bodyStyle:'padding:5px 5px 0',	    		  
    items: [{
        layout:'column',
                                               
        items:[{
            columnWidth:.5,
            layout:'form',
            margin:'5 5 5 5',
            border:0,
            items:[{
                xtype:'textfield',
                labelAlign: 'top',
                fieldLabel:perfil.etiquetas.lbNombreS,
                id:'denominacion',
                name:'denominacion',
                tabIndex:1,
                enableKeyEvents: true,
                allowBlank: false,
                //blankText:perfil.etiquetas.lbMsgReq,
                regex:tipoServidor, 
                regexText:perfil.etiquetas.lbMsgRegex,											
                anchor:'90%'
            }, 
            cbservidores,panelpuerto,chbAgrupador
            ]
        },
        {
            columnWidth:.5,
            layout: 'form',
            margin:'5 5 5 5',
            border:0,
            items: [{
                xtype:'textfield',
                labelAlign: 'top',
                fieldLabel: perfil.etiquetas.lbIPLab,
                id:'ip',
                name:'ip',
                tabIndex:2,
                allowBlank: false,
                blankText:perfil.etiquetas.lbMsgCReq,
                regex: esDirIp,
                regexText: perfil.etiquetas.lbMsgIPInc,
                //enableKeyEvents: true,
                /*listeners: {							},*/
                anchor:'90%'
            },fstiposaut,panel3]
        }
									
        ,
        {
            columnWidth:.99,
            layout: 'form',
            margin:'5 5 5 5',
            border:0,
            items: [fsusuario]
        },
        {
            columnWidth:.99,
            layout: 'form',
            margin:'5 5 5 5',
            border:0,
            items: [{
                xtype:'textarea',
                labelAlign: 'top',
                autoHeight:true,                                                                  
                tabIndex:8,
                fieldLabel: perfil.etiquetas.lbText,
                id: 'descripcion',
                name:'descripcion',
                maxLength:255,
                anchor:'100%'
            }]
        }]
    }],
 buttons:[btnProbarConexAdd]

});

function validar() {
    if(regServ.getForm().isValid())
           btnProbarConexAdd.enable();
       }
			
//********Formulario para probar conexion
probarConForm = new Ext.FormPanel({ 
    labelAlign: 'top',
    autoHeight:true,
    autoWidth:true,
    id:'probarConForm',
    frame:true,
    bodyStyle:'padding:5px 5px 0',	    		  
    items: [{
        xtype:'textfield',
        labelAlign: 'top',
        fieldLabel:perfil.etiquetas.lbUsuario,
        id:'usuario',
        name: 'usuario',
        allowBlank: false,
        maxLength:50,
        //blankText:perfil.etiquetas.lbMsgReq,
        anchor:'90%'
    }, {
						
        xtype:'textfield',
        labelAlign: 'top',
        fieldLabel:perfil.etiquetas.lbContrasena,
        id:'password',
        inputType : 'password',
        name: 'password',
        maxLength:30,
        allowBlank: false,
        //blankText:perfil.etiquetas.lbMsgReq,
        anchor:'90%'
					
    }]
});
			
var winProbarCon = new Ext.Window({             
    modal: true,
    closeAction:'hide',
    title:perfil.etiquetas.lbBtnProbarCon,
    resizable:false,
    autoHeight:true,
    autoWidth:true,
    shadow:false,
    items:[probarConForm],
    buttons:[
    {
        icon:perfil.dirImg+'aprobar.png',
        iconCls:'btn',
        text:perfil.etiquetas.lbBtnProbar,
        handler:function(){
            if(probarConForm.getForm().isValid()){
					
                regServ.getForm().submit({
                    url:'probarConexion',
                    waitMsg:perfil.etiquetas.lbMsgProbandoS,
                    params:{
                        ldap:chbopenldap.getValue(),								
                        usuario:Ext.getCmp('usuario').getValue(),
                        password: Ext.getCmp('password').getValue()														
                    }
                });
                Ext.getCmp('usuario').reset();
                Ext.getCmp('password').reset();
						
            }
            else
                mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);
        }                               
    }]
});
			
////************ EVENTOS ************////
////------------ Evento para activar o desactivar fieldset usuario y radio openldap a travez de radio ldap ------------////
chbldap.on('check',function(){                     
    if(chbldap.getValue() == true)                       
    {
        ldap = 'ldap';
        openldap = '';
        fsusuario.show();
        fsusuario.enable();
        tfusuario.enable();
        tfusuario.show();
        fsusuario.collapse();                                       
        tfpassword.allowBlank = true;
        tfusuario.allowBlank = true;
    }
})
/*cbservidores.on('select',function(){
				chbldap.setValue(true);
				chbopenldap.setValue(false);
			})*/
			
////------------ Evento para activar o desactivar fieldset tipos de autenticacion ------------//// 
cbservidores.on('select',function(){
    tipoAutenticacion((cbservidores.getValue() == 'autenticaci&oacute;n'));                      
    if(tfpuerto.getValue()=='')
        tfpuerto.setValue(389);

    if(cbservidores.getValue() == 'autenticaci&oacute;n')
        baseDN.allowBlank = false;
    else
        baseDN.allowBlank = true;

})

cbservidores.on('change',function(){
    if(cbservidores.getValue() == 'autenticaci&oacute;n')
        baseDN.allowBlank = false;
    else
        baseDN.allowBlank = true;
})
                       
////------------ Evento para activar o desactivar fieldset usuario a travez del radio Openldap ------------////
chbopenldap.on('check',function(){		 
    if(chbopenldap.getValue() == true)
    {
        openldap = 'openldap';
        ldap = '';
        fsusuario.show();
        fsusuario.enable();
        tfusuario.enable();
        tfusuario.show();
        fsusuario.expand();
        tfpassword.allowBlank = false;
        tfusuario.allowBlank = false;
    }
})
			
chbssl.on('check',function(checked){
                         
    if(tfpuerto.getValue() != '636'){
        tfpuerto.setValue('636');                                       
    }
    else{
        tfpuerto.setValue('389');                                       
    }
})
						
function closeWindow(valor) {
    panelpuerto.hide();
    chbAgrupador.hide();
    panel3.hide();
    btnProbarConexAdd.hide();
    btnProbarConexAdd.disable();
       if(valor == 1)
        winIns.hide();
    else
        winMod.hide();
    Ext.getCmp('usuario').reset();
    Ext.getCmp('password').reset();
}
				
			
			
////************ FIN EVENTOS ************////
////------------ Cargar la ventana ------------////			
function winForm(opcion){
    switch(opcion){
        case 'Ins':{
            ventanaadicionar=true;
            if(!winIns)
            {                                                    
                winIns = new Ext.Window({
                    modal: true,
                    closeAction:'hide',
                    title:perfil.etiquetas.lbTitAdicionarV,
                    width:400,
                    //height:400,
                    resizable:false,
                    x:312,
                    y:50,
                    //monitorValid:true,
                    autoHeight:true,
                    autoWidth:true,
                    shadow:false,
                    buttons:[
                    //btnProbarConexAdd,
                    {
                        icon:perfil.dirImg+'cancelar.png',
                        iconCls:'btn',
                        text:perfil.etiquetas.lbBtnCancelar,
                        handler:function(){
                            closeWindow(1);
                        }
                    },
                    {	
                        icon:perfil.dirImg+'aplicar.png',
                        iconCls:'btn',
                        text:perfil.etiquetas.lbBtnAplicar,
                        handler:function(){
                            adicionarservidor('apl');
                        }
                    },
                    {
                        icon:perfil.dirImg+'aceptar.png',
                        iconCls:'btn',
                        text:perfil.etiquetas.lbBtnAceptar,
                        handler:function(){
                            adicionarservidor();
                        }
                    }]
                });
                winIns.on('show',function(){
                    auxIns2 = false;
                    auxMod2 = false;
                    auxDel2 = false;
                },this)
                winIns.on('hide',function(){
                    auxIns2 = true;
                    auxMod2 = true;
                    auxDel2 = true;
                },this)
            }
            panelpuerto.hide();												
            chbAgrupador.hide();
            panel3.hide();
            Ext.getCmp('probarConAdd').hide();
            Ext.getCmp('probarConAdd').disable();
            fsusuario.hide();						
            regServ.getForm().reset();
            fstiposaut.hide();
            winIns.add(regServ);
            winIns.doLayout();
            winIns.show();
						
        }
        break;
        case 'Mod':
        {
            ventanaadicionar=false;
            if(!winMod)
            {
                                                    
                winMod= new Ext.Window({
                    modal: true,
                    closeAction:'hide',
                    layout:'fit',
                    title:perfil.etiquetas.lbTitModificalo,
                    width:400,
                    //height:400,
                    x:312,
                    y:50,
                    resizable:false,
                    autoHeight:true,
                    shadow:false,
                    buttons:[
                    {
                        icon:perfil.dirImg+'cancelar.png',
                        iconCls:'btn',
                        text:perfil.etiquetas.lbBtnCancelar,
                        handler:function(){
                            closeWindow(0);
                        }
                    },
                    {
                        icon:perfil.dirImg+'aceptar.png',
                        iconCls:'btn',
                        text:perfil.etiquetas.lbBtnAceptar,
                        handler:function(){
                            modificarservidor();
                        }
                    }]
                });
                winMod.on('show',function(){
                    auxIns2 = false;
                    auxMod2 = false;
                    auxDel2 = false;
                },this)
                winMod.on('hide',function(){
                    auxIns2 = true;
                    auxMod2 = true;
                    auxDel2 = true;
                },this)
            }
            regServ.getForm().reset();
            winMod.add(regServ);
            winMod.doLayout();						
            winMod.show();
            if(sm.getLastSelected().data.tiposervidor == 'autenticaci&oacute;n')
            {                                                   
                if(sm.getLastSelected().data.type == 'ldap'){
                    chbldap.setValue(true);
                    tfpassword.allowBlank = true;
                    tfusuario.allowBlank = true;
                }
                else {
                    chbopenldap.setValue(true);
                    tfpassword.allowBlank = false;
                    tfusuario.allowBlank = false;
                }
                if(sm.getLastSelected().data.chekssl==true)
                    chbssl.setValue(true);
                if(sm.getLastSelected().data.chektsl==true)
                    chbtsl.setValue(true);
                tipoAutenticacion(true);
                baseDN.allowBlank=false;
            }
            else
            {                                               
                tipoAutenticacion(false);
                baseDN.allowBlank=true;
            }                                              
            regServ.getForm().loadRecord(sm.getLastSelected());
        }
        break;
    }
}

////-------------Función -----------------------////
function tipoAutenticacion(ldap){
    if(ldap){
        if(!chbldap.getValue() && !chbopenldap.getValue())
            chbldap.setValue(true);
        tfpassword.show();
        fstiposaut.show();
        fsusuario.show();
        tfusuario.show();
        panelpuerto.show();               
        chbAgrupador.show();
        panel3.show();
        btnProbarConexAdd.show();
        //(ventanaadicionar) ? Ext.getCmp('probarConAdd').show():Ext.getCmp('probarConMod').show();
        fsusuario.enable();
        fstiposaut.enable();
        tfusuario.enable();                          
        tfpuerto.allowBlank = false;            
    }
    else {
        chbldap.setValue(false);
        chbopenldap.setValue(false);
        fsusuario.hide();
        panelpuerto.hide();
        fstiposaut.hide();
        tfusuario.hide();              
        chbAgrupador.hide();
        panel3.hide();
        btnProbarConexAdd.hide();
        //(ventanaadicionar) ? Ext.getCmp('probarConAdd').hide():Ext.getCmp('probarConMod').hide();
        //  tfpuerto.disable();
        fsusuario.disable();
        fstiposaut.disable();
        tfusuario.disable();                       
        tfpuerto.allowBlank = true;       
        tfusuario.allowBlank = true;
    }    
}
                      
////------------ Adicionar Servidor ------------////
function adicionarservidor(apl){
				
    if(!tfusuario.isVisible()){
        tfusuario.setValue('');
        tfpassword.setValue('');
    }							
    if (regServ.getForm().isValid())
    {
        if(cbservidores.getValue() == 'bd')
        {
            regServ.getForm().submit({
                url:'insertarserv',
                waitMsg:perfil.etiquetas.lbMsgAdicionandoS,
                failure: function(form, action){
                    if(action.result.codMsg != 3)
                    {                       
                        if(!apl) 
                        winIns.hide();                                                                           
                        regServ.getForm().reset();
                        stGpServ.load();
                        sm.clearSelections();
                        btnModificar.disable();
                        btnEliminar.disable();
                    }                    
                }	
            });
        }
        else if(chbopenldap.checked)
        {
            regServ.getForm().submit({
                params:{
                    openldap:openldap
                },
                url:'insertarserv',
                waitMsg:perfil.etiquetas.lbMsgAdicionandoS,
                failure: function(form, action){
                    if(action.result.codMsg != 3)
                    {                         
                        if(!apl) 
                        winIns.hide();                                                                                                                                                          
                        regServ.getForm().reset();
                        chbldap.setValue(true);
                        stGpServ.load();
                        sm.clearSelections();
                        btnModificar.disable();
                        btnEliminar.disable();
                    }                    
                }	
            });
        }
        else if(chbldap.checked)
        {
            regServ.getForm().submit({
                params:{
                    ldap:ldap
                },
                url:'insertarserv',
                waitMsg:perfil.etiquetas.lbMsgAdicionandoS,
                failure: function(form, action){
                    if(action.result.codMsg != 3)
                    {                         
                        if(!apl) 
                            winIns.hide();
                        regServ.getForm().reset();
                        stGpServ.load();
                        sm.clearSelections();
                        btnModificar.disable();
                        btnEliminar.disable();
                        chbldap.setValue(true);
                    }                   
                }	
            });
        }
        else
            mostrarMensaje(3,perfil.etiquetas.lberror);
    }
    else
        mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);                    
}
////------------ Modififcar Servidor ------------////
function modificarservidor()
{				
    if(!tfusuario.isVisible()){
        tfusuario.setValue('');
        tfpassword.setValue('');
    }			
    if (regServ.getForm().isValid())
    {
        if(cbservidores.getValue() == 'bd')
        {
            regServ.getForm().submit({
                url:'modificarserv',
                waitMsg:perfil.etiquetas.lbMsgModificandoS,
                params:{
                    idservidor:sm.getLastSelected().data.idservidor
                    },
                failure: function(form, action){
                    if(action.result.codMsg != 3)
                    {                       
                        stGpServ.load();
                        winMod.hide();
                        regServ.getForm().reset();
                    }                   
                }
            });
        }
        else if(chbldap.checked)
        {
            regServ.getForm().submit({
                url:'modificarserv',
                waitMsg:perfil.etiquetas.lbMsgModificandoS,
                params:{
                    idservidor:sm.getLastSelected().data.idservidor,
                    ldap:ldap
                },
                failure: function(form, action){
                    if(action.result.codMsg != 3)
                    {                       
                        stGpServ.load();
                        winMod.hide();
                        regServ.getForm().reset();
									
                    }                    
                }
            });	
        }
        else if(chbopenldap.checked)
        {
            regServ.getForm().submit({
                url:'modificarserv',
                waitMsg:perfil.etiquetas.lbMsgModificandoS,
                params:{
                    idservidor:sm.getLastSelected().data.idservidor,
                    openldap:openldap
                },
                failure: function(form, action){
                    if(action.result.codMsg != 3)
                    {                       
                        stGpServ.load();
                        regServ.getForm().reset();
                        winMod.hide();
									
                    }                    
                }
            });	
					
        }
    }
    else
        mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);               
}

function eliminarservidor()
{
    var arrServElim = sm.getSelection();
    var arrayServElim = [];
    for (var i=0;i<arrServElim.length;i++)
        arrayServElim.push(arrServElim[i].data.idservidor);
    mostrarMensaje(2,perfil.etiquetas.lbMsgprueba,elimina);
    function elimina(btnPresionado)
    {
        if (btnPresionado == 'ok')
        {
            Ext.Ajax.request({
                url: 'comprobarservidor',
                method:'POST',
                params:{
                    arrayServ:Ext.encode(arrayServElim)
                    },
                callback: function (options,success,response){
                    responseData = Ext.decode(response.responseText);
                    if(responseData.codMsg == 1)
                    {                       
                        stGpServ.load();
                        sm.clearSelections();
                        btnModificar.disable();
                        btnEliminar.disable();
                    }                   
                }
            });
        }
    }
}
			
}
