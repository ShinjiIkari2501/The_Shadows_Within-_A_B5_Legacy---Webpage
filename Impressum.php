<?php 
// 1. Die Layout-Zentrale laden (Startet auch automatisch die Session)
require_once 'Includes/layout.php'; 

// 2. Den Zwischenspeicher für den HTML-Inhalt aktivieren
ob_start(); 
?>

<!-- AB HIER SCHREIBST DU DEN REINEN INHALT FÜR DEN MAIN-BEREICH -->
<h2>Terminal Impressum</h2>
<h3>Legal Entity Identification / Station Command Registry</h3>

<p>
    According to international grid regulations and regional telemedia protocols, 
    this section provides the mandatory legal identification data for the creator of this communication array.
</p>

<!-- DAS TAKTISCHE DATENBLATT IM GLAS-LOOK -->
<div style="max-width: 600px; margin: 30px auto; background-color: rgba(13, 20, 59, 0.4); border: 1px solid rgba(96, 172, 243, 0.3); border-radius: 6px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5); padding: 20px; text-align: left;">
    
    <h4 style="color: #ff9900; margin-top: 0; text-shadow: 0 0 4px rgba(255, 153, 0, 0.5); font-family: 'B5Station', Arial, sans-serif; border-bottom: 1px solid rgba(96, 172, 243, 0.2); padding-bottom: 5px; margin-bottom: 15px;">
        🪪 INFORMATION ACCORDING TO § 5 TMG
    </h4>

    <table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 0.95em; color: hsl(0, 9%, 90%); line-height: 1.8;">
        <tr>
            <td style="width: 35%; font-weight: bold; color: #60acf3; padding: 6px 0; vertical-align: top;">Station Commander:</td>
            <td style="padding: 6px 0;">ShinjiIkari2501 aka Daniel Baedorf</td>
        </tr>
        <tr>
            <td style="font-weight: bold; color: #60acf3; padding: 6px 0; vertical-align: top;">Postal Coordinates:</td>
            <td style="padding: 6px 0;">
                [Karl-Russell-Straße 6]<br>
                [56070 Koblenz]<br>
                Germany / Sol Sector
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold; color: #60acf3; padding: 6px 0; vertical-align: top;">Sub-Space Com-Link:</td>
            <td style="padding: 6px 0;">
                Email: [ikari.kun01@gmail.com]<br>
                Phone: [Available upon email request]
            </td>
        </tr>
    </table>

    <div style="border-top: 1px dashed rgba(96, 172, 243, 0.2); margin: 15px 0; padding-top: 15px;"></div>

    <h4 style="color: #ff9900; font-family: 'B5Station', Arial, sans-serif; font-size: 0.9em; margin-top: 0; margin-bottom: 8px;">
        📢 CLASSIFICATION NOTICE
    </h4>
    <p style="font-size: 0.85em; line-height: 1.4; color: #aaa; margin: 0;">
        This interface is operated strictly as a private, non-commercial fan infrastructure. 
        There are no commercial transaction vectors or premium telemetry tiers associated with this grid. 
        In accordance with international telemetry laws, a physical address is provided solely for legal contact accountability.
    </p>
</div>

<p style="text-align: center; margin-top: 30px;">
    <a href="Index.php" style="color: #ff9900; text-decoration: none; font-weight: bold;">➔ Return to Main Terminal</a>
</p>

<?php 
// 3. Den Inhalt aus dem Zwischenspeicher holen und in eine Variable packen
$seitenInhalt = ob_get_clean(); 

// 4. Das Layout aufrufen und den Titel + Inhalt übergeben
renderLayout("B5 Legacy - Impressum Command Registry", $seitenInhalt); 
?>
