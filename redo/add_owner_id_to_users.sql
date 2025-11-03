-- Add owner_id column to users table for employee assignments
ALTER TABLE users 
ADD COLUMN owner_id INT NULL AFTER role,
ADD FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE SET NULL;

-- Update employee1 to be assigned to owner1 (id = 1)
UPDATE users 
SET owner_id = 1 
WHERE username = 'employee1' AND role = 'employee';
