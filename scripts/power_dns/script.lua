profile = {}

malicious=newDS()
malicious:add(dofile('/etc/powerdns/malicious_domains.lua'))

function getprofile()

    profile = {}
    profile.id = 1
    profile.name='test'
end
    
function preresolve (dq)

    getprofile()

    if malicious:check(dq.qname) then
        print('on the list')
    else
        print('not')
    end

    return false
end 
    
function postresolve (dq)

    print("postresolve == Got question for "..dq.qname:toString())
    
    local records = dq:getRecords()

    for k,v in pairs(records) do
        pdnslog(k.." "..v.name:toString().." "..v:getContent())

        if v.type == pdns.A then
            pdnslog("Changing content v4!")
            v:changeContent("0.0.0.0")
            v.ttl = 1
        elseif v.type == pdns.AAAA then
            pdnslog("Changing content v6!")
            v:changeContent("::1")
            v.ttl = 1
        elseif v.type == pdns.CNAME then
            pdnslog("Changing content CNAME!")
            v:changeContent(".")
            v.ttl = 1
        end        
    end
    
    dq:setRecords(records)

    return true
end