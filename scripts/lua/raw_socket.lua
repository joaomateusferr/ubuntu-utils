local socket = require "socket"
local client = socket.connect('127.0.0.1',80)
local Path = '/'
local Host = 'test'
local Body = 'test=1'

local Request =  'POST '..Path..' HTTP/1.1\r\n'
               ..'Host: '..Host..'\r\n'
               ..'Connection: Keep-Alive\r\n'
               ..'Content-Type: text/plain\r\n'
               ..'Content-Length: '..#Body..'\r\n'
               ..'\r\n'
               .. Body

print(client)
if client then
    client:send(Request)
    local s, status, partial = client:receive(1024)
    print(partial)
end