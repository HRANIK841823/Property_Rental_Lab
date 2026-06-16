# Property Rental Cyber Security Lab (Safe Training Edition)

This project is an intentionally insecure LOCAL training lab for learning:
- SQL Injection (educational example)
- Reflected XSS
- Stored XSS (demonstration only)

⚠️ Use only in an isolated local environment (XAMPP/Docker/VM).
⚠️ Do NOT expose this project to the public Internet.
⚠️ This lab intentionally does NOT include mechanisms for stealing or exfiltrating cookies or credentials.

## Setup
1. Create a MySQL database named `property_lab`.
2. Import `sql/property_lab.sql`.
3. Put the folder into XAMPP htdocs.
4. Browse to http://localhost/property-rental-lab/

## Stored XSS Demo
The comments page intentionally renders comments without HTML escaping.
You can test payloads like:
<script>alert('Stored XSS')</script>

Compare with using htmlspecialchars() to fix it.
