$(document).bind('pageinit',function(){

    $.ajax({
        url: 'index.php/index/cargardominio',
        type: 'POST',
        dataType:'html',
        // waitMsg: 'Entrando al sistema Ecotec...',

        success:function(pResp,atrib){
            var obj=$.parseJSON(pResp);
            for(i=0;i<obj.length;i++){
console.log(obj[i].fullName);
            }
            return;
            alert($.getJSON(pResp))
            var objJson = pResp.data[0].name;
            if (ventana != null) {
                ventana.close();
                ventana = null;
            }
            if (objJson.reload == false) {
                window.location.reload();
            }
            else if (objJson.reload == true) {
                verificarPerfil();
            }
            else if (objJson.codMsg) {
                var msg = objJson.mensaje;
                mensaje = objJson;
                Ext.Msg.show({
                    title: 'ERROR',
                    msg: msg,
                    buttons: Ext.Msg.OKCANCEL,
                    animateTarget: 'elId',
                    icon: Ext.window.MessageBox.ERROR
                });

                //Ext.Msg.alert('ERROR', msg);
//                    mostrarMensaje(mensaje.codMsg, mensaje.mensaje);
            }
        }
    });

});