/*
 * File: app/model/Sancion.js
 *
 * This file was generated by Sencha Architect version 2.1.0.
 * http://www.sencha.com/products/architect/
 *
 * This file requires use of the Ext JS 4.1.x library, under independent license.
 * License of Sencha Architect does not include license for Ext JS 4.1.x. For more
 * details see http://www.sencha.com/license or contact license@sencha.com.
 *
 * This file will be auto-generated each and everytime you save your project.
 *
 * Do NOT hand edit this file.
 */

Ext.define('Sancion.model.Sancion', {
    extend: 'Ext.data.Model',

    fields: [
        {
            name: 'idsancion'
        }, 
        {
            name: 'nombre'
        }, 
        {
            name: 'idalumno'
        }, 
        {
            name: 'apellidos'
        },      
        {
            name: 'alumno', type: 'string', convert: function(valor, record){
                return record.get('nombre') + ' ' + record.get('apellidos') ;
                }
        },
        {
            name: 'cedula'
        },
        {
            name: 'descripcion'
        },
        {
            name: 'fechainicio'
        },
        {
            name: 'fechafin'
        }
    ]
});