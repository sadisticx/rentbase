-- Fix owner_id column data type and add foreign key
-- The column already exists but may have wrong type (UNSIGNED vs signed)

-- Step 1: Drop the column if it exists with wrong type
ALTER TABLE `users` DROP COLUMN `owner_id`;

-- Step 2: Add it back with correct type matching users.id (int(11))
ALTER TABLE `users` 
ADD COLUMN `owner_id` int(11) DEFAULT NULL AFTER `role`,
ADD KEY `owner_id` (`owner_id`);

-- Step 3: Add foreign key constraint for data integrity
ALTER TABLE `users`
ADD CONSTRAINT `users_owner_fk` 
FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) 
ON DELETE SET NULL;

-- Step 4: Update existing tenants to be owned by owner1 (id=1)
UPDATE `users` 
SET `owner_id` = 1 
WHERE `role` = 'tenant' AND `owner_id` IS NULL;
