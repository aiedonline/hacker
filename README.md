# Painel SecAnalysis hacker

1 - Primeiro precisa instalar o MariaDB;

    sudo apt update
    sudo apt install mariadb-server

2 - Rodar as instruções SQL abaixo (sudo mysql -u root -p):

    GRANT ALL PRIVILEGES on *.* to 'root'@'localhost' IDENTIFIED BY '123456';
    FLUSH PRIVILEGES;
    create database hacker;
    create database edb;

3 - Obter arquivo de instalação:

    curl https://www.cyberframework.online/cyber/projects/5/download/install.tar.gz --output /tmp/install.tar.gz
    mkdir /tmp/install
    tar -zxvf /tmp/install.tar.gz -C /tmp/install/
    cd /tmp/install/

4 - Execute o comando de instalação:

    sudo python3 ./install.py

5 - Acessando a ferramenta

    http://IP_DA_VM/secanalysis/
    
    Usuário: root@system
    Password: 1234
 
# Bot SecAnalysis

    sudo apt install python3-pip -y
    sudo apt install nmap -y
    sudo apt install python-dns -y
    sudo python3 -m pip install pyfiglet
    sudo python3 -m pip install dnspython




