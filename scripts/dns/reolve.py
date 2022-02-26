import dns.resolver

my_resolver = dns.resolver.Resolver()

# 8.8.8.8 is Google's public DNS server
my_resolver.nameservers = ['8.8.8.8']

answer = my_resolver.query('google.com', 'A')
  
# Printing record
for val in answer:
    print('A Record : ', val.to_text())