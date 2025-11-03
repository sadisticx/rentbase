<!-- Top Navigation -->
<nav class="top-nav">
    <div class="logo-section">
        <div class="logo-icon">
            <i class="bi bi-building"></i>
        </div>
        <span class="logo-text">RENTBASE</span>
    </div>
    
    <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
        <i class="bi bi-list"></i>
    </button>
    
    <div class="nav-menu" id="navMenu">
        <a href="<?= base_url('owner/dashboard') ?>" class="nav-link <?= ($active ?? '') === 'dashboard' ? 'active' : '' ?>">Home</a>
        <a href="<?= base_url('owner/rooms') ?>" class="nav-link <?= ($active ?? '') === 'rooms' ? 'active' : '' ?>">Rooms</a>
        <a href="<?= base_url('owner/tenants') ?>" class="nav-link <?= ($active ?? '') === 'tenants' ? 'active' : '' ?>">Tenants</a>
        <a href="<?= base_url('owner/employees') ?>" class="nav-link <?= ($active ?? '') === 'employees' ? 'active' : '' ?>">Employees</a>
        <a href="<?= base_url('owner/parking') ?>" class="nav-link <?= ($active ?? '') === 'parking' ? 'active' : '' ?>">Parking</a>
        <a href="<?= base_url('owner/complaints') ?>" class="nav-link <?= ($active ?? '') === 'complaints' ? 'active' : '' ?>">Complaints</a>
    </div>
    
    <div class="user-section">
        <button class="logout-btn" onclick="location.href='<?= base_url('auth/logout') ?>'">
            <i class="bi bi-box-arrow-right"></i> Logout
        </button>
        <div class="user-avatar">
            <?= strtoupper(substr($username, 0, 1)) ?>
        </div>
    </div>
</nav>

<script>
function toggleMobileMenu() {
    const navMenu = document.getElementById('navMenu');
    navMenu.classList.toggle('active');
}

// Close menu when clicking outside
document.addEventListener('click', function(event) {
    const navMenu = document.getElementById('navMenu');
    const toggle = document.querySelector('.mobile-menu-toggle');
    
    if (!navMenu.contains(event.target) && !toggle.contains(event.target)) {
        navMenu.classList.remove('active');
    }
});

// Close menu when clicking on a link
document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', function() {
        document.getElementById('navMenu').classList.remove('active');
    });
});
</script>
