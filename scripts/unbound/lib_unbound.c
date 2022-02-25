//https://unbound.docs.nlnetlabs.nl/en/latest/developer/b-tutorial/index.html

#include <stdio.h>      
#include <arpa/inet.h>
#include <unbound.h>
#include <time.h>

int main(void)
{
        struct ub_ctx* ctx;
        struct ub_result* result;
        int retval;
        float seconds;
        clock_t start, end;

        start = clock();

        ctx = ub_ctx_create();
        if(!ctx) {
                printf("error: could not create unbound context\n");
                return 1;
        }

        retval = ub_resolve(ctx, "www.nlnetlabs.nl", 1, 1, &result);

        if(retval != 0) {
                printf("resolve error: %s\n", ub_strerror(retval));
                return 1;
        }

        if(result->havedata)
                printf("The address is %s\n",
                        inet_ntoa(*(struct in_addr*)result->data[0]));

        ub_resolve_free(result);
        ub_ctx_delete(ctx);

        end = clock();
        seconds = (float)(end - start) / CLOCKS_PER_SEC;

        printf("Time in milliseconds %f\n", seconds*1000);

        return 0;
}

// gcc -o program p.c -I/usr/local/include -L/usr/local/lib -lunbound
// ./program