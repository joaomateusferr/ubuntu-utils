import socket
import dns.resolver as dns
from scapy.all import DNS

port = 2525
ip = '127.0.0.1'

sock = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
sock.bind((ip, port))

while 1:

    data, addr = sock.recvfrom(512)

    print("send")

    decoded = DNS(data)

    print(decoded)

    #sock_gateway.send(data)