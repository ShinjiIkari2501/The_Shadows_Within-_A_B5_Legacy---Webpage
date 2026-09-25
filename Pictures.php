<?php 
// 1. Die Layout-Zentrale laden (Sie beinhaltet bereits Header, Nav und Footer!)
// Achte auf das große "I" bei "Includes", damit XAMPP die Datei sicher findet
require_once 'Includes/layout.php'; 

// 2. Den Zwischenspeicher für den HTML-Inhalt aktivieren
ob_start(); 
?>

<!-- HIER KOMMT NUR NOCH DER REINE INHALT FÜR DEN MAIN-BEREICH -->
<h2>Visual Telemetry & Screenshots</h2>
<h3>Sensor Log Array / Sector Overview</h3>

<p>
    Browse through the encrypted visual logs captured across Babylon 5, the frozen Drasi moons, and the command decks of the Liburnia prototype.
</p>

<!-- START DES BILDER-RASTERS -->
<div class="media-grid">
            
    <div class="media-card">
        <div class="media-placeholder">
            <span>B5_SECTOR_BROWN_SCAN.PNG</span>
        </div>
        <h5>Sector Brown Lower Levels</h5>
    </div> <!-- KORREKTUR: Einrückung und Abschluss hier korrigiert -->

    <div class="media-card">
        <div class="media-placeholder">
            <span>DRASI_MOON_INFILTRATION.PNG</span>
        </div>
        <h5>Drasi Outpost Phalanx</h5>
    </div>

    <div class="media-card">
        <div class="media-placeholder">
            <span>LIBURNIA_CARGO_BAY.PNG</span>
        </div>
        <h5>The Biomechanical Engine Room</h5>
    </div>

    <div class="media-card">
        <div class="media-placeholder">
            <span>GENEVA_psi_HQ_RUINS.PNG</span>
        </div>
        <h5>Geneva Resistance Hub</h5>
    </div>

</div>
<!-- ENDE DES BILDER-RASTERS -->

<?php 
// 3. Den Inhalt aus dem Zwischenspeicher in eine Variable packen
$seitenInhalt = ob_get_clean(); 

// 4. Das Layout aufrufen und den Titel der Seite + den Inhalt übergeben
renderLayout("B5 Legacy - Visual Telemetry", $seitenInhalt); 
?>
