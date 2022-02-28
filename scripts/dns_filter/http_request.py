import requests

url = 'http://127.0.0.1'
r = requests.post(url, "oi")
print(r.text)