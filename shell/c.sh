#!/bin/bash
# This script creates Client VM

CUSER=sest8864
echo "Please enter your OpenNebula password:"
read -s CPASS
CENDPOINT=https://grid5.mif.vu.lt/cloud3/RPC2

CVMREZ=$(onetemplate instantiate "ubuntu-22.04" --name "c-vm"  --user $CUSER --password $CPASS  --endpoint $CENDPOINT)

CVMID=$(echo $CVMREZ |cut -d ' ' -f 3) 
echo "VM ID = " $CVMID

echo "Waiting for VM to RUN 60 sec."
sleep 60
$(onevm show $CVMID --user $CUSER --password $CPASS  --endpoint $CENDPOINT >$CVMID.txt)

CSSH_CON=$(cat $CVMID.txt | grep CONNECT\_INFO1| cut -d '=' -f 2 | tr -d '"'|sed 's/'$CUSER'/root/')
CSSH_PRIP=$(cat $CVMID.txt | grep PRIVATE\_IP| cut -d '=' -f 2 | tr -d '"')
echo "Connection string: $CSSH_CON"
echo "Local IP: $CSSH_PRIP"

echo "[Client]" >> hosts
echo "$CSSH_PRIP" >> hosts
echo "[Client:vars]" >> hosts
echo "ansible_user=root" >> hosts

echo "$CVMID" >> ids

echo "Created Client VM"








