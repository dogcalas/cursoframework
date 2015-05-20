Ext.define('Saue.VisorReportes', {
    extend: 'Ext.Window',
    alias: 'widget.visorreportes',
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
        var pan = new Ext.Panel({
            layout:'fit',
            items:[component]
        });

        Ext.QuickTips.init();

        Ext.apply(me, {
            overflowY: 'scroll',
            autoScroll: true,
            height: 600,
            width: 1000,
            //constrain: true,
            resizable: true,
            title: 'Reporte: '+me.reporte,
            layout: 'fit',
            items:[pan]
        });

        me.callParent(arguments);
    }
});
