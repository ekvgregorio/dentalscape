import qrcode
import os

url = "https://dentalscapeiloilo.serbisyos.com/"

qr = qrcode.make(url)

folder_path = "C:/xampp2/htdocs/dentalscape/assets/img" 

file_path = os.path.join(folder_path, "dentalscapeqr.png")

qr.save(file_path)

print(f"QR code saved at {file_path}")

