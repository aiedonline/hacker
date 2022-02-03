


import  subprocess, os;


if os.path.exists("/tmp/hacker.tar.xz"):
    os.unlink("/tmp/hacker.tar.xz");
subprocess.run("curl https://www.cyberframework.online/cyber/projects/5/download/hacker.tar.xz --output /tmp/hacker.tar.xz ", shell=True);
subprocess.run("tar -xf /tmp/hacker.tar.xz -C /tmp/", shell=True);




