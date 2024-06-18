<table class="menu-container" border="0">
    <tr>
        <td style="padding:10px" colspan="2">
            <table border="0" class="profile-container">
                <tr>
                    <td width="30%" style="padding-left:20px">
                        <img src="../img/user.png" alt="" width="100%" style="border-radius:50%">
                    </td>
                    <td style="padding:0px;margin:0px;">
                        <?php
                        $name = $_SESSION['name'];
                        ?>
                        <p class="profile-title"> <?php echo $name; ?></p>
                        <p class="profile-subtitle">
                            <?php
                            $email = $_SESSION['email'];
                            ?>
                            <?php echo $email; ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <a href="../logout.php">
                            <input type="button" value="Log out" class="logout-btn btn-primary-soft btn">
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr class="menu-row">
        <!-- <td class="menu-btn menu-icon-dashbord menu-active menu-icon-dashbord-active">
            <a href="index.php" class="non-style-link-menu non-style-link-menu-active">
                <div>
                    <p class="menu-text">Dashboard</p>
                </div>
            </a>
        </td> -->
        <td class="menu-btn menu-icon-dashbord">
            <a href="index.php" class="non-style-link-menu">
                <div>
                    <p class="menu-text">Dashboard</p>
                </div>
            </a>
        </td>
    </tr>

    <tr class="menu-row">
        <td class="menu-btn menu-icon-appoinment">
            <a href="appointment.php" class="non-style-link-menu">
                <div>
                    <p class="menu-text">My Appointments</p>
                </div>
            </a>
        </td>
    </tr>

    <tr class="menu-row">
        <td class="menu-btn menu-icon-schedule">
            <a href="session-dates.php" class="non-style-link-menu">
                <div>
                    <p class="menu-text">My Session Dates</p>
                </div>
            </a>
        </td>
    </tr>

    <tr class="menu-row">
        <td class="menu-btn menu-icon-patient">
            <a href="patients.php" class="non-style-link-menu">
                <div>
                    <p class="menu-text">My Patients</p>
                </div>
            </a>
        </td>
    </tr>


    <tr class="menu-row">
        <td class="menu-btn menu-icon-settings">
            <a href="settings.php" class="non-style-link-menu">
                <div>
                    <p class="menu-text">Settings</p>
            </a></div>
        </td>
    </tr>
</table>