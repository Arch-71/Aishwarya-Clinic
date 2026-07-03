# 🏥 Aishwarya Clinic Management System

> A web-based Clinic Management System developed using **PHP**, **MySQL**, **Bootstrap**, and **PDO** to simplify appointment booking, doctor management, and patient interactions.

---

## 📖 Overview

Aishwarya Clinic Management System is a lightweight Hospital/Clinic Management web application that enables patients to register, browse doctors, book appointments, and track booking history, while allowing doctors to manage their profiles and appointments.

The project focuses on reducing manual appointment scheduling by providing an easy-to-use online platform for both patients and doctors.

---

# ✨ Features

## 👨‍⚕️ Patient Module

- Patient Registration
- Secure Login
- Browse Available Doctors
- View Doctor Profiles
- Book Appointments
- View Appointment History
- Cancel / Track Booking Status

---

## 🩺 Doctor Module

- Doctor Registration
- Secure Login
- Manage Profile
- View Appointment Requests
- Manage Patient Bookings

---

## 📅 Appointment Management

- Online Appointment Booking
- Multiple Time Slots
- Disease Description
- Booking Status
    - Pending
    - Completed
    - Cancelled

---

## 🏥 Clinic Information

Different healthcare services are available including:

- ENT
- Laboratory
- Emergency Care
- Wellness
- Pediatrics
- General Consultation

---

## 🔒 Authentication

Separate authentication for

- Patients
- Doctors

using

- PHP Sessions
- Password Hashing
- Password Verification

---

# 🏗 System Architecture

```text
                    +-----------------------+
                    |      Web Browser      |
                    +-----------+-----------+
                                |
                                |
                        HTTP Requests
                                |
                                ▼
                    +-----------------------+
                    |      PHP Backend       |
                    +-----------------------+
                      |        |         |
                      |        |         |
          Authentication   Booking    Doctor
              Module        Module    Module
                      |        |         |
                      +--------+---------+
                               |
                               |
                         PDO Database Layer
                               |
                               ▼
                      +------------------+
                      |    MySQL (HMS)   |
                      +------------------+
```

---

# 🔄 System Flow

```text
Patient/Doctor
      │
      ▼
Login / Signup
      │
      ▼
Authentication
      │
      ▼
Dashboard
      │
      ├───────────────┐
      │               │
      ▼               ▼
View Doctors     Manage Profile
      │
      ▼
Book Appointment
      │
      ▼
Store Booking
      │
      ▼
MySQL Database
      │
      ▼
Booking History
```

---

# 🧩 Application Modules

## 1. Authentication Module

Responsible for

- Login
- Registration
- Session Management

Files

```
login.php
patient_signup.php
doctor_signup.php
```

---

## 2. Home Module

Displays

- Clinic Information
- Doctors List
- Services

Files

```
index.php
base.php
footer.php
```

---

## 3. Doctor Module

Allows

- Doctor Registration
- Doctor Login
- Doctor Profile

---

## 4. Appointment Module

Handles

- Booking
- Booking History
- Booking List

Files

```
booking.php
booking_history.php
booking_list.php
```

---

## 5. Healthcare Services Module

Contains pages for

- ENT
- Emergency
- Laboratory
- Wellness
- Pediatrics
- Medicine

---

# ⚙ Tech Stack

| Technology | Purpose |
|------------|---------|
| PHP | Backend Development |
| HTML5 | Page Structure |
| CSS3 | Styling |
| Bootstrap 5 | Responsive UI |
| JavaScript | Client-side Interaction |
| MySQL | Database |
| PDO | Secure Database Connectivity |
| XAMPP | Local Development Server |

---

# 📂 Project Structure

```
Aishwarya-Clinic
│
├── sql/
│   └── hms.sql
│
├── src/
│   ├── index.php
│   ├── login.php
│   ├── patient_signup.php
│   ├── doctor_signup.php
│   ├── booking.php
│   ├── booking_history.php
│   ├── booking_list.php
│   ├── db.php
│   ├── emergency.php
│   ├── lab.php
│   ├── ent.php
│   ├── med.php
│   ├── parent.php
│   ├── well.php
│   ├── edit.php
│   ├── style.css
│   └── images/
│
└── uploads/
```

---

# 🗄 Database Schema

The application uses a MySQL database named

```
hms
```

Main tables

### patients

Stores

- Patient ID
- Name
- Email
- Phone
- Password

---

### doctors

Stores

- Doctor ID
- Name
- Email
- Phone
- Specialization
- Experience
- Image
- Password

---

### bookings

Stores

- Booking ID
- Patient ID
- Doctor ID
- Patient Details
- Disease
- Time Slot
- Appointment Time
- Booking Status
- Created Date

---

### Entity Relationship

```text
Patient
   │
   │1
   │
   │
   ▼
Bookings
   ▲
   │
   │Many
Doctor
```

---

# 📡 Core Functionality

### Patient Registration

Creates a patient account using encrypted passwords.

---

### Doctor Registration

Allows doctors to create clinic profiles.

---

### Appointment Booking

Patients can

- Select Doctor
- Choose Slot
- Enter Disease
- Book Appointment

---

### Booking History

Patients can view previous appointments along with their booking status.

---

# 🚀 Getting Started

## Prerequisites

- PHP 8+
- MySQL
- XAMPP / WAMP

---

## Installation

Clone repository

```bash
git clone https://github.com/Arch-71/Aishwarya-Clinic.git
```

Move project to

```
xampp/htdocs
```

Import

```
sql/hms.sql
```

Start

- Apache
- MySQL

Visit

```
http://localhost/Aishwarya-Clinic/src
```

---

# 🔑 Configuration

Update

```
src/db.php
```

```php
$host='localhost';
$db='hms';
$user='root';
$pass='';
```

---

# 🔒 Security Features

- Password Hashing
- Password Verification
- PDO Prepared Statements
- Session Authentication

---

# 📈 Future Enhancements

- Admin Dashboard
- Online Payments
- Email Notifications
- SMS Appointment Reminders
- Prescription Management
- Electronic Health Records (EHR)
- Doctor Availability Calendar
- Telemedicine Integration
- Medical Reports Upload
- AI-based Disease Recommendation

---

# 🧪 Testing

Tested modules

- Login
- Registration
- Appointment Booking
- Booking History
- Doctor Listing
- Session Management

---

# 📦 Deployment

Recommended deployment

- Apache Server
- PHP 8+
- MySQL 8+
- Linux/Windows Hosting
- XAMPP (Development)

---

# 🤝 Contributing

Contributions are welcome.

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to GitHub
5. Open a Pull Request

---

# 📄 License

This project is intended for educational and academic purposes.

---


