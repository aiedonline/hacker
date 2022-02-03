# hacker

1 - Primeiro precisa instalar o MariaDB;

    sudo apt update
    sudo apt install mariadb-server

2 - Rodar as instruções SQL abaixo (sudo mysql -u root -p):

    GRANT ALL PRIVILEGES on *.* to 'root'@'localhost' IDENTIFIED BY '123456';
    FLUSH PRIVILEGES;
    create database hacker;
    create database edb;

3 - Obter arquivo de instalação:

    curl https://www.cyberframework.online/cyber/projects/5/download/install.tar.xz --output /tmp/install.tar.xz 
    tar -xf /tmp/install.tar.xz -C /tmp/
    cd /tmp/install/

4 - Execute o comando de instalação:

    sudo python3 ./install.py
