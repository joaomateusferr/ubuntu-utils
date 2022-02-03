# Flamethrower Utils


## Official Repository
Take a look at the official flamethrower repository:

https://github.com/DNS-OARC/flamethrower


## How Docker Containers Work
Take a look at this video to understand how docker containers Work:

https://youtu.be/eGz9DS-aIeY


## How Install Docker On Ubuntu
Take a look at [install_docker.sh](../scripts/install_docker.sh)


## How Flamethrower Work
Take a look at this these tutorials to understand how flamethrower Work:

https://ns1.com/blog/flamethrower-dns-performance-tool

https://youtu.be/iONXcli1afI


## How Install And Test Flamethrower
Take a look at [pull_and_test_flamethrower.sh](../scripts/flamethrower/pull_and_test_flamethrower.sh)

## Flamethrower usage
```
Flamethrower.
    Usage:
      flame [-b BIND_IP] [-q QCOUNT] [-c TCOUNT] [-p PORT] [-d DELAY_MS] [-r RECORD] [-T QTYPE]
            [-o FILE] [-l LIMIT_SECS] [-t TIMEOUT] [-F FAMILY] [-f FILE] [-n LOOP] [-P PROTOCOL] [-M HTTPMETHOD]
            [-Q QPS] [-g GENERATOR] [-v VERBOSITY] [-R] [--class CLASS] [--qps-flow SPEC]
            [--dnssec] [--targets FILE]
            TARGET [GENOPTS]...
      flame (-h | --help)
      flame --version

    TARGET may be a hostname, an IP address, or a comma separated list of either. If multiple targets are specified,
    they will be sent queries in a strict round robin fashion across all concurrent generators. All targets must
    share the same port, protocol, and internet family.

    TARGET may also be the special value "file", in which case the --targets option needs to also be specified.

    Options:
      -h --help        Show this screen
      --version        Show version
      --class CLASS    Default query class, defaults to IN. May also be CH [default: IN]
      -b BIND_IP       IP address to bind to [defaults: 0.0.0.0 for inet, ::0 for inet6]
      -c TCOUNT        Number of concurrent traffic generators per process [default: 10]
      -d DELAY_MS      ms delay between each traffic generator's query [default: 1]
      -q QCOUNT        Number of queries to send every DELAY ms [default: 10]
      -l LIMIT_SECS    Limit traffic generation to N seconds, 0 is unlimited [default: 0]
      -t TIMEOUT_SECS  Query timeout in seconds [default: 3]
      -n LOOP          Loop LOOP times through record list, 0 is unlimited [default: 0]
      -Q QPS           Rate limit to a maximum of QPS, 0 is no limit [default: 0]
      --qps-flow SPEC  Change rate limit over time, format: QPS,MS;QPS,MS;...
      -r RECORD        The base record to use as the DNS query for generators [default: test.com]
      -T QTYPE         The query type to use for generators [default: A]
      -f FILE          Read records from FILE, one per row, QNAME TYPE
      -p PORT          Which port to flame [defaults: 53, 443 for DoH, 853 for DoT]
      -F FAMILY        Internet family (inet/inet6) [default: inet]
      -P PROTOCOL      Protocol to use (udp/tcp/dot/doh) [default: udp]
      -M HTTPMETHOD    HTTP method to use (POST/GET) when DoH is used [default: GET]
      -g GENERATOR     Generate queries with the given generator [default: static]
      -o FILE          Metrics output file, JSON format
      -v VERBOSITY     How verbose output should be, 0 is silent [default: 1]
      -R               Randomize the query list before sending [default: false]
      --targets FILE   Get the list of TARGETs from the given file, one line per host or IP
      --dnssec         Set DO flag in EDNS

     Generators:

       Using generator modules you can craft the type of packet or query which is sent.

       Specify generator arguments by passing in KEY=VAL pairs, where the KEY is a specific configuration
       key interpreted by the generator as specified below in caps (although keys are not case sensitive).

       static                  The basic static generator, used by default, has a single qname/qtype
                               which you can set with -r and -T. There are no KEYs for this generator.

       file                    The basic file generator, used with -f, reads in one qname/qtype pair
                               per line in the file. There are no KEYs for this generator.

       numberqname             Synthesize qnames with random numbers, between [LOW, HIGH], at zone specified with -r

                    LOW        An integer representing the lowest number queried, default 0
                    HIGH       An integer representing the highest number queried, default 100000

       randompkt               Generate COUNT randomly generated packets, of random size [1,SIZE]

                    COUNT      An integer representing the number of packets to generate, default 1000
                    SIZE       An integer representing the maximum size of the random packet, default 600

       randomqname             Generate COUNT queries of randomly generated QNAME's (including nulls) of random length
                               [1,SIZE], at base zone specified with -r

                    COUNT      An integer representing the number of queries to generate, default 1000
                    SIZE       An integer representing the maximum length of the random qname, default 255

       randomlabel             Generate COUNT queries in base zone, each with LBLCOUNT random labels of size [1,LBLSIZE]
                               Use -r to set the base zone to create the labels in. Queries will have a random QTYPE
                               from the most popular set.

                    COUNT      An integer representing the number of queries to generate, default 1000
                    LBLSIZE    An integer representing the maximum length of a single label, default 10
                    LBLCOUNT   An integer representing the maximum number of labels in the qname, default 5


     Generator Example:
        flame target.test.com -T ANY -g randomlabel lblsize=10 lblcount=4 count=1000
```


## Notes
A good option to compare dns query response times would be to use the cloudflare (1dot1dot1dot1.cloudflare-dns.com)


## Acknowledgements
Thanks to NetworkChuck and ns1 for the tutorials found in this file.