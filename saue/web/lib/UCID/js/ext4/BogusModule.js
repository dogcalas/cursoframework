/*

This file is part of Ext JS 4

Copyright (c) 2011 Sencha Inc

Contact:  http://www.sencha.com/contact

GNU General Public License Usage
This file may be used under the terms of the GNU General Public License version 3.0 as published by the Free Software Foundation and appearing in the file LICENSE included in the packaging of this file.  Please review the following information to ensure the GNU General Public License version 3.0 requirements will be met: http://www.gnu.org/copyleft/gpl.html.

If you are unsure which license is appropriate for your use, please contact the sales department at http://www.sencha.com/contact.

*/
/*!
* Ext JS Library 4.0
* Copyright(c) 2006-2011 Sencha Inc.
* licensing@sencha.com
* http://www.sencha.com/license
*/

var windowIndex = 0;

Ext.define('MyDesktop.BogusModule', {
    extend: 'Ext.ux.desktop.Module',

    init : function(){
        this.launcher = {
            text: 'Window '+(++windowIndex),
            iconCls:'bogus',
            handler : this.createWindow,
            scope: this,
            windowId:windowIndex
        }
    },

    createWindow : function(src){
        var desktop = this.app.getDesktop();
        var win = desktop.getWindow('win'+src.aWinConfig.id);
        if(!win){
            win = desktop.createWindow({
                id: 'win'+src.aWinConfig.id,
                title:src.aWinConfig.text,
                layout:'fit',
               funt: src.aWinConfig.id,
                items:new Ext.Panel({
						id: 'iframe'+src.aWinConfig.id,
						border:false,
						html: '<iframe id="ifMarco' + src.aWinConfig.id + '" style="width:100%; height: 100%; border:none;"></iframe>',
						layout:'fit'
					}),
					width:800,
					maximized:true,
					height:490,
					minWidth :800,
					minHeight:480,
                iconCls: 'bogus',
                animCollapse:false,
                constrainHeader:true
            });
        }
        win.show();
         ventanas.register( win );
        idFuncionalidad = src.aWinConfig.id;
			document.getElementById('ifMarco' + src.aWinConfig.id).src = src.aWinConfig.referencia + '?opcion=' + src.aWinConfig.index;
        return win;
    }
});
