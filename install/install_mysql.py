#!/usr/bin/python3
#install/install_mysql.py
# Este script é executado no servidor para fazer a instalação da ferramenta
#Dúvidas: https://www.cyberframework.online/cyber/project.php?id=5

import os, sys, mysql.connector
import traceback;

username = None; password = None; database = None;

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
    username = input("Informe o usuário do banco de dados: ");
    password = input("Informe o password do banco de dados: ");
    database = input("Informe o nome do Database: ");
    if test_connection(username, password, database):
        break;




#mydb = mysql.connector.connect(
#    host="127.0.0.1",
#    user=username,
#    password=password,
#    database=database);
#execute_sql(database, "create table testekkk { }", []);

