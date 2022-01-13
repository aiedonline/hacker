import time, os, sys, json;

sys.path.insert(0, "/home/well/projects/pentest/");

from api.util import *;

ipinfo = shodan_host("94.177.190.137");
print(ipinfo['city']);
for key in ipinfo:
    print(key);
