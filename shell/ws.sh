#!/bin/bash
# This script creates Website VM

CUSER=sest8864
echo "Please enter your OpenNebula password:"
read -s CPASS
CENDPOINT=https://grid5.mif.vu.lt/cloud3/RPC2

CVMREZ=$(onetemplate instantiate "ubuntu-22.04" --name "ws-vm"  --user $CUSER --password $CPASS  --endpoint $CENDPOINT)

CVMID=$(echo $CVMREZ |cut -d ' ' -f 3) 
echo "VM ID = " $CVMID

echo "Waiting for VM to RUN 60 sec."
sleep 60
$(onevm show $CVMID --user $CUSER --password $CPASS  --endpoint $CENDPOINT >$CVMID.txt)

CSSH_CON=$(cat $CVMID.txt | grep CONNECT\_INFO1| cut -d '=' -f 2 | tr -d '"'|sed 's/'$CUSER'/root/')
CSSH_PRIP=$(cat $CVMID.txt | grep PRIVATE\_IP| cut -d '=' -f 2 | tr -d '"')
CLINK=$(cat $CVMID.txt | grep TCP\_PORT\_FORWARDING| cut -d ' ' -f 2| sed 's/....$//')
IP=$(echo ${CSSH_CON} | cut -d '@' -f 2)

#$CLINK="${IP}:${CLINK}"

echo "Link: $IP:$CLINK"

echo "$IP:$CLINK" > address

echo "Connection string: $CSSH_CON"
echo "Local IP: $CSSH_PRIP"

echo "[Webserver]" >> hosts
echo "$CSSH_PRIP" >> hosts
echo "[Webserver:vars]" >> hosts
echo "ansible_user=root" >> hosts
#echo "ansible_password=$CPASS" >> hosts

echo "Created Webserver VM"














