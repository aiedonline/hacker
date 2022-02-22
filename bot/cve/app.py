



import sys, os, subprocess, traceback, time;

os.environ["ROOT"] = "/var/well/secanalysis/bot/";
sys.path.insert(0, os.environ["ROOT"]);
from api.edb import *;

SCRIPT_VERSION_CVE = "1";

def executar(script, dados ):
    p = subprocess.Popen(args=["python3", os.environ["ROOT"] + script], stdout=subprocess.PIPE,  stdin=subprocess.PIPE, stderr=subprocess.PIPE);
    p_out = p.communicate(input=json.dumps(dados).encode('utf-8'));  
    return {"code" : p.returncode, "stdout" : str(p_out[0], 'utf-8'), "stderr" : str(p_out[1], 'utf-8')};

def buscar_cve_mitre(vul, cve):
    mitre = json.loads(executar("cve/mitre.py", {"cve" : vul} )['stdout']);
    mitre['codigo'] = vul;
    if cve[0]['font'] == "database":
        if cve[0]['data']["description"] != None and cve[0]['data']["description"] != "":
            if cve[0]['data']["description"] == mitre["description"]:
                return;
            mitre['description'] = cve[0]['data']["description"];
        cve_save_mitre(cve[0]['data']["_id"], mitre, SCRIPT_VERSION_CVE);
    else:
        cve_save_mitre( hashlib.md5(vul.encode()).hexdigest() , mitre, SCRIPT_VERSION_CVE);

def buscar_cve_detail(vul, cve):
    mitre = json.loads(executar("cve/details.py", {"cve" : vul} )['stdout']);
    mitre['codigo'] = vul;
    if cve[0]['font'] == "database":
        cve_save_mitre(cve[0]['data']["_id"], mitre, SCRIPT_VERSION_CVE);
    else:
        cve_save_mitre( hashlib.md5(vul.encode()).hexdigest() , mitre, SCRIPT_VERSION_CVE);

def buscar_cve():
    hosts = host_list();
    for host in hosts[0]['data']:
        if host['shodan'] != None or host['shodan'] != "":
            try:
                shodan = json.loads(host['shodan']);
                for vul in shodan['vulns']:
                    print('\033[92m[+]\033[0m', vul);
                    cve = cve_read(vul);
                    buscar_cve_mitre(vul, cve);
                    buscar_cve_detail(vul, cve);
            except KeyboardInterrupt:
                print( 'Interrupted');
                sys.exit(0);
            except:
                traceback.print_exc(); 
buscar_cve();