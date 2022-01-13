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
browser.get("https://www.cvedetails.com/cve/" + data["cve"] + "/")
tree = html.fromstring(unicodedata.normalize(u'NFKD', browser.text).encode('ascii', 'ignore').decode('utf8'))
saida = {};
saida['score'] =                    tree.xpath('//*[@id="cvssscorestable"]/tr[1]/td')[0].text_content().strip();
saida['confidentitality'] =         tree.xpath('//*[@id="cvssscorestable"]/tr[2]/td')[0].text_content().strip();
saida['integrity'] =                tree.xpath('//*[@id="cvssscorestable"]/tr[3]/td')[0].text_content().strip();
saida['availability'] =             tree.xpath('//*[@id="cvssscorestable"]/tr[4]/td')[0].text_content().strip();
saida['access_complexity'] =        tree.xpath('//*[@id="cvssscorestable"]/tr[5]/td')[0].text_content().strip();
saida['autentication'] =            tree.xpath('//*[@id="cvssscorestable"]/tr[6]/td')[0].text_content().strip();
saida['gained_access'] =            tree.xpath('//*[@id="cvssscorestable"]/tr[7]/td')[0].text_content().strip();
saida['vulnerability_type'] =       tree.xpath('//*[@id="cvssscorestable"]/tr[8]/td')[0].text_content().strip();
saida['cwe_id'] =                   tree.xpath('//*[@id="cvssscorestable"]/tr[9]/td')[0].text_content().strip();

for key, value in saida.items():
    saida[key] = saida[key].replace("\n", "").replace("\t", "");

print(json.dumps(saida));







