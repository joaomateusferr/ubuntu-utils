--Setup odbc environment (in this case locally within the script)
local driver = require"luasql.odbc"
local env = driver:odbc()

local conn, errorString = env:connect('ClickHouse DSN (Unicode)', 'user','password')
if conn == nil then
	error(errorString)
end

--execute the SQL statement
cursor, errorString = conn:execute([[SELECT duration, url FROM default.visits LIMIT 100]])

if cursor == nil then
	error(errorString)
end

row = cursor:fetch ({}, "a")
while row do
	print(string.format("duration: %s, url: %s", row.duration, row.url))
	-- reusing the table of results
	row = cursor:fetch (row, "a")
end

cursor:close()

print("----------")

status,errorString = conn:execute([[INSERT INTO visits VALUES (1, 12.5, 'http://example4.com', NOW())]])

if status == nil then
	error(errorString)
end

cursor, errorString = conn:execute([[SELECT duration, url FROM default.visits LIMIT 100]])

if cursor == nil then
	error(errorString)
end

row = cursor:fetch ({}, "a")
while row do
	print(string.format("duration: %s, url: %s", row.duration, row.url))
	-- reusing the table of results
	row = cursor:fetch (row, "a")
end

cursor:close()

-- close everything
conn:close()
env:close()