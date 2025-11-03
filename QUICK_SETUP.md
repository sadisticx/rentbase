# 🚀 Quick Setup Guide - RentBase CodeIgniter 4

## Step-by-Step Installation

### 1️⃣ Start XAMPP
- Open XAMPP Control Panel
- Start **Apache** and **MySQL** services

### 2️⃣ Create Database
Open phpMyAdmin (http://localhost/phpmyadmin) and:
- Create a new database named `rentbase`
- Or run this SQL command:
```sql
CREATE DATABASE rentbase;
```

### 3️⃣ Run Database Setup

**Option A: Using CodeIgniter CLI (Recommended)**
Open PowerShell/Command Prompt:
```powershell
cd c:\xampp\htdocs\codeigniter4-framework-68d1a58
php spark migrate
php spark db:seed RentbaseSeeder
```

**Option B: Using SQL File**
- Open phpMyAdmin
- Select `rentbase` database
- Click "Import" tab
- Choose file: `c:\xampp\htdocs\codeigniter4-framework-68d1a58\redo\database.sql`
- Click "Go"

### 4️⃣ Start the Application
In PowerShell/Command Prompt:
```powershell
cd c:\xampp\htdocs\codeigniter4-framework-68d1a58
php spark serve
```

You should see:
```
CodeIgniter 4.x.x Development Server
```

### 5️⃣ Open Your Browser
Navigate to: **http://localhost:8080**

### 6️⃣ Login with Test Accounts

| Role | Username | Password |
|------|----------|----------|
| **Owner** | owner1 | password |
| **Tenant** | tenant1 | password |
| **Employee** | employee1 | password |

---

## ✅ Verification Checklist

- [ ] XAMPP Apache is running (green indicator)
- [ ] XAMPP MySQL is running (green indicator)
- [ ] Database `rentbase` exists in phpMyAdmin
- [ ] Tables are created in `rentbase` database
- [ ] PHP CLI is accessible (run `php -v` to check)
- [ ] Development server is running on port 8080
- [ ] Browser opens http://localhost:8080 successfully
- [ ] Can see the login page
- [ ] Can login with test credentials

---

## 🔧 Common Issues & Solutions

### Issue: "php is not recognized"
**Solution**: Add PHP to Windows PATH
1. Find PHP path: `c:\xampp\php`
2. Add to System Environment Variables → PATH
3. Restart terminal

### Issue: "Database connection failed"
**Solution**: 
- Check MySQL is running in XAMPP
- Verify credentials in `app/Config/Database.php`

### Issue: "Port 8080 is already in use"
**Solution**: Use different port
```powershell
php spark serve --port=8081
```
Then visit: http://localhost:8081

### Issue: "Migration not found"
**Solution**: Check file exists at:
```
app/Database/Migrations/2025-10-30-000001_CreateRentbaseSchema.php
```

### Issue: "Seeder not found"
**Solution**: Check file exists at:
```
app/Database/Seeds/RentbaseSeeder.php
```

---

## 🎯 Quick Test

After setup, test these features:

### Test Registration
1. Go to http://localhost:8080/auth/register
2. Create a new account
3. Check if redirected to login with success message

### Test Login
1. Login with: **owner1** / **password**
2. Should redirect to Owner Dashboard
3. Check if username appears in navbar

### Test Logout
1. Click on username in navbar
2. Click "Logout"
3. Should redirect to login page

### Test Role-Based Access
1. Login as **tenant1**
2. Try to access: http://localhost:8080/owner/dashboard
3. Should be denied and redirected to login

---

## 📱 Access Points

| Page | URL |
|------|-----|
| **Login** | http://localhost:8080/ |
| **Register** | http://localhost:8080/auth/register |
| **Owner Dashboard** | http://localhost:8080/owner/dashboard |
| **Tenant Dashboard** | http://localhost:8080/tenant/dashboard |
| **Employee Dashboard** | http://localhost:8080/employee/dashboard |

---

## 🎨 Features to Test

✅ **Working Features:**
- User registration
- User login/logout
- Role-based redirection
- Session management
- CSRF protection
- Password hashing
- Tenant dashboard with room/parking info

📋 **Placeholder Features (Coming Soon):**
- Room management
- Tenant management
- Parking management
- Complaints system
- Payment system

---

## 📞 Need Help?

Check these files for detailed information:
- **TODO.md** - Complete task list and migration details
- **RENTBASE_README.md** - Full documentation
- **app/Config/Routes.php** - All available routes

---

**Ready to Go!** 🎉

Your RentBase system is now running on CodeIgniter 4 with:
- ✅ Secure authentication
- ✅ Role-based access control
- ✅ Modern UI with UIKit
- ✅ Database migrations
- ✅ MVC architecture
- ✅ CSRF protection
- ✅ Password hashing

Start developing by implementing the CRUD operations listed in `TODO.md`!
