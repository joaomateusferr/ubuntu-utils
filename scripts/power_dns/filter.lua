--[=====[
    sudo apt-get update
    sudo apt-get install luajit libmongoc-dev luarocks
    sudo luarocks install lua-mongo
    sudo luarocks install redis-lua

    sudo apt-get install pdns-recursor
    pdns_recursor --no-config --config | grep config-dir
    sudo nano /etc/powerdns/recursor.conf
    systemctl restart pdns-recursor
    log journalctl -u pdns-recursor -f

--]=====]

--[=====[
local Redis = require 'redis'
local Mongo = require 'mongo'

local RedisClient = Redis.connect('127.0.0.1', 6379)
local Mongoclient = Mongo.Client('mongodb://127.0.0.1')

local exists = RedisClient:exists('user-91')

if exists == false then
    
else

end--]=====]

malicious = dofile('/etc/powerdns/malicious_domains.lua')
    
function preresolve (dq)
    print('aaaaa')
    return false
end 
    
function postresolve (dq)
    print("postresolve == Got question for "..dq.qname:toString())

    local records = dq:getRecords()

    for k,v in pairs(records) do
        pdnslog(k.." "..v.name:toString().." "..v:getContent())

        if v.type == pdns.A then
            pdnslog("Changing content!")
            v:changeContent("0.0.0.0")
            v.ttl = 1
        end        
    end
    
    print('bbbbbb')

    dq:setRecords(records)
    return true
end
