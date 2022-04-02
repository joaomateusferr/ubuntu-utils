local socket = require "socket"
local host, port = '127.0.0.1', 80
local tcp = assert(socket.tcp())
local Path = '/'
local Host = 'localhost'
local Body = 'Funcionou!!!!!'

local Request =  'POST '..Path..' HTTP/1.1\r\n'
               ..'Host: '..Host..'\r\n'
               --..'Connection: Keep-Alive\r\n'
               ..'Content-Type: text/plain\r\n'
               ..'Content-Length: '..#Body..'\r\n'
               ..'\r\n'
               .. Body

for i=1,3 do

  tcp:connect(host, port);
  tcp:send(Request);
               
  while true do
      local s, status, partial = tcp:receive()
      local l = s or partial
      print(l)
      if status == "closed" then -- use KeepAlive Off
        break
      end
  end

  tcp:close()

end