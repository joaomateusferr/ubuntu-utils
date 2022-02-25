#include <sys/types.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <stdio.h>
#include <netdb.h>
#include <stdlib.h>
#include <strings.h>
#include <arpa/inet.h>

main()
{
	int sock, length, tam;
	struct sockaddr_in name;
	char buf[1024], envio[1024];

        /* Cria o socket de comunicacao */
	sock = socket(AF_INET, SOCK_DGRAM, 0);
	if(sock<0) {
	/*
	/- houve erro na abertura do socket
	*/
		perror("opening datagram socket");
		exit(1);
	}
	/* Associa */
	name.sin_family = AF_INET;
	name.sin_addr.s_addr = INADDR_ANY;
	name.sin_port = 0;
	if (bind(sock,(struct sockaddr *)&name, sizeof name ) < 0) {
		perror("binding datagram socket");
		exit(1);
	}
        /* Imprime o numero da porta */
	length = sizeof(name);
	if (getsockname(sock,(struct sockaddr *)&name, &length) < 0) {
		perror("getting socket name");
		exit(1);
	}
	printf("Socket port #%d\n",ntohs(name.sin_port));
    
	printf("\nChat:\n\n");
    
    recvfrom(sock, buf, sizeof buf, 0, (struct sockaddr*)&name,&tam);
	
    if (fork() == 0) {
        while(1){
            setbuf(stdin, NULL);
            recvfrom(sock, buf, sizeof buf, 0, (struct sockaddr*)&name,&tam);
            printf("Outro: %s\n",buf);
        }
    }
    else {
        while(1){
            setbuf(stdin, NULL);
            scanf("%[^\n]s%*s",&envio);
            if (sendto(sock, envio, sizeof envio, 0, (struct sockaddr*)&name, sizeof name) < 0) {
                perror("sending datagram message");
            }
        }
    }
    close(sock);
    exit(0);
}
