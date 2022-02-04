import hashlib, os, sys, subprocess, json, datetime, signal, time;

from threading import Thread

ROOT = os.environ["ROOT"];

class CacheExec():
    def __init__(self, cache=True, time=4):
        self.cache = cache;
        self.stdout = None;
        self.stderr = None;
        self.returncode = None;
        self.time = time;
    
    def erase(self):
        self.stdout = None;
        self.stderr = None;
        self.returncode = None;

    def stop_command(self, timeout, proc):
        try:
            time.sleep(60 * timeout);
            print("\t\t\033[96mParar processo, já passou ", timeout, "\033[00m");
            os.kill(proc.pid, signal.SIGINT);
        except:
            ignorar = ""; # kkkkk

    def run(self, args_exec, data=None, key=None, timeout=2):
        if key == None:
            key = hashlib.md5("".join(args_exec).encode()).hexdigest();
        else:
            key = hashlib.md5(key.encode()).hexdigest();
        buffer = self.cache_load(key);
        if buffer != None:
            self.stdout = buffer['stdout'];
            self.stderr = buffer['stderr'];
            self.returncode = buffer['returncode'];
            return True;
        try:
            p = subprocess.Popen(args=args_exec, stdout=subprocess.PIPE,  stdin=subprocess.PIPE, stderr=subprocess.PIPE);
            t = Thread(target=self.stop_command,   args=(timeout, p,  ) ).start();
            p_out = None;
            if data != None:
                p_out = p.communicate(input=json.dumps(data).encode('utf-8'));
            else:
                p_out = p.communicate();
            self.stdout = str(p_out[0], 'utf-8');
            self.stderr = str(p_out[1], 'utf-8');
            self.returncode = p.returncode;
            if self.returncode == 0:
                self.cache_save(key, {"returncode" : p.returncode, "stdout" : str(p_out[0], 'utf-8'), "stderr" : str(p_out[1], 'utf-8')});
            try:
                t.terminate();
            except:
                print("Problemas em parar a thread de Timeout.");
            return p.returncode;
        except KeyboardInterrupt:
            return 1;
        return 2;

    def cache_load(self, key):
        path_to_file = ROOT + "tmp/" + key + "_v_1";
        if os.path.exists(path_to_file):
            buffer = json.loads(open(path_to_file, "r").read());
            if datetime.datetime.utcnow() < datetime.datetime.strptime(buffer['time'], '%Y-%m-%d %H:%M:%S'):
                return buffer['value'];
        return None;

    def cache_save(self, key, value):
        path_to_file = ROOT + "tmp/" + key + "_v_1";
        path_to_temporary = "/tmp/" + key + "_v_1";
        with open(path_to_temporary, "w") as f:
            f.write(json.dumps({"time" : (datetime.datetime.utcnow() + datetime.timedelta(hours=self.time)).strftime('%Y-%m-%d %H:%M:%S'), "value" : value }));
            f.close();
            if os.path.exists(path_to_file):
                os.unlink(path_to_file);
            os.rename(path_to_temporary, path_to_file)


