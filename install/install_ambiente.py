import subprocess, os, sys;

# Após executar pela primeira vez:- Validar se mysql está instalado;
#- Validar que o curl está instalado;
#- Validar que o pip está instalado;
#- Validar que o apache e o php estào instalados;
#- Criar usuário inicial do edb
#- No mysql rodar o security
#- rodar as regras:
#	GRANT ALL PRIVILEGES on *.* to 'root'@'localhost' IDENTIFIED BY '123456';
#	FLUSH PRIVILEGES;
#sudo apt-get install php-mysql



#subprocess.run("apt install mariadb-server -y", Shell=True);
subprocess.run("apt install apache2 -y", shell=True);
subprocess.run("apt install php7.3 -y", shell=True);
subprocess.run("apt install php7.3-mysql -y", shell=True);
subprocess.run("apt install python3-pip -y", shell=True);
subprocess.run("apt-get install php-mysql -y", shell=True);
subprocess.run("systemctl restart apache2", shell=True);

#GRANT ALL PRIVILEGES on *.* to 'root'@'localhost' IDENTIFIED BY '123456';
#FLUSH PRIVILEGES;
#create database hacker;
#create database edb;