<?php include 'includes/header.php'?>
        <!-- Dropdown menu module -->
            <div class="dropdown ml-auto">
                <button type="button" class="btn btn-danger dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php echo $_SESSION['username']; ?>
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="#">Completed List</a>
                    <a class="dropdown-item" href="#">My Profile</a>
                    <a class="dropdown-item" href="/resetpw.php">Reset Password</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>
        </div>
    </div>
</nav>
