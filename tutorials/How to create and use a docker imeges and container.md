# How to create and use a docker imeges and containers

## Install docker

Take a look at [install_docker.sh](../scripts/docker/install_docker.sh)

Check if docker is active

```
sudo systemctl status docker
```

## Selecting the base image

Go to docker hub and choose the base image, in my case I chose ubuntu latest, then create a folder to to set up the image, browse to the newly created folder and create a file named Dockerfile and add the desired settings. Just like it was done in the [php-image](../scripts/docker/php-image/) forder.

If you want to use a pre-made image from docker hub, use the command below and skip the build image session.

```
docker pull ubuntu:latest
```

## Build image

Now to build the image just run the command:

```
docker build -t ubuntu-server .
```

To check if the image was built correctly run the command below, you should see the ubuntu-server image.

```
docker images

REPOSITORY      TAG       IMAGE ID       CREATED              SIZE
ubuntu-server   latest    f8c5de33a26d   About a minute ago   77.8MB
```

## Create container

To create a container and start it for the first time with the newly created image on port 8080 of the host machine exposing port 80 from the container use the command:

```
docker run -t -d -p 8080:80 -v /home/$USER/Sites:/var/www/html --name server ubuntu-server
```

## Manage containers and images

To check running containers:

```
docker ps

CONTAINER ID   IMAGE           COMMAND                  CREATED         STATUS         PORTS                                            NAMES
8a9f94338edb   ubuntu-server   "bash apachectl -D F…"   3 minutes ago   Up 3 minutes   443/tcp, 0.0.0.0:8080->80/tcp, :::8080->80/tcp   server
```

To check running volumes:

```
docker volume ls

DRIVER    VOLUME NAME
local     6571cdd48877686c7020bde06791b024cde3aa8b5a70922f6416cb1bf3dcf7db
```

To open the newly created container using a bash terminal you can use:

```
docker exec -it server bash
```

**Alpine** based images don't use bash in this case use **sh** instead.

```
apt-get update
```

To stop a container use:

```
docker stop server
```

To check if the container has stopped running, use the commands:

```
docker ps

CONTAINER ID   IMAGE     COMMAND   CREATED   STATUS    PORTS     NAMES

docker ps -a

CONTAINER ID   IMAGE           COMMAND                  CREATED         STATUS                       PORTS     NAMES
8a9f94338edb   ubuntu-server   "bash apachectl -D F…"   7 minutes ago   Exited (137) 8 seconds ago             server
```

To start again an already created container, use:

```
docker start server
```

It's worth remembering that if you haven't configured your coontainer's services to start automatically, either through the image cmd or through the service settings in the container, you'll have to open the container using exec and start the services manually.

To permanently delete a container, use the command below.

```
docker rm server
```

To stop all containers on a machine, use the command below

```
docker stop $(docker ps -a -q)
```

To permanently delete all containers on a machine, use the command below

```
docker rm $(docker ps -a -q)
```

To permanently delete a image, use the command below.

```
docker rmi server-ubuntu
```