
import time, os, sys, json;
import unicodedata

from lxml import html
from urllib.parse import unquote

#os.environ["ROOT"] = "/var/well/secanalysis/bot/";
#data = {"cve" : "CVE-2020-1927" }

sys.path.insert(0, os.environ["ROOT"]);
from api.util import *;
from api.cacherequest import *;
data = sys.stdin.readlines();
data = json.loads(data[0]);


browser = CacheRequest(cache=True);
browser.get("https://cve.mitre.org/cgi-bin/cvename.cgi?name=" + data["cve"])
tree = html.fromstring(unicodedata.normalize(u'NFKD', browser.text).encode('ascii', 'ignore').decode('utf8'))
saida = {};
saida['description'] = tree.xpath('//*[@id="GeneratedTable"]/table/tr[4]/td')[0].text_content().strip();

print(json.dumps(saida));