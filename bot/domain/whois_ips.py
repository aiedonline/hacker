from base64 import encode
import whois, sys, os, json, traceback;

sys.path.insert(0, os.environ["ROOT"]);
data = sys.stdin.readlines();
data = json.loads(data[0]);

PATH_CACHE = os.environ["ROOT"] + "/tmp/";

if os.path.exists(PATH_CACHE + "_whois_" + data['domain'] ):
    print(open(PATH_CACHE + "_whois_" + data['domain'] ).read());
else:
    w = whois.whois(data['domain']);
    print(w);
    try:
        with open( "/tmp/_whois_" + data['domain'], "w") as f:
            f.write(json.dumps(w, indent=4, sort_keys=True, default=str));
            f.close();
        os.rename("/tmp/_whois_" + data['domain'], PATH_CACHE + "/_whois_" + data['domain']);
    except:
        traceback.print_exc();
        sys.exit(0);

