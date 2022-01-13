import socket
import whois, sys, os, json, traceback;

sys.path.insert(0, os.environ["ROOT"]);
data = sys.stdin.readlines();
data = json.loads(data[0]);

print({"ip" : socket.gethostbyname(data['domain']) });

