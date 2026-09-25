<?php 
// 1. Die Layout-Zentrale laden
require_once 'Includes/layout.php'; 

// 2. Den Zwischenspeicher für den HTML-Inhalt aktivieren
ob_start(); 
?>

<!-- HIER KOMMT NUR NOCH DER REINE INHALT FÜR DEN MAIN-BEREICH -->
<h2>Sub-Space Video Feeds</h2>
<h3>Active Sub-Channels / Tactical Playback</h3>
        
<p>
    Access real-time cinematic playback, engine diagnostic sequences, and narrative transmission logs from the frontlines.
</p>

<!-- START DES VIDEO-RASTERS -->
<div class="media-grid">
    
    <div class="video-card">
        <div class="video-placeholder">
            <!-- ZUKÜNFTIGER YOUTUBE-IFRAME ODER VIDEO-TAG HIER REIN -->
            <span>PLAYBACK_TACTICAL_TEASER.MKV</span>
        </div>
        <h5>Project Announcement Trailer</h5>
    </div>

    <div class="video-card">
        <div class="video-placeholder">
            <span>DIAGNOSTIC_HYPERSPACE_CORE.MKV</span>
        </div>
        <h5>Liburnia Engine Boot Sequence</h5>
    </div>

</div>
<!-- ENDE DES VIDEO-RASTERS -->

<?php 
// 3. Den Inhalt aus dem Zwischenspeicher in eine Variable packen
$seitenInhalt = ob_get_clean(); 

// 4. Das Layout aufrufen und den Titel der Seite + den Inhalt übergeben
renderLayout("B5 Legacy - Sub-Space Video Feeds", $seitenInhalt); 
?>
