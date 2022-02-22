import sys, os, subprocess, traceback, time;

os.environ["ROOT"] = "/var/well/secanalysis/bot/";
sys.path.insert(0, os.environ["ROOT"]);

from api.hacker import *;
from api.cacherequest import *;
from api.browser.browser import *;
#data = sys.stdin.readlines();
#data = json.loads(data[0]);

data = {"search" : "apache"};

c = CacheRequest(life=180);
bw = Browser(terminal=False, path_directory_browser="/tmp/");
bw.navegar("https://www.cve.org/");

if c.get("https://cve.mitre.org/cgi-bin/cvekey.cgi?keyword=" + data["search"]):
    links = c.elements('//a[contains(@href, "name=CVE")]'); #/@href
    for link in links:
        print(link.text_content());
        bw.escrever('//*[@class="input cve-id-input"]', link.text_content(), enter=True);
        time.sleep(5);
        if bw.existe_elemento('//li[ span[contains( text(), "Product")]]/span[2]'):
            print("\t", bw.elemento('//li[ span[contains( text(), "Product")]]/span[2]').text);
        if bw.existe_elemento('//ul[ span[contains( text(), "Versions Affected")]]/li'):
            elementos = bw.elementos('//ul[ span[contains( text(), "Versions Affected")]]/li');
            for elemento in elementos:
                print("\t\t", elemento.text);
        time.sleep(15);




