import socket
from dnslib import DNSRecord
from dnslib import A #ipv4

port = 5050
ip = '127.0.0.1'

sock = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
sock.bind((ip, port))

while 1:

    data, addr = sock.recvfrom(512)
    forward_addr = ("8.8.8.8", 53)
    client = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
    client.sendto(data, forward_addr)
    res, _ = client.recvfrom(512)
    d = DNSRecord.parse(res)
    qname = d.questions[0].qname

    print(qname)

    if qname == "www.google.com":
        d.rr[0].rdata = A("0.0.0.0")
        a = bytes(d.pack())
        sock.sendto(a, addr)

    sock.sendto(res, addr)