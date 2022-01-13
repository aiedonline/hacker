import requests, traceback;

def SendService(host, page, data):
    try:
        envelope = {};
        for key,value in data.items():
            envelope[key] = value;
        url = "http://" + host + "/secanalysis/service/" + page;
        buffer_connection = requests.post(url, json=envelope);
        #print(buffer_connection.text);
        return buffer_connection.json();
    except Exception as e:
        traceback.print_exc();
        return None;