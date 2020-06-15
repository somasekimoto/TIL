import requests
bin_ip = "https://httpbin.org/ip"
r = requests.get(bin_ip)

myapp_ip = "http://13.114.122.230/ip"
s = requests.get(myapp_ip)

print(r.json() == s.json())



