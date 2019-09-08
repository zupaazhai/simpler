<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="/dashboard" class="brand-link text-center">
        <span class="brand-text font-weight"><?php echo config('APP_NAME') ?></span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item has-treeview">
                    <a href="/dashboard" class="nav-link <?php echo current_route('/dashboard') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="/page" class="nav-link <?php echo current_route('/page') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-file"></i>
                        <p>Page</p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="/media" class="nav-link <?php echo current_route('/media') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-photo-video"></i>
                        <p>Media</p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="/asset" class="nav-link <?php echo current_route('/asset') ? 'active' : '' ?>"">
                        <i class="nav-icon fas fa-copy"></i>
                        <p>Asset</p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="/user" class="nav-link <?php echo current_route('/user') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-user"></i>
                        <p>User</p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="/setting" class="nav-link <?php echo current_route('/setting') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>Setting</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>