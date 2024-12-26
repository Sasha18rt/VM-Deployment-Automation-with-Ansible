# VM Deployment and Configuration Automation

This script automates the process of deploying and configuring virtual machines (VMs) for web servers, databases, and clients using OpenNebula and Ansible.

## Features
- Instantiates VMs via OpenNebula.
- Configures SSH keys for secure access.
- Automates deployment using Ansible playbooks.
- Generates dynamic hosts file for Ansible inventory.

## Requirements
- OpenNebula CLI tools installed.
- Python 3 and pip installed.
- Ansible installed.
- SSH access to the target VMs.

## Usage
1. Clone the repository:
   ```bash
   git clone [<repository-url>](https://github.com/Sasha18rt/VM-Deployment-Automation-with-Ansible.git)
   cd VM-Deployment-Automation-with-Ansible
   ```
2. Run the script:
   ```bash
   ./bash_script/main.sh
   ```
3. Follow the prompts to provide credentials and deploy the VMs.

## Notes
- Ensure your OpenNebula credentials are correct.
- Customize Ansible playbooks under `./ansible` for specific configurations.
