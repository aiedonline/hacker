#!/usr/bin/python3
# -*- coding: utf-8 -*-
#install/install_edb.py
#
#Dúvidas: https://www.cyberframework.online/cyber/project.php?id=5

import hashlib, traceback, inspect, os, sys, mysql.connector, json, requests;

CURRENT = os.path.dirname(os.path.abspath(inspect.getfile(inspect.currentframe()))) + "/";
sys.path.insert(0, CURRENT);
from edb import *;

url = 'http://127.0.0.1/edb/update.php'
myobj = {'domain': '/local/hacker'}
x = requests.post(url, data = json.dumps(myobj), headers= {'Content-type': 'application/json', 'Accept': 'text/plain'});
print("Database: /local/hacker", x.text);


myobj = {'domain': '/local/edb'}
x = requests.post(url, data = json.dumps(myobj), headers= {'Content-type': 'application/json', 'Accept': 'text/plain'});
print("Database: /local/edb", x.text);

db = Database();
db.SendServer("/local/edb","write",[{"entity":"user","data":{"_id": "81dc9bdb52d04dc20036dbd8313ed055",  "password" : "81dc9bdb52d04dc20036dbd8313ed055", "cpf" : "11111111111", "email" : "root@system"}}]);

db.SendServer("/local/hacker","write",[{"entity":"vulnerability_cicle","data":{"_id": hashlib.md5("Discover".encode()).hexdigest(),  "name" : "Discover", "description" : "Inventory all assets across the network and identify host details including operating system and open services to identify vulnerabilities. Develop a network baseline. Identify security vulnerabilities on a regular automated schedule.", "sequencia" : 1}}]);
db.SendServer("/local/hacker","write",[{"entity":"vulnerability_cicle","data":{"_id": hashlib.md5("Prioritize".encode()).hexdigest(),  "name" : "Prioritize", "description" : "Categorize assets into groups or business units, and assign a business value to asset groups based on their criticality to your business operation.", "sequencia" : 2}}]);
db.SendServer("/local/hacker","write",[{"entity":"vulnerability_cicle","data":{"_id": hashlib.md5("Assess".encode()).hexdigest(),  "name" : "Assess", "description" : "Determine a baseline risk profile so you can eliminate risks based on asset criticality, vulnerability threat, and asset classification.", "sequencia" : 3}}]);
db.SendServer("/local/hacker","write",[{"entity":"vulnerability_cicle","data":{"_id": hashlib.md5("Report".encode()).hexdigest(),  "name" : "Report", "description" : "Measure the level of business risk associated with your assets according to your security policies. Document a security plan, monitor suspicious activity, and describe known vulnerabilities.", "sequencia" : 4}}]);
db.SendServer("/local/hacker","write",[{"entity":"vulnerability_cicle","data":{"_id": hashlib.md5("Remediate".encode()).hexdigest(),  "name" : "Remediate", "description" : "Prioritize and fix vulnerabilities in order according to business risk. Establish controls and demonstrate progress.", "sequencia" : 5}}]);
db.SendServer("/local/hacker","write",[{"entity":"vulnerability_cicle","data":{"_id": hashlib.md5("Verify".encode()).hexdigest(),  "name" : "Verify", "description" : "Verify that threats have been eliminated through follow-up audits.", "sequencia" : 6}}]);


