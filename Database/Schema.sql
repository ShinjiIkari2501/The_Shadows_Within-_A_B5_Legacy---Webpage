/**
 * SECTION 1: System- & Benutzerverwaltung
 * Target: MySQL Port 3307
 */
CREATE DATABASE IF NOT EXISTS b5_project;
USE b5_project;

CREATE TABLE IF NOT EXISTS users (
    user_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(64) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

CREATE TABLE IF NOT EXISTS items (
    item_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(64) NOT NULL,
    item_type VARCHAR(64) NOT NULL
    );


INSERT IGNORE INTO users (user_id, username, password_hash) VALUES
    (1, 'Shinji2501', '$2y$10$7R9f6Lw9f2YtX5mK3ZqVe.O7iB7p1gC9jG3fE6dE5cBcB4aAaAaAa'),
    (2, 'Tiny1983', '$2y$10$M2R1E7w8v9zX4mK2ZqVe.O6iB5p1gC8jG2fE5dE4cBcB3aAaAaAa'),
    (3, 'Test', '$2y$10$K1L2M3N4O5P6Q7R8S9T0Uu1v2w3x4y5z6a7b8c9d0e1f2g3h4i5j6');

INSERT IGNORE INTO items (item_id, item_name, item_type) VALUES
    (5, 'Psi-Rating Scanner', 'Equipment');

/**
 * SECTION 2: Story-Engine (Narrative Core)
 */
CREATE TABLE IF NOT EXISTS story_nodes (
    node_id INT NOT NULL PRIMARY KEY,
    title VARCHAR(128) NOT NULL,
    description TEXT NOT NULL,
    active_sector VARCHAR(64) NOT NULL,
    is_ending BOOLEAN DEFAULT FALSE
    );

CREATE TABLE IF NOT EXISTS story_choices (
    choice_id INT NOT NULL PRIMARY KEY,
    from_node_id INT NOT NULL,
    next_node_id INT NOT NULL,
    choice_text VARCHAR(255) NOT NULL,
    effect_credits INT DEFAULT 0,
    effect_analysis_focus INT DEFAULT 0,
    effect_origin VARCHAR(50) DEFAULT NULL,
    FOREIGN KEY (from_node_id) REFERENCES story_nodes(node_id) ON DELETE CASCADE,
    FOREIGN KEY (next_node_id) REFERENCES story_nodes(node_id) ON DELETE CASCADE
    );

/**
 * SECTION 3: Spieler-Session & RPG-Attribute
 */
CREATE TABLE IF NOT EXISTS player_slots (
    slot_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    slot_number INT NOT NULL CHECK (slot_number BETWEEN 1 AND 3),
    current_node_id INT NOT NULL DEFAULT 10,

    -- Charakter-Attribute
    character_origin VARCHAR(50) DEFAULT 'Standard',
    alignment_score INT DEFAULT 0,
    analysis_focus INT DEFAULT 10,
    lore_knowledge INT DEFAULT 0,
    psi_level INT DEFAULT 0,
    player_hp INT DEFAULT 100,
    credits INT DEFAULT 10000,

    -- Schiffs-Attribute (EAS Liburnia)
    ship_name VARCHAR(64) DEFAULT 'EAS Liburnia',
    ship_obtained BOOLEAN DEFAULT FALSE,
    ship_stealth INT DEFAULT 0,
    ship_calibration_path INT DEFAULT 0,

    -- Fraktions- & Bedrohungs-Level
    bester_threat_level INT DEFAULT 0,
    drakh_influence INT DEFAULT 0,
    control_decryption_progress INT DEFAULT 0,
    great_machine_energy INT DEFAULT 0,

    -- Story-Flags / Meilensteine
    has_delenns_data_crystal BOOLEAN DEFAULT FALSE,
    is_narn_avantgarde_recruited BOOLEAN DEFAULT FALSE,
    is_narn_mercenaries_hired BOOLEAN DEFAULT FALSE,
    lyta_recruited BOOLEAN DEFAULT FALSE,
    talias_trauma_resolved BOOLEAN DEFAULT FALSE,
    constructor_rescued BOOLEAN DEFAULT FALSE,

    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (current_node_id) REFERENCES story_nodes(node_id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_slot (user_id, slot_number)
    );

CREATE TABLE IF NOT EXISTS player_crew (
    slot_id INT NOT NULL,
    member_name VARCHAR(64) NOT NULL,
    role_class VARCHAR(64) NOT NULL,
    is_alive BOOLEAN DEFAULT TRUE,
    PRIMARY KEY (slot_id, member_name),
    FOREIGN KEY (slot_id) REFERENCES player_slots(slot_id) ON DELETE CASCADE
    );

CREATE TABLE IF NOT EXISTS player_visited_locations (
    slot_id INT NOT NULL,
    location_key VARCHAR(64) NOT NULL,
    is_blocked BOOLEAN DEFAULT FALSE,
    PRIMARY KEY (slot_id, location_key),
    FOREIGN KEY (slot_id) REFERENCES player_slots(slot_id) ON DELETE CASCADE
    );

CREATE TABLE IF NOT EXISTS player_history (
    history_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    chosen_choice_id INT NOT NULL,
    selected_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (chosen_choice_id) REFERENCES story_choices(choice_id) ON DELETE CASCADE
    );

-- Initialisierung der Slots auf Startknoten 10 (Charakterauswahl)
INSERT IGNORE INTO player_slots (user_id, slot_number, current_node_id) VALUES
    (1, 1, 10), (1, 2, 10), (1, 3, 10),
    (2, 1, 10), (2, 2, 10), (2, 3, 10),
    (3, 1, 10), (3, 2, 10), (3, 3, 10);

/**
 * SECTION 4: Kampagnen-Inhalt & Auswahloptionen
 */
-- 4a. Kampagnen-Knotenpunkte einspielen
INSERT IGNORE INTO story_nodes (node_id, title, description, active_sector, is_ending) VALUES
(100, 'Scene 1: Prologue – The Echo of the Past', 'A lonely, dying campfire crackles in the cold wind. CITIZEN G\'KAR sits motionlessly by the fire. His heavy, patterned robe is dusty, his single, intact eye reflecting the matte play of the flames.\n\nG\'KAR:\n"The universe is in a constant change, unstoppable and blind to the suffering of those who cross its paths..."', 'Moon / No Man\'s Land', FALSE),
(101, 'Scene 2: The Spark in the Dark', 'The air tastes of recycled oxygen, cheap synthetic coffee and the stale heat of unmonitored freighter valves. The COMMANDER moves like a ghost through the dusty gray of the lower levels.\n\nTHE RANGER:\n"Take it... Bring it... personally to the command staff... Trust no one... The sleepers are awaking... We die... for the One..."', 'Brown Sector', FALSE),
(102, 'Scene 3: The Revolt of Silence', 'The Commander materializes from the shadows directly inside Zack Allan\'s private office, drops the crystal onto the table without a single word. Outside in space, a massive hyperspace vortex ignites. A sleek, dark ship glides silently into the sector. Meldor has reached Babylon 5.\n\nZACK ALLAN:\n"Don\'t move... Earth is gone. Absolute dead silence on all relays."', 'Zack Allan\'s Office', FALSE),
(103, 'Scene 4: The Negotiation of the Alliance', 'Captain Lochley fixes the Commander with an unyielding stare.\n\nLOCHLEY:\n"Your file is a ghost story. Who are you, and why did that Ranger pick you in the bowels of the station?"\n\nZACK ALLAN:\n"Our Commander here used to run covert operations with Marcus Cole..."\n\nLOCHLEY:\n"Deep in Pirate Sector 14 lies a hidden prototype cruiser, an Earth-Minbari hybrid: the LIBURNIA. Bring the constructor Sha\'In and the Liburnia home."', 'Captain\'s Office', FALSE),
(104, 'Scene 5: The Intrusion into Sector 14', 'Through a decommissioned waste chute, the Commander infiltrates the interior of a wrecked warship that serves as a base for the Raiders. In front of a high-frequency energy cell, two heavily armed mercenaries patrol. In the cell sits SHA\'IN – an Ipsha master constructor, exhausted but with an unbroken, proud gaze.', 'Sector 14 Pirate Base', FALSE),
(105, 'Scene 6: The Bridge of the EAS Liburnia', 'Sha\'In slams her private override code into the heavy hangar lock. The blast doors reveal the breathtaking silhouette of the EAS LIBURNIA. Silver Minbari biocrystals run like organic tendons through Earthforce steel.\n\nSHA\'In:\n"Damn it... it\'s my father\'s language-pattern protocol! The binary computer of Earth refuses to interpret the flowing light patterns of the Minbari matrix..."', 'EAS Liburnia - Command Deck', FALSE),
(106, 'Scene 7: The Interstellar Dialogue', 'The ship glides calmly through the restless glow of hyperspace. Sha\'In looks up from her console, observing the Commander. Down below in the cargo bay, Moka, the stumpy Drazi technician, slams a massive sledgehammer onto a blocked energy coupling.\n\nMOKA:\n"Getting stuck is bad for ship. If it won\'t flow, you must break it, Ipsha!"', 'EAS Liburnia - Hyperspace Transit', FALSE),
(107, 'Scene 8: The Alliance Palace on Minbar', 'The Liburnia enters orbit around Minbar. President John Sheridan awaits you in the light-flooded corridors of the Alliance Palace.\n\nSHERIDAN:\n"This is a pure phantom operation. If you are caught out there, the Alliance will deny any connection to you..."\n\nOn the steps of the exit, Delenn presses a cold, highly encrypted data crystal into your hand.', 'Minbar - Alliance Palace', FALSE),
(108, 'Scene 9: The Ashes of the Past', 'The cloaked Liburnia glides like a silent ghost in front of an ash-gray, dying planet. Below stretch the needle-like, petrified ruins.\n\nMELDOR:\n"The Psi-Corps did not find this place by accident. They use the dark relics left behind on this world for their purposes..."', 'Dead Sector - Orbit / Surface', FALSE),
(109, 'Scene 10: The Interstellar Ambush', 'The Liburnia chases towards the Epsilon sector. Suddenly, the ship shakes under a brutal impact! Three heavy Raider fighters intercept your engine resonance.\n\nSHA\'IN:\n"A plasma bolt pierced our primary distribution! If we don\'t contain the pressure manually, the core will explode!"', 'EAS Liburnia - Hyperspace Transit', FALSE),
(110, 'Scene 11: The Arrival in Hangar 7', 'Thick, white cooling steam hisses from the torn vents of the Liburnia as the engines finally die out. Captain Elizabeth Lochley awaits you at the foot of the ramp.\n\nLOCHLEY:\n"You look like you\'ve flown straight through hell, Commander. President Sheridan sent confirmation. The Great Machine is ready."', 'Babylon 5 - Hangar 7', FALSE),
(111, 'Scene 12: The Lower Levels of Epsilon III', 'The transport pod docks at a subterranean airlock. The interior of the planet is an endless canyon of breathing, pulsating technology. Out of the steam stumbles Zathras.\n\nZATHRAS:\n"No, no, no! Zathras said, first the primary coupling, then the guests! But nobody listens to Zathras. Too much time, too little Zathras!"', 'Epsilon III - The Great Machine', FALSE),
(112, 'Scene 13: Draal\'s Metaphors', 'You reach a gewaltige, floating platform. Draal steps forward from the shimmering light, his eyes glowing with unearthly power.\n\nDRAAL:\n"Greetings, traveler. We will give a voice to this silent cage! Beware of the chamber where the echo of old betrayal sleeps..."\n\nThe energy waves break the blockade and the Earth signal bursts through!', 'Epsilon III - Central Core', FALSE),
(113, 'Scene 14: The Franklin Protocol', 'Dr. Sarah Chambers guides a regeneration scanner over your burns, fully restoring your HP. The main projector displays a life-sized hologram of Dr. Stephen Franklin from the Earth underground.\n\nFRANKLIN:\n"Bester is using a modified variant of the Drakh plague to pull up a worldwide, unhackable sleeper network via the telepaths!"', 'Babylon 5 - Medlab One', FALSE),
(114, 'Scene 15: The Free Vector (Missions-Hub)', 'The tactical mission center in the hub is unlocked. The Liburnia is fully operational. Select your hyperspace vector to assemble the pieces of the old alliance and secure the Section 13 data logs.', 'EAS Liburnia - Tactical Hub', FALSE),
(115, 'Scene 16: Centauri Prime – The Net of the Spider', 'The Liburnia touches down on the grand landing platforms of Centauri Prime. EMPEROR LONDO MOLLARI sits on his heavy throne, his eyes narrow, filled with the weariness of a man carrying chains he cannot show.\n\nLONDO MOLLARI:\n"Ah, the Alliance emissary... Sheridan sends you to beg for my royal fleet, yes? I demand exclusive, absolute colonial authority over the Narn sectors once this war is over. Sign the military pact, or leave my palace empty-handed."', 'Centauri Prime - Imperial Throne Room', FALSE),
(116, 'Scene 17: Narn Homeworld – The Citadel of Red Sand', 'The Liburnia drops out of the burning crimson skies of Narn. You enter the heavy, sand-scoured halls of the Central Military Citadel. The Elders of the newly restored Kha\'Ri gather around the stone tactical grid, their eyes filled with the ancient pride of a liberated race.', 'Narn Homeworld - Central Military Citadel', FALSE),
(117, 'Scene 18: The Stranded Alliance Wreck', 'While maneuvering through the hyperdrive streams, Sha\'In intercepts a faint, distorted emergency signal. A drifting, lifeless ghost ship of the Interstellar Alliance is caught in a gravitational shear wave. The Commander leads the team inside the freezing, creaking ruins of the abandoned cruiser to secure resources. Suddenly, a massive structural steel girder collapses directly above Sha\'In!', 'Hyperdrive Transit Vector - Alliance Wreck', FALSE),
(118, 'Scene 22: Lyta\'s Refuge (The Last Crossroad)', 'The tracking vectors lead you into a dark, smoky bar buried deep inside a hollow asteroid. LYTA ALEXANDER sits in a dimly lit corner, her hood pulled low over her face. As you approach her table, her head snaps up. Her eyes flash with an unnatural, terrifying Vorlon-blue light.\n\nLYTA (Mental Projection):\n"You want my power to tear down Bester\'s walls. I want retribution. I want to see this damned Corps burn!"', 'Smuggler Outpost - Asteroid Bar', FALSE),
(120, 'Scene 23: The Mars Coup', 'The Liburnia materializes inside a massive, unmapped hangar deep within the crimson rock of the Tharsis volcanic crater. MICHAEL GARIBALDI sits at a long workbench covered in dismantled plasma rifles and scrambler units.\n\nGARIBALDI:\n"Good to see you alive, Commander. Bester\'s black-ops network inside the Tharsis lab is heavily fortified. They are running Section 13 protocols—the most paranoid encryption the Psi-Corps ever developed."', 'Mars - Tharsis Crater Hangar', FALSE),
(121, 'Scene 24: The Coupe in Sector 13', 'Alarms are blaring in low crimson frequencies. Armed with Garibaldi\'s codes, the Commander overrides the primary biometric security vault. Biocrystalline data tubes pulse in an eerie, synthetic white. As the progress bar hits 100%, a sudden realization hits the team: The central medical isolation tank is empty—Talia Winters has been moved to Geneva!', 'Mars - Tharsis Crater Lab', FALSE),
(122, 'Scene 25: The Escape from Tharsis', 'You burst onto the bridge of the Liburnia, the stolen Mars Section 13 files securely locked into the mainframe. Outside, the entire Tharsis crater facility is waking up. Automated orbital laser batteries are locking onto your exact hull signature. Heavy, high-energy defensive beams tear through the hangar structures!', 'Mars Atmosphere - Command Deck', FALSE),
(119, 'Scene 26: The Reunion in Hyperspace', 'The Liburnia slips into the dense, purple ion-shrouds of a hidden nebula, docking with General Susan Ivanova\'s rogue rebel fleet. Michael Garibaldi charges into the mess hall holding a sizzling pan of traditional Earth meat! Gathered around the table, the old command staff breaks bread and toasts to the memory of MARCUS COLE.\n\nIVANOVA:\n"We honor his sacrifice by finishing this fight. My ships are locked onto our vector. We are going to hammer Bester\'s net into absolute dust."', 'Hidden Nebula - Rebel Fleet Mess Hall', FALSE),
(123, 'Scene 27: The Geneva Stratospheric Approach', 'The viewscreen reveals the sapphire-blue marble of Earth, surrounded by Bester\'s loyal Earthforce destroyers and Black Omega fighters. The entire rebel fleet ignites fusion drives simultaneously, screaming forward in a massive wave of fire.\n\nSHA\'IN:\n"Thermal hull shields are melting, Commander! Two Black Omega heavy interceptors are locking onto our engine matrix! Choose our atmospheric entry angle immediately!"', 'Planet Earth - Upper Atmosphere', FALSE),
(124, 'Scene 28: Garibaldi\'s Global Subspace Broadcast', 'While the Liburnia dives, Michael Garibaldi breaches the primary ISN propaganda relay station over the Geneva grid. His unyielding face cuts through the worldwide broadcast network.\n\nGARIBALDI (Global Broadcast):\n"Bester hasn\'t just sealed Earth. He is holding hundreds of innocent people inside a biological research network beneath Geneva! Look at the screens. Look at the truth!"', 'Global Earth Network - Transmission Deck', FALSE),
(125, 'Scene 29: The Glass Ceiling Bursts', 'With an ear-splitting, thunderous roar, the armored hull of the Liburnia slams directly through the colossal structural glass roof of the Psi-Corps Headquarters lounge! A crystalline rain of a million razor-sharp shards explodes. You, General Ivanova, and elite ground teams drop from the ramps with weapons raised!', 'Psi-Corps Headquarters - Lounge Area', FALSE),
(126, 'Scene 30: The Inner Sanctum', 'The heavy biocrystalline doors blow apart. Inside Bester\'s matrix chamber, TALIA WINTERS is trapped in the center, suspended in stasis wires with cybernetic cables feeding directly into her synapses. Alfred Bester stands calmly, his thumb resting lightly on a matte-black master detonator.\n\nBESTER:\n"One wrong move, Commander, and I fry the synapses of every single sleeper mind on this planet."', 'Psi-Corps HQ - Bester\'s Matrix Chamber', FALSE),
(127, 'Scene 31: The Final Battle – The Evaluation of the Timeline', 'The screaming sirens are slowly dying down. The central neural server drops its shields, and the quantum compiler of the Liburnia evaluates your structural footprint in the dust to compile your final calculated ending.', 'Psi-Corps HQ - Main Matrix Terminal', FALSE),
(128, 'Scene 32: Epilogue – The Sub-Space Frequency from Tuzanor', 'A quiet, respectful calm settles over the bridge of the Liburnia. The lifelike hologram of PRESIDENT JOHN SHERIDAN appears, broadcasting securely from Minbar.\n\nSHERIDAN (Hologram):\n"The provisional government has ordered the complete dissolution of the Corps. Even if the public will never know of your covert mission... so bin ich mir ziemlich sicher, dass Marcus besonders proud of Sie wäre. Come home. Sheridan out."\n\n=== FADE TO WHITE - END OF THE SHADOWS WITHIN ===', 'EAS Liburnia - Command Deck / Earth Orbit', TRUE)
ON DUPLICATE KEY UPDATE title = VALUES(title), description = VALUES(description), active_sector = VALUES(active_sector), is_ending = VALUES(is_ending);

/**
 * SECTION 5: Story-Entscheidungen & Charakter-Setup (Choices)
 * Target: MySQL Port 3307
 */
USE b5_project;

-- Alte unvollständige Fragmente entfernen
DELETE FROM story_choices WHERE from_node_id IN (10, 100, 101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 113, 114, 119, 123, 124, 125, 126, 127);

-- Charakter-Setup Knoten (Node 10)
INSERT INTO story_nodes (node_id, title, description, active_sector, is_ending)
VALUES (10, '=== THE SHADOWS WITHIN - INITIAL SETUP ===', 'Wähle dein Hintergrundsystem aus, um deine Identität in den Systemdaten zu registrieren:', 'Sektor Babylon 5', FALSE)
ON DUPLICATE KEY UPDATE title = VALUES(title), description = VALUES(description);

-- Optionen für das Charakter-Setup
INSERT INTO story_choices (choice_id, from_node_id, next_node_id, choice_text, effect_credits, effect_analysis_focus, effect_origin) VALUES
(998, 10, 100, 'Intelligence (Ex-Agent) -> Start mit optimiertem Analyse-Fokus (95) & taktischen Hacking-Vorteilen.', 0, 95, 'Intelligence'),
(999, 10, 100, 'Underworld (Smuggler) -> Start mit erhöhtem Schmuggel-Budget (+30.000 Credits) & Korruptions-Optionen.', 30000, 10, 'Underworld');

-- Alle regulären Kampagnen-Optionen
INSERT INTO story_choices (choice_id, from_node_id, next_node_id, choice_text, effect_credits, effect_analysis_focus, effect_origin) VALUES
(1, 100, 101, 'Press Enter to ignite prologue and witness the crossing of destinies.', 0, 0, NULL),
(2, 101, 102, 'PATH 1: Activate the illegal modified Chameleon Net to evade the telepath agents.', 0, 0, NULL),
(3, 101, 102, 'PATH 2: Sprint to the open grates and slide down a scrap chute (Warning: Suffers -25 HP damage!).', 0, 0, NULL),
(4, 101, 102, 'PATH 3: Trigger class-specific reaction (Intelligence System Overrides / Underworld Bribery for 5,000 Credits).', 0, 0, NULL),
(5, 102, 103, 'Press Enter to ascend to the Command Deck with Zack Allan.', 0, 0, NULL),
(6, 103, 104, 'PATH 1: Demand conditions based on your hidden file (+20 Lochley Relationship).', 0, 0, NULL),
(7, 103, 104, 'PATH 2: Honor the legacy of your fallen comrade Marcus Cole unconditionally (+25 Ivanova Relationship).', 0, 0, NULL),
(8, 104, 105, 'Choice 1: Tactical attack - Fire your PPG openly (Triggers Alarm / Suffer -10 HP on escape).', 0, 0, NULL),
(9, 104, 105, 'Choice 2: Silent neutralization - Drop from the ceiling pipes and knock out the guards.', 0, 0, NULL),
(10, 104, 105, 'Choice 3: A distraction maneuver - Throw heavy fusion coupling down the corridor.', 0, 0, NULL),
(11, 104, 105, 'Choice 4: Class-specific tactical strike / Smuggler Bribery (Spend 15,000 Credits).', -15000, 0, NULL),
(12, 105, 106, 'WEG 1: Enforce human protocols via Binary Compression (Systems run stable but rigid).', 0, 0, NULL),
(13, 105, 106, 'WEG 2: Give free rein to Minbari logic via Organic Stream (Warning: Displays shift to alien glyphs!).', 0, 0, NULL),
(14, 105, 106, 'WEG 3: Bridge the analytical gap using hybrid translation protocols (Requires High Analysis).', 0, 0, NULL),
(15, 106, 107, 'Press Enter to complete hyperspace transit and approach Minbar orbit.', 0, 0, NULL),
(16, 107, 108, 'Press Enter to deploy the encrypted IPX dossier and jump to the Dead Sector.', 0, 0, NULL),
(17, 108, 109, 'Execute deep orbital scan filter to isolate the organic radiation of the ruins.', 0, 0, NULL),
(18, 109, 110, 'Sprint down to the reactor section and force the manual plasma valve override.', 0, 0, NULL),
(19, 110, 111, 'Press Enter to board the transport pod and descend into Epsilon III.', 0, 0, NULL),
(20, 111, 112, 'Follow Zathras over the narrow catwalk into the mechanical heart of the planet.', 0, 0, NULL),
(21, 112, 113, 'Reroute the immense energy of the Great Machine through subspace to break the Earth blockade.', 0, 0, NULL),
(22, 113, 114, 'Press Enter to close Medlab module and unlock the Act III Core Navigation Hub.', 0, 0, NULL),
(23, 114, 113, 'Vector 1: Return to Sector Babylon 5 (Medlab Base - Allowed only if unblocked).', 0, 0, NULL),
(24, 114, 115, 'Vector 2: Set course for Centauri Prime Imperial Palace (Triggers Scene 18 Wreck on first transit).', 0, 0, NULL),
(25, 114, 116, 'Vector 3: Set course for Narn Homeworld Military Citadel (Triggers Scene 18 Wreck on first transit).', 0, 0, NULL),
(26, 114, 117, 'Vector 4: Infiltrate Tharsis Crater Lab in the Mars Sector (Unlocks final approach on success).', 0, 0, NULL),
(27, 114, 118, 'Vector 5: Travel to Lyta\'s Refuge in the lawless asteroid bar.', 0, 0, NULL),
(28, 114, 119, 'Vector 8: [FINAL APPROACH] Initiate stratospheric drop over Geneva (Requires Mars Section 13 data logs).', 0, 0, NULL),
(29, 119, 123, 'Choice 1: Present the compiled political data logs of your allies (+5 Analysis Focus).', 0, 5, NULL),
(30, 119, 123, 'Choice 2: Skip Briefing and move straight to the tactical assault deployment grids.', 0, 0, NULL),
(31, 123, 124, 'Vector 1: Aggressive dive - Maximum thrust to overpower interceptors (Suffer -15 HP damage).', 0, 0, NULL),
(32, 123, 124, 'Vector 2: Evasive glide - Roll the ship into the turbulent ionization thermal blind spots.', 0, 0, NULL),
(33, 124, 125, 'Execute Garibaldi\'s global subspace transmission override to shock the planet.', 0, 0, NULL),
(34, 125, 126, 'Vector 1: Forceful Breach - Charge the central elevator shafts using heavy tactical cover fire (Suffer -10 HP).', 0, 0, NULL),
(35, 125, 126, 'Vector 2: Flanking Maneuver - Split the squad into the side ventilation grids for a silent lock.', 0, 0, NULL),
(36, 126, 127, 'Strike 1: Activate Meldor\'s technomage phantomschwarm-override to isolate detonator frequencies.', 0, 0, NULL),
(37, 126, 127, 'Strike 2: Engage Alfred Bester in raw, bloody physical hand-to-hand combat (Suffer -35 HP damage!).', 0, 0, NULL),
(38, 127, 128, 'Compile and execute final timeline parameters to witness your calculated campaign ending.', 0, 0, NULL);

/**
 * SECTION 6: Spieler-Initialisierung & Historie
 * Target: MySQL Port 3307
 */
USE b5_project;

-- Setzt alle Spielstände standardmäßig auf den neuen Startknoten 10 (Charakterauswahl)
UPDATE player_slots SET current_node_id = 10;

-- Falls die Datensätze noch nicht existieren, werden sie hier initialisiert
INSERT IGNORE INTO player_slots (user_id, slot_number, current_node_id) VALUES
    (1, 1, 10), (1, 2, 10), (1, 3, 10),
    (2, 1, 10), (2, 2, 10), (2, 3, 10),
    (3, 1, 10), (3, 2, 10), (3, 3, 10);

-- Verknüpft die Test-Historie mit den neuen Setup-Choice-IDs
INSERT IGNORE INTO player_history (user_id, chosen_choice_id) VALUES
    (1, 998),
    (2, 999),
    (3, 998);