# AS Garment POS System

A complete offline-ready **Point of Sale system** for a garment shop with both **retail and wholesale** modes, built using PHP and MySQL. Designed for use in shops like **AS Garment - Bazar Talwaran, Rawalpindi**.

## Features

- **User Roles**: Admin and Cashier
- **Inventory Management**: Add/Edit/Delete products
- **Sales Processing**: Cart system with automatic stock deduction
- **Sales Reports**: Daily/Monthly reports and total sales summary
- **PDF Invoice Generation** (FPDF)
- **Printable Receipts**
- **Product Stock Log** (future-ready)
- Bootstrap UI with responsive design

## Technologies Used

- **PHP (Core)**
- **MySQL (XAMPP)**
- **Bootstrap 5**
- **JavaScript + jQuery**
- **FPDF** for PDF invoices
- **Chart.js** for sales analytics
- **HTML/CSS**

## Installation (Local with XAMPP)

1. **Download or clone the project**:
   ```bash
   git clone https://github.com/abdullahaamir13/garment-pos.git
   ```

Place it in: C:/xampp/htdocs/

2. Import database:
- Open phpMyAdmin
- Create a DB named asgarmentpos
- Import the SQL file: asgarmentpos.sql

3. Start XAMPP:
- Start Apache and MySQL

4. Open the project in browser:
http://localhost/garment-pos/login.php

## Demo Credentials
| Role    | Username | Password     |
|---------|----------|--------------|
| Admin   | admin    | admin123     |
| Cashier | cashier  | cashier123   |

## Project Folder Structure
![Project Folder Structure](https://github.com/user-attachments/assets/dcd433fa-cbc7-4afb-8949-4aa771ab6404)

## Admin Dashboard Preview
- View total sales
- Top selling products
- Low stock alerts
- PDF or Excel export ready

## Future Improvements
- Password encryption (bcrypt)
- Cloud backup & sync option
- GST/VAT tax calculation
- Mobile-responsive sale view
- Product photo and barcode scanner support
- Laravel version for enterprise scale

## Contribution
Feel free to fork. Pull requests are welcome.

## License
Copyright (c) 2025 Abdullah Aamir. All rights reserved. See COPYRIGHT for details. Permission is required for any use, modification, or distribution.

## Developed by Abdullah Aamir
   Contact: abdullahaamir977@gmail.com

   LinkedIn: linkedin.com/in/abdullah-aamir/
