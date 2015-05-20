Ext.define('GestCursos.view.alumno.Search', {
    extend: 'Ext.window.Window',
    alias: 'widget.curso_alumno_search',

    title: 'Listado de alumnos del curso',//Etiqueta

    layout: 'fit',
    modal: true,
    resizable: false,
    autoShow: true,
    width: 800,
    height: 600,

    initComponent: function () {
        this.items = Ext.create('GestCursos.view.alumno.Grid');

        //this.buttons = [];

        this.callParent(arguments);
    }
})