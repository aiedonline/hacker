import traceback
import requests, unicodedata, hashlib, os, json, datetime;

proxies = {
    'http': 'socks5://127.0.0.1:9150',
    'https': 'socks5://127.0.0.1:9150'
}

class CacheRequest():
    def __init__(self, life = 60, force_onion = False, cache=True):
        self.text = "";
        self.life = life;
        self.cache = cache;
        self.force_onion = force_onion;

    def get(self, url):
        name_cache = hashlib.md5(url.encode()).hexdigest() + "_v1";
        try:
            if os.path.exists("/tmp/requests/") == False:
                os.makedirs("/tmp/requests/");
        except:
            traceback.print_exc()
        if self.cache == True:
            if os.path.exists("/tmp/requests/" + name_cache):
                buffer = json.loads(open("/tmp/requests/" + name_cache).read());
                if datetime.datetime.utcnow() < datetime.datetime.strptime(buffer['time'], '%Y-%m-%d %H:%M:%S'):
                    self.text = buffer['html'];
                    return True;
                else:
                    os.unlink("/tmp/requests/" + name_cache);
        page = None;
        if url.find(".onion") > 0 or self.force_onion == True:
            page = requests.get(url, proxies=proxies)
        else:
            page = requests.get(url)
        if page.status_code != 200:
            return False;
        page.encoding = "utf-8";
        self.text = unicodedata.normalize(u'NFKD', page.text).encode('ascii', 'ignore').decode('utf8');
        f = open("/tmp/requests/" + name_cache , "w");
        f.write(json.dumps({"time" : (datetime.datetime.utcnow() + datetime.timedelta(minutes=5)).strftime('%Y-%m-%d %H:%M:%S'), "html" : self.text }));
        f.close();
        return True;