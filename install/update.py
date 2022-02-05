#!/usr/bin/python3
#install/update.py
# Este script é executado no servidor para fazer o update de um sistema que já está em uso
#Dúvidas: https://www.cyberframework.online/cyber/project.php?id=5

import sys, subprocess, os, inspect;

from util import *;

CURRENT = os.path.dirname(os.path.abspath(inspect.getfile(inspect.currentframe()))) + "/";

os.environ['PATH_TMP'] = "/tmp/";
os.environ['PATH_WEB'] = "/var/www/html/";

if os.geteuid() != 0:
    print("O usuário não é ROOT");
    sys.exit(1);

if not os.path.exists( os.environ['PATH_WEB'] + "edb"):
    print("O projeto não foi instalado, você deve rodar o script install.py");
    sys.exit(1);

execute_script("install_ambiente.py", CURRENT, []);   
execute_script("install_get.py", CURRENT,[]);
execute_script("update_clean.py",CURRENT, []);
execute_script("install_files.py",CURRENT, []);
execute_script("update_edb.py", CURRENT,[]);


