Ext.define("Sauxe.App",{extend:"Ext.ux.desktop.App",
requires:[


"MyDesktop.BogusMenuModule",
"MyDesktop.BogusModule",
],
init:function(){
	this.callParent()
	},
getModules:function(){
	return[

new MyDesktop.TabWindow()

]},
getDesktopConfig:function(){
	var b=this,
	a=b.callParent();
	return Ext.apply(a,{
		contextMenuItems:[{text:"Change Settings",
	handler:b.onSettings,scope:b}],
	
	wallpaper:"wallpapers/Blue-Sencha.jpg",
	wallpaperStretch:false
	}
	)},
	getStartConfig:function(){
		var b=this,a=b.callParent();
	  return Ext.apply(a,{
		  title:"Don Griffin",iconCls:"user",height:300,toolConfig:{
		width:100,
		items:[{text:"Settings",iconCls:"settings",handler:b.onSettings,scope:b},
		"-",
		{text:"Logout",iconCls:"logout",handler:b.onLogout,scope:b}
		]
		}
		}
		)},
		getTaskbarConfig:function(){
			var a=this.callParent();
		return Ext.apply(a,{
			quickStart:[{name:"Accordion Window",iconCls:"accordion",module:"acc-win"},
		{name:"Grid Window",iconCls:"icon-grid",module:"grid-win"}],
		trayItems:[{xtype:"trayclock",flex:1}]
		})
		},
		onLogout:function(){
			Ext.Msg.confirm("Logout","Are you sure you want to logout?")
			},
		onSettings:function(){
			var a=new MyDesktop.Settings({desktop:this.desktop});
			a.show()
			}
		});
