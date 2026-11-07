<?php
// Storyscript.php - EAS Liburnia Engine (Initial Setup & Prologue)

return [
    'SETUP' => [
        'title' => '======================================================
=== THE SHADOWS WITHIN - INITIAL SETUP             ===
======================================================',
        'location' => 'SYSTEM INITIALIZATION GRID',
        'script' => <<<'EOD'
SELECT YOUR BACKGROUND SYSTEM:

1 = Intelligence (Ex-Agent)
    -> High Analysis Focus (95)
    -> Tactical hacking advantages
    
2 = Underworld (Smuggler)
    -> Starting stash increased (+30,000 Credits)
    -> Underground bribery opportunities

[SYSTEM]: Ready to start your journey?
EOD
    ],

    1 => [
        'title' => 'SCENE 1: PROLOGUE – THE ECHO OF THE PAST',
        'location' => "EXT. MOON IN NO MAN'S LAND - NIGHT",
        'script' => <<<'EOD'
================================================================================
THE SHADOWS WITHIN – FILM SCRIPT
BASED ON THE LOGICAL ALLIANCE MATRIX
================================================================================

A lonely, dying campfire crackles in the cold wind. The amber glow throws 
long, shadow-like contours against the weathered, ancient stone monuments 
of a forgotten celestial body. The heavy, bittersweet scent of burning 
G'Quaneth incense hangs in the air.

CITIZEN G'KAR sits motionlessly by the fire. His heavy, patterned robe is dusty, 
his single, intact eye reflecting the matte play of the flames. He looks deep 
into the embers as his deep, rough voice breaks the absolute silence of the night.

G'KAR
(low, conjuring)
The universe is in a constant change, unstoppable and blind to the suffering 
of those who cross its paths. We believed we had won the light of peace... 
We believed the great shadows of the past had been vanished into eternal blackness 
with the end of the war. But the darkness does not sleep, it only waits. It creeps 
unseen through the cold spaces between the stars, reshapes itself and slowly becomes... 
a threat that threatens to consume us all.

G'Kar pauses. A cold gust of wind sweeps through the ruins, making the flames 
flare up wildly. He does not turn his gaze away, but his voice rises with a calm, 
knowing certainty.

G'KAR
But perhaps you should step out of the darkness, sit down and warm yourself 
by my fire... for nothing provides more comfort in these times than the light.

Out of the deep shadows of the ruins, two figures emerge silently. ELRIC and 
the young TECHNOMAGE MELDOR step into the circle of the fire. Their long, heavy 
garments wave in the wind, their faces hidden under deep hoods. Their staffs tap 
dully on the stony ground as they sit down in silence by the Narn at the fire. 
Elric looks at G'Kar with serious, ageless eyes.

ELRIC
We seek no comfort, G'Kar. We seek clarity. A creeping cold is spreading in secret. 
There are painful losses in the ranks of our order... brothers who vanished without 
a trace from the protection of our fortresses. We feel a shift in power, a great, 
nameless evil that goes hand in hand with this emptiness. But wherever we look – 
the veil remains silent.

G'Kar closes his eye for a long moment. The steady crackling of the fire is the 
only sound in the wide expanse. When he opens it again, it holds the unyielding 
calm of a seer.

G'KAR
This darkness threatens us all, Elric... and deep in your souls you have long known 
that you can no longer carry this weight alone. Your order turned away from the peoples, 
but isolation will not save you from the coming storm. But perhaps... perhaps one 
of you is enough to drag the truth out of hiding. In that distant place they need 
your help – just as you need theirs to fathom the fate of your brothers.

Silence falls over the fire. Elric looks at Meldor for a long time. Then he turns 
to G'Kar and signals to the young Mage with a slow, meaningful nod that the Narn 
speaks the truth.

The young Technomage Meldor rises silently. He draws his hood deeper into his face, 
turns away from the fire and sets off until he disappears completely into the absolute 
darkness of the horizon.

G'Kar looks up slowly from the fire, his gaze drifting devoutly to the star-studded 
sky as his deep, rough voice untermixes the silence. The gaze slides calmly further 
up into the infinity of space, straight to the familiar, brightly lit silhouette of 
the station, which rotates majestically in front of the bluish shimmer of the planet 
Epsilon III: BABYLON 5.

G'KAR
(as the gaze drifts to the station)
Up there, in the whirling dance of steel and hope, the vectors of our destiny cross. 
There begins the path. Look to the stars, my friend... before the night becomes absolute.

EOD
    ],

    2 => [
        'title' => 'SCENE 2: THE SPARK IN THE DARK',
        'location' => 'INT. BABYLON 5 - MAINTENANCE CORRIDORS (BROWN SECTOR) - NIGHT',
        'script' => <<<'EOD'
The air tastes of recycled oxygen, cheap synthetic coffee and the stale heat of 
unmonitored freighter valves. The COMMANDER (Ranger special agent) moves like a ghost 
through the dusty gray of the lower levels. Every shadowy corner is mapped out in 
his mind. It is the only way he survived after losing his old intelligence partner 
from black-ops days – Marcus Cole.

Suddenly, a heavy maintenance hatch slams open. A man in the torn, blood-stained 
robes of a Ranger breaks through and collapses directly in front of the Commander. 
Far back in the corridor, the eerie, bluish searchlights of Psi-Corps bloodhounds 
cut through the iron fog. The hum of bioscanners approaches unyieldingly.

THE RANGER
(gasping, dark blood welling on his lips)
"Take it... Bring it... personally to the command staff... Trust no one... 
 The sleepers are awaking... We die... for the One..."

With a final rattle, the Ranger dies. His hand opens, releasing an Anla'shok medallion 
and an icy, pulsing data crystal. The light of the Psi security guards is already 
breaking around the corner.

CHOOSE YOUR ESCAPE PATH:
1 = PATH 1 [Activate the cloaking device]
2 = PATH 2 [Flee into the ventilation shafts]
3 = PATH 3 [Trigger class-specific reaction]
EOD
    ],

    3 => [
        'title' => 'SCENE 3: THE REVOLT OF SILENCE',
        'location' => "INT. BABYLON 5 - ZACK ALLAN'S OFFICE / COUNCIL CHAMBER - LATER",
        'script' => <<<'EOD'
The Commander materializes from the shadows directly inside Zack Allan's private office, 
drops the crystal onto the table without a single word, and instantly disappears back 
into the crowded promenade decks of the station.

MOMENTS LATER: In the sealed Council Chamber, CAPTAIN ELIZABETH LOCHLEY slides the 
crystal into the main terminal. A red warning light pulses unreliably:
[CRITICAL ERROR - DATA ENCRYPTED]

LOCHLEY
(grimly)
"It is completely locked. We cannot read the coordinates. 
 We need someone who understands this biochemical, alien encryption matrix..."

Suddenly, the station's sensor sirens wail. Outside in space, a massive 
hyperspace vortex ignites. A sleek, dark ship full of biomechanical, shifting 
light patterns glides silently into the sector. Meldor has reached Babylon 5.

At the exact same moment, all monitors flicker. The ISN news signal from Geneva breaks 
into heavy static snow. The primary communication channels to Earth are dead. 
An suffocating silence drops over the entire Zocalo promenade. The homeworld 
is completely cut off from the network.

Zack Allan catches the Commander on the promenade deck. His eyes are wide with panic.

ZACK ALLAN
"Don't move. I'm not arresting you... but hell just froze over, Commander. 
 Earth is gone. Absolute dead silence on all relays."

He shoves the Commander directly into the high-security elevator leading to the Command Deck.
EOD
    ],

    4 => [
        'title' => 'SCENE 4: THE NEGOTIATION OF THE ALLIANCE',
        'location' => "INT. CAPTAIN'S OFFICE - CONTINUOUS",
        'script' => <<<'EOD'
The heavy blast doors slide shut. Captain Lochley fixes the Commander with an 
unyielding stare. A fragmentary, red-stamped Earthforce military file lies on her desk.

LOCHLEY
"Let's skip the diplomatic protocol. The subspace channels to Earth are pure 
 static ash. And right before this blackout, you throw an unbreakable alien 
 crystal onto my security chief's desk. Your file is a ghost story. 
 Who are you, and why did that Ranger pick you in the bowels of the station?"

COMMANDER
(cold, unmoved)
"The Ranger died at my feet. I don't know what he wanted. But as he bled out, 
 he reminded me of someone. Someone who threw his life away for a uniform 
 I never understood. I brought the crystal to settle a debt of memory. 
 That is all the concessions you get, Captain."

Zack Allan steps forward from the background, a deep recognition in his eyes.

ZACK ALLAN
"Captain... the fragments in the file. They match the sector logs from before 
 the Shadow War. Our Commander here used to run covert operations with Marcus Cole. 
 Long before Marcus ever joined the Anla'shok. The Ranger knew exactly who he was 
 looking for in the Brown Sector."

Lochley's expression turns deadly serious.

LOCHLEY
"Marcus Cole... If he registered your name in the Ranger network before his death, 
 he knew you would fulfill the duty. You might not care about the Alliance, 
 but Earth is blind. President Sheridan has made provisions for this exact case: 
 Deep in Pirate Sector 14 lies a hidden prototype cruiser, an Earth-Minbari hybrid: 
 the LIBURNIA. But our master constructor, Sha'In, was just kidnapped by Raiders. 
 Secure the ship. Bring the constructor and the Liburnia home."

🔀 INTERACTIVE WEGGABELUNG 2: THE ALLIANCE CONTRACT
1 = PATH 1 [Demand conditions based on your hidden file or routes]
2 = PATH 2 [Honor the legacy of your fallen comrade Marcus Cole]
EOD
    ],

    5 => [
        'title' => 'SCENE 5: THE INTRUSION INTO SECTOR 14',
        'location' => 'INT. PIRATE BASE - SECTOR 14 - REPRODUCTION',
        'script' => <<<'EOD'
A small escort ship drops the Commander off. Through a decommissioned waste chute, 
he infiltrates the interior of a wrecked warship that serves as a base for the Raiders. 
In front of a high-frequency energy cell, two heavily armed mercenaries patrol. 
In the cell sits SHA'IN – an Ipsha master constructor, exhausted but with an unbroken, 
proud gaze.

🔀 INTERACTIVE WEGGABELUNG 3: THE LIBERATION
1 = Choice 1 [Tactical attack - Fire your PPG openly]
2 = Choice 2 [Silent neutralization - Drop from the ceiling pipes]
3 = Choice 3 [A distraction maneuver - Fuses down the corridor]
4 = Choice 4 [Class-specific tactical strike / Bribery]

[CONSEQUENCE CHANNELS]:
- CHOICE 1: The Commander draws his PPG and shoots down the guards in an open fire fight. 
  The loud noise echoes heavily through the steel corridors. (Status: Alarm / Suffer -10 HP on escape).
- CHOICE 2: The Commander drops silently from the ceiling pipes and knocks out the guards 
  one after another before they can even react. (Status: Undetected).
- CHOICE 3: A heavy fusion coupling flies down the corridor. The guards investigate 
  the metallic clatter, while the Commander deactivates the cell and pulls Sha'In out. (Status: Undetected).
- CHOICE 4 (Intelligence): A cold bluff with forged Alliance documents intimidates 
  the mercenaries. They open the cell completely voluntarily, fearing a diplomatic fallout.
- CHOICE 4 (Underworld): The Commander pays the mercenaries a tribute of 15,000 Credits. 
  They greedily pocket the cash, turn around, and walk away. (-15,000 Credits).
EOD
    ],

    6 => [
        'title' => 'SCENE 6: THE BRIDGE OF THE EAS LIBURNIA',
        'location' => 'INT. EAS LIBURNIA - COMMAND DECK',
        'script' => <<<'EOD'
Sha'In slams her private override code into the heavy hangar lock. The blast doors 
reveal the breathtaking silhouette of the EAS LIBURNIA. Silver Minbari biocrystals 
run like organic tendons through Earthforce steel.

SHA'IN
(breathless, running her fingers over the glowing crystals)
"Damn it... it's my father's language-pattern protocol! The binary computer of Earth 
 refuses to interpret the flowing light patterns of the Minbari matrix..."

🔀 INTERACTIVE WEGGABELUNG 4: LINGUISTIC CALIBRATION PATH
1 = WEG 1: Enforce human protocols via Binary Compression (Systems run stable but rigid).
2 = WEG 2: Give free rein to Minbari logic via Organic Stream (Warning: Displays shift to alien glyphs!).
3 = WEG 3: Bridge the analytical gap using hybrid translation protocols (Requires High Analysis / Perfect Hybrid).

[CONSEQUENCE CHANNELS]:
- WEG 1: Enforces human structures. The ship responds, but the interface becomes extremely 
  rigid and slow. (Sets shipCalibrationPath = 1).
- WEG 2: Minbari logic flows freely. The entire terminal wraps into complex light patterns 
  and unreadable alien glyphs. (Sets shipCalibrationPath = 2).
- WEG 3 (Triumph): Perfect Hybrid Calibration! The system harmonizes flawlessly. Earthforce steel 
  and Minbari biocrystals fuse into an elite, fluid tactical matrix. (Sets shipCalibrationPath = 3).
EOD
    ],

    7 => [
        'title' => 'SCENE 7: THE INTERSTELLAR DIALOGUE',
        'location' => 'INT. EAS LIBURNIA - HYPERSPACE TRANSIT',
        'script' => <<<'EOD'
The ship glides calmly through the restless, shimmering purple glow of hyperspace. 
Sha'In looks up from her console, carefully observing the Commander as the hybrid systems 
hum in the background. 

Down below in the dusty cargo bay, Moka, the stumpy Drazi technician, slams a massive 
sledgehammer onto a blocked energy coupling with a deafening metallic clang.

MOKA
(wiping sweat from his scaly brow, shouting up the comm-line)
"Getting stuck is bad for ship, Ipsha! If it won't flow, you must break it! 
 Earthforce steel is too rigid, but if you hit it hard enough, it will learn 
 to bend with the Minbari light!"

SHA'IN
(softly, turning to the Commander)
"He is crude, but he is right. The hybrid core requires balance, Commander. 
 Just like this old alliance we are trying to drag back from the ashes of memory."

EOD
    ],

    8 => [
        'title' => 'SCENE 8: THE ALLIANCE PALACE ON MINBAR',
        'location' => 'INT. MINBAR - ALLIANCE PALACE',
        'script' => <<<'EOD'
The Liburnia leaves the swirling hyperspace vortex, entering a magnificent orbit around Minbar. 
The team is escorted directly into the light-flooded, crystalline corridors of the Alliance Palace. 
PRESIDENT JOHN SHERIDAN awaits you near the grand exit balconies, his face carrying the severe 
weight of a galactic leader facing total blackout conditions.

SHERIDAN
"This is a pure phantom operation, Commander. The subspace channels to Geneva are dead, 
 and Earthforce is fracturing under Bester's silent curfew. If you are caught out there 
 by the Black Omega squadron, the Interstellar Alliance will deny any connection to you. 
 You will be an absolute ghost in deep space."

He pauses, looking out over the shining spires of Tuzanor before turning back to you with 
an intense, serious gaze.

SHERIDAN
"But Sheridan does not forget a debt. Marcus Cole gave his life so that this Alliance 
 could breathe. If his old partner is flying the Liburnia, I know the mission is in honorable hands. 
 Bring the fleets together. Break Bester's net."

On the steps of the transport deck, Delenn steps forward from the shadows, pressing a cold, 
highly encrypted blue data crystal firmly into your hand.

DELENN:
"This crystal contains the ancient language-pattern protocol of the Ranger network. 
 Use it wisely, my friend... before the night becomes absolute."

EOD
    ],

    9 => [
        'title' => 'SCENE 9: THE ASHES OF THE PAST',
        'location' => 'EXT. OUTPOST - DEAD SECTOR - ORBIT',
        'script' => <<<'EOD'
The cloaked Liburnia glides like a silent ghost in front of an ash-gray, dying 
planet. The atmosphere is traversed by toxic, yellowish sulfur bands. Below stretch 
the needle-like, petrified ruins of the outpost. It is silent, lifeless and full of 
eerie secrets in the dust.

INT. EAS LIBURNIA - COMMAND DECK

The air temperature drops suddenly. Golden geometric equations cascade along the walls. 
From the shadows steps the Technomage Meldor.

SHA'IN
(startled)
"How did you get on board? Our internal sensors didn't register any teleport!"

MELDOR
"Sensors look for metal and wires, child. They do not look for the echo of the will.
 The Psi-Corps did not find this place by accident. They use the dark relics left behind 
 on this world for their purposes. The old servants of the Shadows have experimented 
 here in secret... and strangely enough, traces of my missing order brothers are also found... 
 what does that mean?"

The Commander steps decisively to the sensor terminal to investigate the eerie remark 
of the Mage and starts a deep scan of the planet's surface.

[SYSTEM ANALYSIS INBOUND...]

[SUCCESS CHANNEL - ANALYSIS >= 90]:
The Commander's fingers fly over the hybrid terminal while optimally calibrating the scan filters. 
He succeeds in isolating the organic radiation of the ruins. On the screen, ancient laboratory 
logbooks of the Psi-Corps flicker. He comes across the trail of missing Technomages and extracts 
an intact, deeply pulsing blue biocrystal fragment from the core matrix.
(controlDecryptionProgress +100)

[FAILURE CHANNEL - ANALYSIS < 90]:
The scan fails due to the dense, biological radiation of the planet. The terminal overheats 
and sprays sparks. An electrostatic discharge shoots out of the console and hits the Commander painfully!
Despite the feedback, the Commander succeeds in ripping a loose, incomplete fragment of the 
glowing crystal from the holder with safety pliers before the relay station finally implodes.
(Suffer -15 HP).

MELDOR: 'To hear what these stones hide, this ship is not enough. We must set course for 
        the Babylon 5 system and travel down into the bowels of Epsilon III. Only the 
        Great Machine can tear this veil.'
EOD
    ],

    10 => [
        'title' => 'SCENE 10: THE INTERSTELLAR AMBUSH',
        'location' => 'INT. EAS LIBURNIA - HYPERSPACE TRAVEL - TRANSIT TO B5',
        'script' => <<<'EOD'
The Liburnia chases with maximum thrust through the swirling hyperspace vortex 
towards the Epsilon sector. You are flying back to investigate the data of the 
recovered Drakh crystal.

Suddenly, the entire ship shakes under a brutal, bone-shattering impact! Red warning 
lights flood the bridge, and the howling of the decompression sirens sets in.

SHA'IN
(thrown against her console)
"Ambush! Three heavy Raider fighters fell directly on us from the shadows of an 
 unstable gravitational shear wave! They lurked in the dead angle of the stream and 
 intercepted our engine resonance! A plasma bolt pierced our primary distribution – 
 the main plasma line in the engine room is leaking! If we don't contain the pressure 
 manually, the core will explode right in hyperspace!"

MELDOR
(calm, remaining in the shadows)
"A test of the flesh. Go, Commander. Show the machine what steel you are made of."

You hesitate for no second. You sprint from the bridge down into the blazing heat of 
the reactor section. The Minbari crystals on the walls pulse in a panicked, dying red. 
You must advance directly to the glowing valve to force the manual override.

[PROCESSING ENGINE EMERGENCY OVERRIDE...]

[CONSEQUENCE CHANNELS]:
- CASE 1 (Binary Compression): The systems react stubbornly and slowly because they must follow the rigid 
  Earthforce safety cycles. You stand agonizingly long in the murderous heat before the valve finally clicks. 
  The plasma burns your arms and sears your face. (Suffer -30 HP).
- CASE 2 (Organic Minbari Stream): The reactor runs with maximum energy, but the warning displays pulse 
  in the three-dimensional light patterns of the Minbari. For human eyes, it is pure chaos! You reach for 
  the wrong switch, triggering a small plasma explosion before finding the valve. (Suffer -25 HP).
- CASE 3 (Perfect Hybrid Path): Thanks to the perfect hybrid protocol, the systems harmonize flawlessly. 
  The ship responds lightning-fast to the manual override. The Minbari crystals automatically throttle 
  the plasma flow in your blind spot to protect you. With precise movements, you seal the leak. (Suffer -10 HP).
EOD
    ],

    14 => [
        'title' => 'SCENE 14: THE FRANKLIN PROTOCOL',
        'location' => 'INT. BABYLON 5 - MEDLAB ONE - HOURS LATER',
        'script' => <<<'EOD'
The energy of the Great Machine has been rerouten directly into the secured medical bays of the station. The Commander lies on a diagnostic table while DR. SARAH CHAMBERS guides a hissing regeneration scanner over his burns. The cool, bluish light closes the scorched skin in a matter of seconds. 

[SYSTEM]: Your health points have been fully restored! (playerHP = 100)

In the center of the room, the main projector displays a life-sized, razor-sharp hologram. It is DR. STEPHEN FRANKLIN, broadcasting directly from the underground of Earth! At his side in the Medlab stand CAPTAIN LOCHLEY, TESSA HOLLORAN, and DELENN.

FRANKLIN (Hologram)
"Thank God, the connection holds! I don't know what energy source you tapped into, but it stands up to Bester's broadband jammers. Listen to me carefully: Bester hasn't just sealed Earth politically. He is using a modified variant of the Drakh plague—a biomechanical tech-cross—to pull up a worldwide, unhackable sleeper network via the telepaths! He is experimenting in secret on kidnapped Technomages to abuse their implants as biological relay stations!"

GARIBALDI (Audio-Feed)
"Lochley, forward this to the Commander immediately. I received confirmation via the industrial espionage channels of Edgars Industries: Bester has built a strictly secret research station right inside the Tharsis volcanic crater on Mars. I've attached the original infiltration codes. Find out what they are doing there."

[CLASS-SPECIFIC DIALOGUE CHANNELS]:
- CASE 'Intelligence': DELENN: "You look at the blueprint of this war, Commander, and you see only numbers and grids. But Marcus did not see the universe as an equation. He saw it as a family worth sacrificing everything for. Do not let his light fade into the database."
- CASE 'Underworld': DELENN: "You pretend you only care about the Credits and the freedom of your cargo routes. But Marcus knew the real person behind that smuggler mask. He trusted you with his life. Settle the debt of memory, my friend."

DELENN: "The Rangers stand ready, Commander. But the Psi-Corps has Earth in a stranglehold. To win this war, we must assemble the pieces of the old alliance. Travel to the alien worlds. Bring us the fleets of the Narn and the Centauri!"
EOD
    ],

    15 => [
        'title' => 'SCENE 15: THE FREE VECTOR (MISSIONS-HUB)',
        'location' => 'INT. EAS LIBURNIA - TACTICAL HUB',
        'script' => <<<'EOD'
The tactical mission center in the hub is unlocked. The Liburnia is fully operational. Select your hyperspace vector to assemble the pieces of the old alliance and secure the Section 13 data logs.

======================================================
=== NAVIGATION SYSTEM: EAS LIBURNIA ===
======================================================
CURRENT SUB-SPACE FEED: [EVALUATING ISN NEWSFEED]

SELECT YOUR NEXT HYPERSPACE VECTOR:
1 = Sector Babylon 5 (Medlab Base) -> [LOCKED if Mars Infiltration completed!]
2 = Centauri Prime (Imperial Palace)
3 = Narn Homeworld (Military Citadel)
4 = Mars Sector (Tharsis Crater Lab Infiltration)
5 = Lyta's Refuge (Lawless Asteroid Bar)
8 = [FINAL APPROACH] PLANET EARTH (Requires Mars Section 13 data logs: Progress 999)

[ALLIANCE MATRIX CONVERGENCE CHRONOLOGY]:
Fires dynamically inside the Hub logic loop as soon as both Centauri Prime (Scene 16) and Narn Homeworld (Scene 17) have been logged as visited. Evaluates and locks your reinforcement assets permanently.
EOD
    ],

    16 => [
        'title' => 'SCENE 16-B: THE NET OF THE SPIDER',
        'location' => 'INT. CENTAURI PRIME - IMPERIAL THRONE ROOM - DAY',
        'script' => <<<'EOD'
The Liburnia touches down on the grand landing platforms of Centauri Prime. 
You are escorted into the high, echoing Imperial Throne Room. 

EMPEROR LONDO MOLLARI sits on his heavy throne, his eyes narrow, filled with the 
weariness of a man carrying chains he cannot show. He looks at you with cold, 
calculated etiquette.

LONDO MOLLARI:
"Ah, the Alliance emissary... Sheridan sends you to beg for my royal fleet, yes? 
 Earthforce is fractured, Bester is playing with forces he cannot comprehend. 
 I will give you my heaviest battle cruisers to shatter the Black Omega lines. 
 But... the Centauri Republic expects a return on its investment. I demand 
 exclusive, absolute colonial authority over the Narn sectors once this war is over. 
 Sign the military pact, or leave my palace empty-handed."

🔀 INTERACTIVE WEGGABELUNG 9-B: THE PALACE ULTRA-PAKT
1 = Choice 1 [Accept Londo's aggressive military pact - Sacrifice Narn sovereignty]
2 = Choice 2 [Reject Londo and look for alternative paths inside the courts]

[CONSEQUENCE CHANNELS]:
- CHOICE 1 (The Londo Pact): You pick up the electronic stylus and sign the imperial treaty. 
  Londo smiles grimly, nodding as heavy cruisers are assigned to your vector. 
  But the shadow of the Drakh over the Centauri throne intensifies. 
  (relationships 'Ivanova_Earthfleet' -20, drakhInfluence +25, besterThreatLevel +15).
- CHOICE 2 (The Coveted Evasion): You refuse the ultimatum, turning your back on the Emperor. 
  In the corridors, VIR COTTO intercepts you, sweating but determined.
  VIR COTTO: "Commander! Londo is not himself. I cannot give you the main fleet, but I have unlocked 
  a secret, unregistered humanitarian rescue fleet through the Ranger channels. Use it to evacuate 
  civilians." (drakhInfluence -30).
EOD
    ],

    17 => [
        'title' => 'SCENE 17-B: THE CITADEL OF RED SAND',
        'location' => 'EXT. NARN HOMEWORLD - THE MILITÄR-ZITADELLE - DAY',
        'script' => <<<'EOD'
The Liburnia drops out of the burning crimson skies of Narn. You enter the heavy, 
sand-scoured halls of the Central Military Citadel. 

The Elders of the newly restored Kha'Ri gather around the stone tactical grid, 
their eyes filled with the ancient pride of a liberated race.

[DYNAMIC CENTAURI AFTERMATH CHANNELS]:
- CASE 'BETRAYED_BY_LONDO' (drakhInfluence > 60):
  The atmosphere inside the Citadel turns ice-cold. The Lead Elder slams his heavy battle blade directly into the red sand before your feet!
  ELDERS OF THE KHA'RI: "You bring G'Kar's seal, yet you signed a treaty that sells our sectors back to Londo Mollari! The official Narn fleet will NEVER support a traitor!"
  [UNDERGROUND OPTION UNLOCKED]: To save your flanks, you must hire criminal underground mercenaries for a flat payment of 20,000 Credits.
  
  [SUB-FINANCIAL CHECK]:
  * CAN_AFFORD (credits >= 20000): 1 = Spend 20,000 Credits to hire kriminelle Narn Mercenaries. 2 = Refuse.
  * BROKE (credits < 20000): Insufficient funds! You cannot afford the mercenaries. The Narn abandon your cause. (besterThreatLevel +35).
  
- CASE 'HONORABLE_ALLIED' (drakhInfluence <= 60):
  A triumphant roar echoes through the red stone halls! The Elders look at you with profound respect.
  ELDERS OF THE KHA'RI: "You protected our freedom in the galactic courts, Commander. Our very first official decree is signed: The elite Narn Stoßtrupp-Avantgarde is mobilized to protect Earth!"
  (isNarnAvantgardeRecruited = true, besterThreatLevel -20).
EOD
    ],


    22 => [
        'title' => 'SCENE 22: LYTA ALEXANDER\'S HIDEOUT (THE LAST CROSSROAD)',
        'location' => 'INT. SMUGGLER OUTPOST - ASTEROID BAR - NIGHT',
        'script' => <<<'EOD'
The tracking vectors lead you into a dark, smoky bar buried deep inside a hollow asteroid. 
This is lawless space, far away from any Alliance jurisdiction. 

LYTA ALEXANDER sits in a dimly lit corner, her hood pulled low over her face. As you 
approach her table, her head snaps up. Her eyes flash with an unnatural, terrifying 
Vorlon-blue light. Her voice hammers directly into your synapses via telepathy.

LYTA (Mental Projection):
"You want my power to tear down Bester's walls. But I do not care about your corporate 
 treaties or military alliances. I want retribution. I want to see this damned Corps burn. 
 Down to its very foundations!"

[PROCESSING MORAL ALIGNMENT FOR RECRUITMENT...]

[RECRUITMENT INTEGRATION CHANNELS]:
- PATH_OF_LIGHT (Progress = 999 && alignmentScore >= 75): 
  You slide the data pad containing the hard Section 13 Mars evidence across the table. 
  You speak with genuine empathy, explaining Talia Winters' tragic fate as an innocent victim.
  COMMANDER: "Talia Winters is no longer an asset of the Corps, Lyta. She is a victim. Bester forced her into a biological switching unit. If you blindly burn the headquarters to ash, you murder a sister in mind. We must destroy the network, not the people caught inside."
  Lyta's eyes widen. The Vorlon-blue flickers and breaks into a deep, human tear. The evidence shatters her blind hatred.
  LYTA: (with a trembling voice) "They did... they did that to her? Oh my God... No. I will go with you, Commander. I will restrain my powers. We will save Talia and burn Bester's net without sacrificing the innocent." (lytaRecruited = true / Resonance-Ending 7 unlocked!).
- PATH_OF_REVENGE (alignmentScore <= 40):
  You nod grimly, matching her burning anger. You embrace her radical campaign of destruction.
  COMMANDER: "Bester started this war, Lyta. We will finish it by his own rules. The Corps will burn. Every single cell."
  Lyta smiles a dark, lethal smile. Her eyes ignite in absolute god-like light. She stands up and follows you back to the hangar, ready to completely pulverize the HQ. Talia's rescue slips completely into the background. (lytaRecruited = true).
- TOTAL_FAILURE (Morality between 41 and 74):
  You fail to hit the correct emotional chord, arguing with dry military metrics or contradictory ideals. Lyta stares at you with pure, icy contempt.
  LYTA: "You are just as blind as the generals in Geneva. You only want to use me as a weapon for your little alliance. Get out of my sight!" (lytaRecruited = false).
EOD
    ],

    25 => [
        'title' => 'SCENE 25: THE ESCAPE FROM THARSIS',
        'location' => 'INT. EAS LIBURNIA - COMMAND DECK / ABOVE MARS ATMOSPHERE - CONTINUOUS',
        'script' => <<<'EOD'
You burst onto the bridge of the Liburnia, the stolen Mars Section 13 files securely 
locked into the mainframe. Outside, the entire Tharsis crater facility is waking up. 
Automated orbital laser batteries are locking onto your exact hull signature.

Heavy, high-energy defensive beams tear through the hangar structures, spraying 
melted titanium and rock directly across your viewport. The ship is shaking violently.

SHA'IN
(screaming over the roar of the alarms, her hands flying over the terminal)
"The hangar doors are sealed! The base commander has initiated an automated magnetic 
 automated magnetic clamp lock! I need to override the primary thruster limiters right now, 
 but the Minbari biocrystals are resisting our tactical human surge inputs!"

COMMANDER
"Force the bypass, Sha'In! Ignite the matrix!"

[PROCESSING HYBRID PROPULSION CHRONOLOGY CORRIDOR...]

[MODERN SWITCH BLOCK EVALUATING PREVIOUS LINGUISTIC CALIBRATION]:
- CASE 3 (Perfect Hybrid Path Bonus): The reaction of the Earth-Minbari fusion matrix is immediate and flawless! The silver-blue crystals flood the bulkheads with a harmonious, blinding light. The Liburnia's engines roar to life in an elegant, effortless surge of power, shattering the magnetic clamps instantly. The prototype shoots into the Martian sky like a streak of lightning, leaving the defense lasers completely out of range!
  SHA'IN: 'Systems stable! No heat friction! The hybrid protocol handled the surge perfectly!'
- CASE 2 (Organic Minbari Stream Path): The engines flare up with immense power, but the entire console shifts back into unreadable alien light glyphs. Karel struggles with the manual helm response because the navigational compass is fully warped into Minbari measurements. The ship rips free from the hangar, but scrapes a heavy defense tower on its way out. (playerHP -15).
- CASE 1 (Binary Compression Path): The hybrid engine hesitates for an agonizing, terrifying second! The system struggles to translate the fließenden crystalline energy bands into rigid, human binary commands. A heavy planetary defense laser strikes your primary shield grid dead-center! (playerHP -20).
EOD
    ],

    26 => [
        'title' => 'SCENE 26: THE REUNION IN HYPERSPACE',
        'location' => 'INT. EAS LIBURNIA - MESS HALL / REBEL FLEET MEETING - NIGHT',
        'script' => <<<'EOD'
The Liburnia slips into the dense, purple ion-shrouds of the hidden nebula, 
successfully docking with General Susan Ivanova's rogue rebel fleet. 

GENERAL SUSAN IVANOVA and DR. STEPHEN FRANKLIN step aboard the prototype, their 
faces hardened by weeks of underground warfare. But before the strategic briefing 
can even begin, the heavy mess hall hatch slams open with a deafening bang!

MICHAEL GARIBALDI charges into the room, holding a massive, sizzling, steaming pan 
high in the air! The intense, rich aroma of genuine traditional Earth meat and 
spices completely floods the sterile military deck. He raided the hidden Mars logistics 
depots and cooked a massive traditional meal for the entire crew.

GARIBALDI:
"If I had to look at another plate of dried Minbari vegetables or nutrient paste 
 for one more minute, I swear to God I would have spaced myself right into the stream! 
 Sit down, eat up. The fleet is warm, and we've got a lot of ground to cover."

Gathered around the table, the old command staff breaks bread together. For the first 
time in cycles, the crushing weight of the war lifts. You toast to the memory of 
MARCUS COLE with tears, silent toasts, and roaring laughter, remembering his terrible, 
stubborn Ranger poetry and pure unyielding loyalty. The ice breaks entirely.

IVANOVA
(sets her glass down, her eyes turning into pure, focused flint)
"He was a heroischer, noble fool... and I am completely done mourning him, Commander. 
 We honor his sacrifice by finishing this fight. My ships are locked onto our vector. 
 I am personally leading the orbital drop and the ground assault. We are going to hammer 
 Bester's net into absolute dust. What is our fleet availability status?"

CHOOSE YOUR STRATEGIC ASSASSIN-BRIEFING OVERVIEW:
1 = Report Fleet Assets: Present the compiled political data logs of your allies. (analysisFocus +5).
2 = Skip Briefing: Move straight to the tactical assault deployment grids.
EOD
    ],

    27 => [
        'title' => 'SCENE 27: THE GENEVA STRATOSPHERIC APPROACH',
        'location' => 'INT. EAS LIBURNIA - COMMAND DECK / EARTH ORBIT - NIGHT',
        'script' => <<<'EOD'
The viewscreen clears, revealing the breathtaking, sapphire-blue marble of Earth. But the cradle of humanity is surrounded by an iron ring—the black, dagger-like silhouettes of Earthforce destroyers loyal to Bester's regime are patrolled by elite Black Omega starfighters.

The subspace relays hum with wild energy. GENERAL SUSAN IVANOVA's face flares up on the main tactical display from her flagship's bridge, her eyes burning with a lethal, uncompromising intensity.

IVANOVA (Holo-Feed):
"All rebel units, this is General Ivanova. We have tracked Bester's primary transponder signals down to the central biocrystalline dome in Geneva. The network is hot, and they know we are here. There will be no elegant maneuvers, no tactical retreats. We are going straight through their defensive line. Lock your shields, heat up your plasma arrays, and push your throttles to the absolute wall. We are going home!"

The entire rebel fleet ignites their fusion drives simultaneously, screaming forward in a massive wave of fire. The Liburnia dives ahead, leading the vanguard. 

SHA'IN
(gripping her console as the atmospheric entry friction turns the viewport blood-red)
"Thermal hull shields are melting, Commander! The friction is hitting critical levels, and two Black Omega heavy interceptors are locking their plasma cannons onto our engine matrix! We must choose our atmospheric entry angle immediately!"

CHOOSE YOUR STRATEGIC STRATOSPHERIC ENTRY VECTOR:
1 = Vector 1 [Aggressive dive: Maximum thrust to overpower the interceptors via raw speed] -> (playerHP -15, alignmentScore -5).
2 = Vector 2 [Evasive glide: Roll the ship into the thermal blind spots of the atmosphere] -> (analysisFocus +5).
EOD
    ],

    28 => [
        'title' => 'SCENE 28: GARIBALDI\'S GLOBAL SUBSPACE BROADCAST',
        'location' => 'INT. REBEL FLAGSHIP - TRANSMISSION DECK / GLOBAL EARTH NETWORK - NIGHT',
        'script' => <<<'EOD'
While the Liburnia dives through the burning lower atmosphere, Michael Garibaldi secures his breach into the primary ISN propaganda relay station over the Geneva grid. The screens on the Liburnia's bridge flicker wildly, the sterile face of the Psi-Corps news anchor dissolving into heavy static snow.

Suddenly, Garibaldi's lined, unyielding face cuts through the worldwide broadcast network. He takes the unlit cigar out of his mouth, leans straight into the camera lens, and his dry, biting voice echoes from every single viewscreen, public terminal, and comm-link across the surface of Planet Earth.

GARIBALDI (Global Broadcast):
"People of Earth, listen to me very carefully. I'm not some voice from a rogue resistance cell, and I'm not here to negotiate. My name is Michael Garibaldi, former Chief of Security on Babylon 5. For months, Alfred Bester and his synthetic net have lied to you. They told you the Interstellar Alliance wants to consume your sectors. They told you the curfew was for your own safety. It's a lie. A massive, bureaucratic lie."

He slams a heavy data cartridge directly into the broadcast deck, projecting the stolen Mars Section 13 files across the worldwide telemedia channels.

GARIBALDI (Global Broadcast):
"Right now, Bester is holding hundreds of innocent people—both mundanes and telepaths—inside a biological research network beneath Geneva. He is frying the brains of your own children to secure his personal grip on power. We have the logs. We have the data. To all Earthforce military units still taking orders from the Corps: stand down. Your government has been blinded. Look at the screens. Look at the truth!"

[PROCESSING INFILTRATION CONVERGENCE VIA SWITCH]:
- CASE 'Intelligence': Garibaldi's data feed utilizes old, embedded Black Watch encryption frequencies. The broadcast bypasses local jammers twice as fast. (besterThreatLevel -15).
- CASE 'Underworld': Garibaldi uses pirate sub-relays and black-market frequencies. Resistance groups rise up, cutting power lines of the automated laser turrets outside! (credits +5000).
EOD
    ],

    29 => [
        'title' => 'SCENE 29: THE GLASS CEILING BURSTS',
        'location' => 'INT. PSI-CORPS HEADQUARTERS - ENTSPANNUNGSBEREICH - NIGHT',
        'script' => <<<'EOD'
The Liburnia screamingly approaches the central biocrystalline tower of the Corps. With an ear-splitting, thunderous roar, the armored hull slams directly through the colossal structural glass roof of the ENTSPANNUNGSBEREICH! 

A crystalline rain of a million razor-sharp shards explodes into the luxury lounge. Alarms scream in high-pitched frequencies as the boarding ramps slam down onto the shattered marble floors. Dust, smoke, and venting hydraulic steam fill the hall.

You, General Susan Ivanova, and the elite ground teams drop from the ramps, weapons raised. Psi-Corps security guards are rushing forward from the side corridors, blazing heavy PPG fire into the smoke.

IVANOVA
(firing a hochenergetischer plasma bolt, her voice cutting through the noise)
"Secure the perimeter! We need to lock down the main stairwells from top to bottom, otherwise they will pin us down in this lounge! Commander, we must coordinate the breach strategy right now!"

CHOOSE YOUR INFILTRATION VECTOR FOR THE STAIRWELLS:
1 = Forceful Breach: Charge the central elevator shafts using heavy tactical cover fire. (playerHP -10).
2 = Flanking Maneuver: Split the squad into the side ventilation grids for a silent lock. (analysisFocus +5).
EOD
    ],

    30 => [
        'title' => 'SCENE 30: THE INNER SANCTUM',
        'location' => 'INT. PSI-CORPS HEADQUARTERS - BESTER\'S MATRIX CHAMBER - CONTINUOUS',
        'script' => <<<'EOD'
The heavy biocrystalline doors blow apart under a perfectly placed thermal charge. Dust and ozone flood the inner sanctum. As the smoke clears, you step into a massive, glowing chamber domed by pulsating alien hardware.

And out of absolute nowhere, you find her: TALIA WINTERS is trapped in the center! Her body is suspended in stasis wires, cybernetic cables feeding directly into her synapses. She is breathing, but her mind is completely woven into the worldwide sleeper net.

ALFRED BESTER stands calmly before the main control matrix, his black gloves folded behind his back, his thumb resting lightly on a small, matte-black master detonator.

BESTER
"One wrong move, Commander, and I fry the synapses of every single sleeper mind on this planet. You came a long way just to witness the end of your species."

GENERAL SUSAN IVANOVA steps to your side, her PPG rifle raised, aimed dead-center at Bester's forehead. Her voice is ice.

IVANOVA
"This is for Marcus, Bester. And the only one leaving this room in the dust... is you."

CHOOSE YOUR FINAL COMPILER-STRIKE:
1 = Activate Meldor's technomage phantomschwarm-override. -> (Golden phantomschwarm-code completely isolates Bester's detonator frequencies in subspace, rendering his trigger useless).
2 = Engage Bester in raw, bloody physical hand-to-hand combat. -> (Tackle Bester before trigger is pressed, slam detonator out of hand. Suffer heavy counter-attacks. playerHP -35).
EOD
    ],

    31 => [
        'title' => 'SCENE 31: THE FINAL BATTLE – THE EVALUATION OF THE TIMELINE',
        'location' => 'INT. PSI-CORPS HEADQUARTERS - MAIN MATRIX TERMINAL - AFTERMATH',
        'script' => <<<'EOD'
The screaming sirens are slowly dying down. The central neural server drops its protective shields, and the stolen Section 13 Mars data streams are verified. The quantum compiler of the Liburnia evaluates your structural footprint in the dust.

================================================================================
=== ALL 7 CALCULATED ENDING VECTOR CHANNELS                                  ===
================================================================================

[ENDING 1: THE PATH OF LIGHT (DIPLOMATIC KANON ENDING)]
Talia overwrites the neural codes peacefully. Alfred Bester is placed under arrest by the Alliance fleet. But as the structural columns of the headquarters collapse, Lennier unexpectedly sacrifices his own life to pull Delenn, Ivanova, and you to safety. Lennier clears his honor in the face of death.

[ENDING 2: THE CENTAURI SHADOW (LONDO'S REVENGE)]
Because your Drakh influence was critical, Londo Mollari's secret shadow ships betray the perimeter! The Psi-Corps burns, but Centauri heavy cruisers glass the Narn homeworld sectors as dictated by the secret pact. You return to a galaxy trapped in a cold, paranoid multi-front war.

[ENDING 3: THE NARNIAN CITADEL REBORN]
The elite Narn Stoßtrupp-Avantgarde locks the entire orbital grid. Bester's net is systematically dismantled by raw firepower. The Kha'Ri establishes an unyielding military protectorate over Geneva to ensure Earth never threatens the stars again.

[ENDING 4: THE RESISTANCE TRIUMPH (MUNDANE ASCENSION)]
Garibaldi's broadcast triggers a total, planet-wide civilian revolution. The automated networks collapse from global overloads. Humanity discards the Psi-Corps entirely, but telepaths are forced into absolute isolation in lawless asteroid sectors.

[ENDING 5: THE SHADOW DIRECTOR (YOUR MASTERPLAN)]
You ruthlessly execute Bester on the spot. Using your old Black Watch security codes, you hijack the entire worldwide neural net for yourself instead of breaking it! You become the new, invisible Director of Bureau 13. The population of Earth keeps sleeping... completely unaware, under your iron rule.

[ENDING 6: ABSOLUTE SILENCE (GAME OVER)]
Your tactical attributes and alliance networks were weak. Bester successfully activates the worldwide neural network, frying the synapses of millions. The Earth fractures permanently into civil war and absolute chaos.

[ENDING 7: THE TELEPATH RESONANCE MASTERPIECE (TRUE KANON ENDING)]
Talia Winters' dormant Ironheart heritage fully awakens to shield the sleeper minds! Lyta Alexander channels her terrifying Vorlon power to completely burn Bester's künstliches Net. The shadow tech fries, the corporate Curfew is shattered forever, and ALL MAIN CHARACTERS SURVIVE UNHARMED! Humanity wakes up to a bright, free tomorrow. (Triggered via lytaRecruited, progress == 999, alignmentScore >= 75).
EOD
    ],

    32 => [
        'title' => 'SCENE 32: EPILOGUE – THE SUB-SPACE FREQUENCY FROM TUZANOR',
        'location' => 'INT. EAS LIBURNIA - COMMAND DECK / EARTH ORBIT - AFTERMATH',
        'script' => <<<'EOD'
A quiet, respectful calm settles over the bridge of the Liburnia. The smoke over Geneva is clearing, and the global network has stabilized. Suddenly, the primary sub-space frequency chimes, building a secure link from Minbar.

The lifelike hologram of PRESIDENT JOHN SHERIDAN appears in the center of the deck. He wears his modest, dark civilian robes. He looks deeply weary from the galactic crisis, but a profound, relieved smile spreads across his face as he looks at you.

SHERIDAN (Hologram):
"I have just received the official tactical logs about the neutralization of the Psi-Corps headquarters, Commander. You and your crew have rendered an indescribably great service to the Interstellar Alliance. Government-loyal forces on Earth have immediately gained the upper hand, and the provisional government has instantly ordered the complete dissolution of the Corps. All collaborators are under arrest.
 
 Even if the public will never know of your covert mission, and even if your share in this success will remain hidden in our deepest archives... so bin ich mir ziemlich sicher, dass Marcus besonders stolz auf Sie wäre. Auf Sie alle. Thank you, Commander. Come home. Sheridan out."

--------------------------------------------------------------------------------
THE CINEMATIC CINEMASCOPE SHOT
EXT. DEEP SPACE - UPPER ATMOSPHERE OF EARTH - CONTINUOUS

The final viewport camera drifts slowly out of the EAS Liburnia, pulling back into the majestic, silent infinity of deep space. 

The camera locks into a sharp, beautiful cinemascope focus. The uninjured, massive heavy cruisers of the Interstellar Alliance—Minbari Sharlin warleaders, Narn G'Quan cruisers, and Earthforce destroyers—lock into perfect formation all around your hull, forming a triumphant escort network.

Right ahead of the fleet, a colossal, glowing crimson-gold jump gate opens up in space, its swirling tachyon vortex illuminating the dark hulls with brilliant energy.

The Liburnia's hybrid engines roar to life in a powerful, silver-blue arc. Together, in a grand, synchronous surge of power, the entire fleet dives forward into the swirling vortex... leaving the cradle of humanity safe in the starlight behind them.

=== FADE TO WHITE - END OF THE SHADOWS WITHIN ===
EOD
    ],
];
