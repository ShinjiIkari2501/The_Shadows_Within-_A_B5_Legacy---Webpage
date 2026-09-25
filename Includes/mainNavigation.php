<mainNav>
    <fieldset>
        <?php
        // Prüfen, ob in der Session vermerkt ist, dass der User eingeloggt ist
        if (isset($_SESSION['eingeloggt']) && $_SESSION['eingeloggt'] === true): 
        ?>
                        <!-- STATUS 1: CREW-MITGLIED IST EINGELOGGT -->
            <legend>System Status:</legend>
            <div style="text-align: left; font-size: 0.9em; padding: 5px 0;">
                <!-- KORREKTUR: Kugel bleibt grün, Text leuchtet jetzt in lesbarem B5-Gold -->
                <p style="color: #ff9900; text-shadow: 0 0 5px rgba(255, 153, 0, 0.6); font-weight: bold; margin: 0; padding: 0; text-align: left; font-family: 'B5Station', Arial, sans-serif;">
                    🟢 UPLINK ACTIVE
                </p>
                <p style="margin: 10px 0 5px 0; padding: 0; text-align: left; font-family: 'B5Station', Arial, sans-serif; font-size: 0.85em; color: hsl(0, 9%, 98%);">
                    Commander:<br>
                    <strong style="color: #ff9900; text-shadow: 0 0 4px rgba(255, 153, 0, 0.4);"><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
                </p>
            </div>
            
            <!-- Der Logout-Button schickt den User an eine logout.php, die wir gleich erstellen -->
            <form action="logout.php" method="post" style="margin-top: 10px;">
                <button type="submit" name="logoutSubmit" class="formButton" style="background-color: rgba(255, 51, 51, 0.2); border: 1px solid #ff3333; color: #ff3333; text-shadow: 0 0 4px #ff3333;">
                    Logout
                </button>
            </form>

        <?php else: ?>
            <!-- STATUS 2: ANONYMER ZUGRIFF / FORMULAR ANZEIGEN -->
            <form action="login.php" method="post">
                <legend>Login:</legend>
                
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" placeholder="Username" required>
                
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" placeholder="Password" required>
                
                <input type="text" id="eMail" name="eMail" tabindex="-1" autocomplete="off">
                
                <button type="submit" name="loginSubmit" class="formButton">Login</button>
            </form>
        <?php endif; ?>
    </fieldset>
    
    <ul> 
        <li><a href="Index.php">Main Terminal</a></li>
        <li><a href="Project_Idea.php">Project Idea</a></li>
        <li><a href="Story_Timeline.php">Story & Timeline</a></li>
        <li><a href="Characters.php">Characters</a></li>
        <li><a href="Gameplay.php">Gameplay</a></li>
        <li><a href="Pictures.php">Pictures</a></li>
        <li><a href="Videos.php">Videos</a></li>
        <li><a href="Project_Future.php">Project Future</a></li>
    </ul> 
</mainNav>
