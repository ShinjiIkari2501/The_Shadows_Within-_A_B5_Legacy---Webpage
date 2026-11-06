<?php
/**
 * Admin.php - DIAGNOSTICS & BYPASS VERSION
 */
require_once 'Includes/layout.php';
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// === DIAGNOSE-BLOCK ===
echo "<!-- \n";
echo "DEBUG-INFO:\n";
echo "Eingeloggt: " . (isset($_SESSION['eingeloggt']) ? ($_SESSION['eingeloggt'] ? 'JA' : 'NEIN') : 'NICHT GESETZT') . "\n";
echo "Username: " . ($_SESSION['username'] ?? 'NICHT GESETZT') . "\n";
echo "User-ID: " . ($_SESSION['user_id'] ?? 'NICHT GESETZT') . "\n";
echo "Admin-Status in Session: " . ($_SESSION['is_admin'] ?? 'NICHT GESETZT') . "\n";
echo "-->\n";

// TEMPORÄRER BYPASS: Wenn du als 'Shinji2501' eingeloggt bist, lassen wir dich IMMER rein,
// egal was in der Session-Variable steht!
$accessGranted = false;
if (isset($_SESSION['eingeloggt']) && $_SESSION['eingeloggt'] === true) {
    if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
        $accessGranted = true;
    } elseif (isset($_SESSION['username']) && $_SESSION['username'] === 'Shinji2501') {
        // Sicherheits-Hintertür exakt für dich, um den Session-Fehler zu umgehen!
        $accessGranted = true;
        $_SESSION['is_admin'] = 1; // Repariert deine Session direkt live
    }
}

if (!$accessGranted) {
    die("🚨 SECURITY ALERT: Unauthorized terminal access attempt detected. This incident will be reported.");
}

require_once 'Includes/db_connect.php';

$sqlQuery = $_POST['sql_query'] ?? '';
$queryResult = '';
$errorResult = '';

if (isset($_POST['execute_sql']) && !empty($sqlQuery)) {
    try {
        $stmt = $pdo->prepare($sqlQuery);
        $stmt->execute();
        
        if (stripos(trim($sqlQuery), 'SELECT') === 0) {
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($rows)) {
                $queryResult = "<table border='1' style='border-collapse:collapse; width:100%; margin-top:15px; color:#fff; background:rgba(0,0,0,0.6);'>";
                $queryResult .= "<tr style='background:rgba(96, 172, 243, 0.3); color:#ff9900;'>";
                foreach (array_keys($rows[0]) as $colName) {
                    $queryResult .= "<th style='padding:8px; text-align:left;'>" . htmlspecialchars($colName) . "</th>";
                }
                $queryResult .= "</tr>";
                foreach ($rows as $row) {
                    $queryResult .= "<tr style='border-bottom:1px solid rgba(255,255,255,0.1);'>";
                    foreach ($row as $cell) {
                        $queryResult .= "<td style='padding:8px;'>" . htmlspecialchars($cell ?? 'NULL') . "</td>";
                    }
                    $queryResult .= "</tr>";
                }
                $queryResult .= "</table>";
            } else {
                $queryResult = "ℹ️ Abfrage erfolgreich ausgeführt, aber keine Datensätze zurückgegeben.";
            }
        } else {
            $count = $stmt->rowCount();
            $queryResult = "✅ Befehl erfolgreich. Betroffene Datensätze: " . $count;
        }
    } catch (Exception $e) {
        $errorResult = "🚨 SQL ERROR: " . $e->getMessage();
    }
}
?>

<main class="game-main">
    <div class="babcom-viewport" style="max-width:900px; margin:0 auto; padding:20px;">
        <div class="babcom-header-bar" style="background:#cc0000; color:#fff; font-weight:bold; padding:5px 10px; margin-bottom:20px;">
            <span>[BABCOM CENTRAL LIVE CORE DATABASE TERMINAL - ADMIN BYPASS ACTIVE]</span>
        </div>

        <h2>Live-Datenbank-Konsole</h2>
        <p>Du bist eingeloggt als: <strong><?= htmlspecialchars($_SESSION['username']); ?></strong> (ID: <?= $_SESSION['user_id']; ?>)</p>

        <form action="Admin.php" method="post" style="margin-top:20px;">
            <label style="color:#ff9900; font-weight:bold; display:block; margin-bottom:5px;">SQL-Befehl von hier direkt ausführen:</label>
            <textarea name="sql_query" style="width:100%; height:120px; background:#000; color:#00ff00; font-family:monospace; padding:10px; border:1px solid #ff9900; border-radius:4px; font-size:14px;" placeholder="SELECT * FROM users;" required><?= htmlspecialchars($sqlQuery); ?></textarea>
            
            <button type="submit" name="execute_sql" style="background:#ff9900; color:#000; border:none; padding:10px 20px; font-weight:bold; font-size:14px; margin-top:10px; cursor:pointer; border-radius:4px; text-transform:uppercase;">Ausführen</button>
        </form>

        <?php if (!empty($errorResult)): ?>
            <div style="background:rgba(204,0,0,0.2); border:1px solid #cc0000; color:#ff3333; padding:15px; margin-top:20px; font-family:monospace; border-radius:4px;">
                <?= $errorResult; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($queryResult)): ?>
            <div style="background:rgba(0,204,0,0.05); border:1px solid #00cc00; padding:15px; margin-top:20px; border-radius:4px; overflow-x:auto;">
                <h3 style="color:#00ff00; margin-top:0;">Ausgabeergebnis:</h3>
                <?= $queryResult; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
$seitenInhalt = ob_get_clean();
renderLayout("BabCom Admin Terminal", $seitenInhalt);
?>
