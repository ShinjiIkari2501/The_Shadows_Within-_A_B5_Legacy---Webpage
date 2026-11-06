prepare("SELECT user_id, password_hash FROM users WHERE username = :username");
            $stmt->execute([':username' => $username]);
            $userRecord = $stmt->fetch();

            // Verify cryptographically hashed password matrix
            if ($userRecord && password_verify($password, $userRecord['password_hash'])) {
                $loginSuccess = true;
                $authenticatedUserId = (int)$userRecord['user_id'];
            }
        } else {
            throw new Exception("Database connection module missing.");
        }
    } catch (Exception $e) {
        // Log background engine failures silently
        error_log('[B5 Auth Error] ' . $e->getMessage());
    }

    // AUTHENTICATION EVALUATION AND REDIRECTION ROUTING
    if ($loginSuccess === true && $authenticatedUserId !== null) {
        $_SESSION['eingeloggt'] = true;
        $_SESSION['username']   = $username;
        $_SESSION['user_id']    = $authenticatedUserId; // Crucial mapping parameter for player_slots sync

        $_SESSION['flash_message'] = "🔒 UPLINK ESTABLISHED: Security Clearance Granted. Terminal Sync Complete.";
        header("Location: Index.php");
        exit();
    } else {
        $_SESSION['flash_message_error'] = "⚠️ ACCESS DENIED: Invalid Security Credentials or Unknown Sector Entity.";
        header("Location: Index.php");
        exit();
    }

} else {
    header("Location: Index.php");
    exit();
}
?>
