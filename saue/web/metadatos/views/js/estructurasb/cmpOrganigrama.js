var mytree = '';
function creaOrg(){
	
	
	mytree = new dTree('mytree');
	var i=0;
	var j=0;
	var bandera=0;
	
	for(i;i<nodosOrg.length;i++)
	{
		bandera=0;
		for(j=0;j<nodosOrg.length;j++)
		{
			if(nodosOrg[i].idpadre==nodosOrg[j].id && nodosOrg[i].id!=nodosOrg[j].id)
			{
				bandera=1;
				break;
			}	
		}
		
		if(bandera)
		{
			mytree.add(nodosOrg[i].id,nodosOrg[i].idpadre,nodosOrg[i].texto);
		}
		else
		{
			mytree.add(nodosOrg[i].id,-1,nodosOrg[i].texto,nodosOrg[i].texto);
		}
		
	}
	
}
creaOrg();
document.getElementById("org").innerHTML=""+mytree+"";

