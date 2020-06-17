import json
import urllib.request


def hello(event, context):
    bin_ip = "https://httpbin.org/ip"
    r = urllib.request.Request(bin_ip)

    myapp_ip = "http://13.114.122.230/ip"
    s = urllib.request.Request(myapp_ip)

    with urllib.request.urlopen(r) as res_r:
        r_result = json.loads(res_r.read())

    with urllib.request.urlopen(s) as res_s:
        s_result = json.loads(res_s.read())


    response = {
        "statusCode": 200,
        "r": r_result, 
        "s": s_result,
        "result": r_result == s_result
    }

    return response
