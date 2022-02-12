import requests, traceback, json, socket;

def SendService(host, page, data, port=80, protocol='http'):
    try:
        envelope = {};
        for key,value in data.items():
            envelope[key] = value;
        url = protocol + "://" + host + ":"+ port +"/secanalysis/service/" + page;
        headers = {"Content-type": "application/json", "User-Agent":"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.97 Safari/537.36"}
        buffer_connection = requests.post(url, json=envelope, headers=headers, verify=False);
        return buffer_connection.json();
    except Exception as e:
        traceback.print_exc();
        return None;