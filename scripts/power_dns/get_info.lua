--[=====[
    Dependencies
    
    sudo apt-get update
    sudo apt-get install luajit libmongoc-dev luarocks
    sudo luarocks install lua-mongo
    sudo luarocks install redis-lua
--]=====]


Redis = require 'redis'
Mongo = require 'mongo'

RedisClient = Redis.connect('127.0.0.1', 6379)
Mongoclient = Mongo.Client('mongodb://127.0.0.1')

local Exists = RedisClient:exists('user-91')
print(Exists)