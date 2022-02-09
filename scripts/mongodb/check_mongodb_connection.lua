local mongo = require 'mongo'
local client = mongo.Client('mongodb://127.0.0.1')
local collection = client:getCollection('test', 'testCollection') -- database, collection

local personId = '' 
--local person = collection:findOne({}, {limit = 1})
local person = collection:findOne({_id = personId}, {})
print(person)

--[=====[
collection:insert{name = 'Rahul', age = 32}

for person in collection:find({}, {}):iterator() do
   print(person.name, person.age)
end
--]=====]