#!/usr/bin/python3
#install/install_mysql.py
# Este script é executado no servidor para fazer a instalação da ferramenta
#Dúvidas: https://www.cyberframework.online/cyber/project.php?id=5

import os, sys, mysql.connector, json, requests
import traceback, inspect;

CURRENT = os.path.dirname(os.path.abspath(inspect.getfile(inspect.currentframe()))) + "/";
print("Diretorio scripts", CURRENT, sep=": ");

DEFAULT = None;
if os.path.exists(CURRENT + "/default.json"): 
    DEFAULT = json.loads(open(CURRENT + "/default.json", "r").read());

username = None; password = None; database = None;

if DEFAULT != None:
    username = DEFAULT['connection']["user"];
    password = DEFAULT['connection']["password"];
    database = DEFAULT['connection']["database"];


def test_connection(username, password, database):
    try:
        mydb = mysql.connector.connect(
            host="127.0.0.1",
            user=username,
            password=password,
            database=database);
        return True;
    except:
        print("Não foi possível conectar");
        return False;

def execute_sql(database, sql, values):
    mycursor = database.cursor();
    mycursor.execute(sql, values);

while True:
    if DEFAULT != None:
        buffer = input("Informe o usuário do banco de dados("+ username +"):");
        if buffer != "":
            username = buffer;
        buffer = input("Informe o password do banco de dados("+ password +"): ");
        if buffer != "":
            password = buffer;
        buffer = input("Informe o nome do Database("+ database +"): ");
        if buffer != "":
            database = buffer;
    else:
        username = input("Informe o usuário do banco de dados: ");
        password = input("Informe o password do banco de dados: ");
        database = input("Informe o nome do Database: ");

    # para testes
    if test_connection(username, password, "mysql"):
        break;

# alteradno arquivo
edb_config    = json.loads(open( os.environ['PATH_WEB'] +  "/edb/data/databases/edb.json").read());
hacker_config = json.loads(open( os.environ['PATH_WEB'] +  "/edb/data/databases/hacker.json").read());

# Valores que serão salvos
edb_config["connection"]["user"] = username;
edb_config["connection"]["password"] = password;
edb_config["connection"]["name"] = "edb";

hacker_config["connection"]["user"] = username;
hacker_config["connection"]["password"] = password;
hacker_config["connection"]["name"] = database;

with open( os.environ['PATH_WEB'] +  "/edb/data/databases/edb.json", "w") as f:
    f.write(json.dumps(edb_config));
    f.close();
with open( os.environ['PATH_WEB'] +  "/edb/data/databases/hacker.json", "w") as f:
    f.write(json.dumps(hacker_config));
    f.close();
