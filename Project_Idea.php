<?php 
// 1. Die Layout-Zentrale laden (Sie beinhaltet bereits Header, Nav und Footer!)
// Achte auf das große "I" bei "Includes", damit XAMPP die Datei sicher findet
require_once 'Includes/layout.php'; 

// 2. Den Zwischenspeicher für den HTML-Inhalt aktivieren
ob_start(); 
?>

<!-- HIER KOMMT NUR NOCH DER REINE INHALT FÜR DEN MAIN-BEREICH -->
<h2>Project Vision & Features</h2>

<p>
    While the main terminal gives you the strategic overview, this section focuses on the core mechanics 
    and artistic drive that make <strong>The Shadows within: A B5 Legacy</strong> a unique experience. 
    This is not just another space adventure—it is a tactical narrative simulation designed to test 
    your loyalty, resourcefulness, and morality in the year 2264.
</p>

<p>
    <strong>Unpredictable Narrative & Branching Paths:</strong> The galaxy does not move in straight lines. 
    Every decision you make resonates through the sectors. Choosing between telepathic extraction 
    or humane isolation, or deciding whether to extort or diplomatize with imperial figures like Londo Mollari, 
    will fundamentally lock or unlock entire fleets, changing the political layout of the Interstellar Alliance 
    and leading to vastly different epilogues.
</p>

<p>
    <strong>The Liburnia – A Living Hybrid Prototype:</strong> Your home in the deep black is the <em>Liburnia</em>, 
    an Earth-Ipsha hybrid vessel. This prototype isn't just a set piece; its biomechanical cores and organic circuitry 
    react to your modifications. You will manage a crew where distinct cultural traits collide—balancing the raw force 
    of Drazi tech with precise Ipsha engineering, while your crew copes with everything from encrypted BabCom arrays to 
    sleeping on rigid, angled Minbari berths.
</p>

<p>
    <strong>Character-Driven Synergies:</strong> The crew members are not static spreadsheet variables. They possess 
    unique curiosities and dynamics. You will witness the ancient, intellectual Technomage Meldor becoming fascinated 
    by the gritty, profane beauty of manual human relay systems, guided by the vibrant enthusiasm 
    of the Ipsha engineer Sha’In. Your interactions directly influence their trust index, proving that surviving 
    the shadows requires respecting both cosmic philosophy and cold, hard iron.
</p>

<p>
    <strong>Behind the Terminal:</strong> Born during a period of professional reorientation, this game is fueled by 
    absolute dedication to J. Michael Straczynski's legendary universe. Developed entirely in personal free time, 
    the project bridges unresolved canon threads—honoring the sacrifices of heroes like Marcus Cole and Lennier to deliver 
    a heavy, atmospheric Sci-Fi experience built by a fan, for the fans.
</p>

<?php 
// 3. Den Inhalt aus dem Zwischenspeicher in eine Variable packen
$seitenInhalt = ob_get_clean(); 

// 4. Das Layout aufrufen und den Titel der Seite + den Inhalt übergeben
renderLayout("B5 Legacy - Project Vision & Features", $seitenInhalt); 
?>
