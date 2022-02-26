import socket

HEADER = 64
PORT = 5050
FORMAT = 'utf-8'
DISCONNECT_MESSAGE = "!DISCONNECT"
SERVER = "127.0.1.1"
ADDR = (SERVER, PORT)

PORTDNS = 2525
ADDRDNS = (SERVER, PORTDNS)

clientdns = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
clientdns.bind(ADDRDNS)

client = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
client.connect(ADDR)

def send(msg):
    message = msg.encode(FORMAT)
    msg_length = len(message)
    send_length = str(msg_length).encode(FORMAT)
    send_length += b' ' * (HEADER - len(send_length))
    client.send(send_length)
    client.send(message)
    print(client.recv(2048).decode(FORMAT))

while 1:

    data, addr = client.recvfrom(512)

    print("send")

    decoded = DNS(data)
    decoded.show()


send("Hello World!")
send("Hello Everyone!")
send("Hello Tim!")

send(DISCONNECT_MESSAGE)
