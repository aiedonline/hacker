



function shodan_main(){
    try{
        objeto_carregado.shodan = JSON.parse(objeto_carregado.shodan);
        AddJson("shodan", "json_shodan_domain", "Shodan", undefined, objeto_carregado.shodan);
    }catch(e){
        console.error(e);
    }

}