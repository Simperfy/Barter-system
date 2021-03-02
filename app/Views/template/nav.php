<nav>
        <div>
            <li><a href="<?= route_to('home') ?>">Home</a></li>
        </div>
        <div class="inline">
            <?php if (session()->get('isLoggedIn') === true): ?>
                <li><a href="<?= route_to('logout') ?>">Logout</a></li>
            <?php else: ?>
                <li><a href="<?= route_to('signup') ?>">Register</a></li>
                <div class="spacer"></div>
                <li class="login"><a href="<?= route_to('login') ?>">Login</a></li>
            <?php endif; ?>
        </div>
</nav>