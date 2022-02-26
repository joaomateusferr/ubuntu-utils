import socket
import dns.resolver as dns
from scapy.all import DNS
from dnslib import DNSRecord

port = 2525
ip = '127.0.0.1'

sock = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
sock.bind((ip, port))

while 1:

    data, addr = sock.recvfrom(512)
    forward_addr = ("8.8.8.8", 53) # dns and port
    client = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
    client.sendto(data, forward_addr)
    res, _ = client.recvfrom(1024)
    d = DNSRecord.parse(res)
    print("r", str(d.rr[0].rdata))
    sock.sendto(res, addr)
    print(addr)