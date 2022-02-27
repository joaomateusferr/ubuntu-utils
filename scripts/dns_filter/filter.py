import socket
from dnslib import DNSRecord
from dnslib import A    #ipv4
from dnslib import AAAA #ipv6

def main():

    DnsDistIp = "127.0.0.1"
    DnsDistPort = 5050
    DnsDistAddress = (DnsDistIp, DnsDistPort)

    PowerDnsIp = "8.8.8.8"
    PowerDnsPort = 53
    PowerDnsAddress = (PowerDnsIp, PowerDnsPort)

    BufferSize = 512

    DnsDisConnection = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
    DnsDisConnection.bind(DnsDistAddress)   #opens a connection to a port not in use

    PowerDnsConnection = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
    PowerDnsConnection.connect(PowerDnsAddress) #connects to a port already in use

    while 1:

        DnsQuery, DnsQueryAddress = DnsDisConnection.recvfrom(BufferSize)  

        PowerDnsConnection.send(DnsQuery)
        DnsResponse, DnsResponseAddress = PowerDnsConnection.recvfrom(BufferSize)

        DecodedDnsResponse = DNSRecord.parse(DnsResponse)

        #test only
        DnsQueryName = DecodedDnsResponse.questions[0].qname
        print(str(DnsQueryName))

        DnsQueryNameToModify = "br.yahoo.com"

        if DnsQueryName == DnsQueryNameToModify:
            NeedModifications = True
            ModificationType = "BLOCK"
        else:
            NeedModifications = False

        if NeedModifications == True:

            if ModificationType == "BLOCK":

                for DnsResponse in DecodedDnsResponse.rr:

                    if DnsResponse.rtype == 1:
                        DnsResponse.rdata = A("127.0.0.1")  #localhost ipv4
                    elif DnsResponse.rtype == 28:
                        DnsResponse.rdata = AAAA("::1") #localhost ipv6

                ModifiedDnsResponse = bytes(DecodedDnsResponse.pack())
                DnsDisConnection.sendto(ModifiedDnsResponse, DnsQueryAddress)

        else :
            DnsDisConnection.sendto(DnsResponse, DnsQueryAddress)

    DnsDisConnection.close()
    PowerDnsConnection.close()

main()