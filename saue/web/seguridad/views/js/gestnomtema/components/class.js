//Desktop
Ext.define('Ext.form.field.Color', {
    extend:'Ext.form.field.Picker',
    alias: 'widget.ColorField',
    requires: ['Ext.picker.Color'],
    alternateClassName: ['Ext.form.ColorField', 'Ext.form.Color'],

   
 
    
   // invalidText : "{0} is not a valid date - it must be in the format {1}",
  
    //triggerCls : Ext.baseCSSPrefix + 'form-date-trigger',
	
    /**
     * @cfg {Boolean} showHexValue
     * True to display the HTML Hexidecimal Color Value in the field
     * so it is manually editable.
     */
    showHexValue : false,
	
	/**
     * @cfg {String} triggerClass
     * An additional CSS class used to style the trigger button.  The trigger will always get the
     * class 'x-form-trigger' and triggerClass will be <b>appended</b> if specified (defaults to 'x-form-color-trigger'
     * which displays a calendar icon).
     */
    triggerClass : 'x-form-color-trigger',
	
    /**
     * @cfg {String/Object} autoCreate
     * A DomHelper element spec, or true for a default element spec (defaults to
     * {tag: "input", type: "text", size: "10", autocomplete: "off"})
     */
    // private
    defaultAutoCreate : {tag: "input", type: "text", size: "10",
						 autocomplete: "off", maxlength:"6"},
	
	/**
	 * @cfg {String} lengthText
	 * A string to be displayed when the length of the input field is
	 * not 3 or 6, i.e. 'fff' or 'ffccff'.
	 */
	lengthText: "Color hexadecimal debe ser de 3 o 6 caracteres.",
	
	//text to use if blank and allowBlank is false
	blankText: "Debes entrar un valor hexadecimal en el formato ABCDEF.",
	
	/**
	 * @cfg {String} color
	 * A string hex value to be used as the default color.  Defaults
	 * to 'FFFFFF' (white).
	 */
	defaultColor: 'FFFFFF',
	enforceMaxLength:true,
	maxLength:6,
	
	maskRe:/^[A-F\d]{1}$/,
	// These regexes limit input and validation to hex values
	//regex: /[a-f0-9]{6}/i,
	regex:/^[A-F\d]{6}$/,
	regexText: 'Debes entrar un valor hexadecimal en el formato ABCDEF',

	//private
	curColor: 'ffffff',

    matchFieldWidth: false,
    setColor:function(d){
		var me = this;
      
       if(me.inputEl)
        me.inputEl.setStyle({
		'background-color': '#'+d,
			'background-image': 'none'	
			});
		},
	resetView:function(){
		var me = this;
       var d=me.getValue();
       if(me.inputEl)
        me.inputEl.setStyle({
		'background-color': '#'+d,
			'background-image': 'none'	
			});
		},
    /**
     * @cfg {Number} startDay
     * Day index at which the week should begin, 0-based (defaults to 0, which is Sunday)
     */
   setValue:function(hex){
	   Ext.form.ColorField.superclass.setValue.call(this, hex);
	   this.setColor(hex);
	},
    
    initComponent : function(){
        var me = this,
            isString = Ext.isString,
            min, max;
		this.on('afterrender',function(){
		this.resetView();	
			
		},this)
        

        me.callParent();
         var me = this;
		 me.setValue(this.defaultColor);
       
    },

    initValue: function() {
        var me = this,
            value = me.value;
		
        
       

        me.callParent();
        
    },

    // private
    

    /**
     * Replaces any existing disabled dates with new values and refreshes the Date picker.
     * @param {Array} disabledDates An array of date strings (see the <tt>{@link #disabledDates}</tt> config
     * for details on supported values) used to disable a pattern of dates.
     */
   

    /**
     * Replaces any existing disabled days (by index, 0-6) with new values and refreshes the Date picker.
     * @param {Array} disabledDays An array of disabled day indexes. See the <tt>{@link #disabledDays}</tt>
     * config for details on supported values.
     */
    

    createPicker: function() {
        var me = this,
            format = Ext.String.format;

        return Ext.create('Ext.picker.Color', {
            ownerCt: me.ownerCt,
            renderTo: document.body,
            floating: true,
            hidden: true,
            focusOnShow: true,
            //style:'background:red',
            listeners: {
                scope: me,
                select: me.onSelect
            },
            keyNavConfig: {
                esc: function() {
                    me.collapse();
                }
            }
        });
        
    },
    

		
    onSelect: function(m, d) {
        var me = this;
        
        me.setValue(d);
        me.inputEl.setStyle({
		'background-color': '#'+d,
			'background-image': 'none'	
			});
        me.fireEvent('select', me, d);
        me.collapse();
    },

      listeners:{
		afterrender:function(){
		
		} ,
		validitychange:function( f,isValid,options ){
				if(isValid){
				 this.setValue(f.getValue());	
				}
			
			}
	  },      
      
        
    /**
     * @private
     * Sets the Date picker's value to match the current field value when expanding.
     */
    onExpand: function() {
        var me = this,
            value = me.getValue();
      //  me.picker.setValue(value);
    },

    /**
     * @private
     * Focuses the field when collapsing the Date picker.
     */
    onCollapse: function() {
        this.focus(false, 60);
    },

    // private
    beforeBlur : function(){
        var me = this,
          
            focusTask = me.focusTask;
        
        if (focusTask) {
            focusTask.cancel();
        }
        v=me.getValue();
        if (v) {
            me.setValue(v);
        }
    }

    /**
     * @cfg {Boolean} grow @hide
     */
    /**
     * @cfg {Number} growMin @hide
     */
    /**
     * @cfg {Number} growMax @hide
     */
    /**
     * @hide
     * @method autoSize
     */
});

Ext.define('Ext.panel.VistaPresentacion',{
	extend:'Ext.panel.Panel',
	alias:'widget.VistaPresentacion',
	//title: 'Vista Previa',
	layout:'fit',
	region:'center',
	margins: '0 0 0 5',
	frame:true,

	html: ''
	
	
});

Ext.define('Ext.form.ColorBarras',{
	extend:'Ext.form.field.Color',
	alias:'widget.ColorBarras',
	
	showHexValue:true,
    allowBlank:false,
       
       
       
	
	width: '98%',
	labelAlign: 'top',
	
    initComponent:function(){
	var me=this;	 
	
	me.callParent();

	}
	
});
	
	Ext.define('Ext.form.ColorInicio',{
	extend:'Ext.form.field.Color',
	alias:'widget.ColorInicio',
	
	showHexValue:true,
        allowBlank:false,
       width: '50%',
       labelAlign: 'top',
	
     initComponent:function(){
	var me=this;	 
	
	me.callParent();

	}
	
	});
	
	Ext.define('Ext.form.ComboTipografias',{
	extend:'Ext.form.ComboBox',
	alias:'widget.ComboTipografias',
	
	queryMode: 'local',
    displayField:'tipo',
    valueField: 'cod',
    allowBlank:false,
    labelAlign: 'top',
    name:'inicio_tipo_letra',
     width: '50%',
     margin:'0 0 5 5',
     initComponent:function(){
	var me=this;	 
	me.store=Ext.create('Ext.data.Store', {
    fields: ['tipo','cod'],
    data : [
        {"tipo":"Arial","cod":"arial"},
        {"tipo":"Helvetica","cod":"helvetica"},
        {"tipo":"Serif","cod":"serif"},
        {"tipo":"Sans-Serif","cod":"san-serif"},
        {"tipo":"Cursive","cod":"cursive"},
        {"tipo":"Fantasy","cod":"fantasy"},
        {"tipo":"Monospace","cod":"monospace"}
       
        //...
    ]
});
me.callParent();

	}
	
	});
	
Ext.define('Ext.form.ComboSize',{
	extend:'Ext.form.ComboBox',
	alias:'widget.ComboSize',
	
	
	hideLabel:true,
    queryMode: 'local',
    displayField: 'no',
    valueField: 'no',
    allowBlank:false,
    labelAlign: 'top',
    name:'inicio_font_size',
     width: '35%',
     margin:'0 0 5 5',
     
     initComponent:function(){
	var me=this;	 
	me.store=Ext.create('Ext.data.Store', {
    fields: ['no'],
    data : [
        {"no":5},
        {"no":8},
        {"no":10},
        {"no":12},
        {"no":14},
        {"no":16},
        {"no":18},
        {"no":20},
        {"no":24},
        //...
    ]
});
me.callParent();

	}
	
	});
	
	Ext.define('Ext.form.ComboSizeBase',{
	extend:'Ext.form.ComboBox',
	alias:'widget.ComboSize',
	
	
    queryMode: 'local',
    displayField: 'no',
    valueField: 'no',
    allowBlank:false,
    labelAlign: 'top',
    
     width: '35%',
     margin:'0 0 5 5',
     
     initComponent:function(){
	var me=this;	 
	me.store=Ext.create('Ext.data.Store', {
    fields: ['no'],
    data : [
        {"no":5},
        {"no":8},
        {"no":10},
        {"no":12},
        {"no":14},
        {"no":16},
        {"no":18},
        {"no":20},
        {"no":24},
        //...
    ]
});
me.callParent();

	}
	
	});
	
	
	Ext.define('Ext.form.RadioGroupSombra',{
	extend:'Ext.form.RadioGroup',
	  
    
    labelAlign: 'top', 
    width: '100%',
    height: 50,
    layout:'hbox',
    radio1:null,
    radio2:null,
    valor:false,
    initComponent:function(){
	var me=this;
	this.radio1=this.createRadio1();
	this.radio2=this.createRadio2();
	this.fieldLabel=perfil.etiquetas.lbDesktopModoDegrad;
	
	me.addEvents('seleccionado');
	me.radio1.handler=function(checked){
             
             // console.debug(checked.checked)
              if(checked.checked==true){
                  me.valor=false;
                  me.fireEvent('seleccionado',me,false)
              }
      };
    me.radio2.handler=function(checked){
              
             // console.debug(checked.checked)
              if(checked.checked==true){
                   me.valor=true;
                  me.fireEvent('seleccionado',me,true)
              }
      }
	me.items=[  
          me.radio1,  
          me.radio2
          
     ];
     
     me.callParent(); 
	
	
	},	
	createRadio1:function(){
		return new Ext.form.field.Radio({id:'val_puro',margin:5,boxLabel:perfil.etiquetas.lbDesktopShadow1,name: 'framework', inputValue: 'Puro'});
	},
	createRadio2:function(){
		return new Ext.form.field.Radio({id:'val_sombreado',margin:5,boxLabel: perfil.etiquetas.lbDesktopShadow2, name: 'framework', inputValue: 'Sombreado'});
	},
	setCheked:function(radio){
		if(radio){
			this.radio1.setRawValue(false);
			this.radio2.setRawValue(true);
		}else{
			this.radio1.setRawValue(true);
			this.radio2.setRawValue(false);
		}
		//alert(radio)
		
	}
	});
	
	Ext.define('Ext.form.field.CargaArchivos',{
	extend:'Ext.form.field.File',
	alias:'widget.cargaArchivos',
	
   
    
    labelAlign: 'top',
    buttonOnly:true,
           // fieldStyle:'display:none;width:0px;',
    width: '100%',
            buttonConfig: {
                text: 'Explorar',
                 iconCls: 'upload-icon',
                margin:5
            },
    initComponent:function(){
			this.buttonConfig.icon=perfil.dirImg+'buscar.png',
            this.buttonConfig.text=perfil.etiquetas.lbClascargaArchivos;
			this.callParent();
			
	},
	listeners: {
            render: function(c) {
               if(c.qtip){
               
               Ext.create('Ext.tip.ToolTip', {
					target:  c.getEl(),
					html: c.qtip,
					trackMouse: true,
				});
				
				
               }
               this.fileInputEl.dom.title="";
               this.button.on('mouseover',function(){
				   this.fileInputEl.dom.title="";
				},this)
            }
     }
	
	});

//Para cargar las texturas
Ext.define('Ext.form.ComboTexturas',{
	extend:'Ext.form.ComboBox',
	alias:'widget.ComboTexturas',
	
	
   
    queryMode: 'local',
    displayField: 'name',
    valueField: 'name',
    allowBlank:false,
    labelAlign: 'top',
    name:'texturas',
     width: '100%',
     margin:'0 0 5 5',
     
     forceSelection:true,
     initComponent:function(){
	var me=this;
	this.fieldLabel= perfil.etiquetas.lbTextura,	 
	me.store=Ext.create('Ext.data.Store', {
    fields: ['name', 'abr','dir'],
    data : [
        {"name":"1", "abr":"Alabama","dir":"/lib/ExtJS/temas/Texturas/pattern-122s.png"},
        {"name":"2", "abr":"Alaska","dir":"/lib/ExtJS/temas/Texturas/peasoup_02.png"},
       
        {"name":"3", "abr":"Arizona","dir":"/lib/ExtJS/temas/Texturas/right-005w.png"},
        {"name":"4", "abr":"Arizona","dir":"/lib/ExtJS/temas/Texturas/top-010.png"},
        //...
    ]
});
me.callParent();

me.store=new Ext.data.Store({
					
		 fields: ['name','dir'],
					
						
		proxy: { type: 'ajax', url: 'cargarTexturas',
        actionMethods:{ //Esta Linea es necesaria para el metodo de llamada POST o GET
                                        
            read:'POST'
        },
		reader:{ totalProperty: "cantidad_filas", root:"data", id: "idtema" }
    } });
    
  


	},
	tpl: Ext.create('Ext.XTemplate',
        '<tpl for=".">',
            '<div class="x-boundlist-item"><img src="{dir}" /><span style="float:right;text-align:center;position:relative;width:75px;height:52px;padding-top:26px;">{name}</span></div>',
        '</tpl>'
    ),
    
    selectFirst:function(){
			if(this.rendered)
			this.setValue('1');
	}
     
	
	});
/*	
Ext.define('Ext.CheckTexture',{
	extend:'Ext.form.field.Checkbox',
	
	
})*/

//Presentacion

Ext.define('Ext.form.ComboVentanas',{
	extend:'Ext.form.ComboBox',
	alias:'widget.ComboVentanas',
	editable:false ,
	
   
    queryMode: 'local',
    displayField: 'name',
    valueField: 'name',
    allowBlank:false,
    labelAlign: 'top',
    name:'ventanas',
     width: '100%',
     //margin:'0 0 5 5',
     
     forceSelection:true,
     initComponent:function(){
	var me=this;	 
	me.store=Ext.create('Ext.data.Store', {
    fields: ['name', 'dir'],
   
   
		proxy: { type: 'ajax', url: 'cargarExtThemes',
        actionMethods:{ //Esta Linea es necesaria para el metodo de llamada POST o GET
                                        
            read:'POST'
        },
		reader:{  root:"data" }
    }
});
me.callParent();
	me.store.load();
	}
	
	});
	
	Ext.define('Ext.form.ComboIconos',{
	extend:'Ext.form.ComboBox',
	alias:'widget.ComboIconos',
	
    name:'iconos',  
    forceSelection:true,  
    
    emptyText:'Escoja un juego de Iconos..',  
 
   queryMode: 'local',
    editable:false ,
    displayField:"name",
     valueField: 'name',
     width: '100%', 
  
     labelAlign: 'top',  
   
    
     
     initComponent:function(){
	var me=this;
	this.emptyText=	 perfil.etiquetas.MsgIconosCombo
	me.store=me.store=new Ext.data.Store({
					
		 fields: ['name','dir'],
					
						
		proxy: { type: 'ajax', url: 'cargarIconos',
        actionMethods:{ //Esta Linea es necesaria para el metodo de llamada POST o GET
                                        
            read:'POST'
        },
		reader:{ totalProperty: "cantidad_filas", root:"data"}
		} });
		me.callParent();

	}
	
	});
	
	//comercial
	Ext.define('Ext.FormatButton',{
	extend:'Ext.container.ButtonGroup',
	columns: 3,
	
    bold:null,
    createBold:function(){
		 var me = this;
		return Ext.create('Ext.Button',{
                text: '<b >N</b>',
                ownerCt: me.ownerCt,
             
                rowspan: 1,
                height:20,
                width:20,
               
                iconAlign: 'top',
               
                enableToggle:true
                
            });
	},
    italic:null,      
	createItalic:function(){
		 var me = this;
		return Ext.create('Ext.Button',{
              
                text: '<i style="font-weight:bold;">I</i>',
                ownerCt: me.ownerCt,
                rowspan: 2,
           
                height:20,
                width:20,
            
                iconAlign: 'top',
                enableToggle:true
               
            });
	},
    
    underline:null,
    createUnder:function(){
		 var me = this;
		return Ext.create('Ext.Button',{
              
                text: '<i style="text-decoration:underline;font-weight:bold;">S</i>',
				ownerCt: me.ownerCt,
                rowspan: 2,
                height:20,
             
                width:20,
              
                iconAlign: 'top',
                enableToggle:true
            });
	},
            
        width:'50%',
    initComponent:function(){
		this.addEvents('buttontoggle');
		this.bold=this.createBold();
		this.italic=this.createItalic();
		this.underline=this.createUnder();
		this.items=[this.bold,this.italic,this.underline];
		this.bold.on('toggle',this.onToggle,this);
		this.italic.on('toggle',this.onToggle,this);
		this.underline.on('toggle',this.onToggle,this);
		
		
		this.callParent(arguments);
		
	},
	
	onToggle:function(){
		this.fireEvent('buttontoggle');
	},
	
	getB:function(){
		return this.bold.pressed;
	},
	getI:function(){
		return this.italic.pressed;
	},
	getU:function(){
		return this.underline.pressed;
	},
	
	setData:function(data){
		if(eval('data.'+this.nameB)!=null)
			this.bold.toggle(eval('data.'+this.nameB),true);
		if(eval('data.'+this.nameI)!=null)
			this.italic.toggle(eval('data.'+this.nameI),true);
		if(eval('data.'+this.nameU)!=null)
			this.underline.toggle(eval('data.'+this.nameU),true);
	}
});


