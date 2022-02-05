
import subprocess;

def install_dependence(dependence, path_directory):
    sub = subprocess.run(["python3", path_directory + "/install_test_module.py", dependence['name']]);
    if sub.returncode != 0:
        subprocess.run(["python3", "-m", "pip", "install", dependence['install']]);

def execute_script(script, path_directory, dependencias=[]):
    for dependencia in dependencias:
        install_dependence(dependencia, path_directory);
    p = subprocess.Popen(["python3", path_directory + script]);
    p.communicate();