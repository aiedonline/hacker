/*
*	AUTOR: Wellington Pinto de Oliveira
*	E-mail: wellington (ponto) aied (arroba) gmail (ponto) com
*	Info: Neste arquivo você encontra o código genérico de Layout, as funçoes específicas dos controles estão em arquivos individuais.
*   Documentação: https://docs.google.com/document/d/1hSXg0XoKraoVtbrUawc8kz1MKgCJk1YL_oWFNF7Ze0M/edit?usp=sharing
*/


function SetContext(div){
	if(typeof(div) === typeof("")){
		if(window[div] == undefined){
			eval("var " + div + "  = undefined;");
			window[div] = $("#" + div);
		}
	}
	window[div].AddText = function(id, type, label, descricao, options){
		AddText($(window[div]), type, label, descricao, id, options);
	}
	
	window[div].AddLabel = function(label, foriten){
		if(foriten != undefined)
			AddLabel($(window[div]), label, foriten);
		else
			$(window[div]).html("<strong>"+ label +"</strong>");
	}
	
	window[div].AddTextArea = function(id, rows, options){
		AddTextArea($(window[div]), id, rows, options);
	}
	
	window[div].AddButton = function(id, label, onclick, options){
		AddButton($(window[div]), label, onclick, id, options);
	}
	window[div].AddDiv = function (id, html){
		AddDiv($(window[div]), html, id);
	}
	window[div].AddRow = function (id, cols, layout){
		//AddRow(div, cols, layout, id, values, options)
		AddRow($(window[div]), cols, layout, id);
	}
	window[div].AddBr = function (number){
		AddBr($(window[div]), number);
	}
	window[div].AddDropDown = function(id, label, array, colunaChave, colunaRotulo, onChange) {
		AddDropDownV3($(window[div]), array, colunaChave, colunaRotulo, onChange, label, id)
		//AddDropDownV3(div, array, colunaChave, colunaRotulo, onChange, label, id)
	}
	
	window[div].AddTable = function (id, headers, data, onRender, options) {
		AddRowTable($(window[div]), headers, data, id, onRender, options)
	}
	
	window[div].AddHtml = function(id, html, options){
		AddHtmlV2($(window[div]), html, id, options)
	}
	
	window[div].AddDateTime = function(id, label, options){
		AddRowDateTimeV2($(window[div]), label, id, options)
	}
	
	window[div].AddHeader = function(id, number, text, options){
		AddHeader($(window[div]), number, text, id, options)
	}
	
	
	//AddRowDateTimeV2(div, label, id, options)

}


function print(obj1, obj2, obj3, obj4, obj5, obj6){
	
	console.log(obj1);
}


function NotifyClear(){
    $(".notifyjs-wrapper").remove();
}
function Notify(message, element, type, time){
	type = type || 'info';
	element = element || $;
	time = time || 10000;
    $(".notifyjs-wrapper").remove();
    return element.notify(message, {"className" : "error", "autoHideDelay" : time, "className" : type});
}

function NotifySave(element, sucess){
	sucess = sucess || false;
	if(element != undefined && element.length == 0) element = undefined;
	return Notify((sucess  == true ? "Está salvo, sucesso." : "Aguarde enquanto estamos salvando"), (element == undefined ? $ : element), (sucess == true ? "success" : "info"), (sucess == true ? 10000 : 100000));
}

function NotifyError(error, time){
	if(time == undefined) time = 100000;
	return Notify(error, $, "error", time);
}

function NotifySucess(message, time){
	if(time == undefined) time = 100000;
	return Notify(message, $, "sucess", time);
}

$('.alphaonly').bind('keyup blur',function(){
    var node = $(this);
    node.val(node.val().replace(/[^a-z ]/g,'') ); }
);

function guid() {
    function s4() {
        return Math.floor((1 + Math.random()) * 0x10000)
            .toString(16)
            .substring(1);
    }
    return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
        s4() + '-' + s4() + s4() + s4();
}

// Adiciona a classe COL para o controle, pode receber um elemento JQUERY ou o nome de um controle, pode receber números positivos ou Undefined conforme explicação nos exemplos.
function ColAll(element, xs, sm, md, lg){
    lg = lg || md || sm || xs;
    md = md || sm || xs;
    sm = sm || sm || xs;

    if(typeof(element) == typeof("")) element = $("#" + element);

    if (xs > 0) element.addClass("col-xs-" + xs);
    if (sm > 0) element.addClass("col-sm-" + sm);
    if (md > 0) element.addClass("col-md-" + md);
    if (lg > 0) element.addClass("col-lg-" + lg);
}

function Base64Encode(str, encoding = 'utf-8') {
    var bytes = new (TextEncoder || TextEncoderLite)(encoding).encode(str);        
    return base64js.fromByteArray(bytes);
}

function Base64Decode(str, encoding = 'utf-8') {
    var bytes = base64js.toByteArray(str);
    return new (TextDecoder || TextDecoderLite)(encoding).decode(bytes);
}


// -----------------------------------------




// ----------- SCRIPTS --------------------------



// Mensagem de método deprecidado
function Deprecated(met){
    console.error("Warning: método depreciado " + met);
}

function DeprecatedUrl(url){
    console.error("URL usando método depreciado: " + url);
}

// Importa um script remoto utilizndo Ajax, a função callback pode ou não ser passada, se for passada uma função callback ela será executada.
// https://docs.google.com/document/d/1hSXg0XoKraoVtbrUawc8kz1MKgCJk1YL_oWFNF7Ze0M/edit#heading=h.uxjrd9n1279t
function ImportScriptSync(script, callback){
    $.getScript( script )
        .done(function( script, textStatus ) {
            if(callback != undefined) callback();
        })
        .fail(function( jqxhr, settings, exception ) {
            console.error("Script não carregado: " + script + "\n" + exception);
			Show();
        });
	
    /* 
	var opcoes = {
		type: "GET",
		url: script,
		success: callback,
		dataType: "script",
		cache: true,
		success: function (response) {
        	if(callback != undefined) callback();
      	},
      	error: function (xhr, ajaxOptions, thrownError) {
			console.error("Script não carregado: " + script + "\n");
			console.error(xhr.status);
        	console.error(thrownError);
      	}
    };
	jQuery.ajax(opcoes);
	*/

}

function ImportScriptList(scripts, callback, pos){
	if(pos == undefined) pos = 0;

	if(pos == scripts.length){
		callback();
		return;
	}

	ImportScriptSync(scripts[pos], function () {
		ImportScriptList(scripts, callback, ++pos)
	});
}


// DEPRECIADO, use ImportScriptSync
function AddScript(scriptSource){
    Deprecated("AddScript");
    var name = "script_" + scriptSource.replace(/\W+/igm, "_");
    if($("#" + name).length > 0)
    {
        return;
    }
    $("head").append("<script id=\""+ name +"\" name=\""+ name +"\" src=\""+ scriptSource +"\"></script>");
}

function AddStyle(scriptSource){
    Deprecated("AddStyle");
    var name = "style_" + scriptSource.replace(/\W+/igm, "_");
    if($("#" + name).length > 0)
    {
        return;
    }
    $("head").append("<style id=\""+ name +"\" name=\""+ name +"\" href=\""+ scriptSource +"\"></style>");
}

function includeCSSfile(href) {
    var head_node = document.getElementsByTagName('head')[0];
    var link_tag = document.createElement('link');
    link_tag.setAttribute('rel', 'stylesheet');
    link_tag.setAttribute('type', 'text/css');
    link_tag.setAttribute('href', href);
    head_node.appendChild(link_tag);
}

//-------------- BOTAO LOAD -----------------------
// DEPRECIADO, usar o COMUTARBOTAOV2
function CommutarBotaoLoad(name){
    Deprecated("CommutarBotaoLoad");
    if($("#li_" + name).hasClass("fa-circle-o-notch")){
        $("#" + name).removeClass("btn-danger");
        $("#" + name).addClass("btn-success");
        $("#li_" + name).removeClass("fa-circle-o-notch");
    }
    else{
        $("#" + name).removeClass("btn-success");
        $("#" + name).addClass("btn-danger");
        $("#li_" + name).addClass("fa-circle-o-notch");
    }
}

// https://docs.google.com/document/d/1hSXg0XoKraoVtbrUawc8kz1MKgCJk1YL_oWFNF7Ze0M/edit#heading=h.w8ooj9hbx218
// Comuta um botão entre o padrão antivo e o aguarde, é muito útil para rotinas que demoram e que são assincronas.
function CommutarBotaoLoadV2(name){
	RemoveMessage(button);
    var button = $("#" + name);

    var li = $("#" + name + "_li_comm");
    if(li.hasClass("fa-circle-o-notch")){ // voltar
        button.removeClass("btn-danger");
        button.addClass("btn-primary");
        li.removeClass("fa-circle-o-notch");
    }
    else{  //ir
        button.removeClass("btn-primary");
        button.addClass("btn-danger");
        li.addClass("fa-circle-o-notch");
    }
}


// Pode ser usado com elementos que executam atividades de usuário para exibir uma mensagem de erro.
// https://docs.google.com/document/d/1hSXg0XoKraoVtbrUawc8kz1MKgCJk1YL_oWFNF7Ze0M/edit#heading=h.z3a90hcdofk
function ErroExecucao(button, type, message, time){
	RemoveMessage(button);
    $( "<div id='"+ button +"_message' class=\"alert alert-"+ type +"\">"+ message +"</div>" ).insertAfter( "#" + button );
    if(time != undefined){
        setTimeout(function(){
            $("#" + button + "_message").remove();
        }, time * 1000);
    }
	
	// Mandando para o servidor de erros.
	console.error(message);
}

// Pode ser usado com elementos que executam atividades de usuário para exibir uma mensagem de sucesso.
// https://docs.google.com/document/d/1hSXg0XoKraoVtbrUawc8kz1MKgCJk1YL_oWFNF7Ze0M/edit#heading=h.vcrx044d1a0k
function SucessoExecucao(button, message, time){
	RemoveMessage(button);
    time = time || 3;
    //ErroExecucao(button, "success", message);
	$( "<div id='"+ button +"_message' class=\"alert alert-success\">"+ message +"</div>" ).insertAfter( "#" + button );
    setTimeout(function(){
        $("#" + button + "_message").remove();
    }, time * 1000);
}

// Depreciado
function RemoveErroExecucao(button){
    Deprecated("RemoveErroExecucao");
    RemoveMessage(button);
}

// Remove mensagens adicionadas a um botão.
// https://docs.google.com/document/d/1hSXg0XoKraoVtbrUawc8kz1MKgCJk1YL_oWFNF7Ze0M/edit#heading=h.ju7lubyaj7fs
function RemoveMessage(button){
    $("#" + button +"_message").remove();
}

//-------------------------------------- editores de formulário POR JAVASCRIPT --------------

function AddHeader(div, number, text, id, options){
	var criarElemento = (id != undefined);
	if(typeof(div) === typeof("")) div = $("#" + div);
	
	if (id == undefined) {
        id = guid();
    }
	
	if($("#" + id).length > 0){
		$("#" + id).remove();
	}
	
	div.append("<h"+ number +" id='"+ id +"'>" + text + "</h"+ number +">");
	if(criarElemento) window[id] = $("#" + id);
	return id;
}

function AddLink(div, text, href, id, options){
	var criarElemento = (id != undefined);
	if(typeof(div) === typeof("")) div = $("#" + div);
	if (id == undefined) {
        id = guid();
    }
	
	div.append("<a id='"+ id +"' href='"+ href +"'>" + text + "</a>");
	if(criarElemento) window[id] = $("#" + id);
	return id;
}

function AddHr(div){
	if(typeof(div) === typeof("")) div = $("#" + div);
	div.append("<hr />");
}

function AddAlert(div, text){
    if(typeof(div) === typeof("")) div = $("#" + div);
    if(typeof (text) != typeof ("")){
        var buffer = "<ul>";
        for(var i = 0; i < text.length; i++){
            buffer += "<li>"+ text[i] +"</li>";
        }
        buffer += "</ul>";
        text = buffer;
    }

    div.append('<div class="alert alert-danger" role="alert">'+ text +'</div>');

}

function AddLabel(div, label, foriten){
	if(typeof(div) === typeof("")) div = $("#" + div);
	div.append("<label>"+ label +"</label>");
}

function AddRowDateTime(div, id, options){
    Deprecated("AddRowDateTime");
    AddRowDateTimeV2(div, undefined, id, options);
}



// ------------------ LAYOUT AUTOMÁTICO -----------------

//Adiciona uma aba a uma TAB, deve-se então informar um ID de uma TAB, um texto para exibição da aba, um html para a div de corpo.
// https://docs.google.com/document/d/1hSXg0XoKraoVtbrUawc8kz1MKgCJk1YL_oWFNF7Ze0M/edit#heading=h.qupsmbbokm69
function AddRowTabItem(id, text, html, i){
    $("#" + id + "_tab_ul").append("<li id='" + id + "_tab_ul_"+ i +"_li' name='" + id + "_tab_ul_"+ i +"_li'><a id='" + id + "_tab_ul_"+ i +"_li_a' name='" + id + "_tab_ul_"+ i +"_li_a' data-toggle='tab' href='#"+ id +"_menu_"+ i +"'>"+ text +"</a></li>");
    $("#" + id + "_tab_content").append("<div id='"+ id +"_menu_"+ i +"' class='tab-pane fade'>"+ html +"</div>");

    if( $("#" + id + "_tab_content").children().length == 1){
        $("#" + id + "_tab_ul_"+ i +"_li").addClass("active");
        $("#" + id +"_menu_"+ i ).addClass("in");
        $("#" + id +"_menu_"+ i ).addClass("active");
    }
}



// (div, [6, 2, 4], "lg", "div_row")
function AddRow(div, cols, layout, id, values, options){
    try {
		var criarElemento = (id != undefined);
		if(typeof(div) === typeof("")) div = $("#" + div);
		if(id == undefined){
			id = guid();
		}

		if(layout == undefined)
        {
            layout = "xs";
        }
		
		options = options || {};
		options.values = values;
		
		
        div.append("<div class='container-fluid'><div id='" + id + "' class='row-fluid'></div></div>");
		window[id] = $("#" + id);
        
		for (var i = 0; i < cols.length; i++) {
            $("#" + id).append("<div id='" + id + "_" + i + "' class='col-" + layout + "-" + cols[i] + "'></div>");
			
			window[id + "_" + i] = $("#" + id + "_" + i);
			SetContext(id + "_" + i);
			
        }
		
		if(options.values != undefined){
			for(var i = 0; i < options.values.length; i++){
				window[id + "_" + i].html(options.values[i]);
			}
		}
		
		
    }catch(e){
        console.error(e, e.stack);
    }
    return id;
}

// Adiciona um formulário com uma DIV interna, fundamental para preparar uma interface de cadastro.
// https://docs.google.com/document/d/1hSXg0XoKraoVtbrUawc8kz1MKgCJk1YL_oWFNF7Ze0M/edit#heading=h.kwhtxwmgsgq
function AddForm(div, action, id){
    if(id == undefined){
        id = guid();
    }
    if($("#" + id).length > 0){
        id = guid();
    }
    div.append("<form action='"+ action +"' method='post' id='"+ id +"' name='"+ id +"' enctype='multipart/form-data' ></form>");
    $("#" + id).append("<div  id='"+ id +"_div' name='"+ id +"_div' ></div>");

    return id;
}


// Deprecidado usar AddRowText
function AddRowForm(div, type, label, descricao, id, options){
    Deprecated("AddRowForm");
    return AddRowText(div, type, label, descricao, id, options);
}




function AddBr(div, number){
	
	if(typeof(div) === typeof("")) div = $("#" + div);
	
    if(number == undefined) number = 1;

    for(var i = 0; i < number; i++)
	{div.append("<br />")}
	//{div.append("<div>&nbsp;</div>")}
}

function AddRowDiv(div, html, id){
	AddDiv(div, html, id);
}
function AddDiv(div, html, id){
	
    try {
		if(typeof(div) === typeof("") )div = $("#" + div);
		if(html == undefined || html == null) {
		    html = "";
        }
        if (id == undefined) {
            id = guid();
        }
        if ($("#" + id).length > 0) {
            id = guid();
        }
		
		window[id] = $("#" + id);
		
        div.append("<div id='" + id + "' name='" + id + "'>" + html + "</div>")
		SetContext(id);
		
    }catch(e){
        console.error(e, e.stack);
		return undefined;
    }
    return id;
}


/*
 Definição:
 Exemplo:
 AddRowTable($("#raiz"), ["Command", "Script"], [], "tbl_scripts_python")
 */


function pageTable(htmlButton, incremento){
    var button = $("#" + htmlButton.id );
    var pagina = parseInt($("#" + button.attr("table-id")).attr("page")) + incremento;
    PopulateTable(button.attr("table-id"), undefined, {"next" : pagina}, function (row, key, value, data) {							return "<a href='/"+ PREFIX +"generic/layout/" + $("#" + button.attr("table-id")).attr("form") + "/" + data._id + "'>" + value + "</a>"; });
}


/*
 Definição:
 Exemplo:
 AddRowTab($("#raiz"), [{"text" : "Propriedades"},{"text" : "Scripts"}], "tab_python_project_details");
 */

function AddRowTab(div, headers, id){
	AddTab(div, headers, id);
}
function AddTab(div, headers, id, options){
    try {
		options = options || {};
        if (id == undefined) {
            id = guid();
        }
		
        if($("#" + id + "_tab").length > 0){
            return;
        }
		
		if(options.position == undefined) options.position = "top";
		
		
        div.append("<div id='" + id + "_tab' name='" + id + "_tab'  ></div>"); // TAB principal e mais externa
		if(options.position == "top") {
        	$("#" + id + "_tab").append("<ul id='" + id + "_tab_ul' name='" + id + "_tab_ul' class='nav nav-tabs'></ul>");
			$("#" + id + "_tab").append("<div id='" + id + "_tab_content' name='" + id + "_tab_content' class='tab-content'  style='min-height : 200px; border-left: 1px solid #d3d3d3; border-right: 1px solid #d3d3d3; border-bottom: 1px solid #d3d3d3' ></div>");
		}else{
			$("#" + id + "_tab").append("<div id='" + id + "_tab_content' name='" + id + "_tab_content' class='tab-content'  style='min-height : 200px; border-left: 1px solid #d3d3d3; border-right: 1px solid #d3d3d3; border-bottom: 1px solid #d3d3d3' ></div>");
			$("#" + id + "_tab").append("<ul id='" + id + "_tab_ul' name='" + id + "_tab_ul' class='nav nav-tabs'></ul>");
			
			$("#" + id + "_tab").addClass("tabs-bottom");
			
		}
			

        for(var i = 0; i < headers.length; i++){
            $("#" + id + "_tab_ul").append("<li id='" + id + "_tab_ul_"+ i +"_li' name='" + id + "_tab_ul_"+ i +"_li'><a div='"+ ( headers[i].div != undefined ? headers[i].div : id +"_menu_"+ i ) +"' id='" + id + "_tab_ul_"+ i +"_li_a' name='" + id + "_tab_ul_"+ i +"_li_a' data-toggle='tab' href='#" +   ( headers[i].div != undefined ? headers[i].div : id +"_menu_"+ i ) +"'>"+ headers[i].text +"</a></li>");
            if(i == 0){
                $("#" + id + "_tab_ul_"+ i +"_li").addClass("active");
            }
        }
		
        for(var i = 0; i < headers.length; i++){
            $("#" + id + "_tab_content").append("<div id='"+ ( headers[i].div != undefined ? headers[i].div : id +"_menu_"+ i ) +"' class='tab-pane fade' style='padding: 10px;'></div>");
				window[headers[i].div] = $("#" + headers[i].div);
				SetContext(headers[i].div);

            if(i == 0){
                $("#" + ( headers[i].div != undefined ? headers[i].div : id +"_menu_"+ i )).addClass("in");
                $("#" + ( headers[i].div != undefined ? headers[i].div : id +"_menu_"+ i )).addClass("active");
            }
        }
		$("#" + id + "_tab_ul" + ' a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
			if(window[$(this).attr("div") + "_selected"] != undefined) window[$(this).attr("div") + "_selected"]();
			
		});
		
    }
    catch(e){
        console.error(e, e.stack);
		return undefined;
    }
    return id;
}


function NewTabRow(id, text, div_name){
	var ul = $("#" + id + "_tab_ul");
	var content = $("#" + id + "_tab_content");

	console.log( ul.length, content.length );

	content.append("<div id='"+ div_name +"' class='tab-pane fade' style='padding: 10px;'></div>");
	ul.append("<li id='" + id + "_tab_ul_"+ ul.children().length +"_li' name='" + id + "_tab_ul_"+ ul.children().length +"_li'><a div='"+ div_name +"' id='" + id + "_tab_ul_"+ ul.children().length  +"_li_a' name='" + id + "_tab_ul_"+ ul.children().length  +"_li_a' data-toggle='tab' href='#" + div_name +"'>"+ text +"</a></li>");
}


// --------------------------		DEPRECIADO   ---------------------------
function AddRowText(div, type, label, descricao, id, options){
	return AddText(div, type, label, descricao, id, options);
}

function AddRowBr(div, number){
	return AddBr(div, number);
}







