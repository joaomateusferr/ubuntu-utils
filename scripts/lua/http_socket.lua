local url = "http://127.0.0.1/"
local http = require "socket.http"
local parameters = 'test'
local body, c, h = http.request(url, parameters)
print(body) 