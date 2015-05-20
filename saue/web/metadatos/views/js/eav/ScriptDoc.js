/**
 * @author proyecto
 */
Ext.ux.form.ScriptEditor = Ext.extend(Ext.BoxComponent, {
  /**
   * The value used by the scripteditor (defaults null)
   * @type {String}
   */
  value : undefined,  
  /**
   * Default language of scripteditor (defaults javascript)
   * @type {String}
   @cfg */
  language : 'javascript',  
  /**
   * Should it use codePress as code editor (defaults true)
   * @type {Boolean}
   @cfg */
  codePress : true, //codePress enabled
  
  /**
   * @private overridde setValue so value property value is set
   */
  setValue : function(text){
    this.value =  text;
  },

  /**
   * @private overridde getValue so value property value is read
   */  
  getValue : function(){
    return this.value || "";
  },
  
  /**
   * @private The data is always valid
   */
  isValid : function(preventMark){
     return true;
  },
   
  /**
   * We open the scripteditor window on this event
   */
  onTriggerClick : function() {
    if(this.disabled){return;}
    if (!this.editorWin) {
    		var idval;
			var error = '';
			
			var st_ValDefTmp = st_ValDef;
			
			this.tbar = new Ext.Toolbar([{
									text:"Adicionar",
									iconCls:"btn",
									id:'btnAdicionar',												
									icon:perfil.dirImg+'adicionar.png',
									handler:OnAdicionarClick,
									tooltip:"Adicionar campo"
								},{
									text:"Eliminar",
									iconCls:"btn",
									disabled:true,
									id:'btnEliminar',
									icon:perfil.dirImg+'eliminar.png',
									handler:OnEliminarClick,
									tooltip:"Eliminar campo"
									}]);
		var text = new Ext.form.TextField({
							name:'text',
							id:'text',
							allowBlank: false,
							maxLength: longitud,
							regex:regExp
						});
		var sm_ValDef = new Ext.grid.RowSelectionModel({singleSelect:true, listeners: {rowselect: On_RowClickVal}});
		
		var gridValDef = new Ext.grid.EditorGridPanel({
				  frame:true,
				  title: 'Valores por defecto para el campo '+campo,
				  iconCls:'icon-grid',
				  autoExpandColumn:'expandir',
				  store:st_ValDefTmp,
				  sm:sm_ValDef,
				  loadMask:{msg :'Cargando Valores ....'},
				  columns:[{id:'expandir', header: "Valor", dataIndex: 'valor',editor: text}],
				  tbar:this.tbar
		});	
		
      this.editorWin = new Ext.Window({
          title  : "ScriptEditor",
          iconCls: 'icon-editEl',
          closable:true,
          width:600,
          height:450,
          plain:true,
          modal: true,
          maximizable : true,          
          layout      : 'fit',
          items       : gridValDef,
          closeAction : 'hide',
          keys: [{
              key: 27,
              scope: this,
              fn: function() {
                this.editorWin.hide();
                Ext.getCmp(this.editor).cancelEdit();
              }}],
          buttons: [{
             text    : "Cancelar",
             scope   : this,
             handler : function() {             
               this.editorWin.hide(); 
               Ext.getCmp(this.editor).cancelEdit();  
             }
            },{
             text    : "Aceptar",
             scope   : this,
             handler : function() {       
               this.setValue(tf.getValue());
               this.editorWin.hide();
               this.editorWin.el.unmask(); 
               Ext.getCmp(this.editor).completeEdit();
             }
           }] 
        });
      this.editorWin.tf = tf;
      this.editorWin.doLayout();
      this.editorWin.on('resize',function () {tf.resize()});
      }
    this.editorWin.show();
    this.editorWin.tf.setValue(this.value || this.defaultValue);
  },
  
  /**
   * @private During render we create the the 'Click to edit' box
   * @param {Component} ct The component to render
   * @param {Object} position A object containing the position of the component
   */
  onRender : function(ct, position){
   this.editor = ct.id;
   Ext.ux.form.ScriptEditor.superclass.onRender.call(this, ct, position);
   this.el = ct.createChild({tag: "div",cls:'x-form-text'},position);
   this.trigger = this.el.createChild({tag: "div"});
   this.trigger.createChild({tag:"div", cls:"icon-scripteditor",html:"&nbsp;"});
   this.trigger.createChild({tag:"div",cls:"text-scripteditor",html:"Click para editar"});
   this.trigger.on("click", this.onTriggerClick, this, {preventDefault:true});
  }

});
Ext.reg('scripteditor', Ext.ux.form.ScriptEditor);
