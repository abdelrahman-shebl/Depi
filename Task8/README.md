# SSH Connection and Ansible Setup Guide

This guide walks you through setting up SSH key-based authentication between two devices and using Ansible for remote management.

## Prerequisites

- Two Linux/Unix systems (control node and target node)
- SSH client installed on both systems
- Ansible installed on the control node
- Network connectivity between the systems

## Step 1: Generate SSH Key Pair

On your control node (the machine you'll be connecting FROM), generate an SSH key pair:

```bash
ssh-keygen 
```

When prompted:
- Press Enter to accept the default file location (`~/.ssh/id_rsa`)
- Optionally enter a passphrase for additional security (or press Enter for no passphrase)

This creates two files:
- `~/.ssh/id_rsa` (private key - keep this secure!)
- `~/.ssh/id_rsa.pub` (public key - this gets copied to target systems)

## Step 2: Copy SSH Key to Target Device

Use `ssh-copy-id` to copy your public key to the target device:

```bash
ssh-copy-id username@target_ip_address
```

Example:
```bash
ssh-copy-id ansible@10.96.1.236
```

You'll be prompted for the target user's password. After successful authentication, your public key will be added to the target's `~/.ssh/authorized_keys` file.

## Step 3: Test SSH Connection

Verify that password-less SSH authentication works:

```bash
ssh username@target_ip_address
```

You should now be able to connect without entering a password.

## Step 4: Create Ansible Inventory

Create an inventory file to define your target hosts. Save this as `inventory.ini`:

```ini
[webservers]
webserver1 ansible_host=10.96.1.236 ansible_user=abdelrahman

```

## Step 5: Test Ansible Connectivity

### Ping Test

Test connectivity to all hosts:

```bash
ansible all -i inventory.yml -m ping
```

Or for specific groups:

```bash
ansible webservers -i inventory.ini -m ping
```

Expected output:
```
webserver1 | SUCCESS => {
    "ansible_facts": {
        "discovered_interpreter_python": "/usr/bin/python3"
    },
    "changed": false,
    "ping": "pong"
}
```

### Check Uptime

Get system uptime from all hosts:

```bash
ansible all -i inventory.ini -m shell -a "uptime"
```
Expected output:
```
webserver1 | CHANGED | rc=0 >>
 14:30:25 up 2 days,  3:42,  1 user,  load average: 0.15, 0.10, 0.08
```