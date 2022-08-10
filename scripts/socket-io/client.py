import sys
import socketio

def main():

    try:
        Host = sys.argv[1]
        Port = sys.argv[2]
        Type = sys.argv[3]
        Json = sys.argv[4]

        ConnectionString = "http://"+ Host + ":" + Port

        SocketIO = socketio.Client()
        SocketIO.connect(ConnectionString)
        SocketIO.emit(Type, Json)
        SocketIO.disconnect()

    except:
        print("Error sending message - argvs -> " + str(sys.argv))
        
main()