import  subprocess, os;


if os.path.exists("/tmp/hacker.tar.xz"):
    os.unlink("/tmp/hacker.tar.xz");
subprocess.run("curl https://www.cyberframework.online/cyber/projects/5/download/hacker.tar.gz --output /tmp/hacker.tar.gz ", shell=True);

if os.path.exists("/tmp/hacker/"):
    subprocess.run("rm -r /tmp/hacker", shell=True);

subprocess.run("mkdir /tmp/hacker", shell=True);
subprocess.run("tar -zxvf /tmp/hacker.tar.gz -C /tmp/hacker/", shell=True);


