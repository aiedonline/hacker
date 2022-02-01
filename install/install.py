#!/usr/bin/python3
#install/install.py
# Este script é executado no servidor para fazer a instalação da ferramenta
#Dúvidas: https://www.cyberframework.online/cyber/project.php?id=5
import sys, subprocess, os, inspect;

ROOT = os.path.dirname(os.path.abspath(inspect.getfile(inspect.currentframe()))) + "/";
print(ROOT);

def install_dependence(dependence):
    sub = subprocess.run(["python3", ROOT + "/install_test_module.py", dependence['name']]);
    if sub.returncode != 0:
        subprocess.run(["python3", "-m", "pip", "install", dependence['install']]);

def execute_script(script, dependencias):
    for dependencia in dependencias:
        install_dependence(dependencia);
    subprocess.run(["python3", ROOT + script]);

execute_script("install_mysql.py", [{"name" : "mysql", "install" : "mysql-connector-python"}]);
