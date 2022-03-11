import sys, os, subprocess, traceback, time, traceback;

os.environ["ROOT"] = "/var/well/secanalysis/bot/";
sys.path.insert(0, os.environ["ROOT"]);

from api.hacker import *;
from api.cacherequest import *;
from api.browser.browser import *;
data = sys.stdin.readlines();
data = json.loads(data[0]);
print(data);
#data = {"search" : "apache", "server" : "127.0.0.1", "port" : "80", "protocol" : "http", "user" :  "c308f6804bdd1a856355d3a34113f22a5d5f799b"};

c = CacheRequest(life=180);
bw = Browser(terminal=False);
bw.navegar("https://www.cve.org/");

if c.get("https://cve.mitre.org/cgi-bin/cvekey.cgi?keyword=" + data["search"]):
    links = c.elements('//a[contains(@href, "name=CVE")]'); #/@href
    for link in links:
        try:
            cve_banco = SendService(data["server"], "cve_list.php", {"cve" : link.text_content(), "user" : data["user"]}, port=data["port"], protocol=data["protocol"] );
            #CVE-2022-24112 {'_id': 'b121670227b5b61dbc051307e1acb04f', 'codigo': 'CVE-2022-24112', 
            # ------------------------CVE MITRE ---------------------------------
            # 'description': None, 'full_description': None, 'script_version': None, 
            #  --------------------- CVE DETAILS --------------------------
            #  'score': None,
            #  'confidentitality': None, 'integrity': None, 'availability': None,
            #  'access_complexity': None, 'autentication': None,
            #  'gained_access': None, 'vulnerability_type': None, 'cwe_id': None,
            #  }
            print("\t\t", link.text_content());
            if cve_banco.get("description") == None:
                print("URL:", "https://www.cve.org/CVERecord?id=" + link.text_content());
                sys.exit(0);
                bw.navegar("https://www.cve.org/CVERecord?id=" + link.text_content());
                time.sleep(30);
                bw.clicar("/html/body/div/div/div/div/div/main/div/div/div/div/section/div/div[1]/button");
                time.sleep(3);
                js_cve = json.loads(bw.elemento('//*[@id="cve-json"]/*[1]').text);
                dados = {"cve" : link.text_content(), "user" : data["user"]};
                dados['description'] = js_cve["CVE_data_meta"]["TITLE"];
                dados['assigned'] = js_cve["CVE_data_meta"]["ASSIGNER"];
                dados['state'] = js_cve["CVE_data_meta"]["STATE"];
                dados['full_description'] = ""; dados['productor'] = ""; dados["work_around"] = "";dados["problemtype"] = "";
                for description in js_cve["description"]["description_data"]:
                    dados['full_description'] += "(" + description["lang"] + ") " + description["value"] + "\n";
                if js_cve.get("affects") != None:
                    for product in js_cve["affects"]["vendor"]["vendor_data"]:
                        #print("Produto", product);
                        for product_data in product["product"]["product_data"]:
                            product_name = product_data["product_name"];
                            product_version = "";
                            for version in product_data["version"]["version_data"]:
                                version_affected = version["version_affected"];
                                version_value    = version["version_value"];
                                product_version += version_affected + " " + version_value + ", ";
                            dados['productor'] += product_name + ": " + product_version + "; ";
                if js_cve.get("work_around") != None:
                    for work_around in js_cve["work_around"]:
                        dados["work_around"] += "(" + work_around['lang'] + ") " + work_around['value'] + "; ";
                if js_cve.get("problemtype") != None:
                    for problemtype in js_cve["problemtype"]["problemtype_data"]:
                        for problemdesciption in problemtype["description"]:
                            dados["problemtype"] += "(" + problemdesciption["lang"] + ") " + problemdesciption["value"] + "; "; 
                retorno = SendService(data["server"], "cve_write.php", dados, port=data["port"], protocol=data["protocol"] );
                time.sleep(5);
        except KeyboardInterrupt:
            sys.exit(0);
        except:
            print("Não foi possível obter dados do CVE. Continuar.");
            traceback.print_exc();
            


