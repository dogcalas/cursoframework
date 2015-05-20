var perfil = window.parent.UCID.portal.perfil;
UCID.portal.cargarEtiquetas('gestinterfaz', function () {
    cargarInterfaz();
});

////------------ Inicializo el singlenton QuickTips ------------////
Ext.QuickTips.init();
var gen, edi;
var otro, seleccionado;

function cargarInterfaz() {
    /*var seleccionar = Ext.create('Ext.Button', {
     text: 'Seleccionar',
     // renderTo: Ext.getBody(),
     handler: function() {
     if(seleccionado==null){
     Ext.MessageBox.alert('Error', 'Debe seleccionar una interfaz');
     }
     else{
     Ext.MessageBox.show({
     title:'Guardar Interfaz',
     msg: 'Usted ha seleccionado la interfaz '+seleccionado+' <br />Desea guardarla?',
     buttons: Ext.MessageBox.YESNO,
     //fn: showResult,
     // animateTarget: 'mb4',
     icon: Ext.MessageBox.QUESTION,
     });
     }
     function showResult(btn){
     Ext.example.msg('Button Click', 'You clicked the {0} button', btn);
     };
     },
     icon:perfil.dirImg+'aceptar.png',
     iconCls:'btn',
     });

     var a = Ext.create('Ext.Button', {//boton para agregar columnas al grid
     text:null,
     layout:'border',
     icon: perfil.dirImg + 'adicionar.png',
     iconCls:'add',
     handler: function() {
     if(col==""){
     col="{\n name: '"+ Ext.getCmp('c').getValue()+"'\n}";
     cold="{\n  text: '"+Ext.getCmp('c').getValue()+"',\n  flex: 1,\n  dataIndex: '"+Ext.getCmp('c').getValue()+"'\n}";
     colf="{\n  fieldLabel:'"+Ext.getCmp('c').getValue()+"',\n  name:'"+Ext.getCmp('c').getValue()+"'\n}";
     }
     else{
     col=col+","+"{\n name: '"+ Ext.getCmp('c').getValue()+"'\n}";
     cold=cold+","+"{\n  text: '"+Ext.getCmp('c').getValue()+"',\n  flex: 1,\n  dataIndex: '"+Ext.getCmp('c').getValue()+"'\n}";
     colf=colf+","+"{\n  fieldLabel:'"+Ext.getCmp('c').getValue()+"',\n  name:'"+Ext.getCmp('c').getValue()+"'\n}";
     }
     Ext.getCmp('c').setValue("");
     }
     });
     var componente = Ext.create('Ext.form.field.ComboBox', {//combo para agregar componentes
     id:'cmp',
     fieldLabel: 'Componente',
     editable: false,
     draggable :true,
     store:['Grid','Arbol','Panel','Combobox'],
     name: 'componentes',
     listeners:{
     'select': function(){
     if(this.getValue()=='Grid'){
     Ext.getCmp('d').setVisible(true);
     Ext.getCmp('pc').setVisible(true);
     Ext.getCmp('reg').setVisible(false);
     }
     else if(this.getValue()=='Arbol'){
     Ext.getCmp('d').setVisible(true);
     Ext.getCmp('pc').setVisible(false);
     Ext.getCmp('reg').setVisible(true);
     }
     else{
     Ext.getCmp('d').setVisible(false);
     Ext.getCmp('pc').setVisible(false);
     Ext.getCmp('reg').setVisible(false);
     }
     }
     },
     emptyText:'--seleccione--'
     });

     var region= Ext.create('Ext.form.field.ComboBox', {
     id:'reg',
     hidden:true,
     fieldLabel:'Localización',
     editable: false,
     draggable:true,
     store:['Derecha','Izquierda'],
     name: 'region',
     emptyText:'Izquierda',
     listeners:{
     'select':function(){
     if(this.getValue()=='Derecha'){
     cardinal='east';
     }
     else{
     cardinal='west';
     }
     }
     }
     });
     var formEdit = Ext.create('Ext.form.Panel', {
     frame: true,
     width: 340,
     bodyPadding: 5,

     fieldDefaults: {
     labelAlign: 'left',
     labelWidth: 90,
     anchor: '100%'
     },
     items: [/*{
     xtype: 'filefield',
     name: 'file1',
     id:'f1',
     fieldLabel: 'Iterfaz'
     }, {
     xtype: 'textareafield',
     name: 'textarea1',
     height: 420,
     anchor:'100%',
     // fieldLabel: 'TextArea',
     value: ''
     }]
     });
     var simple = Ext.create('Ext.form.Panel', {//panel para generar la interfaz
     frame:true,
     bodyStyle:'padding:5px 5px 0',
     width: 350,
     fieldDefaults: {
     msgTarget: 'side',
     labelWidth: 75
     },
     defaultType: 'textfield',
     defaults: {
     anchor: '100%'
     },

     items: [{
     fieldLabel: 'Nombre de la Interfaz',
     id:'NI',
     name: 'nombInt'
     },componente,{
     fieldLabel: 'url',
     hidden:true,
     id:'d',
     name: 'dir'
     },{
     xtype:'panel',
     id:'pc',
     hidden:true,
     layout:'hbox',
     fieldDefaults: {
     msgTarget: 'side'

     },
     items:[{
     xtype:'textfield',
     fieldLabel: 'Columnas',
     id:'c',
     name: 'col',
     },a]

     },region
     ]


     });
     function addComp(c){//funcion para agregar componente
     switch(c){
     case 'Grid':
     {

     url=Ext.getCmp('d').getValue();
     var fun="var winAdicionar"+cont+";\n var winModificar"+cont+";\n var form"+cont+"=Ext.create('Ext.form.Panel', {\n   frame:true,\n  bodyStyle:'padding:5px 5px 0',\n  width: 350,\n  fieldDefaults: {\n   msgTarget: 'side',\n   labelWidth: 75\n  },\n  defaultType: 'textfield',\n  defaults: {\n   anchor: '100%'\n  },\n  items: ["+colf+"]\n });\n function mostForm"+cont+"(opcion){\n  switch(opcion){\n  case 'add':\n  {\n   winAdicionar"+cont+" = Ext.create('Ext.Window', {\n   title: 'Adicionar',\n   closeAction: 'hide',\n   width: 500,\n    height: 250,\n    x: 220,\n    y: 100,\n    constrain: true,\n    layout: 'fit',\n   buttons: [{\n    text: 'Aplicar',\n    icon:perfil.dirImg + 'aplicar.png',\n    handler:function(){\n    }\n   },{\n    text: 'Aceptar',\n    icon:perfil.dirImg + 'aceptar.png',\n    handler:function(){\n    }\n   },{\n    text: 'Cancelar',\n    icon:perfil.dirImg + 'cancelar.png',\n    handler:function(){\n    winAdicionar"+cont+".hide();\n    }\n   }\n   ]\n    });\n   winAdicionar"+cont+".add(form"+cont+");\n   winAdicionar"+cont+".show();\n  }break;\n  case 'mod':\n  {\n   winModificar"+cont+" = Ext.create('Ext.Window', {\n   title: 'Modificar',\n   closeAction: 'hide',\n   width: 500,\n    height: 250,\n    x: 220,\n    y: 100,\n    constrain: true,\n    layout: 'fit',\n   buttons: [{\n    text: 'Aplicar',\n    icon:perfil.dirImg + 'aplicar.png',\n    handler:function(){\n    }\n   },{\n    text: 'Aceptar',\n    icon:perfil.dirImg + 'aceptar.png',\n    handler:function(){\n    }\n   },{\n    text: 'Cancelar',\n    icon:perfil.dirImg + 'cancelar.png',\n    handler:function(){\n    winModificar"+cont+".hide();\n    }\n   }\n   ]\n    });\n   winModificar"+cont+".add(form"+cont+");\n   winModificar"+cont+".show();\n  }break;\n}\n}\n";
     var add="var btnAdicionar"+cont+" = Ext.create('Ext.Button', {\n  id: 'btnAgr"+cont+"',\n  text: '<b>' + perfil.etiquetas.lbBtnAdicionar + '</b>',\n  icon: perfil.dirImg + 'adicionar.png',\n  iconCls:'btn',\n  handler: function() {	\n   mostForm"+cont+"('add');\n }\n});\n";
     var mod="var btnModificar"+cont+" = Ext.create('Ext.Button', {\n  id: 'btnMod"+cont+"',\n  text: '<b>' + perfil.etiquetas.lbBtnModificar + '</b>',\n  disabled: true,\n   icon: perfil.dirImg + 'modificar.png',\n  iconCls:'btn',\n  handler: function() {	\n   mostForm"+cont+"('mod');\n}\n});\n";
     var del="var btnEliminar"+cont+" = Ext.create('Ext.Button', {\n  id: 'btnEli"+cont+"',\n  text: '<b>' + perfil.etiquetas.lbBtnEliminar + '</b>',\n  disabled: true,\n   icon: perfil.dirImg + 'eliminar.png',\n  iconCls:'btn',\n  handler: function() {\n   Ext.MessageBox.show({\n   title:'Eliminar',\n   msg: 'Desea eliminar el elemento?',\n   buttons: Ext.MessageBox.YESNO,\n   icon: Ext.MessageBox.QUESTION,\n  }); \n}\n});\n";
     var grid="var stGpGrid"+cont+" = Ext.create('Ext.data.ArrayStore', {\n fields: ["+col+"],\n proxy: {\n  type: 'ajax',\n  url: '"+url+"',\n  actionMethods: {\n  read: 'POST'\n }\n  }\n});"+'\n'+"var GpGrid"+cont+" =Ext.create('Ext.grid.Panel', {\n store: stGpGrid"+cont+",\n stateful: true,\n stateId:'stateGrid',\n columns: ["+cold+"],\n region: 'center',\n tbar:[btnAdicionar"+cont+",btnModificar"+cont+",btnEliminar"+cont+"]\n});\n";
     cod=cod+fun+add+mod+del+grid;
     if(elem==""){
     elem='GpGrid'+cont;
     }
     else{
     elem=elem+',GpGrid'+cont;
     }
     }break;
     case 'Arbol':
     {
     url=Ext.getCmp('d').getValue();
     var arbol="var stTrArbol"+cont+" = Ext.create('Ext.data.TreeStore', {\n proxy: {\n type: 'ajax',\n url:'"+url+"' \n  },\n root: {\n  id: 'src',\n expanded: true\n},\n folderSort: true,\n sorters: [{\n property: 'text',\n direction: 'ASC'\n}]\n });\n var TrArbol"+cont+" = Ext.create('Ext.tree.Panel', {\n  title:'Arbol',\n  store: stTrArbol"+cont+",\n  collapsible: true,\n  viewConfig: {\n plugins: {\n ptype: 'treeviewdragdrop'\n  }\n  },\n height: 700,\n width: 250,\n  region: '"+cardinal+"',\n   useArrows: true,\n dockedItems: [{\n  xtype: 'toolbar',\n items: [{\n text: 'Expandir todo',\n handler: function(){\n  TrArbol"+cont+".expandAll();\n  }\n }, {\n  text: 'Contraer todo',\n  handler: function(){\n TrArbol"+cont+".collapseAll();\n  }\n }]\n }]\n});\n";
     cod=cod+arbol;
     if(elem==""){
     elem='TrArbol'+cont;
     }
     else{
     elem=elem+',TrArbol'+cont;
     }
     }break;
     case 'Panel':
     {
     var pan="var panel"+cont+"= Ext.create('Ext.panel.Panel', {\n    title:'Panel',\n    region: 'center',\n    layout:'border'\n});\n";
     cod=cod+pan;
     if(elem==""){
     elem='panel'+cont;
     }
     else{
     elem=elem+',panel'+cont;
     }
     }break;
     case 'Combobox':
     {
     var box="var combo"+cont+" = Ext.create('Ext.form.field.ComboBox', {\n fieldLabel: 'Seleccione',\n editable: false,\n width: 500,\n  labelWidth: 130,\n store: [],\n emptyText:'--seleccione--',\n queryMode: 'local',\n typeAhead: true\n});\n";
     cod=cod+box;
     if(elem==""){
     elem='combo'+cont;
     }
     else{
     elem=elem+',combo'+cont;
     }
     }break;
     }
     }
     function mostForm(opcion){//funcion para mostrar el formulario
     switch(opcion){
     case 'gen':
     {
     if(!gen){
     gen = Ext.create('Ext.Window', {
     title: 'Generador',
     closeAction: 'hide',
     width: 500,
     height: 250,
     x: 220,
     y: 100,
     constrain: true,
     layout: 'fit',
     //items: simple,
     buttons: [{
     text: 'Agregar componente',
     icon:perfil.dirImg + 'aplicar.png',
     handler:function(){
     //cod=cod+Ext.getCmp('cmp').getValue();
     addComp(Ext.getCmp('cmp').getValue());
     //alert(cod);
     cont++;
     Ext.getCmp('cmp').setValue("");
     col="";
     cold="";
     cardinal='west';
     Ext.getCmp('pc').setVisible(false);
     Ext.getCmp('reg').setValue("");
     Ext.getCmp('reg').setVisible(false);
     Ext.getCmp('d').setVisible(false);
     Ext.getCmp('d').setValue("");
     url="";
     }
     },{
     text: 'Generar',
     icon:perfil.dirImg + 'aceptar.png',
     handler:function(){
     if(Ext.getCmp('cmp').getValue()!=null){
     addComp(Ext.getCmp('cmp').getValue());
     }
     var nombre=Ext.getCmp('NI').getValue();
     Ext.Ajax.request({
     url:'genInterfaz',
     method:'POST',
     params:{
     nombre:nombre,
     comp:cod,
     elem:elem
     },
     success: function(){
     alert(cont)
     }
     });
     gen.hide();
     cont=0;
     elem="";
     cod="";
     cardinal='west';
     col="";
     cold="";
     Ext.getCmp('cmp').setValue("");
     Ext.getCmp('NI').setValue("");
     Ext.getCmp('reg').setValue("");
     Ext.getCmp('reg').setVisible(false);
     Ext.getCmp('pc').setVisible(false);
     Ext.getCmp('d').setVisible(false);
     Ext.getCmp('d').setValue("");
     url="";
     }
     },{
     text: 'Cancelar',
     icon:perfil.dirImg + 'cancelar.png',
     handler:function(){
     //alert(Ext.getCmp('reg').getValue());
     cod="";
     col="";
     cold="";
     cardinal='west';
     cont=0;
     elem="";
     gen.hide();
     Ext.getCmp('cmp').setValue("");
     Ext.getCmp('NI').setValue("");
     Ext.getCmp('reg').setValue("");
     Ext.getCmp('pc').setVisible(false);
     Ext.getCmp('reg').setVisible(false);
     Ext.getCmp('d').setVisible(false);
     Ext.getCmp('d').setValue("");
     url="";
     }
     }
     ]
     });
     gen.add(simple);
     }
     gen.show();

     }
     break;
     case 'edit':
     {
     if(!edi){
     edi = Ext.create('Ext.Window', {
     title: 'Editar',
     closeAction: 'hide',
     width: 500,
     height: 550,
     x: 220,
     y: 100,
     constrain: true,
     layout: 'fit',
     buttons: [{
     text: 'Cargar',
     icon:perfil.dirImg + 'cargarfuentededatos.png',
     handler:function(){
     var dir=seleccionado;
     //alert(dir);
     Ext.Ajax.request({
     url:'editarInterfaz',
     params:{
     dir:dir
     },
     success: function(cod){
     alert(cod.responseText)
     }
     });
     }
     },{
     text: 'Guardar',
     icon:perfil.dirImg + 'aceptar.png',
     handler:function(){

     }
     },{
     text: 'Cancelar',
     icon:perfil.dirImg + 'cancelar.png',
     handler:function(){
     //alert(Ext.getCmp('f1').getValue());
     edi.hide();
     }
     }]
     });
     edi.add(formEdit);
     }
     edi.show();

     }break;

     }
     }*/

    var generar = Ext.create('Ext.Button', {//boton generar
        text: 'Generar',
        disabled: true,
        icon: perfil.dirImg + 'generardocumentos.png',
        iconCls: 'btn',
        handler: function () {
            if (Ext.getCmp('ll').getValue() == null) {
                Ext.MessageBox.show({
                    msg: "Seleccione una librería",
                    buttons: Ext.MessageBox.OK,
                    icon: Ext.MessageBox.ERROR
                });
            }
            else {
                Ext.Ajax.request({
                    url: 'genInterfaz',
                    method: 'POST',
                    params: {
                        libreria: Ext.getCmp('ll').getValue(),
                        concepto: sm.getLastSelected().data.concepto
                    },
                    success: function (aa) {
                        Ext.MessageBox.show({
                            msg: aa.responseText,
                            buttons: Ext.MessageBox.OK,
                            icon: Ext.MessageBox.INFO
                        });
                    }
                });
                Ext.getCmp('ll').setValue(null)
            }
        }
    });

    var stGpConceptos = Ext.create('Ext.data.ArrayStore', {
        fields: [
            {
                name: 'concepto'
            },
            {
                name: 'plantilla'
            }
        ],
        proxy: {
            type: 'ajax',
            url: 'cargarConceptos',
            actionMethods: {
                read: 'POST'
            }
        }
    });
    stGpConceptos.load();
    function mostrarPlantilla() {
        var nombre = sm.getLastSelected().data.plantilla;
        seleccionado = nombre;
        generar.enable();
        otro.load({
            params: {
                node: nombre
            }
        });
    }

    sm = Ext.create('Ext.selection.RowModel', {
        mode: 'SINGLE'
    });
    sm.on('select', function (smodel, record, eOpts) {
        mostrarPlantilla()
    }, this);
    var GpConceptos = Ext.create('Ext.grid.Panel', {
        store: stGpConceptos,
        stateful: true,
        stateId: 'stateGrid',
        columns: [
            {
                text: 'Concepto',
                id: 'concept',
                flex: 1,
                dataIndex: 'concepto'
            },
            {
                text: 'Plantilla',
                id: 'plant',
                flex: 1,
                dataIndex: 'plantilla'
            }
        ],
        region: 'center',
        tbar: [
            {text: 'Librería:'},
            {
                xtype: 'combobox',
                editable: false,
                store: ['ExtJS4', 'ExtJS2', 'Dojo', 'JQuery', 'Algo mas'],
                id: 'll',
                emptyText: '--seleccione--',
                vtype: 'combo'
            }
        ],
        selModel: sm
    });

    var g = Ext.create('Ext.Panel', { //subpanel general
        id: 'images-view',
        frame: true,
        region: 'center',
        width: 750,
        items: GpConceptos
    });
    ImageModel = Ext.define('ImageModel', {//utilizado para cargar las imagenes
        extend: 'Ext.data.Model',
        fields: [
            {name: 'name'},
            {name: 'url'},
            {name: 'size', type: 'float'},
            {name: 'lastmod', type: 'date', dateFormat: 'timestamp'}
        ]
    });
    otro = Ext.create('Ext.data.Store', {//utilizado para mostrar la imagen seleccionada
        model: 'ImageModel',

        proxy: {
            type: 'ajax',
            url: 'loadPreview',

            reader: {
                type: 'json',
                root: 'images'
            }
        }
    });
    var ok = Ext.create('Ext.Panel', { //utilizado para mostrar la imagen seleccionada
        title: 'Plantilla',
        id: 'images-view1',
        frame: true,
        region: 'east',
        collapsible: true,
        width: 290,
        items: Ext.create('Ext.view.View', {
            store: otro,
            tpl: [
                '<tpl for=".">',
                '<div class="thumb-wrap1" id="{name}">',
                '<div class="thumb1"><img src="{url}" title="{name}"></div>',
                '<span class="x-editable">{shortName}</span></div>',
                '</tpl>',
                '<div class="x-clear"></div>'
            ],
            multiSelect: false,
            height: 600,
            trackOver: true,
            overItemCls: 'x-item-over',
            itemSelector: 'div.thumb-wrap1',
            emptyText: 'No hay imagen',


            prepareData: function (data) {
                Ext.apply(data, {
                    shortName: Ext.util.Format.ellipsis(data.name, 15),
                    sizeString: Ext.util.Format.fileSize(data.size),
                    dateString: Ext.util.Format.date(data.lastmod, "m/d/Y g:i a")
                });
                return data;
            }

        })
    });
    var panel = Ext.create('Ext.panel.Panel', {//panel general
        // title: 'Selector de interfaz',
        layout: 'border',
        tbar: [generar],
        items: [g, ok]
    });
    var vpGestSistema = Ext.create('Ext.Viewport', {//viewport general
        layout: 'fit',
        items: panel
    });
}
