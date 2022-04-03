local Socket = require 'socket'

local Host, Port = '127.0.0.1', 80
local Tcp = assert(Socket.tcp())

local Path = '/'
local Host = 'localhost'
local Body = 'Funcionou!!!!!'

local Request =  'POST '..Path..' HTTP/1.1\r\n'
               ..'Host: '..Host..'\r\n'
               ..'User-Agent: Joao-POST\r\n'
               ..'Content-Type: text/plain\r\n'
               ..'Content-Length: '..#Body..'\r\n'
               ..'\r\n'
               .. Body

Tcp:connect(Host, Port);
Tcp:setoption('tcp-nodelay',true)
Tcp:setoption('reuseaddr',true)
Tcp:send(Request)

local RequestStatus = ''
local RequestHeader = ''
local RequestBody = ''
local RequestBodyStart = 0

local I = 1

while true do

  local SocketS, SocketStatus, SocketPartial = Tcp:receive()
  local Line = SocketS or SocketPartial

  if I == 1 then

    local StatusLine = Line
    local Tokens = {}

    for Value in StatusLine:gmatch("([^ ]+)") do
      table.insert(Tokens, Value) 
    end

    if table.getn(Tokens) > 2 then
      RequestStatus  =  Tokens[2]
    end

  end

  if string.len(Line) == 0 then
    RequestBodyStart = I
  end

  if (RequestBodyStart ~= 0 and I > RequestBodyStart) or (RequestBodyStart ~= 0 and I > RequestBodyStart and string.len(Line) ~= 0) then

    if string.len(RequestBody) == 0 then
      RequestBody = Line
    else
      RequestBody = RequestBody .. '\r\n' .. Line
    end
    
  end
      
  if SocketStatus == "closed" then -- use KeepAlive Off
    break
  end

  I = I + 1

end

Tcp:close()

if RequestStatus == '200' then
  print(RequestBody)
end