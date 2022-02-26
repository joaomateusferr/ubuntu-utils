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
    qname = "google.com" # query 
    q = DNSRecord.question(qname)
    client.sendto(bytes(q.pack()), forward_addr)
    data, _ = client.recvfrom(1024)
    d = DNSRecord.parse(data)
    #print("r", str(d.rr[0].rdata)) # prints the A record of duckgo.com

    sock.sendto(data, addr)

    print(addr)

    #sock_gateway.send(data)