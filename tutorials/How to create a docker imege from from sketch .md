# How to create and use a docker imege

## Selecting the base image

Go to docker hub and choose the base image, in my case I chose ubuntu latest, and then create a folder with to create the image, navigate to the newly created folder and create a file named Dockerfile and add the desired settings as in the example below.

The code below is just a simple example.

```
FROM ubuntu
CMD ["bash"]
```

If you want to use a pre-made image from docker hub, use the command below and skip the build image session.

```
docker pull ubuntu:latest
```

## Build image

Now to build the image just run the command below.

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

To create a container and start it for the first time with the newly created image on port 8080 of the host machine exposing port 80 from the container use the command below.

```
docker run -t -d -p 8080:80 --name server ubuntu-server
```

## Manage containers

To check running containers use the command below.

```
docker ps

CONTAINER ID   IMAGE           COMMAND   CREATED          STATUS          PORTS                                   NAMES
2f50319f880d   ubuntu-server   "bash"    16 minutes ago   Up 16 minutes   0.0.0.0:8080->80/tcp, :::8080->80/tcp   server
```

To open the newly created container using a bash terminal you can use the command below.

```
docker exec -it server bash
```

**Alpine** based images don't use bash in this case use **sh** instead.

If you are using **Ubuntu**, update the repositories list before doing anything else by running the command below.

```
apt-get update
```

To stop a container use the command below.

```
docker stop server
```

To check if the container has stopped running use the commands below.

```
docker ps

CONTAINER ID   IMAGE     COMMAND   CREATED   STATUS    PORTS     NAMES

docker ps -a

CONTAINER ID   IMAGE           COMMAND   CREATED          STATUS                       PORTS     NAMES
2f50319f880d   ubuntu-server   "bash"    38 minutes ago   Exited (137) 4 minutes ago             server
```

To start again an already created container use the command below.

```
docker start server
```

It's worth remembering that if you haven't configured your coontainer's services to start automatically, either through the image cmd or through the service settings in the container, you'll have to open the container using exec and start the services manually.

To permanently delete a container use the command below.

```
docker rm server
```

To permanently delete a container use the command below.

```
docker rmi server-ubuntu
```