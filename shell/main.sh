#!/bin/bash

GREEN='\e[1;32m'
YELLOW='\e[1;33m'
RED='\e[1;31m'
CRESET='\e[0m'

# Install opennebula tools and ansible
wget -q -O- https://downloads.opennebula.org/repo/repo.key | sudo apt-key add -
echo "deb [trusted=yes] https://downloads.opennebula.org/repo/5.6/Ubuntu/18.04 stable opennebula" | sudo tee /etc/apt/sources.list.d/opennebula.list
sudo apt update
sudo apt install -y opennebula-tools
sudo apt install -y ansible
sudo apt install -y sshpass
sudo apt install -y python3-passlib



# Generate ssh key and save password with ssh-agent and ssh-add so you don't need to git retype pass every time
echo -e "\nGenerate and add ssh key"
eval `ssh-agent -s`
ssh-keygen -f ~/.ssh/id_rsa
echo "Please enter password for your SSH key: "
ssh-add
echo ""



ENDPOINT=https://grid5.mif.vu.lt/cloud3/RPC2

# Create a web server virtual machine

while true; do
  # Ask the user for the web server VM username
  read -p "Enter web server VM username (leave blank to skip): " WEB_USER

  # If the username is empty, skip the creation of the VM
  if [ -z "$WEB_USER" ]; then
    echo -e "Skipping web server VM creation."
    WEB_CREATED=false
    break
  fi

  # Ask the user for the web server VM password
  read -p "Enter $WEB_USER password: " -s WEB_PASS
  echo ""

  # Create the web server VM using the onetemplate tool
  WEB_REZ=$(onetemplate instantiate 2401 --name "webserver-vm" --user "$WEB_USER" --password "$WEB_PASS" --endpoint $ENDPOINT)
  WEB_ID=$(echo $WEB_REZ | cut -d ' ' -f 3)

  # Check if the VM ID is a valid number
  if ! [[ "$WEB_ID" =~ ^[0-9]+$ ]]; then
    echo "$WEB_REZ"
    echo -e "Failed to create web server VM for user $WEB_USER."
  else
    echo -e "Successfully created web server VM with ID: $WEB_ID."
    WEB_CREATED=true
    break
  fi
done

echo ""

# Create a database virtual machine

while true; do
  # Ask the user for the database VM username
  read -p "Enter database VM username (leave blank to skip): " DATA_USER

  # If the username is empty, skip the creation of the VM
  if [ -z "$DATA_USER" ]; then
    echo -e "Skipping database VM creation."
    DATA_CREATED=false
    break
  fi

  # Ask the user for the database VM password
  read -p "Enter $DATA_USER password: " -s DATA_PASS
  echo ""

  # Create the database VM using the onetemplate tool
  DATA_REZ=$(onetemplate instantiate 2401 --name "database-vm" --user "$DATA_USER" --password "$DATA_PASS" --endpoint $ENDPOINT)
  DATA_ID=$(echo $DATA_REZ | cut -d ' ' -f 3)

  # Check if the VM ID is a valid number
  if ! [[ "$DATA_ID" =~ ^[0-9]+$ ]]; then
    echo "$DATA_REZ"
    echo -e "Failed to create database VM for user $DATA_USER."
  else
    echo -e "Successfully created database VM with ID: $DATA_ID."
    DATA_CREATED=true
    break
  fi
done

echo ""

# Create a client virtual machine

while true; do
  # Ask the user for the client VM username
  read -p "Enter client VM username (leave blank to skip): " CLIENT_USER

  # If the username is empty, skip the creation of the VM
  if [ -z "$CLIENT_USER" ]; then
    echo -e "Skipping client VM creation."
    CLIENT_CREATED=false
    break
  fi

  # Ask the user for the client VM password
  read -p "Enter $CLIENT_USER password: " -s CLIENT_PASS
  echo ""

  # Create the client VM using the onetemplate tool
  CLIENT_REZ=$(onetemplate instantiate 2421 --name "client-vm" --user "$CLIENT_USER" --password "$CLIENT_PASS" --endpoint $ENDPOINT)
  CLIENT_ID=$(echo $CLIENT_REZ | cut -d ' ' -f 3)

  # Check if the VM ID is a valid number
  if ! [[ "$CLIENT_ID" =~ ^[0-9]+$ ]]; then
    echo "$CLIENT_REZ"
    echo -e "Failed to create client VM for user $CLIENT_USER."
  else
    echo -e "Successfully created client VM with ID: $CLIENT_ID."
    CLIENT_CREATED=true
    break
  fi
done

echo ""

# Wait for VMs to start

echo -e "Waiting for virtual machines to start..."
echo -e "(Press any key to skip)"

SECONDS_LEFT=45

while [ $SECONDS_LEFT -gt 0 ]; do
  echo -ne "Waiting $SECONDS_LEFT seconds for VMs to run...\r"

  read -t 1 -n 1 key
  if [ $? -eq 0 ]; then
    echo -e "\nWaiting skipped by user."
    break
  fi

  ((SECONDS_LEFT--))
done

echo -e "\nVirtual machines have started."

# Copy private IPs and connection strings from files

if $WEB_CREATED; then
  echo -e "Retrieving information about web server VM..."
  onevm show $WEB_ID --user $WEB_USER --password $WEB_PASS --endpoint $ENDPOINT > $WEB_ID.txt
  WEB_CON=$(cat $WEB_ID.txt | grep CONNECT_INFO1 | cut -d '=' -f 2 | tr -d '"' | sed 's/'$WEB_USER'/root/')
  WEB_IP=$(cat $WEB_ID.txt | grep PRIVATE_IP | cut -d '=' -f 2 | tr -d '"')
  WEB_PUBLIC_IP=$(cat $WEB_ID.txt | grep PUBLIC_IP | cut -d '=' -f 2 | tr -d '"')
  WEB_PORT_80=$(cat $WEB_ID.txt | grep 'TCP_PORT_FORWARDING=' | sed 's/.* \([0-9]\+\):80.*/\1/')
  WEB_PORT_3389=$(cat $WEB_ID.txt | grep 'TCP_PORT_FORWARDING=' | sed 's/.* \([0-9]\+\):3389.*/\1/')
  echo -e "Successfully retrieved information about web server VM\n"
  echo "Private IP: $WEB_IP"
  echo "Public IP: $WEB_PUBLIC_IP"
  echo "Port 80: $WEB_PORT_80:80"
  echo "Port 3389: $WEB_PORT_3389:3389"
else
  echo -e "Web server VM not created, skipping..."
fi

if $DATA_CREATED; then
  echo -e "Retrieving information about database VM..."
  onevm show $DATA_ID --user $DATA_USER --password $DATA_PASS --endpoint $ENDPOINT > $DATA_ID.txt
  DATA_CON=$(cat $DATA_ID.txt | grep CONNECT_INFO1 | cut -d '=' -f 2 | tr -d '"' | sed 's/'$DATA_USER'/root/')
  DATA_IP=$(cat $DATA_ID.txt | grep PRIVATE_IP | cut -d '=' -f 2 | tr -d '"')
  DATA_PORT_3306=$(cat $DATA_ID.txt | grep 'TCP_PORT_FORWARDING=' | sed 's/.* \([0-9]\+\):3306.*/\1/')
  echo -e "Successfully retrieved information about database VM\n"
  echo "Private IP: $DATA_IP"
else
  echo -e "Database VM not created, skipping..."
fi

if $CLIENT_CREATED; then
  echo -e "Retrieving information about client VM..."
  onevm show $CLIENT_ID --user $CLIENT_USER --password $CLIENT_PASS --endpoint $ENDPOINT > $CLIENT_ID.txt
  CLIENT_CON=$(cat $CLIENT_ID.txt | grep CONNECT_INFO1 | cut -d '=' -f 2 | tr -d '"' | sed 's/'$CLIENT_USER'/root/')
  CLIENT_IP=$(cat $CLIENT_ID.txt | grep PRIVATE_IP | cut -d '=' -f 2 | tr -d '"')
  CLIENT_PUBLIC_IP=$(cat $CLIENT_ID.txt | grep PUBLIC_IP | cut -d '=' -f 2 | tr -d '"')
  CLIENT_PORT_3389=$(cat $CLIENT_ID.txt | grep 'TCP_PORT_FORWARDING=' | sed 's/.* \([0-9]\+\):3389.*/\1/')
  echo -e "Successfully retrieved information about client VM\n"
  echo "Private IP: $CLIENT_IP"
else
  echo -e "Client VM not created, skipping..."
fi

# Initial password set in VM template
INITIAL_PASSWORD=password

# Copy SSH key to remote machines

if $WEB_CREATED; then
  echo -e "Copying SSH key to webserver-vm at $WEB_USER@$WEB_IP..."
  sshpass -p "$INITIAL_PASSWORD" ssh-copy-id -o StrictHostKeyChecking=no -f "$WEB_USER@$WEB_IP"
  echo -e "${GREEN}Successfully copied SSH key to webserver-vm.\n${CRESET}"
fi

if $DATA_CREATED; then
  echo -e "Copying SSH key to database-vm at $DATA_USER@$DATA_IP..."
  sshpass -p "$INITIAL_PASSWORD" ssh-copy-id -o StrictHostKeyChecking=no -f "$DATA_USER@$DATA_IP"
  echo -e "${GREEN}Successfully copied SSH key to database-vm.\n${CRESET}"
fi

if $CLIENT_CREATED; then
  echo -e "Copying SSH key to client-vm at $CLIENT_USER@$CLIENT_IP..."
  sshpass -p "$INITIAL_PASSWORD" ssh-copy-id -o StrictHostKeyChecking=no -f "$CLIENT_USER@$CLIENT_IP"
  echo -e "${GREEN}Successfully copied SSH key to client-vm.\n${CRESET}"
fi

# Create Ansible vault

echo -e "Setting up Ansible vault..."

# Prompt user to enter and confirm new sudo password
while true; do
  read -p "Enter new sudo password: " -s NEW_SUDO_PASS
  echo ""
  read -p "Re-enter new sudo password: " -s NEW_SUDO_PASS2
  echo ""

  if [ "$NEW_SUDO_PASS" == "$NEW_SUDO_PASS2" ]; then
    echo -e "New sudo password successfully added to vault.yml."
    break
  else
    echo -e "Passwords do not match. Please try again."
  fi
done

echo ""

# Create vault.yml file and store sensitive information
printf "---\nsudo_pass: \"$SUDO_PASS\"\nnew_sudo_pass: \"$NEW_SUDO_PASS\"\n" > vault.yml
printf "webserver_private_ip: \"$WEB_IP\"\nwebserver_public_ip: \"$WEB_PUBLIC_IP\"\nwebserver_port: \"$WEB_PORT_80\"\n" >> vault.yml
printf "database_private_ip: \"$DATA_IP\"\ndatabase_port: \"$DATA_PORT_3306\"\n" >> vault.yml

# If a database VM was created, prompt for and store database password
if $DATA_CREATED; then
  while true; do
    read -p "Enter new database password: " -s DATABASE_PASSWORD
    echo ""
    read -p "Re-enter new database password: " -s DATABASE_PASSWORD2
    echo ""

    if [ "$DATABASE_PASSWORD" == "$DATABASE_PASSWORD2" ]; then
      printf "database_password: \"$DATABASE_PASSWORD\"" >> vault.yml
      echo -e "Database password successfully added to vault.yml."
      break
    else
      echo -e "Passwords do not match. Please try again."
    fi
  done
fi

echo ""

# Encrypt the vault.yml file using ansible-vault
while true; do
  ansible-vault encrypt vault.yml

  if [ $? -eq 0 ]; then
    echo -e "Successfully encrypted vault.yml."
    break
  else
    echo -e "Vault encryption failed. Please try again."
  fi
done

echo -e ""

# Create hosts file for Ansible connections
echo "Creating hosts file..."
echo "" > hosts

if $WEB_CREATED; then
  printf "[webservers]\n$WEB_USER@$WEB_IP\n\n" >> hosts
fi

if $DATA_CREATED; then
  printf "[database]\n$DATA_USER@$DATA_IP\n\n" >> hosts
fi

if $CLIENT_CREATED; then
  printf "[clients]\n$CLIENT_USER@$CLIENT_IP" >> hosts
fi

echo -e "Successfully created hosts file."

echo ""

# Test connection to remote machines using Ansible ping
echo "Pinging remote machines with Ansible..."
ansible all -m ping -i ./hosts
echo ""

echo -e "Ansible setup completed."

# Run Ansible playbooks

if $WEB_CREATED; then
  echo "Running webserver.yaml playbook to configure the web server VM..."
  ansible-playbook ../ansible/webserver.yaml -i ./hosts --ask-vault-pass
fi

if $DATA_CREATED; then
  echo "Running database.yaml playbook to configure the database VM..."
  ansible-playbook ../ansible/database.yaml -i ./hosts --ask-vault-pass
fi

if $CLIENT_CREATED; then
  echo "Running client.yaml playbook to configure the client VM..."
  ansible-playbook ../ansible/client.yaml -i ./hosts --ask-vault-pass

  # Provide instructions for connecting to the client VM
  echo ""
  echo "To connect to the client VM using rdesktop (Linux):"
  echo "rdesktop $CLIENT_PUBLIC_IP:$CLIENT_PORT_3389"

  echo ""
  echo "To connect to the client VM using mstsc.exe (Windows):"
  echo "mstsc.exe /v:$CLIENT_PUBLIC_IP:$CLIENT_PORT_3389"
fi
