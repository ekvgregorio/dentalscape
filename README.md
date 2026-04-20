# DentalScape: Digital Health Records and Appointment System

## Description
DentalScape is a web-based healthcare management system designed to streamline patient record management, appointment scheduling, and medical report handling. 
The system emphasizes security, efficiency, and accessibility, making it suitable for clinics and small healthcare facilities.

## Purpose
This system aims to:
- Improve patient data management
- Secure sensitive medical information
- Simplify appointment scheduling
- Support healthcare staff (Admin, Doctor, Secretary)

## Features

1. User Authentication System  
   - Role-based access control (Admin, Doctor, Secretary)

2. OTP Verification  
   - Secure login using email/SMS OTP

3. Appointment Management  
   - Schedule, update, and track appointments

4. Dashboard Reports  
   - Monthly summaries and analytics

5. Medical Records Management  
   - Store and retrieve patient information

6. Encrypted Medical Reports  
   - AES-256 encryption for sensitive files

7. Login Tracking  
   - IP address, device information, and geolocation logging

8. Security Protection  
   - Anti-brute force login system  
   - Login history monitoring  

## Technologies Used

Frontend:
- HTML
- CSS
- Bootstrap 4
- JavaScript

Backend:
- PHP (Core system)
- Python (Geolocation services)

Database:
- MySQL (via XAMPP)

Other Tools:
- PHPMailer / Twilio (OTP authentication)

## Installation Guide

1. Clone the Repository
git clone https://github.com/ekvgregorio/dentalscape.git

2. Move to XAMPP Directory
Copy the project folder to:
C:\xampp\htdocs\

3. Setup Database
- Open phpMyAdmin
- Create a new database (e.g., dentalscape)
- Import the provided .sql file

4. Start Server
- Open XAMPP
- Start Apache and MySQL

5. Run the System
Open your browser:
http://localhost/dentalscape

## Usage

Admin:
- Manage users
- View reports and system logs

Doctor:
- Access patient records
- View medical history

Secretary:
- Manage appointments
- Assist in scheduling patients

## Security Features
- AES-256 encryption for medical data
- OTP-based authentication system
- Anti-brute force protection (5 failed attempts = 1-hour lock)
- Secure file storage system
- Login history tracking (IP, device, location)

## Future Improvements
- Mobile application integration
- Real-time notifications (SMS/Email)
- Advanced analytics dashboard
- Biometric authentication (mobile fingerprint support)
- Cloud backup integration (Firebase / replication)

## Authors
- Therence Hilcee Defante – Project Manager / Documentation Head
- Ericka Kate Gregorio – Software Developer
- Chery Nicole Pastilloso – Documentation Specialist

## License
This project is for educational and academic purposes only.

## Notes
- Ensure database credentials are properly configured in PHP files
- Configure email/SMS API keys for OTP functionality
