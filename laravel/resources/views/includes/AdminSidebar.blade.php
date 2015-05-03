<aside>
    <div id="sidebar"  class="nav-collapse ">
        <ul class="sidebar-menu" id="nav-accordion">
            <li class="sub-menu">
                <a href="javascript:;"<?php echo
                    (in_array($_controllerAction, array('admin.adduser', 'admin.edituser', 'admin.listusers', 'admin.addrole', 'admin.editroles',  'admin.listroles'))
                        ? ' class="active"'
                        : '');
                ?>>
                    <i class="fa fa-shield"></i> <span>Super Admin</span>
                </a>
                <ul class="sub">
                    <li class="<?php echo
                    ($_controllerAction == 'admin.addrole'
                        ? ' active'
                        : '');
                    ?>">
                        <a href="/Admin/addRole">Add Role</a>
                    </li>
                    <li class="<?php echo
                        ($_controllerAction == 'admin.adduser'
                            ? ' active'
                            : '');
                    ?>">
                        <a href="/Admin/addUser">Add User</a>
                    </li>
                    <li class="<?php echo
                        ($_controllerAction == 'admin.listroles'
                            ? ' active'
                            : '');
                    ?>">
                        <a href="/Admin/listRoles">List Roles</a>
                    </li>
                    <li class="<?php echo
                        ($_controllerAction == 'admin.listusers'
                            ? ' active'
                            : '');
                    ?>">
                        <a href="/Admin/listUsers">List Users</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</aside>
