#!/bin/bash

read -p "please enter the name of the user to be added: " user
sudo useradd $user
echo "$user is added"
echo "-----------------------------"

sudo passwd "$user"
echo "Password for user $user is set"
echo "-----------------------------"

sudo userdel $user
echo "$user is removed"
echo "-----------------------------"

touch test
echo "test file created"
echo "-----------------------------"

chmod 742 test
echo "permissions set to 742"
echo "-----------------------------"

chmod u+rwx,g+r,o+w test
echo "permissions updated with symbolic mode"
echo "-----------------------------"

locate new | grep -x '.*/new'
echo "searched for 'test' using locate and grep"
echo "-----------------------------"

mkdir folder
echo "folder created"
echo "-----------------------------"

rm -rf folder
echo "folder deleted"
echo "-----------------------------"

echo "Random contents" >> test
echo "random contents added to test"
echo "-----------------------------"

cat test
echo "test file contents displayed"
echo "-----------------------------"

alias d=date
alias n=nano
alias h=history
echo "aliases set for date, nano, and history"
echo "-----------------------------"

pwd
echo "current working directory displayed"
echo "-----------------------------"

read -p "please enter the name of the user to be added: " user
sudo useradd $user
echo "$user is added"
echo "-----------------------------"

sudo passwd "$user"
echo "Password for user $user is set"
echo "-----------------------------"

echo "the command used to switch to $user is: sudo su $user"
echo "-----------------------------"

mkdir test_dir
echo "test_dir created"
echo "-----------------------------"

cp test test_dir/
echo "test copied to test_dir"
echo "-----------------------------"

mv test test_dir/
echo "test moved to test_dir"
ls -ltr
echo "-----------------------------"

mv test_dir/test test_dir/name_changed
echo "test renamed to name_changed in test_dir"
tree
echo "-----------------------------"

rm -rf test_dir
echo "test_dir deleted"
echo "-----------------------------"

ls -ltr
