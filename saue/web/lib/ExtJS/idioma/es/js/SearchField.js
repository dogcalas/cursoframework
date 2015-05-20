Ext.define('Ext.ux.form.SearchField', {
    extend: 'Ext.form.field.Trigger',

    alias: 'widget.searchfield',

    trigger1Cls: Ext.baseCSSPrefix + 'form-clear-trigger',

    trigger2Cls: Ext.baseCSSPrefix + 'form-search-trigger',

    hasSearch: false,
    filterParamName: 'filtros',
    filterPropertysNames: [],

    initComponent: function () {
        var me = this;

        me.callParent(arguments);
        me.on('specialkey', function (f, e) {
            if (e.getKey() == e.ENTER) {
                me.onTrigger2Click();
            }
        });

        if (Ext.isString(me.store)){
            me.store = Ext.data.StoreManager.lookup(me.store);
        }

        // We're going to use filtering
        me.store.remoteFilter = true;

        // Set up the proxy to encode the filter in the simplest way as a name/value pair

        // If the Store has not been *configured* with a filterParam property, then use our filter parameter name

        if (!me.store.proxy.hasOwnProperty('filterParam')) {
            me.store.proxy.filterParam = me.filterParamName;
        }

        //Creando los filtros por cada filterPropertysNames definido
        me.filtros = Ext.create('Ext.util.MixedCollection');
    },

    afterRender: function () {
        this.callParent();
        this.triggerCell.item(0).setDisplayed(false);
    },

    onTrigger1Click: function () {
        var me = this;

        if (me.hasSearch) {
            me.setValue('');
            me.filtros.each(
                function (filtro) {
                    me.store.filters.eachKey(
                        function (key, filter) {
                            if (filtro.id === key) {
                                me.store.filters.remove(filter);
                            }
                        }
                    )
                }
            )
            /*me.filtros.each(
             function (filtro) {
             console.log();
             me.store.removeFilter(filtro);
             }
             )*/
            me.store.load();
            me.hasSearch = false;
            me.triggerCell.item(0).setDisplayed(false);
            me.updateLayout();
        }
    },

    onTrigger2Click: function () {
        var me = this,
            value = me.getValue();

        if (value != null) {

            if (me.filterPropertysNames.length > 0) {
                for (var i = 0; i < me.filterPropertysNames.length; i++) {
                    me.filtros.add(i, Ext.create('Ext.util.Filter', {
                        id: 'id' + me.filterPropertysNames[i],
                        property: me.filterPropertysNames[i],
                        value: value
                    }))
                }
            }

            // Param name is ignored here since we use custom encoding in the proxy.
            // id is used by the Store to replace any previous filter
            me.filtros.each(
                function (filtro, index, len) {
                    me.store.addFilter(
                        [
                            filtro
                        ], (index == len - 1)
                    );
                }
            )

            me.hasSearch = true;
            me.triggerCell.item(0).setDisplayed(true);
            me.updateLayout();
        }
    }
});