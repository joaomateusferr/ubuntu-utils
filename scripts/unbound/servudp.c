#include <sys/types.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <stdio.h>
#include <netdb.h>
#include <stdlib.h>
#include <strings.h>
#include <arpa/inet.h>
#include <unbound.h>

#define PORT 10001

main()
{
	int sock, length, tam;
	struct sockaddr_in name;
	char buf[1024], envio[1024];
	struct ub_ctx* ctx;
    struct ub_result* result;
    int retval;

    //Cria o socket de comunicacao
	sock = socket(AF_INET, SOCK_DGRAM, 0);

	//houve erro na abertura do socket
	if(sock<0) {
		perror("opening datagram socket");
		exit(1);
	}

	//Associa
	name.sin_family = AF_INET;
	name.sin_addr.s_addr = INADDR_ANY;
	name.sin_port = htons( PORT );
	if (bind(sock,(struct sockaddr *)&name, sizeof name ) < 0) {
		perror("binding datagram socket");
		exit(1);
	}

    //Imprime o numero da porta

	length = sizeof(name);
	if (getsockname(sock,(struct sockaddr *)&name, &length) < 0) {
		perror("getting socket name");
		exit(1);
	}
	printf("Socket port #%d\n",ntohs(name.sin_port));


    recvfrom(sock, buf, sizeof buf, 0, (struct sockaddr*)&name,&tam);
	
    while(1){

        setbuf(stdin, NULL);
        recvfrom(sock, buf, sizeof buf, 0, (struct sockaddr*)&name,&tam);
        printf("Outro: %s\n",buf);
		
        ctx = ub_ctx_create();
        if(!ctx) {
                printf("error: could not create unbound context\n");
                return 1;
        }

        retval = ub_resolve(ctx, buf, 1, 1, &result);

        if(retval != 0) {
                printf("resolve error: %s\n", ub_strerror(retval));
                return 1;
        }

        if(result->havedata)
                printf("The address is %s\n",
                        inet_ntoa(*(struct in_addr*)result->data[0]));

        ub_resolve_free(result);
        ub_ctx_delete(ctx);

    }
    
    close(sock);
    exit(0);
}
