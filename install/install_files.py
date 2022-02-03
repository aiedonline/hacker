#!/usr/bin/python3
#install/install_files.py
# Este script é executado no servidor para fazer a instalação da ferramenta
#Dúvidas: https://www.cyberframework.online/cyber/project.php?id=5

import os, sys, shutil;

#if os.path.exists( os.environ['PATH_WEB'] + "/edb"):
#    os.unlink( os.environ['PATH_WEB'] + "/edb");
#if os.path.exists( os.environ['PATH_WEB'] + "/jscloud"):
#    os.unlink( os.environ['PATH_WEB'] + "/jscloud");
#if os.path.exists( os.environ['PATH_WEB'] + "/secanalysis"):
#    os.unlink( os.environ['PATH_WEB'] + "/secanalysis");

shutil.move(os.environ['PATH_TMP'] + "/hacker/edb", os.environ['PATH_WEB'] + "/");
shutil.move(os.environ['PATH_TMP'] + "/hacker/jscloud", os.environ['PATH_WEB'] + "/");
shutil.move(os.environ['PATH_TMP'] + "/hacker/secanalysis", os.environ['PATH_WEB'] + "/");
