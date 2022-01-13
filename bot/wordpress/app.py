import subprocess, os, json, sys;

print("#------------------- WPSCAN -----------------------");
#os.environ["ROOT"] = "/var/well/secanalysis/bot/";
sys.path.insert(0, os.environ["ROOT"]);
from api.edb import *;

def run_command(domain_id, args_array):
    global domains_project;
    p = subprocess.Popen(args=args_array, stdout=subprocess.PIPE,  stdin=subprocess.PIPE, stderr=subprocess.PIPE);
    p_out = p.communicate();
    if p.returncode == 0:
        note_save(domain_id, "Output wpscan",   ' '.join(args_array) +  "<br/><br/>" +  (str(p_out[0], 'utf-8') +  str(p_out[1], 'utf-8')).replace("\n", "<br/>"), "wpscan-v1");
        return {"code" : p.returncode, "stdout" : str(p_out[0], 'utf-8'), "stderr" : str(p_out[1], 'utf-8')};
    return None;

domains_project = domains("95dba8f6-4421-9395-d527-5469d7806fb0");
for data in domains_project[0]['data']:
    print('\033[92m[+]\033[0m', data["domain"]);
    run_command(data["_id"], ("/usr/local/src/wpscan/bin/wpscan --url " + data["domain"] + " --detection-mode aggressive -e ap --ignore-main-redirect --random-user-agent --force --wp-content-dir /wp-content/ -v").split(" "));
