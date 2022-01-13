function cves_main(){
	try {
		shodan = JSON.parse(objeto_carregado.shodan);
		if(shodan.vulns != null){
			saida = "<font color='red'>Os CVEs abaixo foram listados automaticamente de fontes externas, deve ser confirmado antes de adicionar na sua lista.</font><br/>";
			for(var i = 0; i < shodan.vulns.length; i++){
				saida += "<a href='JavaScript:ExibirCve(\""+ shodan.vulns[i] +"\")'>" + shodan.vulns[i] + "</a>";
				if(i < shodan.vulns.length -1){
					saida += ", ";
				}
			}
			$("#cves").html(saida );
		}
	}catch(e){
		console.error(e);
	}
}
function ExibirCve(cve){
	AbrirPopup("/secanalysis/pages/cve.php?cve=" + cve);

}