#!/bin/bash

set -e

REDIS_USER="admin"
REDIS_PASS="SuperSecurePassword123"
REDIS_IMAGE="redis:7.2"

NETWORK_NAME="redis-cluster-net"
CLUSTER_DIR="$(pwd)/redis-cluster-data"

HOST_IP=$(ip route get 1 | awk '{print $(NF-2);exit}')

if [ -z "$HOST_IP" ]; then
  echo "Could not determine the host's IP!"
  exit 1
fi

echo "Host IP detected: $HOST_IP"

docker rm -f redis-node-1 redis-node-2 redis-node-3 redis-node-4 redis-node-5 redis-node-6 2>/dev/null
docker network rm $NETWORK_NAME 2>/dev/null
sudo rm -rf $CLUSTER_DIR

echo "Creating a Docker network ($NETWORK_NAME)..."
docker network create $NETWORK_NAME

echo "Creating directories and configuration files..."
mkdir -p $CLUSTER_DIR

cat <<EOF > $CLUSTER_DIR/users.acl
user default off
user ${REDIS_USER} on >${REDIS_PASS} ~* &* +@all
EOF

for i in 1 2 3 4 5 6; do
  PORT=$((7000 + i))
  BUS_PORT=$((17000 + i))

  mkdir -p $CLUSTER_DIR/node-$i/data

  cat <<EOF > $CLUSTER_DIR/node-$i/redis.conf
port 6379
cluster-enabled yes
cluster-config-file nodes.conf
cluster-node-timeout 5000
appendonly yes
aclfile /etc/redis/users.acl
masteruser ${REDIS_USER}
masterauth ${REDIS_PASS}
cluster-announce-ip $HOST_IP
cluster-announce-port $PORT
cluster-announce-bus-port $BUS_PORT
EOF

  echo "Initializing redis-node-$i (External Port: $PORT, Bus: $BUS_PORT)..."
  docker run -d --name redis-node-$i --net $NETWORK_NAME -p $PORT:6379 -p $BUS_PORT:16379 -v $CLUSTER_DIR/node-$i/redis.conf:/etc/redis/redis.conf:ro -v $CLUSTER_DIR/users.acl:/etc/redis/users.acl:ro -v $CLUSTER_DIR/node-$i/data:/data $REDIS_IMAGE redis-server /etc/redis/redis.conf

done

sleep 5

NODES_ANNOUNCED=""

for i in 1 2 3 4 5 6; do

  PORT=$((7000 + i))
  NODES_ANNOUNCED="$NODES_ANNOUNCED $HOST_IP:$PORT"

done

echo "Forming Redis Cluster..."
docker run --rm -i --net host $REDIS_IMAGE redis-cli --user ${REDIS_USER} -a ${REDIS_PASS} --cluster create $NODES_ANNOUNCED --cluster-replicas 1 --cluster-yes

echo ""
echo "Nodes exposed in: $HOST_IP (Ports 7001 a 7006)"
echo "User: $REDIS_USER"
echo "Password: $REDIS_PASS"
echo ""