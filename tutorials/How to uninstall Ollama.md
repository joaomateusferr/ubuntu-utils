# How to uninstall Ollama

## Stop the system process:
```bash
sudo systemctl stop ollama
```
## Remove the Service File:
```bash
sudo systemctl disable ollama
```

## Delete the Ollama Binary:
```bash
sudo rm $(which ollama)
```

## Remove Ollama User:
```bash
sudo rm -r /usr/share/ollama
```

## Remove Downloaded Models:
```bash
sudo userdel ollama sudo groupdel ollama
```
