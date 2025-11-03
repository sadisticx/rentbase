# Owner-Tenant Association Fix

## Problem
All tenants were visible to all owners, regardless of who created them.

## Solution
Added `owner_id` column to `users` table to associate tenants with their owner.

## SQL to Run in phpMyAdmin

```sql
-- Step 1: Add owner_id column to users table
ALTER TABLE users 
    ADD COLUMN owner_id INT(11) UNSIGNED NULL AFTER role,
    ADD CONSTRAINT fk_users_owner 
        FOREIGN KEY (owner_id) REFERENCES users(id) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE;

-- Step 2: Verify the column was added
DESCRIBE users;

-- Step 3: (Optional) Update existing tenants to assign them to an owner
-- Replace '1' with the actual owner's user ID
UPDATE users 
SET owner_id = 1 
WHERE role = 'tenant' AND owner_id IS NULL;
```

## What Changed

### 1. Database Structure
- Added `owner_id` column to `users` table
- Foreign key constraint ensures referential integrity
- CASCADE on delete means if owner is deleted, their tenants are also deleted

### 2. UserModel.php
- Added `owner_id` to `$allowedFields`
- Updated `getTenantsWithProfiles()` to accept `$ownerId` parameter and filter by it

### 3. Owner Controller
- **tenants()**: Now passes owner ID to only fetch their tenants
- **addTenant()**: Sets `owner_id` when creating new tenant
- **rooms()**: Filters tenant dropdown to only show owner's tenants
- **parking()**: Filters tenant dropdown to only show owner's tenants

## How It Works Now

### When Owner Creates a Tenant
```php
'owner_id' => $this->session->get('user_id')  // Current owner's ID
```

### When Owner Views Tenants
```php
$tenants = $this->userModel->getTenantsWithProfiles($ownerId);
// Only returns tenants where owner_id matches
```

### When Owner Assigns Tenants to Rooms/Parking
```php
$tenants = $this->userModel->where('role', 'tenant')
                           ->where('owner_id', $ownerId)
                           ->findAll();
// Dropdown only shows their own tenants
```

## Testing

1. **Run the SQL** in phpMyAdmin (rentbase database)

2. **Login as Owner 1**
   - Create a tenant (e.g., tenant1)
   - Tenant gets `owner_id = 1`

3. **Login as Owner 2**
   - Create a tenant (e.g., tenant2)
   - Tenant gets `owner_id = 2`

4. **Login as Owner 1 again**
   - Go to Tenants page
   - Should ONLY see tenant1
   - Should NOT see tenant2

5. **Assign to Room/Parking**
   - Dropdown should only show owner1's tenants
   - Cannot assign other owner's tenants

## Benefits

✅ **Data Isolation**: Each owner only sees their own tenants
✅ **Security**: Owners cannot access or modify other owners' tenants
✅ **Cascading Delete**: Deleting an owner removes all their tenants
✅ **Clean Dropdowns**: Room/parking assignment only shows relevant tenants
✅ **Database Integrity**: Foreign key constraint prevents orphaned records

## What Happens to Existing Tenants?

Run this SQL to assign existing tenants to an owner:

```sql
-- Get all owner IDs
SELECT id, username FROM users WHERE role = 'owner';

-- Assign all existing tenants to owner with ID = 1
UPDATE users 
SET owner_id = 1 
WHERE role = 'tenant' AND owner_id IS NULL;
```

Or manually assign them based on who created them.

---

**Status**: Ready to test after running SQL
**Files Modified**: 
- `app/Models/UserModel.php`
- `app/Controllers/Owner.php` (4 methods updated)
