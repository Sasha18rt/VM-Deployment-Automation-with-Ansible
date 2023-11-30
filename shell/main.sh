#!/bin/bash
# Main script

rm hosts ids
touch hosts ids

bash ws.sh # Creating webserver-vm

bash db.sh # Creating database-vm

bash c.sh # Creating client-vm

#rm *.txt

#ansible-playbook ws.yaml
#ansible-playbook db.yaml
#ansible-playbook c.yaml

echo "Setup complete"
