#!/bin/bash
#install/make.sh
# Este script é executado para gerar os arquivos que serão enviados ao servidor
#       o script limpa diretórios, garante que não seja vazado usuários e senhas de banco
#       para isso gera um diretório em /etc/hacker com todos os arquivos que devem ser enviados
#       para a máquina que vai rodar a ferramenta
#Dúvidas: https://www.cyberframework.online/cyber/project.php?id=5


SOURCE=${BASH_SOURCE[0]}
while [ -h "$SOURCE" ]; do 
  DIR=$( cd -P "$( dirname "$SOURCE" )" >/dev/null 2>&1 && pwd )
  SOURCE=$(readlink "$SOURCE")
  [[ $SOURCE != /* ]] && SOURCE=$DIR/$SOURCE 
done
DIR=$( cd -P "$( dirname "$SOURCE" )" >/dev/null 2>&1 && pwd );
DIR_ROOT="$(dirname "$DIR")"
DIR_PROJECT="$(dirname "$DIR_ROOT")"

if [ -d "$DIR_ROOT/api" ]; then
  echo "Directory: ${DIR_ROOT}/api";
else
  echo "O diretório não existe: ${DIR_ROOT}/api";
  exit 0;
fi

if [ -d "/tmp/hacker" ]; then
  rm -r /tmp/hacker;
fi

mkdir /tmp/hacker;
echo '[+] Diretórios criado';

cp -r "$DIR_PROJECT/edb" /tmp/hacker/edb/
cp -r "$DIR_PROJECT/jscloud" /tmp/hacker/jscloud/
cp -r "$DIR_PROJECT/secanalysis" /tmp/hacker/secanalysis/
echo '[+] Arquivos copiados';

rm -r /tmp/hacker/edb/.git
rm -r /tmp/hacker/jscloud/.git
rm -r /tmp/hacker/secanalysis/.git

rm -r /tmp/hacker/edb/tmp
rm -r /tmp/hacker/jscloud/tmp
rm -r /tmp/hacker/secanalysis/tmp
mkdir /tmp/hacker/edb/tmp
mkdir /tmp/hacker/jscloud/tmp
mkdir /tmp/hacker/secanalysis/tmp
echo '[+] Diretórios padrões excluídos';

rm -r /tmp/hacker/edb/data
mkdir /tmp/hacker/edb/data
cp -r "$DIR_PROJECT/edb/data/config.json" /tmp/hacker/edb/data/
cp -r "$DIR_PROJECT/edb/data/config.php" /tmp/hacker/edb/data/
mkdir /tmp/hacker/edb/data/databases
echo '{"type":"mysql","connection":{"host":"127.0.0.1","name":"edb","user":"root","password":"SUASENHA","port":"3306"},"description":""}'    > /tmp/hacker/edb/data/databases/edb.json
echo '{"type":"mysql","connection":{"host":"127.0.0.1","name":"hacker","user":"root","password":"SUASENHA","port":"3306"},"description":""}' > /tmp/hacker/edb/data/databases/hacker.json
cp -r "$DIR_PROJECT/edb/data/databases/hacker" /tmp/hacker/edb/data/databases
cp -r "$DIR_PROJECT/edb/data/databases/edb" /tmp/hacker/edb/data/databases
rm -r /tmp/hacker/edb/painel
echo '[+] EDB configurado (padrão)';

rm -r /tmp/hacker/jscloud/editor
echo '[+] JSCLOUD configurado (padrão)';

rm /tmp/hacker/secanalysis/install/make.sh
rm -r /tmp/hacker/secanalysis/uploads
mkdir /tmp/hacker/secanalysis/uploads
echo '[+] SECANALYSIS configurado (padrão)';




