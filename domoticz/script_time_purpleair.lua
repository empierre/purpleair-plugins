commandArray = {}
 
json = (loadfile "/home/pi/domoticz/scripts/lua/JSON.lua")()  -- For Linux
 
       --  API call - change IP to yours
       local config=assert(io.popen('curl "http://192.168.0.30/json"'))
       local Stringjson = config:read('*all')
       config:close()
       local jsonData = json:decode(Stringjson)
       
       pm2_5 = jsonData.pm2_5_atm
       pm1_0 = jsonData.pm1_0_atm
       pm10_0 = jsonData.pm10_0_atm

-- print (Stringjson)  -- debug json
-- print (pm) -- parsed json value


	-- change devide ID to yours
        commandArray[1]={['UpdateDevice']= 535 .. "|" .. pm1_0 .. "|0" }
        commandArray[2]={['UpdateDevice']= 532 .. "|" .. pm2_5 .. "|0" }
        commandArray[3]={['UpdateDevice']= 534 .. "|" .. pm10_0 .. "|0" }

return commandArray


