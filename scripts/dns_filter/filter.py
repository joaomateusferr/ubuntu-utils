import socket
import threading
from dnslib import DNSRecord
from dnslib import A     #ipv4
from dnslib import AAAA  #ipv6
from dnslib import CNAME #redirection

def ProcessDNSQuery(DnsDisConnection, PowerDnsConnection, BufferSize, DnsQuery, DnsQueryAddress):
    
    PowerDnsConnection.send(DnsQuery)
    DnsResponse, DnsResponseAddress = PowerDnsConnection.recvfrom(BufferSize)

    DecodedDnsResponse = DNSRecord.parse(DnsResponse)

    try:

        DnsQueryName = DecodedDnsResponse.questions[0].qname
        DnsQueryNameToModify = "br.yahoo.com"

        if DnsQueryName == DnsQueryNameToModify:
            NeedModifications = True
            ModificationType = "REMOVE"
        else:
            NeedModifications = False

        if NeedModifications == True:

            if ModificationType == "BLOCK":

                for DnsResponse in DecodedDnsResponse.rr:

                    if DnsResponse.rtype == 1:
                        DnsResponse.rdata = A("127.0.0.1")  #localhost ipv4
                        DnsResponse.ttl = 1
                    elif DnsResponse.rtype == 28:
                        DnsResponse.rdata = AAAA("::1") #localhost ipv6
                        DnsResponse.ttl = 1

            elif ModificationType == "GOOGLE_SAFE_SEARCH":

                for DnsResponse in DecodedDnsResponse.rr:

                    if DnsResponse.rtype == 1:
                        DnsResponse.rdata = A("216.239.38.120")
                        DnsResponse.ttl = 1
                    elif DnsResponse.rtype == 28:
                        DnsResponse.rdata = AAAA("2001:4860:4802:32::78")
                        DnsResponse.ttl = 1
                    elif DnsResponse.rtype == 5:
                        DnsResponse.rdata = CNAME("forcesafesearch.google.com") #redirect
                        DnsResponse.ttl = 1

            elif ModificationType == "REMOVE":

                for DnsResponse in list (DecodedDnsResponse.rr):

                    if DnsResponse.rtype == 1:
                        DecodedDnsResponse.rr.remove(DnsResponse)
                    elif DnsResponse.rtype == 28:
                        DecodedDnsResponse.rr.remove(DnsResponse)

            elif "CUSTOM=A=" in ModificationType:

                Target = ModificationType[9:]

                if ":" in Target :
                    TargetIpType = "ipv6"
                else :
                    TargetIpType = "ipv4"

                for DnsResponse in DecodedDnsResponse.rr:

                    if DnsResponse.rtype == 1 and TargetIpType == "ipv4" :
                        DnsResponse.rdata = A(Target)
                        DnsResponse.ttl = 1
                    elif DnsResponse.rtype == 1 and TargetIpType == "ipv6" :
                        DnsResponse.rdata = AAAA(Target)
                        DnsResponse.ttl = 1

            ModifiedDnsResponse = bytes(DecodedDnsResponse.pack())
            DnsDisConnection.sendto(ModifiedDnsResponse, DnsQueryAddress)

        else :

            DnsDisConnection.sendto(DnsResponse, DnsQueryAddress)

    except:

        DnsDisConnection.sendto(DnsResponse, DnsQueryAddress) #only forward dns query if something goes wrong 

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

    try:

        while True:

            DnsQuery, DnsQueryAddress = DnsDisConnection.recvfrom(BufferSize)

            ProcessDNSQueryThread = threading.Thread(target=ProcessDNSQuery, args=(DnsDisConnection, PowerDnsConnection, BufferSize, DnsQuery, DnsQueryAddress))
            ProcessDNSQueryThread.start()

            #print(str(threading.active_count()-1) + " active thread resolving dns") #thread debug

    except (KeyboardInterrupt, SystemExit): #handles ctrl + c and kil

        DnsDisConnection.close()
        PowerDnsConnection.close()

main()