#!/usr/bin/python3
#install/install.py
# Este script é executado no servidor para fazer a instalação da ferramenta
#Dúvidas: https://www.cyberframework.online/cyber/project.php?id=5

import sys, subprocess, os, inspect;

from util import *;

CURRENT = os.path.dirname(os.path.abspath(inspect.getfile(inspect.currentframe()))) + "/";

os.environ['PATH_TMP'] = "/tmp/";
os.environ['PATH_WEB'] = "/var/www/html/";

if os.geteuid() != 0:
    print("O usuário não é ROOT");
    sys.exit(1);

if os.path.exists( os.environ['PATH_WEB'] + "edb"):
    print("Já existe um ambiente instalado");
    sys.exit(1);

execute_script("install_ambiente.py", CURRENT, []);    
execute_script("install_get.py", CURRENT,[]);
execute_script("install_files.py",CURRENT, []);
execute_script("install_mysql.py",CURRENT, [{"name" : "mysql", "install" : "mysql-connector-python"}, {"name" : "requests", "install" : "requests"}]);
execute_script("install_edb.py", CURRENT,[]);




