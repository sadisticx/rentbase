<nav class="uk-navbar-container uk-margin" uk-navbar>
    <div class="uk-navbar-left">
        <a class="uk-navbar-item uk-logo" href="#">RentBase - <?= esc($role ?? 'User') ?></a>
    </div>
    <div class="uk-navbar-right">
        <ul class="uk-navbar-nav">
            <li>
                <a href="#">
                    <span uk-icon="user"></span> <?= esc($username) ?>
                </a>
                <div class="uk-navbar-dropdown">
                    <ul class="uk-nav uk-navbar-dropdown-nav">
                        <li><a href="<?= base_url('auth/logout') ?>"><span uk-icon="sign-out"></span> Logout</a></li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</nav>
