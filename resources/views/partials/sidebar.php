<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="../index3.html" class="brand-link text-center">
        <span class="brand-text font-weight"><?php echo config('APP_NAME') ?></span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item has-treeview">
                    <a href="/dashboard" class="nav-link <?php echo current_route('/dashboard') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="" class="nav-link">
                        <i class="nav-icon fas fa-file"></i>
                        <p>
                            Page
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="/user" class="nav-link <?php echo current_route('/user') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            User
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="" class="nav-link">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>
                            Setting
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>