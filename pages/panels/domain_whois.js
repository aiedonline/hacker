



function whois_main(){
    var s = {
        "title": "Json Whois",
        "type": "object",
        "properties": {
        }
    }
    
    try{
        objeto_carregado.whois = JSON.parse(objeto_carregado.whois);
        AddJson("whois", "json_whois_domain", "Whois", undefined, objeto_carregado.whois);
    }catch(e){
        console.error(e);
    }

}