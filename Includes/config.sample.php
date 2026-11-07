<?php
/**
 * config.sample.php - template for the MySQL connection data.
 *
 * HOW TO USE (on the hosting, e.g. InfinityFree):
 *  1. Copy this file to Includes/config.php (same folder, new name).
 *  2. Replace the four values with the data from the hosting panel (MySQL Databases).
 *  3. Never upload config.php to GitHub (it is listed in .gitignore).
 */
return [
    'host' => 'sqlXXX.infinityfree.com',   // "MySQL Hostname" from the panel (NOT localhost)
    'name' => 'if0_12345678_b5',            // database name
    'user' => 'if0_12345678',               // MySQL user name
    'pass' => 'your-mysql-password',        // MySQL password (the one of the database, not of the account)
];
