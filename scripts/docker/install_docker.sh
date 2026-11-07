
#!/bin/bash

#This script requires root privileges
#Based on the official Docker documentation:
#https://docs.docker.com/engine/install/ubuntu/

if [ $(id -u) -ne 0 ]; then
    echo 'No root privileges detected!'
    echo 'Please, run this script as root'
    exit 2
fi

apt update

apt install -y ca-certificates curl

#Adding Docker GPG key
install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg -o /etc/apt/keyrings/docker.asc
chmod a+r /etc/apt/keyrings/docker.asc

#Detecting Ubuntu version and architecture
. /etc/os-release
ARCH=$(dpkg --print-architecture)

#Adding Docker repository
echo "Types: deb
URIs: https://download.docker.com/linux/ubuntu
Suites: ${UBUNTU_CODENAME:-$VERSION_CODENAME}
Components: stable
Architectures: ${ARCH}
Signed-By: /etc/apt/keyrings/docker.asc" > /etc/apt/sources.list.d/docker.sources

apt update

apt-cache policy docker-ce

#Installing Docker
apt install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin

#Creating docker group if necessary
groupadd -f docker

#Adding users to docker group
for MACHINE_USER in $(ls /home); do
    usermod -aG docker $MACHINE_USER
done

#Starting Docker service
systemctl enable docker
systemctl start docker

echo 'Now, restart and log back in so that your group membership is re-evaluated and then you can use docker without sudo'

#Run "sudo systemctl status docker" to check if Docker is active
