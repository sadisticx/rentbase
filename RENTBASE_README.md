# RentBase - Apartment Management System (CodeIgniter 4)

A comprehensive apartment management system built with CodeIgniter 4, featuring role-based access control for owners, tenants, and employees.

## 🚀 Features

### Authentication System
- ✅ User registration with role selection (Owner/Tenant/Employee)
- ✅ Secure login with password hashing (bcrypt)
- ✅ Session management
- ✅ Role-based dashboard redirection
- ✅ CSRF protection enabled

### Owner Features
- ✅ Dashboard with management overview
- 📋 Room management (placeholder)
- 👥 Tenant management (placeholder)
- 🚗 Parking slot management (placeholder)
- 💬 View complaints (placeholder)

### Tenant Features
- ✅ Dashboard with personal information
- ✅ View room details and parking slot
- 💬 Manage complaints (placeholder)
- 💳 Make payments (placeholder)

### Employee Features
- ✅ Dashboard
- 💬 Manage complaints (placeholder)

## 📋 Prerequisites

- PHP 8.1 or higher
- MySQL 5.7 or higher
- XAMPP/WAMP/LAMP (or any PHP development environment)
- Composer (for CodeIgniter dependencies)

## 🛠️ Installation

### 1. Clone or Copy the Project
Ensure the project is located at:
```
c:\xampp\htdocs\codeigniter4-framework-68d1a58
```

### 2. Install Dependencies
```bash
cd c:\xampp\htdocs\codeigniter4-framework-68d1a58
composer install
```

### 3. Environment Configuration
Copy the environment file:
```bash
copy env .env
```

Edit `.env` file and configure:
```ini
CI_ENVIRONMENT = development

database.default.hostname = localhost
database.default.database = rentbase
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
```

### 4. Database Setup

#### Option A: Using CodeIgniter Migrations (Recommended)
```bash
# Run migrations to create tables
php spark migrate

# Seed the database with sample users
php spark db:seed RentbaseSeeder
```

#### Option B: Using SQL File
Import the SQL file located at `redo/database.sql` into your MySQL database:
```bash
mysql -u root -p rentbase < redo/database.sql
```

### 5. Set Permissions (Linux/Mac)
```bash
chmod -R 777 writable/
```

### 6. Start the Development Server
```bash
php spark serve
```

### 7. Access the Application
Open your browser and navigate to:
```
http://localhost:8080
```

## 👥 Default Test Accounts

After running the seeder, you can login with:

| Role     | Username   | Password  |
|----------|-----------|-----------|
| Owner    | owner1    | password  |
| Tenant   | tenant1   | password  |
| Employee | employee1 | password  |

## 📁 Project Structure

```
app/
├── Controllers/
│   ├── Auth.php           # Authentication (login, register, logout)
│   ├── Owner.php          # Owner dashboard and management
│   ├── Tenant.php         # Tenant dashboard and services
│   └── Employee.php       # Employee dashboard
├── Models/
│   └── UserModel.php      # User authentication and data
├── Views/
│   ├── layouts/
│   │   ├── header.php     # Common header with CSS/JS
│   │   ├── footer.php     # Common footer
│   │   └── navbar.php     # Navigation bar component
│   ├── auth/
│   │   ├── login.php      # Login form
│   │   └── register.php   # Registration form
│   ├── owner/             # Owner views (dashboard, rooms, tenants, etc.)
│   ├── tenant/            # Tenant views (dashboard, complaints, payments)
│   └── employee/          # Employee views (dashboard)
├── Filters/
│   └── AuthFilter.php     # Authentication filter
├── Config/
│   ├── Database.php       # Database configuration
│   ├── Routes.php         # Application routes
│   └── Filters.php        # Filter configuration
└── Database/
    ├── Migrations/        # Database schema migrations
    └── Seeds/             # Sample data seeders

public/
├── css/
│   └── style.css          # Custom CSS styles
└── js/
    └── main.js            # Custom JavaScript

redo/                      # Original PHP files (legacy)
└── database.sql           # Database schema and sample data
```

## 🔒 Security Features

- ✅ **CSRF Protection**: Enabled globally for all POST requests
- ✅ **Password Hashing**: Using bcrypt algorithm
- ✅ **XSS Protection**: Using `esc()` helper for all output
- ✅ **SQL Injection Prevention**: Using Query Builder and prepared statements
- ✅ **Session Security**: Secure session handling by CodeIgniter
- ✅ **Input Validation**: Server-side validation for all forms
- ✅ **Authentication Filter**: Route protection based on user roles

## 🎨 UI Framework

- **UIKit 3.21.6**: Modern front-end framework
- **Franken UI 1.1.0**: Enhanced UI components
- **Dark Mode**: Pre-configured dark theme
- **Responsive Design**: Mobile-friendly layouts

## 🔄 Routes

### Public Routes
- `GET /` - Redirects to login
- `GET /auth/login` - Login page
- `POST /auth/processLogin` - Process login
- `GET /auth/register` - Registration page
- `POST /auth/processRegister` - Process registration
- `GET /auth/logout` - Logout user

### Owner Routes (Protected)
- `GET /owner/dashboard` - Owner dashboard
- `GET /owner/rooms` - Manage rooms
- `GET /owner/tenants` - Manage tenants
- `GET /owner/parking` - Manage parking
- `GET /owner/complaints` - View complaints

### Tenant Routes (Protected)
- `GET /tenant/dashboard` - Tenant dashboard
- `GET /tenant/complaints` - Manage complaints
- `GET /tenant/payments` - Make payments

### Employee Routes (Protected)
- `GET /employee/dashboard` - Employee dashboard

## 📊 Database Schema

### Tables
1. **users** - User accounts (id, username, password, role, created_at)
2. **user_profiles** - User personal information
3. **rooms** - Room details and assignments
4. **parking_slots** - Parking slot management
5. **complaints** - Tenant complaints tracking
6. **payments** - Payment records

### Relationships
- Users → User Profiles (One-to-One)
- Users (Owner) → Rooms (One-to-Many)
- Users (Tenant) → Rooms (One-to-One)
- Users (Tenant) → Parking Slots (One-to-One)
- Users (Tenant) → Complaints (One-to-Many)
- Users (Tenant) → Payments (One-to-Many)
- Rooms → Complaints (One-to-Many)

## 🛠️ Development Commands

```bash
# Run migrations
php spark migrate

# Rollback migrations
php spark migrate:rollback

# Seed database
php spark db:seed RentbaseSeeder

# Create new migration
php spark make:migration CreateTableName

# Create new model
php spark make:model ModelName

# Create new controller
php spark make:controller ControllerName

# Clear cache
php spark cache:clear
```

## 🐛 Troubleshooting

### Database Connection Error
- Ensure MySQL is running in XAMPP
- Check database credentials in `app/Config/Database.php`
- Verify database `rentbase` exists

### CSRF Token Mismatch
- Clear browser cookies and cache
- Check if CSRF is properly enabled in `app/Config/Filters.php`

### 404 Page Not Found
- Ensure `.htaccess` file exists in `public/` directory
- Check if mod_rewrite is enabled in Apache
- Verify routes in `app/Config/Routes.php`

### Session Issues
- Check writable permissions on `writable/session/` directory
- Clear session files in `writable/session/`

## 📈 Future Enhancements

See `TODO.md` for a complete list of pending features and enhancements.

Key areas for development:
- Complete CRUD operations for all entities
- Additional models (Room, Parking, Complaint, Payment)
- AJAX form submissions
- Real-time notifications
- Payment receipt generation (PDF)
- Email notifications
- Dashboard statistics and charts
- Advanced search and filtering

## 📚 Documentation

- [CodeIgniter 4 Documentation](https://codeigniter.com/user_guide/)
- [UIKit Documentation](https://getuikit.com/docs/)
- [Franken UI Documentation](https://www.franken-ui.dev/)

## 📝 License

This project is for educational purposes.

## 👨‍💻 Migration Notes

This system was migrated from standalone PHP files to CodeIgniter 4 framework. The original files are preserved in the `redo/` folder for reference.

### Key Changes
1. ✅ Implemented MVC architecture
2. ✅ Replaced inline SQL with Query Builder
3. ✅ Added input validation and security measures
4. ✅ Implemented session-based authentication
5. ✅ Created reusable view components
6. ✅ Added CSRF protection
7. ✅ Organized routes properly
8. ✅ Implemented password hashing

---

**Last Updated**: October 30, 2025  
**Framework Version**: CodeIgniter 4  
**PHP Version**: 8.1+  
**Database**: MySQL 5.7+
