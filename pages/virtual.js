//require Parameter util v1
//require layout materializador v1

var layout = Parameter("layout");
var entity = Parameter("entity");

function main() {
	EnviarJsonPost( PREFIX + "route/edb.php", {"action" : "layoutget", "entity" : entity  , "name" : layout, "domain" : undefined}, function(retorno, status, error){
		if(retorno != undefined && retorno.rows != undefined && retorno.status == true  ) {
			materializar("raiz", retorno.rows.layout.containers[0], guid().substring(0, 5))
		} else {
			console.error(retorno);
		}
	}); 

}









