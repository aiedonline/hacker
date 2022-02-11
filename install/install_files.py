#!/usr/bin/python3
#install/install_files.py
# Este script é executado no servidor para fazer a instalação da ferramenta
#Dúvidas: https://www.cyberframework.online/cyber/project.php?id=5

import os, sys, shutil, subprocess;
from pathlib import Path

def copy_dir(src, dst):
    dst.mkdir(parents=True, exist_ok=True)
    for item in os.listdir(src):
        s = src / item
        d = dst / item
        if s.is_dir():
            copy_dir(s, d)
        else:
            shutil.copy2(str(s), str(d))

copy_dir( Path( os.environ['PATH_TMP'] + "/hacker/secanalysis"), Path( os.environ['PATH_WEB'] + "/secanalysis"));
copy_dir( Path( os.environ['PATH_TMP'] + "/hacker/jscloud"), Path( os.environ['PATH_WEB'] + "/jscloud"));
copy_dir( Path( os.environ['PATH_TMP'] + "/hacker/edb"), Path(os.environ['PATH_WEB'] + "/edb"));

subprocess.run("chown -R www-data:www-data /var/www/html/*", shell=True);

