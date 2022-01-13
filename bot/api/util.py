import sys, os, json;

from shodan import Shodan
PATH_CACHE = os.environ["ROOT"] + "/tmp/";

def shodan_search(query):
    global PATH_CACHE;
    api = Shodan('vzQC4ZanjYpS6doAogbnrVoZHYcXY8w6')
    if os.path.exists(PATH_CACHE + query.replace("/", "")):
        return json.loads(open(PATH_CACHE + query).read());
    ipinfo = api.search(query)
    
    with open(PATH_CACHE + query.replace("/", ""), "w") as f:
        f.write(json.dumps(ipinfo));
    return ipinfo;

def shodan_host(query):
    global PATH_CACHE;
    api = Shodan('vzQC4ZanjYpS6doAogbnrVoZHYcXY8w6')
    if os.path.exists(PATH_CACHE + query.replace("/", "")):
        return json.loads(open(PATH_CACHE + query).read());
    ipinfo = api.host(query)
    
    with open(PATH_CACHE + query.replace("/", ""), "w") as f:
        f.write(json.dumps(ipinfo));
    return ipinfo;
