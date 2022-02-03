#!/usr/bin/env python
# -*- coding: utf-8 -*-

import traceback, os, requests, json, uuid;

from threading import Thread
from multiprocessing import Queue
from multiprocessing.pool import ThreadPool
from uuid import *


class Database:
	def __init__(self, port=80):
		self.port = 80
		self.sessionId = str(uuid.uuid4())

	def SendServer(self, domain, action, data):
		try:
			edb = {"url" : "http://127.0.0.1/edb/", "port" : 80, "file" : "execute.php", "default" : "/local/edb", "token" : '11111111-1111-1111-111111111111'};
			envelope = {};
			envelope['sessionId'] = self.sessionId;
			envelope['trasactionId'] = str(uuid.uuid4());
			envelope['token'] = edb['token'];
			envelope['domain'] = domain ;
			envelope['action'] = action;
			envelope['envelop'] = data;
			envelope['user'] = None;

			url = edb['url'] + edb['file'];
			buffer_connection = requests.post(url, json=envelope);
			print(buffer_connection.text);
			buffer = buffer_connection.json();
			if buffer.get('version') and buffer['version'] == 2:
				if buffer['status']:
					result = buffer['data'];
				else:
					result = None;
			else:
				result = buffer;
			return result;
		except Exception as e:
			traceback.print_exc();
			return None;













