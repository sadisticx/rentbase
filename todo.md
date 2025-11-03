


## System Features

- [x] Login and Logout for Owners, Tenants, and Employees.
- [x] Multi-tenancy isolation (owner_id filtering).
- [x] Modern UI redesign with Bootstrap 5.3.
- [x] Reply system for complaints (all roles).
- [x] Responsive navigation for mobile devices.

## Owner Features:

- [x] View tenant details for owned rooms.
- [x] Create and manage tenant profiles.
- [x] Create and manage employee profiles with owner assignment.
- [x] Allot and manage parking slots.
- [x] View and reply to complaints from owned rooms.
- [x] View room details.
- [x] Create and manage tenant rooms.
- [x] Dashboard with statistics (rooms, tenants, complaints, parking).

## Tenant Features:

- [x] View allotted parking slot.
- [x] Pay maintenance fees.
- [x] Submit, view, and reply to complaints.
- [x] View personal and room details.
- [x] Dashboard with statistics (room info, payments count, active complaints, parking).
- [x] View recent payments history.

## Employee Features:

- [x] View and manage complaints submitted by tenants.
- [x] Update complaint status (open, in_progress, closed).
- [x] Reply to complaints.
- [x] Dashboard with complaint statistics.
- [x] View assigned owner information.

## Pending Tasks:

- [ ] Execute SQL migration: `add_replies_table.sql` (for complaint replies functionality).
- [ ] Execute SQL migration: `add_owner_id_to_users.sql` (for employee-owner assignment).
- [ ] Add additional room fields (monthly_rate, occupancy_count, max_occupancy) if needed for production.
- [ ] Implement lease/contract viewing feature for tenants.
- [ ] Add email notifications for complaints and payments.
- [ ] Implement payment status tracking and approval workflow.
