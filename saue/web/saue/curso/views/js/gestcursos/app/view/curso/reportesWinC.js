Ext.define('GestCursos.view.curso.reportesWinC', {
    extend: 'Ext.Window',
    alias: 'widget.reportes_winC',
    idalumno: null,
    idreporte: null,
    initComponent: function () {
        var me = this;
        var html = '<style>';
        html += '.ball {border: 5px solid rgba(0,183,229,0.9);border-top: 5px solid rgba(0,183,229,0.3);border-left: 5px solid rgba(0,183,229,0.3);border-radius: 40px;';
        html += 'width: 40px;height: 40px;-moz-animation: spin 1s infinite linear;-webkit-animation: spin 1s infinite linear;position: absolute;top: 50%;left: 50%;}';
        html += '@-moz-keyframes spin {0% {-moz-transform: rotate(0deg);}100% {-moz-transform: rotate(360deg);}}';
        html += '@-webkit-keyframes spin {0% {-webkit-transform: rotate(0deg); }100% {-webkit-transform: rotate(360deg);}}';
        html += '</style><body><div class="ball"></div><iframe style="position: relative;" top="-23" frameborder="0" height="100%" width="100%" src="'+me.url+'"></iframe></body>';
        var component = new Ext.Component({
            html: html
        });
        var panel = new Ext.Panel({
            id:'visorReporteNuevo',
            layout:'fit',
            items:[component]
        });
        var comboFormat = new Ext.form.ComboBox({
            xtype: 'combo',
            id: 'idformato',
            name: 'idformato',
            fieldLabel: 'Formato de salida',
            allowBlank: false,
            //emptyText: perfil.etiquetas.lbEmpCombo,
            editable: false,
            store: Ext.create('Ext.data.Store', {
                fields: ['formato'],
                data: [
                    {"formato": "xls"},
                    {"formato": "pdf"}
                ]
            }),
            anchor: '100%',
            //labelWidth: 130,
            queryMode: 'local',
            displayField: 'formato',
            valueField: 'formato'
        });
        var btnExportar = new Ext.Button({
            text: 'Exportar',
            icon: perfil.dirImg + 'exportar.png',
            handler: function () {
                if (comboFormat.getValue() != null) {
                    window.open(me.url + "&formato=" + comboFormat.getRawValue(), '_blank');
                }else
                    mostrarMensaje(3, 'Debe seleccionar a que formato desea exportar el reporte.');
            }
        });

        var btnImprimir = new Ext.Button({
            text: 'Imprimir',
            icon: perfil.dirImg + 'imprimir.png',
            handler: function () {
                document.getElementById('PDFtoPrint').focus();
                document.getElementById('PDFtoPrint').contentWindow.print();
            }
        });
        Ext.QuickTips.init();

        Ext.apply(me, {
            overflowY: 'scroll',
            autoScroll: true,
            modal: true,
            constrain: true,
            resizable: false,
            border: false,
            layout: 'fit',
            height: 600,
            width: 1000,
            //tbar:[btnImprimir, '->',comboFormat,btnExportar],
            items:[panel]
        });

        me.callParent(arguments);
    }
});
