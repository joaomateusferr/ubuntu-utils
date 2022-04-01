local socket = require "socket"
local client = socket.connect('127.0.0.1',80)
local Path = '/'
local Host = 'test'
local Body = 'Funcionou!!!!!'

local Request =  'POST '..Path..' HTTP/1.1\r\n'
               ..'Host: '..Host..'\r\n'
               --..'Connection: Keep-Alive\r\n'
               ..'Content-Type: text/plain\r\n'
               ..'Content-Length: '..#Body..'\r\n'
               ..'\r\n'
               .. Body

if client then
    client:send(Request)
    s, status, partial = client:receive('*a')
    print(s)
    client:close()
end