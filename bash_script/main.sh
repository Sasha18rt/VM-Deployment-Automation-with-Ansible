#!/bin/bash

GREEN='\e[1;32m'
YELLOW='\e[1;33m'
RED='\e[1;31m'

ENDPOINT=https://grid5.mif.vu.lt/cloud3/RPC2
COMPARE_NUM='^[0-9]+$'
TEMPLATE_ID=2435
SUDO_PASS=password
WAIT_TIME=40 #initialization wait time

sudo apt update
sudo apt install gnupg
sudo apt install python3
curl https://bootstrap.pypa.io/get-pip.py -o get-pip.py
python3 get-pip.py --user
wget -q -O- https://downloads.opennebula.org/repo/repo.key | sudo apt-key add -
echo "deb [trusted=yes] https://downloads.opennebula.org/repo/5.6/Ubuntu/18.04 stable opennebula" | sudo tee /etc/apt/sources.list.d/opennebula.list
sudo apt update
sudo apt install -y opennebula-tools
sudo apt install -y sshpass
sudo apt install ansible
python3 -m pip install --upgrade --user ansible


echo -e "\nGenerate and add ssh key"
eval `ssh-agent -s`
ssh-keygen -f ~/.ssh/id_rsa
echo "Please enter password for your SSH key: "
ssh-add
echo ""

while true; do
  echo -e "\nNow you are creating a websever-vm:"
  echo -e "\nEnter credentials for opennebula user:"
  read -p "Enter VMs user: " USER

  read -p "Enter $USER password: " -s PASS
  echo ""
  WEBSERVER_REZ=$(onetemplate instantiate "$TEMPLATE_ID" --name "webserver-vm" --user "$USER" --password "$PASS" --endpoint $ENDPOINT)
  WEBSERVER_ID=$(echo $WEBSERVER_REZ |cut -d ' ' -f 3)

  
  if ! [[ "$WEBSERVER_ID" =~ $COMPARE_NUM ]]; then
    echo "$WEBSERVER_REZ"
    echo -e "- Could not create webserver-vm for user $USER"
  else
    echo -e "- Successfuly created webserver-vm id: $WEBSERVER_ID"
    WEBSERVER_CREATED=true
    break
  fi
done


while true; do
  echo -e "\nNow you are creating a database-vm:"
  read -p "Enter VMs user: " USER

  read -p "Enter $USER password: " -s PASS
  echo ""
  DATABASE_REZ=$(onetemplate instantiate "$TEMPLATE_ID" --name "database-vm" --user "$USER" --password "$PASS" --endpoint $ENDPOINT)
  DATABASE_ID=$(echo $DATABASE_REZ |cut -d ' ' -f 3)

  if ! [[ "$DATABASE_ID" =~ $COMPARE_NUM ]]; then
    echo "$DATABASE_REZ"
    echo -e "- Could not create database-vm for user $USER"
  else
    echo -e "- Successfuly created database-vm: $DATABASE_ID"
    DATABASE_CREATED=true
    break
  fi
done


while true; do
  echo -e "\nNow you are creating a client-vm:"
  read -p "Enter VMs user: " USER

  read -p "Enter $USER password: " -s PASS
  echo ""
  CLIENT_REZ=$(onetemplate instantiate "$TEMPLATE_ID" --name "client-vm" --user "$USER" --password "$PASS" --endpoint $ENDPOINT)
  CLIENT_ID=$(echo $CLIENT_REZ |cut -d ' ' -f 3)

  if ! [[ "$CLIENT_ID" =~ $COMPARE_NUM ]]; then
    echo "$CLIENT_REZ"
    echo -e "- Could not create client-vm for user $USER"
  else
    echo -e "- Successfuly created client-vm: $CLIENT_ID"
    CLIENT_CREATED=true
    break
  fi
done


echo ""


echo -ne "Initialization Progress:"

for ((i = 0; i < WAIT_TIME; i++)); do
  echo -ne "##"
  sleep 1
done

echo -e "\nVM Initialization Complete\n"

if $WEBSERVER_CREATED; then
    echo -e "Retrieving info about webserver-vm"
    onevm show $WEBSERVER_ID --user $USER --password $PASS --endpoint $ENDPOINT > $WEBSERVER_ID.txt
    WEBSERVER_CON=$(cat $WEBSERVER_ID.txt | grep CONNECT\_INFO1| cut -d '=' -f 2 | tr -d '"'|sed 's/'$USER'/root/')
    WEBSERVER_IP=$(cat $WEBSERVER_ID.txt | grep PRIVATE\_IP| cut -d '=' -f 2 | tr -d '"')
    WEBSERVER_PUBLIC_IP=$(cat $WEBSERVER_ID.txt | grep PUBLIC\_IP| cut -d '=' -f 2 | tr -d '"')
    WEBSERVER_PORT_80=$(cat $WEBSERVER_ID.txt | grep 'TCP_PORT_FORWARDING=' | sed 's/.* \([0-9]\+\):80.*/\1/')
    echo -e "Success\nPrivate_ip: $WEBSERVER_IP\nPublic_ip: $WEBSERVER_PUBLIC_IP\nPort_80: $WEBSERVER_PORT_80:80\n$"
fi


if $WEBSERVER_CREATED; then
    echo "Copying ssh key to websever-vm at $USER@$WEBSERVER_IP"
    sshpass -p "$SUDO_PASS" ssh-copy-id -o StrictHostKeyChecking=no -f "$USER@$WEBSERVER_IP"
    echo -e "Successfuly copied ssh key to webserver-vm\n"
fi


if $DATABASE_CREATED; then
  echo -e "Retrieving info about database-vm"
  onevm show $DATABASE_ID --user $USER --password $PASS --endpoint $ENDPOINT > $DATABASE_ID.txt
  DATABASE_CON=$(cat $DATABASE_ID.txt | grep CONNECT\_INFO1| cut -d '=' -f 2 | tr -d '"'|sed 's/'$USER'/root/')
  DATABASE_IP=$(cat $DATABASE_ID.txt | grep PRIVATE\_IP| cut -d '=' -f 2 | tr -d '"')
  DATABASE_PUBLIC_IP=$(cat $DATABASE_ID.txt | grep PUBLIC\_IP| cut -d '=' -f 2 | tr -d '"')
  DATABASE_PORT_80=$(cat $DATABASE_ID.txt | grep 'TCP_PORT_FORWARDING=' | sed 's/.* \([0-9]\+\):80.*/\1/')
  echo -e "Successfully retrieved info about database-vm\nPrivate_ip: $DATABASE_IP\nPublic_ip: $DATABASE_PUBLIC_IP\nPort_80: $DATABASE_PORT_80:80\n$"
fi


if $DATABASE_CREATED; then
  echo "Copying ssh key to database-vm at $USER@$DATABASE_IP"
  sshpass -p "$SUDO_PASS" ssh-copy-id -o StrictHostKeyChecking=no "$USER@$DATABASE_IP"
  echo -e "Successfully copied ssh key to database-vm\n"
fi


if $CLIENT_CREATED; then
  echo -e "Retrieving info about client-vm"
  onevm show $CLIENT_ID --user $USER --password $PASS --endpoint $ENDPOINT > $CLIENT_ID.txt
  CLIENT_CON=$(cat $CLIENT_ID.txt | grep CONNECT\_INFO1| cut -d '=' -f 2 | tr -d '"'|sed 's/'$USER'/root/')
  CLIENT_IP=$(cat $CLIENT_ID.txt | grep PRIVATE\_IP| cut -d '=' -f 2 | tr -d '"')
  CLIENT_PUBLIC_IP=$(cat $CLIENT_ID.txt | grep PUBLIC\_IP| cut -d '=' -f 2 | tr -d '"')
  CLIENT_PORT_80=$(cat $CLIENT_ID.txt | grep 'TCP_PORT_FORWARDING=' | sed 's/.* \([0-9]\+\):80.*/\1/')
  echo -e "Successfully retrieved info about client-vm\nPrivate_ip: $CLIENT_IP\nPublic_ip: $CLIENT_PUBLIC_IP\nPort_80: $CLIENT_PORT_80:80\n$"
fi


if $CLIENT_CREATED; then
  echo "Copying ssh key to client-vm at $USER@$CLIENT_IP"
  sshpass -p "$SUDO_PASS" ssh-copy-id -o StrictHostKeyChecking=no "$USER@$CLIENT_IP"
  echo -e "Successfully copied ssh key to client-vm\n"
fi



echo "Creating hosts file"
echo "" > hosts
if $WEBSERVER_CREATED; then
    printf "[webservers]\n$WEBSERVER_IP\n\n" >> hosts
fi
if $DATABASE_CREATED; then
    printf "[database]\n$DATABASE_IP\n\n" >> hosts
fi
if $CLIENT_CREATED; then
    printf "[clients]\n$CLIENT_IP"  >> hosts
fi
echo -e "Successfully created hosts file\n"

echo "Pinging remote machines with ansible"
ansible all -m ping -i ./hosts
echo ""
