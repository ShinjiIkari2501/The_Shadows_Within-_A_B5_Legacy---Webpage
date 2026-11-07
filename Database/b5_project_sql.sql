-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql112.infinityfree.com
-- Erstellungszeit: 07. Nov 2026 um 09:17
-- Server-Version: 11.4.13-MariaDB
-- PHP-Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `if0_43374783_b5_project`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `contact_messages`
--
-- Erstellt am: 07. Nov 2026 um 13:44
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `sender_name` varchar(255) NOT NULL,
  `sender_email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `items`
--
-- Erstellt am: 06. Nov 2026 um 21:59
--

CREATE TABLE `items` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(64) NOT NULL,
  `item_type` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `items`
--

INSERT INTO `items` (`item_id`, `item_name`, `item_type`) VALUES
(5, 'Psi-Rating Scanner', 'Equipment');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `player_crew`
--
-- Erstellt am: 06. Nov 2026 um 21:59
--

CREATE TABLE `player_crew` (
  `slot_id` int(11) NOT NULL,
  `member_name` varchar(64) NOT NULL,
  `role_class` varchar(64) NOT NULL,
  `is_alive` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `player_history`
--
-- Erstellt am: 06. Nov 2026 um 21:59
--

CREATE TABLE `player_history` (
  `history_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `chosen_choice_id` int(11) NOT NULL,
  `selected_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `player_history`
--

INSERT INTO `player_history` (`history_id`, `user_id`, `chosen_choice_id`, `selected_at`) VALUES
(1, 1, 1, '2026-11-06 22:27:36'),
(2, 1, 998, '2026-11-06 23:18:13'),
(3, 1, 2, '2026-11-07 04:28:10'),
(4, 1, 5, '2026-11-07 04:28:13'),
(5, 1, 6, '2026-11-07 04:28:32');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `player_slots`
--
-- Erstellt am: 06. Nov 2026 um 21:59
--

CREATE TABLE `player_slots` (
  `slot_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `slot_number` int(11) NOT NULL,
  `current_node_id` int(11) NOT NULL DEFAULT 100,
  `character_origin` varchar(50) DEFAULT 'Standard',
  `alignment_score` int(11) DEFAULT 0,
  `analysis_focus` int(11) DEFAULT 10,
  `lore_knowledge` int(11) DEFAULT 0,
  `psi_level` int(11) DEFAULT 0,
  `player_hp` int(11) DEFAULT 100,
  `credits` int(11) DEFAULT 10000,
  `ship_name` varchar(64) DEFAULT 'EAS Liburnia',
  `ship_obtained` tinyint(1) DEFAULT 0,
  `ship_stealth` int(11) DEFAULT 0,
  `ship_calibration_path` int(11) DEFAULT 0,
  `bester_threat_level` int(11) DEFAULT 0,
  `drakh_influence` int(11) DEFAULT 0,
  `control_decryption_progress` int(11) DEFAULT 0,
  `great_machine_energy` int(11) DEFAULT 0,
  `has_delenns_data_crystal` tinyint(1) DEFAULT 0,
  `is_narn_avantgarde_recruited` tinyint(1) DEFAULT 0,
  `is_narn_mercenaries_hired` tinyint(1) DEFAULT 0,
  `lyta_recruited` tinyint(1) DEFAULT 0,
  `talias_trauma_resolved` tinyint(1) DEFAULT 0,
  `constructor_rescued` tinyint(1) DEFAULT 0,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `story_flags` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `player_slots`
--

INSERT INTO `player_slots` (`slot_id`, `user_id`, `slot_number`, `current_node_id`, `character_origin`, `alignment_score`, `analysis_focus`, `lore_knowledge`, `psi_level`, `player_hp`, `credits`, `ship_name`, `ship_obtained`, `ship_stealth`, `ship_calibration_path`, `bester_threat_level`, `drakh_influence`, `control_decryption_progress`, `great_machine_energy`, `has_delenns_data_crystal`, `is_narn_avantgarde_recruited`, `is_narn_mercenaries_hired`, `lyta_recruited`, `talias_trauma_resolved`, `constructor_rescued`, `last_updated`, `story_flags`) VALUES
(1, 1, 1, 104, 'Intelligence', -5, 95, 0, 0, 100, 10000, 'EAS Liburnia', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2026-11-07 04:28:32', '{\"seal\":1}'),
(2, 1, 2, 100, 'Standard', 0, 10, 0, 0, 100, 10000, 'EAS Liburnia', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2026-11-06 22:26:57', NULL),
(3, 1, 3, 100, 'Standard', 0, 10, 0, 0, 100, 10000, 'EAS Liburnia', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2026-11-06 22:26:57', NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `player_visited_locations`
--
-- Erstellt am: 06. Nov 2026 um 21:59
--

CREATE TABLE `player_visited_locations` (
  `slot_id` int(11) NOT NULL,
  `location_key` varchar(64) NOT NULL,
  `is_blocked` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `story_choices`
--
-- Erstellt am: 06. Nov 2026 um 21:59
--

CREATE TABLE `story_choices` (
  `choice_id` int(11) NOT NULL,
  `from_node_id` int(11) NOT NULL,
  `next_node_id` int(11) NOT NULL,
  `choice_text` text NOT NULL,
  `effect_credits` int(11) DEFAULT 0,
  `effect_analysis_focus` int(11) DEFAULT 0,
  `effect_origin` varchar(50) DEFAULT NULL,
  `requires` text DEFAULT NULL,
  `sets` text DEFAULT NULL,
  `locked_hint` text DEFAULT NULL,
  `route` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `story_choices`
--

INSERT INTO `story_choices` (`choice_id`, `from_node_id`, `next_node_id`, `choice_text`, `effect_credits`, `effect_analysis_focus`, `effect_origin`, `requires`, `sets`, `locked_hint`, `route`) VALUES
(1, 100, 10, 'Press Enter to ignite prologue and witness the crossing of destinies.', 0, 0, NULL, NULL, '[\"seal=1\"]', NULL, NULL),
(2, 101, 102, 'PATH 1: Activate the illegal modified Chameleon Net to evade the telepath agents.', 0, 0, NULL, NULL, NULL, NULL, NULL),
(3, 101, 102, 'PATH 2: Sprint to the open grates and slide down a scrap chute (Warning: Suffers -25 HP damage!).', 0, 0, NULL, NULL, NULL, NULL, NULL),
(4, 101, 102, 'PATH 3: Trigger class-specific reaction (Intelligence System Overrides / Underworld Bribery for 5,000 Credits).', 0, 0, NULL, NULL, NULL, NULL, NULL),
(5, 102, 103, 'Press Enter to ascend to the Command Deck with Zack Allan.', 0, 0, NULL, NULL, NULL, NULL, NULL),
(6, 103, 104, 'PATH 1: Demand conditions based on your hidden file (+20 Lochley Relationship).', 0, 0, NULL, NULL, '[\"alignment-=5\"]', NULL, NULL),
(7, 103, 104, 'PATH 2: Honor the legacy of your fallen comrade Marcus Cole unconditionally (+25 Ivanova Relationship).', 0, 0, NULL, NULL, '[\"alignment+=10\"]', NULL, NULL),
(8, 104, 105, 'Choice 1: Tactical attack - Fire your PPG openly (Triggers Alarm / Suffer -10 HP on escape).', 0, 0, NULL, NULL, '[\"alignment-=5\"]', NULL, NULL),
(9, 104, 105, 'Choice 2: Silent neutralization - Drop from the ceiling pipes and knock out the guards.', 0, 0, NULL, NULL, NULL, NULL, NULL),
(10, 104, 105, 'Choice 3: A distraction maneuver - Throw heavy fusion coupling down the corridor.', 0, 0, NULL, NULL, NULL, NULL, NULL),
(11, 104, 105, 'Choice 4: Class-specific tactical strike / Smuggler Bribery (Spend 15,000 Credits).', -15000, 0, NULL, NULL, '[\"alignment-=5\"]', NULL, NULL),
(12, 105, 106, 'WEG 1: Enforce human protocols via Binary Compression (Systems run stable but rigid).', 0, 0, NULL, NULL, '[\"calibration=1\"]', NULL, NULL),
(13, 105, 106, 'WEG 2: Give free rein to Minbari logic via Organic Stream (Warning: Displays shift to alien glyphs!).', 0, 0, NULL, NULL, '[\"calibration=2\"]', NULL, NULL),
(14, 105, 106, 'WEG 3: Bridge the analytical gap using hybrid translation protocols (Requires High Analysis).', 0, 0, NULL, '[\"focus>=60\"]', '[\"calibration=3\"]', 'Requires high analysis focus (60 or higher)', NULL),
(15, 106, 107, 'Press Enter to complete hyperspace transit and approach Minbar orbit.', 0, 0, NULL, NULL, NULL, NULL, NULL),
(16, 107, 108, 'Press Enter to deploy the encrypted IPX dossier and jump to the Dead Sector.', 0, 0, NULL, NULL, '[\"crystal=1\"]', NULL, NULL),
(17, 108, 109, 'Execute deep orbital scan filter to isolate the organic radiation of the ruins.', 0, 0, NULL, NULL, NULL, NULL, NULL),
(18, 109, 110, 'Sprint down to the reactor section and force the manual plasma valve override (rigid human protocols: -30 HP).', 0, 0, NULL, '[\"calibration!=2\",\"calibration!=3\"]', '[\"hp-=30\"]', NULL, NULL),
(19, 110, 111, 'Press Enter to board the transport pod and descend into Epsilon III.', 0, 0, NULL, NULL, NULL, NULL, NULL),
(20, 111, 112, 'Follow Zathras over the narrow catwalk into the mechanical heart of the planet.', 0, 0, NULL, NULL, NULL, NULL, NULL),
(21, 112, 113, 'Reroute the immense energy of the Great Machine through subspace to break the Earth blockade.', 0, 0, NULL, NULL, NULL, NULL, NULL),
(22, 113, 114, 'Press Enter to close Medlab module and unlock the Act III Core Navigation Hub.', 0, 0, NULL, NULL, '[\"hub_seen=1\"]', NULL, NULL),
(23, 114, 113, 'Vector 1: Return to Babylon 5 Medlab (treatment and briefing replay)', 0, 0, NULL, '[\"mars_gate=0\"]', NULL, 'Medlab is out of reach after Mars', NULL),
(24, 114, 200, 'Vector 2: Set course for Centauri Prime Imperial Palace', 0, 0, NULL, '[\"mars_gate=0\",\"!pact\"]', '[\"visited_centauri=1\",\"jumps+=1\",\"threat+=3\"]', 'Centauri Prime: the pact is already decided', NULL),
(25, 114, 201, 'Vector 3: Set course for Narn Homeworld Military Citadel', 0, 0, NULL, '[\"mars_gate=0\",\"!narn_done\"]', '[\"visited_narn=1\",\"jumps+=1\",\"threat+=3\"]', 'Narn Homeworld: already resolved', NULL),
(26, 114, 180, 'Vector 4: Infiltrate Tharsis Crater Lab on Mars (point of no return)', 0, 0, NULL, NULL, NULL, NULL, NULL),
(27, 114, 202, 'Vector 5: Travel to Lyta\'s refuge in the lawless asteroid bar', 0, 0, NULL, '[\"mars_gate=0\",\"lyta_visits<1\"]', '[\"jumps+=1\",\"threat+=3\"]', 'Lyta: she will not listen again without proof from Mars', NULL),
(28, 181, 123, 'Initiate the stratospheric drop over Geneva', 0, 0, NULL, NULL, NULL, NULL, NULL),
(29, 119, 181, 'Choice 1: Present the compiled political data logs of your allies (+5 Analysis Focus).', 0, 5, NULL, NULL, '[\"alignment+=5\"]', NULL, NULL),
(30, 119, 181, 'Choice 2: Skip Briefing and move straight to the tactical assault deployment grids.', 0, 0, NULL, NULL, NULL, NULL, NULL),
(31, 123, 124, 'Vector 1: Aggressive dive - Maximum thrust to overpower interceptors (Suffer -15 HP damage).', 0, 0, NULL, NULL, NULL, NULL, NULL),
(32, 123, 124, 'Vector 2: Evasive glide - Roll the ship into the turbulent ionization thermal blind spots.', 0, 0, NULL, NULL, NULL, NULL, NULL),
(33, 124, 125, 'Execute Garibaldi\'s global subspace transmission override to shock the planet.', 0, 0, NULL, NULL, NULL, NULL, NULL),
(34, 125, 126, 'Vector 1: Forceful Breach - Charge the central elevator shafts using heavy tactical cover fire (Suffer -10 HP).', 0, 0, NULL, NULL, NULL, NULL, NULL),
(35, 125, 126, 'Vector 2: Flanking Maneuver - Split the squad into the side ventilation grids for a silent lock.', 0, 0, NULL, NULL, NULL, NULL, NULL),
(36, 126, 127, 'Strike 1: Activate Meldor\'s technomage phantomschwarm-override to isolate detonator frequencies.', 0, 0, NULL, NULL, '[\"final=meldor\"]', NULL, NULL),
(37, 126, 127, 'Strike 2: Engage Alfred Bester in raw, bloody physical hand-to-hand combat (Suffer -35 HP damage!).', 0, 0, NULL, NULL, '[\"final=melee\"]', NULL, NULL),
(38, 127, 128, 'Compile and execute final timeline parameters to witness your calculated campaign ending.', 0, 0, NULL, NULL, NULL, NULL, 'ending'),
(100, 115, 151, 'Sign Londo\'s military pact (heavy Centauri warships, Drakh influence rises)', 0, 0, NULL, '[\"!visited_narn\"]', '[\"pact=londo\",\"drakh+=15\",\"alignment-=15\"]', NULL, NULL),
(101, 115, 151, 'Sign Londo\'s military pact (Narn learn of the betrayal immediately)', 0, 0, NULL, '[\"visited_narn\"]', '[\"pact=londo\",\"drakh+=15\",\"alignment-=15\",\"narn_state=betrayed\",\"narn_avantgarde=0\",\"narn_done=0\"]', NULL, NULL),
(102, 115, 152, 'Refuse Londo and follow Vir Cotto (covert rescue fleet, Drakh influence falls)', 0, 0, NULL, '[\"!visited_narn\"]', '[\"pact=vir\",\"drakh-=15\",\"alignment+=10\"]', NULL, NULL),
(103, 115, 152, 'Refuse Londo and follow Vir Cotto (the Narn will hear that you defied him)', 0, 0, NULL, '[\"visited_narn\"]', '[\"pact=vir\",\"drakh-=15\",\"alignment+=10\",\"narn_state=allied\",\"narn_avantgarde=1\"]', NULL, NULL),
(104, 151, 114, 'Leave Centauri Prime', 0, 0, NULL, NULL, NULL, NULL, NULL),
(106, 152, 114, 'Leave Centauri Prime', 0, 0, NULL, NULL, NULL, NULL, NULL),
(108, 117, 161, 'Throw yourself over Sha\'In (-20 HP, +25,000 credits from the war chest)', 25000, 0, NULL, NULL, '[\"hp-=20\",\"alignment+=5\",\"wreck_style=hero\",\"wreck_done=1\"]', NULL, NULL),
(109, 117, 161, 'Order Moka to hold the girder and hack the terminal first (-10 HP, +25,000 credits)', 25000, 0, NULL, NULL, '[\"hp-=10\",\"wreck_style=tactical\",\"wreck_done=1\"]', NULL, NULL),
(110, 161, 114, 'Return to the Tactical Hub', 0, 0, NULL, NULL, NULL, NULL, NULL),
(111, 116, 141, 'Show G\'Kar\'s seal of the Kha\'Ri, the last seal of the old council', 0, 0, NULL, '[\"seal\",\"!pact\"]', '[\"narn_state=allied\",\"narn_avantgarde=1\",\"narn_done=1\",\"alignment+=5\"]', NULL, NULL),
(112, 116, 141, 'Tactical alliance bluff: claim backing from the Alliance that you do not have', 0, 0, NULL, '[\"!pact\"]', '[\"narn_state=wary\",\"narn_avantgarde=1\",\"narn_done=1\",\"alignment-=5\"]', NULL, NULL),
(113, 116, 141, 'Address the Kha\'Ri: you refused Londo\'s offer and stand with Narn', 0, 0, NULL, '[\"pact=vir\"]', '[\"narn_state=allied\",\"narn_avantgarde=1\",\"narn_done=1\",\"alignment+=5\"]', NULL, NULL),
(114, 116, 142, 'Hire the Narn underground mercenaries (20,000 credits)', -20000, 0, NULL, '[\"pact=londo\",\"origin!=Underworld\",\"credits>=20000\"]', '[\"narn_state=mercs\",\"narn_mercs=1\",\"narn_done=1\",\"alignment-=5\"]', NULL, NULL),
(115, 116, 117, 'Withdraw from Narn (on the way out Sha\'In picks up a distress signal)', 0, 0, NULL, '[\"pact=londo\",\"!wreck_done\"]', NULL, NULL, NULL),
(120, 141, 117, 'Leave the Narn Homeworld', 0, 0, NULL, '[\"!wreck_done\"]', NULL, NULL, NULL),
(121, 141, 114, 'Leave the Narn Homeworld', 0, 0, NULL, '[\"wreck_done\"]', NULL, NULL, NULL),
(122, 142, 117, 'Leave the Narn Homeworld', 0, 0, NULL, '[\"!wreck_done\"]', NULL, NULL, NULL),
(123, 142, 114, 'Leave the Narn Homeworld', 0, 0, NULL, '[\"wreck_done\"]', NULL, NULL, NULL),
(124, 116, 114, 'Withdraw to the Tactical Hub', 0, 0, NULL, '[\"pact=londo\",\"wreck_done\"]', NULL, NULL, NULL),
(125, 115, 153, 'Smuggler\'s way: bribe the chamberlain (12,000 credits) for Londo\'s warships WITHOUT the exclusive pact', -12000, 0, NULL, '[\"origin=Underworld\",\"!visited_narn\",\"credits>=12000\"]', '[\"pact=double\",\"drakh+=10\",\"alignment-=10\"]', NULL, NULL),
(126, 115, 153, 'Smuggler\'s way: bribe the chamberlain (12,000 credits), the Narn will never learn of the price', -12000, 0, NULL, '[\"origin=Underworld\",\"visited_narn\",\"credits>=12000\"]', '[\"pact=double\",\"drakh+=10\",\"alignment-=10\",\"narn_state=allied\",\"narn_avantgarde=1\"]', NULL, NULL),
(127, 116, 142, 'Smuggler\'s connections: hire the Narn underground mercenaries at the insider price (12,000 credits)', -12000, 0, NULL, '[\"pact=londo\",\"origin=Underworld\",\"credits>=12000\"]', '[\"narn_state=mercs\",\"narn_mercs=1\",\"narn_done=1\",\"alignment-=5\"]', NULL, NULL),
(128, 118, 172, 'Smuggler\'s way: reach her through her own contacts in the Underworld', 0, 0, NULL, '[\"mars_gate=0\",\"origin=Underworld\"]', '[\"lyta_visits=1\",\"lyta_state=refused\",\"lyta_contact=1\"]', NULL, NULL),
(129, 118, 173, 'Show her the Section 13 proof: your Underworld contacts vouch for you', 0, 0, NULL, '[\"mars_gate=1\",\"mars_data\",\"lyta_contact\",\"alignment>=-15\"]', '[\"lyta=1\",\"lyta_state=light\",\"lyta_visits=2\"]', NULL, NULL),
(130, 109, 110, 'Sprint down to the reactor section and force the manual plasma valve override (Minbari glyph displays: -25 HP)', 0, 0, NULL, '[\"calibration=2\"]', '[\"hp-=25\"]', NULL, NULL),
(131, 109, 110, 'Sprint down to the reactor section and force the manual plasma valve override (perfect hybrid protocol: -10 HP)', 0, 0, NULL, '[\"calibration=3\"]', '[\"hp-=10\"]', NULL, NULL),
(132, 183, 122, 'Smuggler\'s way: pay a hangar technician to seal the fire doors between you and the Warmage (8,000 credits)', -8000, 0, NULL, '[\"origin=Underworld\",\"credits>=8000\"]', '[\"warmage=bribe\"]', NULL, NULL),
(133, 183, 122, 'Override the hangar security with your old Black Watch codes and lock the Warmage out', 0, 0, NULL, '[\"focus>=60\"]', '[\"warmage=override\"]', 'Requires analysis focus 60 or higher', NULL),
(134, 153, 114, 'Leave Centauri Prime', 0, 0, NULL, NULL, NULL, NULL, NULL),
(135, 116, 141, 'Address the Kha\'Ri: speak of the Centauri fleet and keep the price to yourself', 0, 0, NULL, '[\"pact=double\"]', '[\"narn_state=allied\",\"narn_avantgarde=1\",\"narn_done=1\"]', NULL, NULL),
(139, 126, 127, 'Strike 3: Execute Bester with the Black Watch override codes', 0, 0, NULL, '[\"alignment<=-25\"]', '[\"final=execute\"]', 'Requires a ruthless record (alignment -25 or lower)', NULL),
(140, 118, 170, 'Take her side: swear to burn the Corps with her', 0, 0, NULL, '[\"mars_gate=0\",\"alignment<=-15\"]', '[\"lyta=1\",\"lyta_state=vengeance\",\"lyta_visits=1\",\"alignment-=10\"]', NULL, NULL),
(141, 118, 172, 'Tell her Section 13 is real and that you will bring proof', 0, 0, NULL, '[\"mars_gate=0\"]', '[\"lyta_visits=1\",\"lyta_state=refused\"]', NULL, NULL),
(142, 118, 114, 'Leave without a word', 0, 0, NULL, '[\"mars_gate=0\"]', NULL, NULL, NULL),
(143, 118, 173, 'Show her the Section 13 proof: Talia is a victim, not a weapon', 0, 0, NULL, '[\"mars_gate=1\",\"mars_data\",\"lyta_visits>=1\",\"alignment>=0\"]', '[\"lyta=1\",\"lyta_state=light\",\"lyta_visits=2\"]', 'Requires Mars proof, an earlier meeting with Lyta and a compassionate record (alignment 0 or higher)', NULL),
(144, 118, 173, 'Show her the Section 13 proof: Talia is a victim, not a weapon', 0, 0, NULL, '[\"mars_gate=1\",\"mars_data\",\"lyta_visits=0\",\"alignment>=15\"]', '[\"lyta=1\",\"lyta_state=light\",\"lyta_visits=2\"]', NULL, NULL),
(145, 118, 170, 'Take her side: swear to burn the Corps with her', 0, 0, NULL, '[\"mars_gate=1\",\"alignment<=-15\"]', '[\"lyta=1\",\"lyta_state=vengeance\",\"lyta_visits=2\",\"alignment-=10\"]', NULL, NULL),
(146, 118, 174, 'Argue purely militarily: she is a weapon you need', 0, 0, NULL, '[\"mars_gate=1\"]', '[\"lyta_visits=2\",\"lyta_state=failed\",\"alignment-=5\"]', NULL, NULL),
(147, 118, 181, 'Step back to the Liburnia', 0, 0, NULL, '[\"mars_gate=1\"]', NULL, NULL, NULL),
(150, 170, 114, 'Return to the Tactical Hub', 0, 0, NULL, '[\"mars_gate=0\"]', NULL, NULL, NULL),
(151, 170, 181, 'Return to the war cabinet', 0, 0, NULL, '[\"mars_gate=1\"]', NULL, NULL, NULL),
(152, 172, 114, 'Return to the Tactical Hub', 0, 0, NULL, NULL, NULL, NULL, NULL),
(153, 173, 181, 'Return to the war cabinet', 0, 0, NULL, NULL, NULL, NULL, NULL),
(154, 174, 181, 'Return to the war cabinet', 0, 0, NULL, NULL, NULL, NULL, NULL),
(160, 180, 203, 'Descend to Mars. There is no way back to the other worlds', 0, 0, NULL, NULL, '[\"mars_gate=1\",\"jumps+=1\",\"threat+=3\"]', NULL, NULL),
(161, 180, 114, 'Not yet. Return to the Tactical Hub', 0, 0, NULL, NULL, NULL, NULL, NULL),
(162, 120, 121, 'Slip into the ventilation locks with Meldor', 0, 0, NULL, NULL, NULL, NULL, NULL),
(163, 121, 183, 'Break the Section 13 encryption with your own analysis', 0, 0, NULL, '[\"focus>=40\"]', '[\"mars_data=1\"]', 'Requires analysis focus 40 or higher', NULL),
(164, 121, 183, 'Buy the decryption keys from a Tharsis data broker (12,000 credits)', -12000, 0, NULL, '[\"origin=Underworld\",\"credits>=12000\"]', '[\"mars_data=1\"]', NULL, NULL),
(165, 121, 183, 'Copy the raw files without decrypting them (corrupted, no usable proof)', 0, 0, NULL, NULL, '[\"hp-=5\"]', NULL, NULL),
(166, 183, 122, 'Shoot the Warmage down in open combat (-30 HP, Tharsis is alarmed)', 0, 0, NULL, NULL, '[\"hp-=30\",\"alignment-=5\",\"warmage=fight\"]', NULL, NULL),
(167, 183, 122, 'Use Meldor\'s technomage barrier and flank him with an EMP shock', 0, 0, NULL, NULL, '[\"alignment+=5\",\"warmage=barrier\"]', NULL, NULL),
(168, 122, 119, 'Dive into the debris field and jump toward the waiting Liburnia', 0, 0, NULL, NULL, NULL, NULL, NULL),
(169, 181, 204, 'Detour: travel to Lyta\'s refuge in the asteroid bar', 0, 0, NULL, '[\"!lyta\",\"lyta_visits<2\"]', '[\"jumps+=1\",\"threat+=3\"]', NULL, NULL),
(200, 200, 115, 'Drop out of hyperspace over Centauri Prime', 0, 0, NULL, NULL, NULL, NULL, NULL),
(201, 201, 116, 'Drop out of hyperspace over Narn', 0, 0, NULL, NULL, NULL, NULL, NULL),
(202, 202, 118, 'Drop out of hyperspace beside the asteroid', 0, 0, NULL, NULL, NULL, NULL, NULL),
(203, 203, 120, 'Drop out of hyperspace over Mars', 0, 0, NULL, NULL, NULL, NULL, NULL),
(204, 204, 118, 'Drop out of hyperspace beside the asteroid', 0, 0, NULL, NULL, NULL, NULL, NULL),
(998, 10, 101, 'Intelligence (Ex-Agent) -> Start mit optimiertem Analyse-Fokus (95) & taktischen Hacking-Vorteilen.', 0, 95, 'Intelligence', NULL, NULL, NULL, NULL),
(999, 10, 101, 'Underworld (Smuggler) -> Start mit erhöhtem Schmuggel-Budget (+30.000 Credits) & Korruptions-Optionen.', 30000, 10, 'Underworld', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `story_nodes`
--
-- Erstellt am: 06. Nov 2026 um 21:59
--

CREATE TABLE `story_nodes` (
  `node_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` mediumtext NOT NULL,
  `active_sector` varchar(255) NOT NULL,
  `is_ending` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `story_nodes`
--

INSERT INTO `story_nodes` (`node_id`, `title`, `description`, `active_sector`, `is_ending`) VALUES
(10, '=== THE SHADOWS WITHIN - INITIAL SETUP ===', 'SELECT YOUR BACKGROUND SYSTEM:\r\n\r\n1 = Intelligence (Ex-Agent)\r\n    -> High Analysis Focus (95)\r\n    -> Tactical hacking advantages\r\n    \r\n2 = Underworld (Smuggler)\r\n    -> Starting stash increased (+30,000 Credits)\r\n    -> Underground bribery opportunities\r\n\r\n[SYSTEM]: Ready to start your journey?', 'SYSTEM INITIALIZATION GRID', 0),
(100, 'Scene 1: Prologue – The Echo of the Past', '================================================================================\r\nTHE SHADOWS WITHIN – FILM SCRIPT\r\nBASED ON THE LOGICAL ALLIANCE MATRIX\r\n================================================================================\r\n\r\nA lonely, dying campfire crackles in the cold wind. The amber glow throws \r\nlong, shadow-like contours against the weathered, ancient stone monuments \r\nof a forgotten celestial body. The heavy, bittersweet scent of burning \r\nG\'Quaneth incense hangs in the air.\r\n\r\nCITIZEN G\'KAR sits motionlessly by the fire. His heavy, patterned robe is dusty, \r\nhis single, intact eye reflecting the matte play of the flames. He looks deep \r\ninto the embers as his deep, rough voice breaks the absolute silence of the night.\r\n\r\nG\'KAR\r\n(low, conjuring)\r\nThe universe is in a constant change, unstoppable and blind to the suffering \r\nof those who cross its paths. We believed we had won the light of peace... \r\nWe believed the great shadows of the past had been vanished into eternal blackness \r\nwith the end of the war. But the darkness does not sleep, it only waits. It creeps \r\nunseen through the cold spaces between the stars, reshapes itself and slowly becomes... \r\na threat that threatens to consume us all.\r\n\r\nG\'Kar pauses. A cold gust of wind sweeps through the ruins, making the flames \r\nflare up wildly. He does not turn his gaze away, but his voice rises with a calm, \r\nknowing certainty.\r\n\r\nG\'KAR\r\nBut perhaps you should step out of the darkness, sit down and warm yourself \r\nby my fire... for nothing provides more comfort in these times than the light.\r\n\r\nOut of the deep shadows of the ruins, two figures emerge silently. ELRIC and \r\nthe young TECHNOMAGE MELDOR step into the circle of the fire. Their long, heavy \r\ngarments wave in the wind, their faces hidden under deep hoods. Their staffs tap \r\ndully on the stony ground as they sit down in silence by the Narn at the fire. \r\nElric looks at G\'Kar with serious, ageless eyes.\r\n\r\nELRIC\r\nWe seek no comfort, G\'Kar. We seek clarity. A creeping cold is spreading in secret. \r\nThere are painful losses in the ranks of our order... brothers who vanished without \r\na trace from the protection of our fortresses. We feel a shift in power, a great, \r\nnameless evil that goes hand in hand with this emptiness. But wherever we look – \r\nthe veil remains silent.\r\n\r\nG\'Kar closes his eye for a long moment. The steady crackling of the fire is the \r\nonly sound in the wide expanse. When he opens it again, it holds the unyielding \r\ncalm of a seer.\r\n\r\nG\'KAR\r\nThis darkness threatens us all, Elric... and deep in your souls you have long known \r\nthat you can no longer carry this weight alone. Your order turned away from the peoples, \r\nbut isolation will not save you from the coming storm. But perhaps... perhaps one \r\nof you is enough to drag the truth out of hiding. In that distant place they need \r\nyour help – just as you need theirs to fathom the fate of your brothers.\r\n\r\nSilence falls over the fire. Elric looks at Meldor for a long time. Then he turns \r\nto G\'Kar and signals to the young Mage with a slow, meaningful nod that the Narn \r\nspeaks the truth.\r\n\r\nThe young Technomage Meldor rises silently. He draws his hood deeper into his face, \r\nturns away from the fire and sets off until he disappears completely into the absolute \r\ndarkness of the horizon.\r\n\r\nG\'Kar looks up slowly from the fire, his gaze drifting devoutly to the star-studded \r\nsky as his deep, rough voice untermixes the silence. The gaze slides calmly further \r\nup into the infinity of space, straight to the familiar, brightly lit silhouette of \r\nthe station, which rotates majestically in front of the bluish shimmer of the planet \r\nEpsilon III: BABYLON 5.\r\n\r\nG\'KAR\r\n(as the gaze drifts to the station)\r\nUp there, in the whirling dance of steel and hope, the vectors of our destiny cross. \r\nThere begins the path. Look to the stars, my friend... before the night becomes absolute.', 'EXT. MOON IN NO MAN\'S LAND - NIGHT', 0),
(101, 'Scene 2: The Spark in the Dark', 'The air tastes of recycled oxygen, cheap synthetic coffee and the stale heat of \r\nunmonitored freighter valves. The COMMANDER (Ranger special agent) moves like a ghost \r\nthrough the dusty gray of the lower levels. Every shadowy corner is mapped out in \r\nhis mind. It is the only way he survived after losing his old intelligence partner \r\nfrom black-ops days – Marcus Cole.\r\n\r\nSuddenly, a heavy maintenance hatch slams open. A man in the torn, blood-stained \r\nrobes of a Ranger breaks through and collapses directly in front of the Commander. \r\nFar back in the corridor, the eerie, bluish searchlights of Psi-Corps bloodhounds \r\ncut through the iron fog. The hum of bioscanners approaches unyieldingly.\r\n\r\nTHE RANGER\r\n(gasping, dark blood welling on his lips)\r\n\"Take it... Bring it... personally to the command staff... Trust no one... \r\n The sleepers are awaking... We die... for the One...\"\r\n\r\nWith a final rattle, the Ranger dies. His hand opens, releasing an Anla\'shok medallion \r\nand an icy, pulsing data crystal. The light of the Psi security guards is already \r\nbreaking around the corner.\r\n\r\nCHOOSE YOUR ESCAPE PATH:\r\n1 = PATH 1 [Activate the cloaking device]\r\n2 = PATH 2 [Flee into the ventilation shafts]\r\n3 = PATH 3 [Trigger class-specific reaction]', 'INT. BABYLON 5 - MAINTENANCE CORRIDORS (BROWN SECTOR) - NIGHT', 0),
(102, 'Scene 3: The Revolt of Silence', 'The Commander materializes from the shadows directly inside Zack Allan\'s private office, \r\ndrops the crystal onto the table without a single word, and instantly disappears back \r\ninto the crowded promenade decks of the station.\r\n\r\nMOMENTS LATER: In the sealed Council Chamber, CAPTAIN ELIZABETH LOCHLEY slides the \r\ncrystal into the main terminal. A red warning light pulses unreliably:\r\n[CRITICAL ERROR - DATA ENCRYPTED]\r\n\r\nLOCHLEY\r\n(grimly)\r\n\"It is completely locked. We cannot read the coordinates. \r\n We need someone who understands this biochemical, alien encryption matrix...\"\r\n\r\nSuddenly, the station\'s sensor sirens wail. Outside in space, a massive \r\nhyperspace vortex ignites. A sleek, dark ship full of biomechanical, shifting \r\nlight patterns glides silently into the sector. Meldor has reached Babylon 5.\r\n\r\nAt the exact same moment, all monitors flicker. The ISN news signal from Geneva breaks \r\ninto heavy static snow. The primary communication channels to Earth are dead. \r\nAn suffocating silence drops over the entire Zocalo promenade. The homeworld \r\nis completely cut off from the network.\r\n\r\nZack Allan catches the Commander on the promenade deck. His eyes are wide with panic.\r\n\r\nZACK ALLAN\r\n\"Don\'t move. I\'m not arresting you... but hell just froze over, Commander. \r\n Earth is gone. Absolute dead silence on all relays.\"\r\n\r\nHe shoves the Commander directly into the high-security elevator leading to the Command Deck.', 'INT. BABYLON 5 - ZACK ALLAN\'S OFFICE / COUNCIL CHAMBER - LATER', 0),
(103, 'Scene 4: The Negotiation of the Alliance', 'The heavy blast doors slide shut. Captain Lochley fixes the Commander with an \r\nunyielding stare. A fragmentary, red-stamped Earthforce military file lies on her desk.\r\n\r\nLOCHLEY\r\n\"Let\'s skip the diplomatic protocol. The subspace channels to Earth are pure \r\n static ash. And right before this blackout, you throw an unbreakable alien \r\n crystal onto my security chief\'s desk. Your file is a ghost story. \r\n Who are you, and why did that Ranger pick you in the bowels of the station?\"\r\n\r\nCOMMANDER\r\n(cold, unmoved)\r\n\"The Ranger died at my feet. I don\'t know what he wanted. But as he bled out, \r\n he reminded me of someone. Someone who threw his life away for a uniform \r\n I never understood. I brought the crystal to settle a debt of memory. \r\n That is all the concessions you get, Captain.\"\r\n\r\nZack Allan steps forward from the background, a deep recognition in his eyes.\r\n\r\nZACK ALLAN\r\n\"Captain... the fragments in the file. They match the sector logs from before \r\n the Shadow War. Our Commander here used to run covert operations with Marcus Cole. \r\n Long before Marcus ever joined the Anla\'shok. The Ranger knew exactly who he was \r\n looking for in the Brown Sector.\"\r\n\r\nLochley\'s expression turns deadly serious.\r\n\r\nLOCHLEY\r\n\"Marcus Cole... If he registered your name in the Ranger network before his death, \r\n he knew you would fulfill the duty. You might not care about the Alliance, \r\n but Earth is blind. President Sheridan has made provisions for this exact case: \r\n Deep in Pirate Sector 14 lies a hidden prototype cruiser, an Earth-Minbari hybrid: \r\n the LIBURNIA. But our master constructor, Sha\'In, was just kidnapped by Raiders. \r\n Secure the ship. Bring the constructor and the Liburnia home.\"\r\n\r\n🔀 INTERACTIVE WEGGABELUNG 2: THE ALLIANCE CONTRACT\r\n1 = PATH 1 [Demand conditions based on your hidden file or routes]\r\n2 = PATH 2 [Honor the legacy of your fallen comrade Marcus Cole]', 'INT. CAPTAIN\'S OFFICE - CONTINUOUS', 0),
(104, 'Scene 5: The Intrusion into Sector 14', 'A small escort ship drops the Commander off. Through a decommissioned waste chute, \r\nhe infiltrates the interior of a wrecked warship that serves as a base for the Raiders. \r\nIn front of a high-frequency energy cell, two heavily armed mercenaries patrol. \r\nIn the cell sits SHA\'IN – an Ipsha master constructor, exhausted but with an unbroken, \r\nproud gaze.\r\n\r\n🔀 INTERACTIVE WEGGABELUNG 3: THE LIBERATION\r\n1 = Choice 1 [Tactical attack - Fire your PPG openly]\r\n2 = Choice 2 [Silent neutralization - Drop from the ceiling pipes]\r\n3 = Choice 3 [A distraction maneuver - Fuses down the corridor]\r\n4 = Choice 4 [Class-specific tactical strike / Bribery]\r\n\r\n[CONSEQUENCE CHANNELS]:\r\n- CHOICE 1: The Commander draws his PPG and shoots down the guards in an open fire fight. \r\n  The loud noise echoes heavily through the steel corridors. (Status: Alarm / Suffer -10 HP on escape).\r\n- CHOICE 2: The Commander drops silently from the ceiling pipes and knocks out the guards \r\n  one after another before they can even react. (Status: Undetected).\r\n- CHOICE 3: A heavy fusion coupling flies down the corridor. The guards investigate \r\n  the metallic clatter, while the Commander deactivates the cell and pulls Sha\'In out. (Status: Undetected).\r\n- CHOICE 4 (Intelligence): A cold bluff with forged Alliance documents intimidates \r\n  the mercenaries. They open the cell completely voluntarily, fearing a diplomatic fallout.\r\n- CHOICE 4 (Underworld): The Commander pays the mercenaries a tribute of 15,000 Credits. \r\n  They greedily pocket the cash, turn around, and walk away. (-15,000 Credits).', 'INT. PIRATE BASE - SECTOR 14 - REPRODUCTION', 0),
(105, 'Scene 6: The Bridge of the EAS Liburnia', 'Sha\'In slams her private override code into the heavy hangar lock. The blast doors \r\nreveal the breathtaking silhouette of the EAS LIBURNIA. Silver Minbari biocrystals \r\nrun like organic tendons through Earthforce steel.\r\n\r\nSHA\'IN\r\n(breathless, running her fingers over the glowing crystals)\r\n\"Damn it... it\'s my father\'s language-pattern protocol! The binary computer of Earth \r\n refuses to interpret the flowing light patterns of the Minbari matrix...\"\r\n\r\n🔀 INTERACTIVE WEGGABELUNG 4: LINGUISTIC CALIBRATION PATH\r\n1 = WEG 1: Enforce human protocols via Binary Compression (Systems run stable but rigid).\r\n2 = WEG 2: Give free rein to Minbari logic via Organic Stream (Warning: Displays shift to alien glyphs!).\r\n3 = WEG 3: Bridge the analytical gap using hybrid translation protocols (Requires High Analysis / Perfect Hybrid).\r\n\r\n[CONSEQUENCE CHANNELS]:\r\n- WEG 1: Enforces human structures. The ship responds, but the interface becomes extremely \r\n  rigid and slow. (Sets shipCalibrationPath = 1).\r\n- WEG 2: Minbari logic flows freely. The entire terminal wraps into complex light patterns \r\n  and unreadable alien glyphs. (Sets shipCalibrationPath = 2).\r\n- WEG 3 (Triumph): Perfect Hybrid Calibration! The system harmonizes flawlessly. Earthforce steel \r\n  and Minbari biocrystals fuse into an elite, fluid tactical matrix. (Sets shipCalibrationPath = 3).', 'INT. EAS LIBURNIA - COMMAND DECK', 0),
(106, 'Scene 7: The Interstellar Dialogue', 'The ship glides calmly through the restless, shimmering purple glow of hyperspace. \r\nSha\'In looks up from her console, carefully observing the Commander as the hybrid systems \r\nhum in the background. \r\n\r\nDown below in the dusty cargo bay, Moka, the stumpy Drazi technician, slams a massive \r\nsledgehammer onto a blocked energy coupling with a deafening metallic clang.\r\n\r\nMOKA\r\n(wiping sweat from his scaly brow, shouting up the comm-line)\r\n\"Getting stuck is bad for ship, Ipsha! If it won\'t flow, you must break it! \r\n Earthforce steel is too rigid, but if you hit it hard enough, it will learn \r\n to bend with the Minbari light!\"\r\n\r\nSHA\'IN\r\n(softly, turning to the Commander)\r\n\"He is crude, but he is right. The hybrid core requires balance, Commander. \r\n Just like this old alliance we are trying to drag back from the ashes of memory.\"', 'INT. EAS LIBURNIA - HYPERSPACE TRANSIT', 0),
(107, 'Scene 8: The Alliance Palace on Minbar', 'The Liburnia leaves the swirling hyperspace vortex, entering a magnificent orbit around Minbar. \r\nThe team is escorted directly into the light-flooded, crystalline corridors of the Alliance Palace. \r\nPRESIDENT JOHN SHERIDAN awaits you near the grand exit balconies, his face carrying the severe \r\nweight of a galactic leader facing total blackout conditions.\r\n\r\nSHERIDAN\r\n\"This is a pure phantom operation, Commander. The subspace channels to Geneva are dead, \r\n and Earthforce is fracturing under Bester\'s silent curfew. If you are caught out there \r\n by the Black Omega squadron, the Interstellar Alliance will deny any connection to you. \r\n You will be an absolute ghost in deep space.\"\r\n\r\nHe pauses, looking out over the shining spires of Tuzanor before turning back to you with \r\nan intense, serious gaze.\r\n\r\nSHERIDAN\r\n\"But Sheridan does not forget a debt. Marcus Cole gave his life so that this Alliance \r\n could breathe. If his old partner is flying the Liburnia, I know the mission is in honorable hands. \r\n Bring the fleets together. Break Bester\'s net.\"\r\n\r\nOn the steps of the transport deck, Delenn steps forward from the shadows, pressing a cold, \r\nhighly encrypted blue data crystal firmly into your hand.\r\n\r\nDELENN:\r\n\"This crystal contains the ancient language-pattern protocol of the Ranger network. \r\n Use it wisely, my friend... before the night becomes absolute.\"', 'INT. MINBAR - ALLIANCE PALACE', 0),
(108, 'Scene 9: The Ashes of the Past', 'The cloaked Liburnia glides like a silent ghost in front of an ash-gray, dying \r\nplanet. The atmosphere is traversed by toxic, yellowish sulfur bands. Below stretch \r\nthe needle-like, petrified ruins of the outpost. It is silent, lifeless and full of \r\neerie secrets in the dust.\r\n\r\nINT. EAS LIBURNIA - COMMAND DECK\r\n\r\nThe air temperature drops suddenly. Golden geometric equations cascade along the walls. \r\nFrom the shadows steps the Technomage Meldor.\r\n\r\nSHA\'IN\r\n(startled)\r\n\"How did you get on board? Our internal sensors didn\'t register any teleport!\"\r\n\r\nMELDOR\r\n\"Sensors look for metal and wires, child. They do not look for the echo of the will.\r\n The Psi-Corps did not find this place by accident. They use the dark relics left behind \r\n on this world for their purposes. The old servants of the Shadows have experimented \r\n here in secret... and strangely enough, traces of my missing order brothers are also found... \r\n what does that mean?\"\r\n\r\nThe Commander steps decisively to the sensor terminal to investigate the eerie remark \r\nof the Mage and starts a deep scan of the planet\'s surface.\r\n\r\n[SYSTEM ANALYSIS INBOUND...]\r\n\r\n[SUCCESS CHANNEL - ANALYSIS >= 90]:\r\nThe Commander\'s fingers fly over the hybrid terminal while optimally calibrating the scan filters. \r\nHe succeeds in isolating the organic radiation of the ruins. On the screen, ancient laboratory \r\nlogbooks of the Psi-Corps flicker. He comes across the trail of missing Technomages and extracts \r\nan intact, deeply pulsing blue biocrystal fragment from the core matrix.\r\n(controlDecryptionProgress +100)\r\n\r\n[FAILURE CHANNEL - ANALYSIS < 90]:\r\nThe scan fails due to the dense, biological radiation of the planet. The terminal overheats \r\nand sprays sparks. An electrostatic discharge shoots out of the console and hits the Commander painfully!\r\nDespite the feedback, the Commander succeeds in ripping a loose, incomplete fragment of the \r\nglowing crystal from the holder with safety pliers before the relay station finally implodes.\r\n(Suffer -15 HP).\r\n\r\nMELDOR: \'To hear what these stones hide, this ship is not enough. We must set course for \r\n        the Babylon 5 system and travel down into the bowels of Epsilon III. Only the \r\n        Great Machine can tear this veil.\'', 'EXT. OUTPOST - DEAD SECTOR - ORBIT', 0);
INSERT INTO `story_nodes` (`node_id`, `title`, `description`, `active_sector`, `is_ending`) VALUES
(109, 'Scene 10: The Interstellar Ambush', 'The Liburnia chases with maximum thrust through the swirling hyperspace vortex \r\ntowards the Epsilon sector. You are flying back to investigate the data of the \r\nrecovered Drakh crystal.\r\n\r\nSuddenly, the entire ship shakes under a brutal, bone-shattering impact! Red warning \r\nlights flood the bridge, and the howling of the decompression sirens sets in.\r\n\r\nSHA\'IN\r\n(thrown against her console)\r\n\"Ambush! Three heavy Raider fighters fell directly on us from the shadows of an \r\n unstable gravitational shear wave! They lurked in the dead angle of the stream and \r\n intercepted our engine resonance! A plasma bolt pierced our primary distribution – \r\n the main plasma line in the engine room is leaking! If we don\'t contain the pressure \r\n manually, the core will explode right in hyperspace!\"\r\n\r\nMELDOR\r\n(calm, remaining in the shadows)\r\n\"A test of the flesh. Go, Commander. Show the machine what steel you are made of.\"\r\n\r\nYou hesitate for no second. You sprint from the bridge down into the blazing heat of \r\nthe reactor section. The Minbari crystals on the walls pulse in a panicked, dying red. \r\nYou must advance directly to the glowing valve to force the manual override.\r\n\r\n[PROCESSING ENGINE EMERGENCY OVERRIDE...]\r\n\r\n[CONSEQUENCE CHANNELS]:\r\n- CASE 1 (Binary Compression): The systems react stubbornly and slowly because they must follow the rigid \r\n  Earthforce safety cycles. You stand agonizingly long in the murderous heat before the valve finally clicks. \r\n  The plasma burns your arms and sears your face. (Suffer -30 HP).\r\n- CASE 2 (Organic Minbari Stream): The reactor runs with maximum energy, but the warning displays pulse \r\n  in the three-dimensional light patterns of the Minbari. For human eyes, it is pure chaos! You reach for \r\n  the wrong switch, triggering a small plasma explosion before finding the valve. (Suffer -25 HP).\r\n- CASE 3 (Perfect Hybrid Path): Thanks to the perfect hybrid protocol, the systems harmonize flawlessly. \r\n  The ship responds lightning-fast to the manual override. The Minbari crystals automatically throttle \r\n  the plasma flow in your blind spot to protect you. With precise movements, you seal the leak. (Suffer -10 HP).', 'INT. EAS LIBURNIA - HYPERSPACE TRAVEL - TRANSIT TO B5', 0),
(110, 'Scene 11: The Arrival in Hangar 7', 'Thick, white cooling steam hisses from the torn vents of the Liburnia as the engines finally die out. Captain Elizabeth Lochley awaits you at the foot of the ramp.\n\nLOCHLEY:\n\"You look like you\'ve flown straight through hell, Commander. President Sheridan sent confirmation. The Great Machine is ready.\"', 'Babylon 5 - Hangar 7', 0),
(111, 'Scene 12: The Lower Levels of Epsilon III', 'The transport pod docks at a subterranean airlock. The interior of the planet is an endless canyon of breathing, pulsating technology. Out of the steam stumbles Zathras.\n\nZATHRAS:\n\"No, no, no! Zathras said, first the primary coupling, then the guests! But nobody listens to Zathras. Too much time, too little Zathras!\"', 'Epsilon III - The Great Machine', 0),
(112, 'Scene 13: Draal\'s Metaphors', 'You reach a massive, floating platform. Draal steps forward from the shimmering light, his eyes glowing with unearthly power.\n\nDRAAL:\n\"Greetings, traveler. We will give a voice to this silent cage! Beware of the chamber where the echo of old betrayal sleeps...\"\n\nThe energy waves break the blockade and the Earth signal bursts through!', 'Epsilon III - Central Core', 0),
(113, 'Scene 14: The Franklin Protocol', 'The energy of the Great Machine has been rerouten directly into the secured medical bays of the station. The Commander lies on a diagnostic table while DR. SARAH CHAMBERS guides a hissing regeneration scanner over his burns. The cool, bluish light closes the scorched skin in a matter of seconds. \r\n\r\n[SYSTEM]: Your health points have been fully restored! (playerHP = 100)\r\n\r\nIn the center of the room, the main projector displays a life-sized, razor-sharp hologram. It is DR. STEPHEN FRANKLIN, broadcasting directly from the underground of Earth! At his side in the Medlab stand CAPTAIN LOCHLEY, TESSA HOLLORAN, and DELENN.\r\n\r\nFRANKLIN (Hologram)\r\n\"Thank God, the connection holds! I don\'t know what energy source you tapped into, but it stands up to Bester\'s broadband jammers. Listen to me carefully: Bester hasn\'t just sealed Earth politically. He is using a modified variant of Drakh biotechnology—a biomechanical tech-cross—to pull up a worldwide, unhackable sleeper network via the telepaths! He is experimenting in secret on kidnapped Technomages to abuse their implants as biological relay stations!\"\r\n\r\nGARIBALDI (Audio-Feed)\r\n\"Lochley, forward this to the Commander immediately. I received confirmation via the industrial espionage channels of Edgars Industries: Bester has built a strictly secret research station right inside the Tharsis volcanic crater on Mars. I\'ve attached the original infiltration codes. Find out what they are doing there.\"\r\n\r\n[CLASS-SPECIFIC DIALOGUE CHANNELS]:\r\n- CASE \'Intelligence\': DELENN: \"You look at the blueprint of this war, Commander, and you see only numbers and grids. But Marcus did not see the universe as an equation. He saw it as a family worth sacrificing everything for. Do not let his light fade into the database.\"\r\n- CASE \'Underworld\': DELENN: \"You pretend you only care about the Credits and the freedom of your cargo routes. But Marcus knew the real person behind that smuggler mask. He trusted you with his life. Settle the debt of memory, my friend.\"\r\n\r\nDELENN: \"The Rangers stand ready, Commander. But the Psi-Corps has Earth in a stranglehold. To win this war, we must assemble the pieces of the old alliance. Travel to the alien worlds. Bring us the fleets of the Narn and the Centauri!\"', 'INT. BABYLON 5 - MEDLAB ONE - HOURS LATER', 0),
(114, 'Scene 15: The Free Vector (Mission Hub)', 'The tactical hub of the Liburnia glows in the calm silver-blue of a perfectly tuned bridge. SHA\'IN sits at the telemetry console, the routing vectors unfolded across the main viewport.\n\nSHA\'IN:\n\"Delenn\'s data crystal gave us routes nobody is watching. The engines are ready, Commander. Where do we jump first? But remember: every jump costs time, and the Corps\' sleeper network grows while we hesitate.\"\n\n{{STATUS}}', 'EAS Liburnia - Tactical Hub', 0),
(115, 'Scene 16: Centauri Prime - The Net of the Spider', 'The Liburnia touches down on the grand landing platforms of Centauri Prime. EMPEROR LONDO MOLLARI sits on his heavy throne, his eyes narrow, filled with the weariness of a man carrying chains he cannot show.\n\nLONDO MOLLARI:\n\"Ah, the Alliance emissary... Sheridan sends you to beg for my royal fleet, yes? I will lend my warships, but only in exchange for an exclusive first-strike pact for the Empire. Sign, and my fleet is yours. Refuse, and you leave my palace empty-handed.\"\n\nThe words sound rehearsed. Something small and dark rides on the Emperor\'s shoulder, and his tired eyes say something else than his mouth. Londo is not the one who wants this pact.\n\nBehind a marble pillar a nervous figure watches you in silence: VIR COTTO.', 'Centauri Prime - Imperial Throne Room', 0),
(116, 'Scene 17: Narn Homeworld - The Citadel of Red Sand', 'The Liburnia drops out of hyperspace into the burning crimson skies of Narn. Three massive G\'Quan-class cruisers swing into formation and charge their green laser batteries. A hard voice crackles over the channel.\n\nNARN COMMANDER:\n\"Unknown vessel, identify yourself or be torn apart!\"\n\nBelow you lie the sand-scoured halls of the Central Military Citadel, where the Elders of the newly restored Kha\'Ri wait to decide whether you are friend or threat.', 'Narn Homeworld - Orbit / Central Military Citadel', 0),
(117, 'Scene 18: The Stranded Alliance Wreck', 'While maneuvering through the hyperspace streams, Sha\'In intercepts a faint, distorted emergency signal. A drifting, lifeless ghost ship of the Interstellar Alliance is caught in a gravitational shear wave. Meldor senses a damaged matrix aboard.\n\nThe team boards the freezing, creaking cruiser to secure resources. Suddenly a massive steel girder tears loose directly above Sha\'In, while a still-powered terminal blinks with the unlocked war chest of a dead crew.', 'Hyperspace Transit Vector - Alliance Wreck', 0),
(118, 'Scene 22: Lyta\'s Refuge (The Last Crossroad)', 'The tracking vectors lead you into a dark, smoky bar buried deep inside a hollow asteroid. LYTA ALEXANDER sits in a dim corner, her hood pulled low. As you approach her table, her head snaps up. Her eyes flash in an unnatural, terrifying Vorlon blue.\n\nLYTA (Mental Projection):\n\"You want my power to tear down Bester\'s walls. I do not want treaties. I want retribution. I want to see this damned Corps burn, down to the foundations!\"', 'Smuggler Outpost - Asteroid Bar', 0),
(119, 'Scene 26: The Reunion in Hyperspace', 'The captured Starfuries are pulled in by the Liburnia\'s tractor beam, and the ship slips into a dense, shimmering nebula deep in no man\'s land, where it docks with General Susan Ivanova\'s rogue rebel destroyer. IVANOVA steps onto the bridge, DR. STEPHEN FRANKLIN at her side, evacuated from his Earth bunker at great risk.\n\nThen the galley door bangs open: MICHAEL GARIBALDI charges in with a sizzling pan of real Earth food. He sneaked aboard on Mars before you ever left orbit. Around the table the old command staff breaks bread and toasts to the memory of MARCUS COLE: with a tear in the eye, but loud, warm laughter about his unreadable Ranger poems and his habit of facing an army with a wooden staff.\n\nIVANOVA:\n\"He was a fool. A noble, loyal fool. We honor his sacrifice by finishing this fight. My ships are locked onto our vector. We are going to hammer Bester\'s net into dust.\"', 'Hidden Nebula - Rebel Fleet Mess Hall', 0),
(120, 'Scene 23: The Mars Coup', 'The Liburnia glides out of the dead angle of the orbital defense satellites and settles unseen into a hidden Edgars Industries hangar. A heavily encrypted holo-signal builds in the center of the bridge: MICHAEL GARIBALDI, chewing a wooden toothpick, his eyes full of personal worry.\n\nGARIBALDI (Hologram):\n\"Good to see you made it. Bester buried his secret research station deep in the Tharsis crater. A frontal attack is suicide, so you go in as a ghost, through the secondary ventilation locks. I got you the original access codes. And Commander... Talia Winters was a friend. I want to know what that damned Corps did to her.\"\n\nSHA\'IN:\n\"A megacorporation hangar nobody registered. Mr. Garibaldi knows how to make an impression. Stealth mode engaged, we go down.\"', 'Mars - Tharsis Crater Hangar', 0),
(121, 'Scene 24: In the Bowels of the Corps', 'Cold sterile steel and pulsing blood-red neon rule the endless underground corridors. The air tastes of ozone, disinfectant and sedatives. Behind thick armored glass the horror of Bester\'s work lies bare: abducted technomages strapped into cybernetic chairs, black Drakh implants pulsing under their skin like veins, their minds feeding the worldwide sleeper program.\n\nMELDOR:\n\"They have defiled their bodies. They crossed the sacred geometries of my order with the unclean technology of the Shadows.\"\n\nYou reach the central control room of Section 13. The master plans of the sleeper network wait behind a wall of Psi-Corps encryption.', 'Mars - Tharsis Crater Lab (Section 13)', 0),
(122, 'Scene 25: The Escape in the Cockpit', 'The whole complex shudders under secondary explosions. You throw Meldor into the cockpit of the second pitch-black Black Omega Starfury and strap into the first. The hangar doors are blown away by internal blasts, and both fighters shoot out into the raging red sandstorm of Mars, up into the freezing orbit.\n\nThree interceptors lock onto your tail. The transponders still call you friend, but the flight control is getting suspicious.\n\nCOMMANDER:\n\"Hold on, Meldor! I have to push this crate to its absolute limits!\"', 'Mars Atmosphere - Black Omega Starfury', 0),
(123, 'Scene 27: The Geneva Stratospheric Approach', 'The viewscreen clears, revealing the breathtaking, sapphire-blue marble of Earth. But the cradle of humanity is surrounded by an iron ring—the black, dagger-like silhouettes of Earthforce destroyers loyal to Bester\'s regime are patrolled by elite Black Omega starfighters.\r\n\r\nThe subspace relays hum with wild energy. GENERAL SUSAN IVANOVA\'s face flares up on the main tactical display from her flagship\'s bridge, her eyes burning with a lethal, uncompromising intensity.\r\n\r\nIVANOVA (Holo-Feed):\r\n\"All rebel units, this is General Ivanova. We have tracked Bester\'s primary transponder signals down to the central biocrystalline dome in Geneva. The network is hot, and they know we are here. There will be no elegant maneuvers, no tactical retreats. We are going straight through their defensive line. Lock your shields, heat up your plasma arrays, and push your throttles to the absolute wall. We are going home!\"\r\n\r\nThe entire rebel fleet ignites their fusion drives simultaneously, screaming forward in a massive wave of fire. The Liburnia dives ahead, leading the vanguard. \r\n\r\nSHA\'IN\r\n(gripping her console as the atmospheric entry friction turns the viewport blood-red)\r\n\"Thermal hull shields are melting, Commander! The friction is hitting critical levels, and two Black Omega heavy interceptors are locking their plasma cannons onto our engine matrix! We must choose our atmospheric entry angle immediately!\"\r\n\r\nCHOOSE YOUR STRATEGIC STRATOSPHERIC ENTRY VECTOR:\r\n1 = Vector 1 [Aggressive dive: Maximum thrust to overpower the interceptors via raw speed] -> (playerHP -15, alignmentScore -5).\r\n2 = Vector 2 [Evasive glide: Roll the ship into the thermal blind spots of the atmosphere] -> (analysisFocus +5).', 'INT. EAS LIBURNIA - COMMAND DECK / EARTH ORBIT - NIGHT', 0),
(124, 'Scene 28: Garibaldi\'s Global Subspace Broadcast', 'While the Liburnia dives through the burning lower atmosphere, Michael Garibaldi secures his breach into the primary ISN propaganda relay station over the Geneva grid. The screens on the Liburnia\'s bridge flicker wildly, the sterile face of the Psi-Corps news anchor dissolving into heavy static snow.\r\n\r\nSuddenly, Garibaldi\'s lined, unyielding face cuts through the worldwide broadcast network. He takes the unlit cigar out of his mouth, leans straight into the camera lens, and his dry, biting voice echoes from every single viewscreen, public terminal, and comm-link across the surface of Planet Earth.\r\n\r\nGARIBALDI (Global Broadcast):\r\n\"People of Earth, listen to me very carefully. I\'m not some voice from a rogue resistance cell, and I\'m not here to negotiate. My name is Michael Garibaldi, former Chief of Security on Babylon 5. For months, Alfred Bester and his synthetic net have lied to you. They told you the Interstellar Alliance wants to consume your sectors. They told you the curfew was for your own safety. It\'s a lie. A massive, bureaucratic lie.\"\r\n\r\nHe slams a heavy data cartridge directly into the broadcast deck, projecting the stolen Mars Section 13 files across the worldwide telemedia channels.\r\n\r\nGARIBALDI (Global Broadcast):\r\n\"Right now, Bester is holding hundreds of innocent people—both mundanes and telepaths—inside a biological research network beneath Geneva. He is frying the brains of your own children to secure his personal grip on power. We have the logs. We have the data. To all Earthforce military units still taking orders from the Corps: stand down. Your government has been blinded. Look at the screens. Look at the truth!\"\r\n\r\n[PROCESSING INFILTRATION CONVERGENCE VIA SWITCH]:\r\n- CASE \'Intelligence\': Garibaldi\'s data feed utilizes old, embedded Black Watch encryption frequencies. The broadcast bypasses local jammers twice as fast. (besterThreatLevel -15).\r\n- CASE \'Underworld\': Garibaldi uses pirate sub-relays and black-market frequencies. Resistance groups rise up, cutting power lines of the automated laser turrets outside! (credits +5000).', 'INT. REBEL FLAGSHIP - TRANSMISSION DECK / GLOBAL EARTH NETWORK - NIGHT', 0),
(125, 'Scene 29: The Glass Ceiling Bursts', 'The Liburnia screamingly approaches the central biocrystalline tower of the Corps. With an ear-splitting, thunderous roar, the armored hull slams directly through the colossal structural glass roof of the ENTSPANNUNGSBEREICH! \r\n\r\nA crystalline rain of a million razor-sharp shards explodes into the luxury lounge. Alarms scream in high-pitched frequencies as the boarding ramps slam down onto the shattered marble floors. Dust, smoke, and venting hydraulic steam fill the hall.\r\n\r\nYou, General Susan Ivanova, and the elite ground teams drop from the ramps, weapons raised. Psi-Corps security guards are rushing forward from the side corridors, blazing heavy PPG fire into the smoke.\r\n\r\nIVANOVA\r\n(firing a hochenergetischer plasma bolt, her voice cutting through the noise)\r\n\"Secure the perimeter! We need to lock down the main stairwells from top to bottom, otherwise they will pin us down in this lounge! Commander, we must coordinate the breach strategy right now!\"\r\n\r\nCHOOSE YOUR INFILTRATION VECTOR FOR THE STAIRWELLS:\r\n1 = Forceful Breach: Charge the central elevator shafts using heavy tactical cover fire. (playerHP -10).\r\n2 = Flanking Maneuver: Split the squad into the side ventilation grids for a silent lock. (analysisFocus +5).', 'INT. PSI-CORPS HEADQUARTERS - ENTSPANNUNGSBEREICH - NIGHT', 0),
(126, 'Scene 30: The Inner Sanctum', 'The heavy biocrystalline doors blow apart under a perfectly placed thermal charge. Dust and ozone flood the inner sanctum. As the smoke clears, you step into a massive, glowing chamber domed by pulsating alien hardware.\r\n\r\nAnd out of absolute nowhere, you find her: TALIA WINTERS is trapped in the center! Her body is suspended in stasis wires, cybernetic cables feeding directly into her synapses. She is breathing, but her mind is completely woven into the worldwide sleeper net.\r\n\r\nALFRED BESTER stands calmly before the main control matrix, his black gloves folded behind his back, his thumb resting lightly on a small, matte-black master detonator.\r\n\r\nBESTER\r\n\"One wrong move, Commander, and I fry the synapses of every single sleeper mind on this planet. You came a long way just to witness the end of your species.\"\r\n\r\nGENERAL SUSAN IVANOVA steps to your side, her PPG rifle raised, aimed dead-center at Bester\'s forehead. Her voice is ice.\r\n\r\nIVANOVA\r\n\"This is for Marcus, Bester. And the only one leaving this room in the dust... is you.\"\r\n\r\nCHOOSE YOUR FINAL COMPILER-STRIKE:\r\n1 = Activate Meldor\'s technomage phantomschwarm-override. -> (Golden phantomschwarm-code completely isolates Bester\'s detonator frequencies in subspace, rendering his trigger useless).\r\n2 = Engage Bester in raw, bloody physical hand-to-hand combat. -> (Tackle Bester before trigger is pressed, slam detonator out of hand. Suffer heavy counter-attacks. playerHP -35).', 'INT. PSI-CORPS HEADQUARTERS - BESTER\'S MATRIX CHAMBER - CONTINUOUS', 0);
INSERT INTO `story_nodes` (`node_id`, `title`, `description`, `active_sector`, `is_ending`) VALUES
(127, 'Scene 31: The Final Battle – The Evaluation of the Timeline', 'The screaming sirens are slowly dying down. The central neural server drops its protective shields, and the stolen Section 13 Mars data streams are verified. The quantum compiler of the Liburnia evaluates your structural footprint in the dust.\r\n\r\n================================================================================\r\n=== ALL 7 CALCULATED ENDING VECTOR CHANNELS                                  ===\r\n================================================================================\r\n\r\n[ENDING 1: THE PATH OF LIGHT (DIPLOMATIC KANON ENDING)]\r\nTalia overwrites the neural codes peacefully. Alfred Bester is placed under arrest by the Alliance fleet. But as the structural columns of the headquarters collapse, Lennier unexpectedly sacrifices his own life to pull Delenn, Ivanova, and you to safety. Lennier clears his honor in the face of death.\r\n\r\n[ENDING 2: THE CENTAURI SHADOW (LONDO\'S REVENGE)]\r\nBecause your Drakh influence was critical, Londo Mollari\'s secret shadow ships betray the perimeter! The Psi-Corps burns, but Centauri heavy cruisers glass the Narn homeworld sectors as dictated by the secret pact. You return to a galaxy trapped in a cold, paranoid multi-front war.\r\n\r\n[ENDING 3: THE NARNIAN CITADEL REBORN]\r\nThe elite Narn Stoßtrupp-Avantgarde locks the entire orbital grid. Bester\'s net is systematically dismantled by raw firepower. The Kha\'Ri establishes an unyielding military protectorate over Geneva to ensure Earth never threatens the stars again.\r\n\r\n[ENDING 4: THE RESISTANCE TRIUMPH (MUNDANE ASCENSION)]\r\nGaribaldi\'s broadcast triggers a total, planet-wide civilian revolution. The automated networks collapse from global overloads. Humanity discards the Psi-Corps entirely, but telepaths are forced into absolute isolation in lawless asteroid sectors.\r\n\r\n[ENDING 5: THE SHADOW DIRECTOR (YOUR MASTERPLAN)]\r\nYou ruthlessly execute Bester on the spot. Using your old Black Watch security codes, you hijack the entire worldwide neural net for yourself instead of breaking it! You become the new, invisible Director of Bureau 13. The population of Earth keeps sleeping... completely unaware, under your iron rule.\r\n\r\n[ENDING 6: ABSOLUTE SILENCE (GAME OVER)]\r\nYour tactical attributes and alliance networks were weak. Bester successfully activates the worldwide neural network, frying the synapses of millions. The Earth fractures permanently into civil war and absolute chaos.\r\n\r\n[ENDING 7: THE TELEPATH RESONANCE MASTERPIECE (TRUE KANON ENDING)]\r\nTalia Winters\' dormant Ironheart heritage fully awakens to shield the sleeper minds! Lyta Alexander channels her terrifying Vorlon power to completely burn Bester\'s künstliches Net. The shadow tech fries, the corporate Curfew is shattered forever, and ALL MAIN CHARACTERS SURVIVE UNHARMED! Humanity wakes up to a bright, free tomorrow. (Triggered via lytaRecruited, progress == 999, alignmentScore >= 75).', 'INT. PSI-CORPS HEADQUARTERS - MAIN MATRIX TERMINAL - AFTERMATH', 0),
(128, 'Ending 7: Canon Resonance - The Sub-Space Frequency from Tuzanor', 'Talia Winters and Lyta Alexander step forward together. Talia draws on her Ironheart legacy and weaves an empathic shield around the minds of the human sleepers, isolating Bester\'s activation pulse. At the same instant Lyta unleashes her Vorlon power. Because the control signal of the drones and the Shadow technology share one frequency, she sends a vast resonance wave through Garibaldi\'s hijacked channels. The sleeper commands are erased, the drones deactivated, yet the natural psi gifts of humanity remain untouched. The control matrix is destroyed, the headquarters collapses, Bester is left powerless, and every main character survives.\n\nA quiet, respectful calm settles over the bridge of the Liburnia. The lifelike hologram of PRESIDENT JOHN SHERIDAN appears, broadcasting from a room of soft Minbari light in Tuzanor.\n\nSHERIDAN (Hologram):\n\"The provisional government has ordered the complete dissolution of the Corps. Even if the public will never know of your covert mission... I am quite certain that Marcus would be especially proud of you. Come home. Sheridan out.\"\n\n=== FADE TO WHITE - END OF THE SHADOWS WITHIN - ENDING 7 OF 7 ===', 'EAS Liburnia - Command Deck / Earth Orbit', 1),
(141, 'Scene 17b: The Council of the Kha\'Ri', 'G\'Kar\'s seal flickers as a hologram on the Narn bridge monitors. A reverent silence falls over the channel, and the cruisers grant you free passage to the Citadel.\n\nIn the red sand the ELDER OF THE REGIME listens to your warning about Bester\'s global threat. The newly founded Kha\'Ri convenes and votes on its very first historic decree: to stand by Earth. Setting their own hardship aside, they mobilize the elite Narn Avantgarde as loyal frontline fighters.', 'Narn Homeworld - Central Military Citadel', 0),
(142, 'Scene 17c: The Price of Silence', 'In a smoke-filled cellar below the Citadel, a one-eyed broker counts your credits twice. The Narn regulars will never fly for you, but the underground sells what the Kha\'Ri will not: hardened mercenaries with their own ships and no questions about whose war this is.\n\nBROKER:\n\"Pleasure doing business, outsider. They fly for you. They do not die for you.\"', 'Narn Homeworld - Underground Market', 0),
(151, 'Scene 18: The Fateful Pact', 'You sign. Londo\'s smile does not reach his eyes, and for a moment something small and dark seems to shift on his shoulder. The heaviest warships of the Empire are yours for the front, and in the background the Drakh influence over Centauri Prime rises.', 'Centauri Prime - Imperial Throne Room', 0),
(152, 'Scene 18: The Humanitarian Pact', 'Londo throws you out of the hall. In the corridor VIR COTTO catches your sleeve, his hands trembling.\n\nVIR COTTO:\n\"Not here. Not where he can hear. Londo has never told me what is wrong, Commander. He hides it from me, and I think he does it to protect me. But I know him. When he drinks deeply enough, the thing on his shoulder goes quiet for a little while, and he talks. Fragments. The old raids on your shipping lines, the seal of the Throne Regent, masters who are not Centauri. The next morning he remembers nothing, and he looks at me as if I might have heard. I never asked. I only listened, and I have always had a nose for things that do not add up. Slowly I put the pieces together. I cannot prove it, but I believe the Drakh are behind all of it. So I have been preparing, quietly, where nobody looks, with every source I have. I cannot give you the fleet. But I can give you the Ranger-guarded rescue ships we hid for the civilians. Take them. Please. Somebody has to remember what we used to be, and somebody has to save him.\"\n\nThe covert Centauri rescue fleet is pledged to you, and the Drakh influence at court wanes.', 'Centauri Prime - Palace Corridors', 0),
(153, 'Scene 18: The Chamberlain\'s Price', 'In Centauri Prime\'s palaces everything has a price, and a smuggler knows the exchange rate. A fat envelope vanishes into the sleeve of the Emperor\'s chamberlain, and suddenly Londo\'s signature appears on a very different paper: his warships will fly with the Alliance, no exclusive pact, no humiliation.\n\nThe Drakh are not fooled, though. In the background their influence over the court grows a little, and the thing on the Emperor\'s shoulder seems to smile. Vir Cotto only watches you from the shadows, his face unreadable.', 'Centauri Prime - Palace Corridors', 0),
(161, 'Scene 18b: The Salvage', 'You throw yourself over Sha\'In as the girder crashes down. Burns tear across your back, but she is alive. Meldor lowers his staff and looks at you for a long moment: trust has been earned.\n\nBefore leaving the wreck you crack the terminal and secure the dead crew\'s war chest: 25,000 credits for the alliance.', 'EAS Liburnia - Transit', 0),
(170, 'Scene 22a: The Pact of Vengeance', 'You nod darkly and strike into her radical campaign.\n\nCOMMANDER:\n\"Bester started this war, Lyta. We finish it by his own rules. The Corps will burn. Every single cell.\"\n\nLyta smiles grimly, her eyes igniting in murderous god-light. She boards the Liburnia, ready to pulverize the headquarters. With her on board, Talia\'s rescue fades completely into the background.', 'Smuggler Outpost - Asteroid Bar', 0),
(172, 'Scene 22a: The Seed', 'You tell her that Section 13 is real, and that you will bring proof. Lyta\'s Vorlon blue flickers.\n\nLYTA:\n\"Proof? I have heard promises for years. Bring me something I cannot burn, and maybe I will listen. Now go.\"\n\nShe vanishes into the smoke. Not a refusal forever, but a door that will only open for a Commander who comes back with evidence.', 'Smuggler Outpost - Asteroid Bar', 0),
(173, 'Scene 26b: The Path of Light', 'You lay the data pad with the stolen Section 13 streams on the table and show her the logs about Talia Winters.\n\nCOMMANDER:\n\"Talia is no tool of the Corps any more, Lyta. She is a victim. Bester forced her into a biological switching unit. If you smash the headquarters blind, you murder a sister. We destroy the artificial network, not the people.\"\n\nLyta\'s eyes widen. The Vorlon blue flickers and breaks into deeply human tears. Her blind hate collapses.\n\nLYTA:\n\"They did that to her? Oh, God... No. I will go with you, Commander. I will hold my power back. We save Talia and burn Bester\'s net without sacrificing the innocent.\"', 'Smuggler Outpost - Asteroid Bar', 0),
(174, 'Scene 26b: The Total Failure', 'You miss the tone and argue purely in military terms. Lyta looks at you with icy contempt.\n\nLYTA:\n\"You are just as blind as the generals in Geneva. You only want to use me as a weapon. Get out of my sight!\"\n\nShe refuses to board and vanishes into the shadows of the asteroid. You will have to face the finale without her Vorlon power.', 'Smuggler Outpost - Asteroid Bar', 0),
(180, 'Scene 22b: The Point of No Return', 'The Liburnia hangs in the shadow of the Mars moons. Garibaldi\'s coordinates blink in the HUD. Sha\'In looks at you with a serious face.\n\nSHA\'IN:\n\"Once we go down into that crater, Commander, there is no road back to the other worlds. The Corps will know we have been there. Section 13 will tighten every lock, and every ally we do not have by then, we will never have.\"\n\n{{LOST_LIST}}\n\nAfter the Mars operation only one detour remains before the assault on Earth: Lyta\'s refuge in the asteroid bar.', 'Mars Orbit - EAS Liburnia - Command Deck', 0),
(181, 'Scene 27: The War Cabinet in Hyperspace', 'The final, high-risk plan to attack Earth is coordinated. The tactical holo-grid shows the planetary defense lines.\n\nIVANOVA:\n\"Bester coordinates the mass control through a heavily fortified ground jamming station. If it falls, the Gold Channels light up again and his grip breaks within seconds.\"\n\nGARIBALDI will take command on Ivanova\'s flagship in orbit to run the fleet coordination and the media network, the ultimate symbol of the bond between free Mars and Earth. Ivanova draws her PPG, ready to lead the ground infiltration.\n\nMELDOR:\n\"The eye of the Corps is watchful. A transponder will not fool Bester\'s paranoia. But my order will give you the theater of your life.\"\n\nThere is a last window before the descent. Only one place is still within reach: the hideout of Lyta Alexander.', 'EAS Liburnia - Command Deck', 0),
(183, 'Scene 24b: The Hangar Showdown', 'You copy the raw files into your log. Without the keys they are only noise, but Meldor holds a fragment against the light and flinches.\n\nMELDOR:\n\"There is something older than Bester at the heart of this net. Shadow-tainted. I cannot read more than that.\"\n\nThen you scan the central stasis chamber. According to the Section 13 files, the biological master switch of the entire global net should lie here: Talia Winters. The massive biocrystalline capsule in front of you is empty. The life-support cables hang slack, a faint blue residual signature flickering in the lines.\n\nMELDOR:\n\"The logs show a security transfer three days ago. Destination classified at the highest level. You have the cipher and the data, Commander. But the fate of Garibaldi\'s friend remains a mystery.\"\n\nA tonne-heavy steel bulkhead slams shut behind you. Red alarm lights flood the hangar, and an enormous figure detaches from the shadows of the Black Omega Starfuries: the WARMAGE, an elite Corps enforcer with implanted combat modules and glowing runes, a biomechanical staff spitting blue plasma in his hands.\n\nWARMAGE (Mental Projection):\n\"The intruder from the silent sector... You think Michael Garibaldi\'s cheap corporate codes could fool the eyes of the Corps? This switch unit is the property of Alfred Bester. You will not leave this crater alive.\"', 'Mars - Tharsis Crater Hangar Bay', 0),
(190, 'Ending 1: The Diplomatic Triumph (A Sad Canon Ending)', 'Talia Winters overwrites the codes in peace, and the sleeper commands die without a single shot. Bester is led away in chains, his thumb finally off the detonator.\n\nThen the headquarters begins to collapse. A ceiling beam tears loose above Delenn, Ivanova and you. From the smoke steps LENNIER. He has carried his guilt through all those years. Without a word, he throws himself under the falling stone and holds it long enough for all of you to escape, paying his old debt with the only coin he has left.\n\nEarth is free. Talia lives. But on the bridge of the Liburnia nobody celebrates. They stand at the viewport in silence, a Minbari candle burning for the one who is not coming back.\n\nSHERIDAN (Hologram):\n\"Marcus would have said Lennier finally found his way home. Come home, all of you. Sheridan out.\"\n\n=== END OF THE SHADOWS WITHIN - ENDING 1 OF 7 ===', 'Psi-Corps HQ - Matrix Chamber / Earth Orbit', 1),
(191, 'Ending 2: The Price of the Empire', 'The heavy Centauri warships tear through Bester\'s loyalist fleet, and the bombardment is devastating. The jamming station falls, the network collapses, Earth is free.\n\nBut the price has been written in advance. On the viewscreen Londo Mollari accepts the thanks of a liberated Earth, and at his shoulder, barely visible, the shadow that is not his own smiles. The Narn sectors are handed to the Empire in the treaty you signed. The Alliance, bound by your signature, cannot object.\n\nSHERIDAN (Hologram):\n\"We won the war, Commander. And I am not sure what we lost with it. The Drakh have not been defeated. They have only changed hosts.\"\n\n=== END OF THE SHADOWS WITHIN - ENDING 2 OF 7 ===', 'Earth Orbit - Centauri Flagship', 1),
(192, 'Ending 3: The Silent Sacrifice', 'Bester falls to Ivanova\'s PPG, and the master detonator never fires. But the network does not release its captive. When the neural server finally shuts down, the cables that held Talia Winters go slack, and with them her last heartbeat. She gave the world its silence back with her own life.\n\nGaribaldi\'s broadcast carries the truth about Section 13 to every screen on the planet. Earth is free. In the rubble nobody speaks of victory.\n\nSHERIDAN (Hologram):\n\"The Corps is dissolved, Commander. Nobody will ever know what you did or what it cost. Marcus would have been proud, and heartbroken. Come home.\"\n\n=== END OF THE SHADOWS WITHIN - ENDING 3 OF 7 ===', 'Psi-Corps HQ - Ruins', 1),
(193, 'Ending 4: Firestorm (The Canon Echo)', 'Lyta does not wait for a plan. Her eyes ignite in the full terrible light of the Vorlons, and she opens her mind like a furnace door. The Corps does not collapse, it burns: the matrix chamber, the training wards, every cable and every mind connected to the net.\n\nFrom the smoke steps LENNIER, the disgraced Ranger who has carried his guilt for years. He raises his hands against the fire, not to stop Lyta, but to hold the walls long enough for the others to run. Ivanova screams at you to go. You go.\n\nBehind you the headquarters tears itself apart in one white explosion. Lyta Alexander and Lennier do not come out. Bester, however, was never in the chamber: a hidden lift carried him away minutes earlier, and Garibaldi\'s voice on the radio is flat and cold.\n\nGARIBALDI:\n\"He ran. Let him run. I will find him.\"\n\nEarth is free of the Corps, and the price was exactly what Lyta asked for. Some of the dead were never part of the war.\n\n=== END OF THE SHADOWS WITHIN - ENDING 4 OF 7 ===', 'Psi-Corps HQ - Geneva', 1),
(194, 'Ending 5: The Shadow Director', 'You do not hesitate. A cold shot ends Bester\'s life, and before the echo has faded your old Black Watch override codes are already in the system, rewriting the master matrix. Ivanova stares at you, her weapon still raised.\n\nCOMMANDER:\n\"The network stays. It simply answers to somebody else now.\"\n\nWithin a week the Corps is officially dissolved. Unofficially, a new, invisible Bureau 13 appears on no org chart, and its director controls the dreams of humanity from the shadows. Marcus Cole would not have recognized the person who now sits at his old partner\'s desk.\n\n=== END OF THE SHADOWS WITHIN - ENDING 5 OF 7 ===', 'Psi-Corps HQ - Bureau 13', 1),
(195, 'Ending 6: The Silence (Game Over)', 'The assault force is too small. Without the fleets you did not win over, the Black Omega squadrons close the ring. The Liburnia takes hit after hit, Sha\'In\'s voice breaks over the intercom, and the last thing on the viewscreen is a sapphire-blue Earth that stays silent.\n\nThe Corps tightens its net. Nobody ever finds out how close the Alliance came to ending it.\n\nSHERIDAN (Hologram, fading):\n\"If anyone hears this... the sleepers are awake. Valen forgive us.\"\n\n=== END OF THE SHADOWS WITHIN - ENDING 6 OF 7 ===', 'Earth Orbit - Wreckage', 1),
(200, 'Transit: Course for Centauri Prime', 'The Liburnia slips into the shimmering orange of hyperspace. The engines settle into a steady hum, and the long hours toward Centauri Prime begin.', 'Hyperspace - EAS Liburnia', 0),
(201, 'Transit: Course for the Narn Homeworld', 'The Liburnia slips into the shimmering orange of hyperspace. Hours remain until the crimson skies of Narn.', 'Hyperspace - EAS Liburnia', 0),
(202, 'Transit: Course for the Asteroid Bar', 'The Liburnia leaves the main lanes and follows a smuggler\'s route through the outer dark. Hours remain until the hollow asteroid.', 'Hyperspace - EAS Liburnia', 0),
(203, 'Transit: Course for Mars', 'The Liburnia runs dark, every unnecessary system shut down, as the last jump toward Mars begins. Nobody is in a hurry to speak.', 'Hyperspace - EAS Liburnia', 0),
(204, 'Transit: The Last Detour', 'A short jump back out to the lawless fringe. In the hold the rebel fleet\'s engines are still warm.', 'Hyperspace - EAS Liburnia', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `story_node_text`
--
-- Erstellt am: 06. Nov 2026 um 21:59
--

CREATE TABLE `story_node_text` (
  `text_id` int(11) NOT NULL,
  `node_id` int(11) NOT NULL,
  `priority` int(11) NOT NULL DEFAULT 0,
  `requires` text DEFAULT NULL,
  `body` mediumtext NOT NULL,
  `mode` varchar(16) NOT NULL DEFAULT 'replace'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `story_node_text`
--

INSERT INTO `story_node_text` (`text_id`, `node_id`, `priority`, `requires`, `body`, `mode`) VALUES
(1, 100, 0, NULL, 'Before you turn to leave, G\'Kar presses something heavy into your hand: a weathered stone seal. \"The seal of the Kha\'Ri. I am the last of the old council who still carries it. Where the Narn doubt you, let it speak for me.\"', 'append'),
(2, 112, 10, '[\"alignment>=0\"]', 'Draal lays a cool hand on your uninjured shoulder, his voice a whisper:\n\"You gather the shards of a broken mirror and believe you see the whole. A noble, laborious road. Beware the chamber where the echo of old betrayal sleeps. When you reach out in the red sand to break the chains, make sure you do not become the tool of the smith. Some gates open only to lock you in deeper.\"', 'append'),
(3, 112, 10, '[\"alignment<0\"]', 'The lights of the Great Machine flicker in the rhythm of his words. Draal steps back, his gaze stern:\n\"The river divides, traveler, and you already draw the blade through the water. A throne is only ever as high as the ash it is built upon. If the hunger for control steers your mind, you will rule the worldwide net, and the net will erase your own tracks in the dust.\"', 'append'),
(4, 113, 10, '[\"hub_seen\"]', 'Dr. Sarah Chambers guides the regeneration scanner over your wounds once more, fully restoring your HP. The hologram of Franklin is gone, but the Section 13 files still glow on the main screen, and Delenn\'s quiet words from earlier still hang in the room. Tessa Holloran nods at you from the door: the Liburnia is waiting.', 'replace'),
(5, 115, 10, '[\"visited_narn\"]', 'The Liburnia touches down on Centauri Prime, and the Court has already heard from Narn. EMPEROR LONDO MOLLARI sits on his heavy throne, his eyes narrow, foaming with barely concealed anger.\n\nLONDO MOLLARI:\n\"You come to me with the stench of Narn still on your boots! Sheridan wants my fleet? I demand absolute colonial authority over the Narn sectors once this war is over. Sign the military pact, or leave my palace empty-handed.\"\n\nThe demand rings hollow. Something small and dark rides on the Emperor\'s shoulder, and for a heartbeat his eyes plead with you. These are the words of the Drakh keeper, not of the man who once wept over Narn.\n\nBehind a marble pillar a nervous figure watches you: VIR COTTO.', 'replace'),
(6, 116, 20, '[\"pact=londo\"]', 'The Liburnia drops out of hyperspace over Narn, and every battery of the orbital grid is already locked onto you. News travels fast through subspace.\n\nELDER OF THE REGIME:\n\"You sold our sovereignty to Londo Mollari! The seal of the Kha\'Ri has lost its value. The regular Narn fleet will never fly for you.\"\n\nOnly the shadows remain. A smuggler contact in the lower city offers the services of Narn underground mercenaries for 20,000 credits. No honour, no questions.', 'replace'),
(7, 116, 20, '[\"pact=vir\"]', 'The Liburnia drops out of hyperspace over Narn, and the cruisers of the G\'Quan class part to let you through. A triumphant reception awaits in the red dust: the Narn have heard that you defied the imperial arrogance of Londo Mollari.\n\nELDER OF THE REGIME:\n\"The outsider who refused the Emperor. Come. The Kha\'Ri will hear you.\"', 'replace'),
(8, 116, 20, '[\"pact=double\"]', 'The Liburnia drops out of hyperspace over Narn. The cruisers of the G\'Quan class hold their fire: news has reached the Kha\'Ri that Centauri warships will fly with the Alliance, and nobody on Narn knows what it cost.\n\nNARN COMMANDER:\n\"Centauri ships under an Alliance flag. Interesting. Come down, outsider. The Elders want to hear how you managed that.\"', 'replace'),
(9, 141, 10, '[\"narn_state=wary\"]', 'After a long, tedious bureaucratic process the cruisers let you land, but two interceptors shadow every step. In the red sand the Elder studies you with deep distrust.\n\nThe Kha\'Ri nonetheless votes: the Narn Avantgarde will fly with you, but they will not forget the bluff.', 'replace'),
(10, 141, 20, '[\"pact=vir\"]', 'In the Citadel the Elder of the regime listens, moved by your refusal of the imperial arrogance. The newly founded Kha\'Ri, in its very first official decree, mobilizes the Narn fleet for the rescue of Earth. They set their own hardship aside and send their elite Avantgarde as loyal frontline fighters.', 'replace'),
(11, 141, 20, '[\"pact=double\"]', 'In the red sand the Elder of the regime listens to your report: the Centauri fleet will fly with the Alliance. The Kha\'Ri weighs it carefully, a people that has learned to distrust Centauri gifts. In the end it votes for the Earth, and the elite Narn Avantgarde joins the fleet. Nobody asks what the Emperor\'s chamberlain was paid.', 'replace'),
(12, 151, 10, '[\"visited_narn\"]', 'The ink is not yet dry when the news reaches Narn by subspace. The Kha\'Ri cancels the alliance in fury, and the Avantgarde is permanently gone. Only the underground, and its price, remain.', 'append'),
(13, 152, 10, '[\"visited_narn\"]', 'Word travels fast: the Narn learn that you refused the Emperor. Their trust in you is confirmed, and the Kha\'Ri fleet stays loyal.', 'append'),
(14, 183, 10, '[\"mars_data\"]', 'The decrypted master plans show what Section 13 really is. At the heart of the sleeper network sits a salvaged Shadow device: the same kind of control core that once steered the Centauri cruisers like puppets, delivered to the Corps by the Drakh. The first devices of this kind were found years ago on the Drazi homeworld, in the wreckage of the raids that the Drakh, servants of the Shadows, launched through the authority of the Throne Regent to sow discord in the Alliance. Nobody recognised what they were. Alone such a device only commands machines. Bester found a way to make it command minds. The abducted technomages, their geometry twisted with Shadow technology, serve as living processors that carry the device\'s signal into every sleeper agent at once. Through them Bester controls all Section 13 operatives simultaneously, and through the operatives Earth\'s ministries, fleets and media.\n\nMELDOR:\n\"My brothers are the lock and the key. As long as the device runs, they can neither die nor wake. Break the link at its source, in the relay beneath Geneva, and the chains fall from all of them at once.\"\n\nThen you scan the central stasis chamber. According to the files, the biological master switch of the net should lie here: Talia Winters. The massive biocrystalline capsule in front of you is empty. The life-support cables hang slack, a faint blue residual signature flickering in the lines. A security transfer three days ago; destination classified.\n\nA tonne-heavy steel bulkhead slams shut behind you. Red alarm lights flood the hangar, and an enormous figure detaches from the shadows of the Black Omega Starfuries: the WARMAGE, an elite Corps enforcer with implanted combat modules and glowing runes, a biomechanical staff spitting blue plasma in his hands.\n\nWARMAGE (Mental Projection):\n\"The intruder from the silent sector... You think Michael Garibaldi\'s cheap corporate codes could fool the eyes of the Corps? This switch unit is the property of Alfred Bester. You will not leave this crater alive.\"', 'replace'),
(15, 181, 8, '[\"mars_data\"]', 'The Section 13 files change the plan. Ivanova taps the holo-grid: the relay beneath Geneva is the one point where the Shadow device and the technomage processors meet. Destroy it before the confrontation with Bester, and every sleeper agent on Earth is cut loose in the same second.', 'append'),
(16, 181, 8, '[\"!mars_data\"]', 'Without the Mars files the plan stays blunt: smash the jamming station, and hope that whatever feeds Bester\'s net breaks with it.', 'append'),
(17, 125, 10, '[\"mars_data\"]', 'Ivanova\'s ground teams do not stop to fight. The Section 13 files show them the way to the conduit hall beneath the lounge, where the cable bundles of the sleeper net run into the relay core: a Shadow device, wrapped in the living geometry of imprisoned technomages. Ivanova plants the charges herself. The core dies in a blue-white flash, and across the planet the sleeper agents collapse awake, their strings cut.\n\nMELDOR:\n\"My brothers... they wake.\"\n\nBester\'s global control is broken. Only his failsafe remains.', 'append'),
(18, 125, 10, '[\"!mars_data\"]', 'Without the Mars files the way to the conduit hall is a guessing game. Three wrong corridors later Meldor feels the echo of the Shadow device and leads the teams down. Ivanova\'s charges bring the relay core down, but too late to spare the sleepers a few terrible minutes: across the planet agents collapse in convulsions before the strings finally snap.\n\nBester\'s global control is broken. Only his failsafe remains.', 'append'),
(19, 200, 10, '[\"jumps=1\"]', 'MOKA:\n\"Centauri. Always Centauri. In my clan we spit when we say the name.\"\n\nSHA\'IN:\n\"You spit when you say anything, Moka.\"\n\nMOKA:\n\"Yes. But for this one I spit with feeling.\"', 'append'),
(20, 200, 5, '[\"visited_narn\"]', 'MELDOR:\n\"You carry the dust of Narn into the Emperor\'s court, Commander. A court like that smells everything.\"', 'append'),
(21, 201, 10, '[\"!pact\"]', 'Sha\'In turns G\'Kar\'s stone seal over in her fingers.\n\nSHA\'IN:\n\"It is lighter than I expected. For something a whole people is meant to obey.\"', 'append'),
(22, 201, 10, '[\"pact=londo\"]', 'MOKA:\n\"So you signed. And now we ask the Narn to look away while we do it. Very good plan.\"\n\nNobody answers him. Nobody tells him he is wrong, either.', 'append'),
(23, 201, 10, '[\"pact=vir\"]', 'Sha\'In has finally stopped glancing at the exit hatches. Somebody has left her a mug of tea.', 'append'),
(24, 202, 10, NULL, 'MELDOR:\n\"She burns brightly, Commander. Fires consume whatever stands too close.\"', 'append'),
(25, 203, 10, NULL, 'SHA\'IN:\n\"If anyone wants to be afraid, now would be a good time. Afterwards there will be no time.\"', 'append'),
(26, 204, 10, NULL, 'Somewhere behind the bulkhead Ivanova is shouting at a quartermaster. The sound is oddly comforting.', 'append'),
(27, 172, 10, '[\"lyta_contact\"]', 'The bar owner never asks your name. A few quiet words through the right smugglers, a few favours that do not appear in any ledger, and Lyta Alexander finally turns her chair toward you.\n\nLYTA:\n\"You came through my own people. That tells me you at least know how to listen. I do not believe in promises, Commander. Bring me proof from the Corps\' own vaults, and I will think about it. Now go.\"\n\nShe dissolves into the smoke. The door is not locked, but it only opens for someone who brings evidence.', 'replace'),
(28, 161, 10, '[\"wreck_style=tactical\"]', 'You shout at Moka, who braces his shoulder under the girder while you bridge the dead terminal. The Drazi grunts, bones cracking, but holds. The war chest unlocks, 25,000 credits for the alliance, and Sha\'In scrambles free with her tool bag. Meldor only nods, unreadable beneath his hood, but the nod is there.', 'replace'),
(29, 118, 20, '[\"mars_gate=1\"]', 'The asteroid bar is as dark as before. Lyta sits in the same corner, but this time she watches you from the start, her Vorlon blue dimmed.\n\nLYTA:\n\"You went to the crater. I felt the screaming even from here. So tell me, why should I follow you?\"', 'replace'),
(30, 118, 5, '[\"mars_gate=1\",\"lyta_visits>=1\"]', 'She remembers your first visit and the promise you made.', 'append'),
(31, 121, 10, '[\"lyta_state=vengeance\"]', 'Lyta watches the live feed from the Liburnia, silent. She distrusts Talia Winters as a former Corps tool, so this operation is about stolen data, nothing else.', 'append'),
(32, 126, 10, '[\"lyta\"]', 'At the back of the chamber the air hums blue. LYTA ALEXANDER has come, her eyes burning with the light of the Vorlons.', 'append'),
(33, 181, 10, '[\"lyta\"]', 'Lyta stands apart from the hologram table, arms crossed, her eyes glowing faintly. She says nothing, but she is here.', 'append'),
(34, 181, 5, '[\"!lyta\",\"lyta_visits>=2\"]', 'Lyta is beyond reach now. The asteroid bar is empty.', 'append'),
(35, 191, 10, '[\"lyta_state=light\"]', 'Lyta\'s light touches the court of Centauri Prime for an instant, and the shadow on Londo\'s shoulder flinches, but the Drakh have only learned to hide.', 'append'),
(36, 194, 20, '[\"origin=Underworld\"]', 'You do not hesitate. A cold shot ends Bester\'s life, and before the echo fades your smuggler contacts have already bought the last keys to the master matrix, wiring the sleeper net into your own cartel.\n\nCOMMANDER:\n\"The network stays. It simply answers to somebody else now.\"\n\nWithin a week the Corps is officially dissolved. Unofficially, an invisible syndicate with a new, quiet name controls the dreams of humanity from the shadows, and the Commander who once ran contraband now runs the minds of billions. Marcus Cole would not have recognized the person behind the desk.\n\n=== END OF THE SHADOWS WITHIN - ENDING 5 OF 7 ===', 'replace'),
(37, 195, 20, '[\"hp<=0\"]', 'The wounds were too deep. The reactor burns, the controls slip from your fingers, and the sapphire Earth disappears behind the glare of a breaking hull. Sha\'In\'s voice calls your name over the intercom, again and again.\n\nNobody will ever know how close you came.\n\n=== END OF THE SHADOWS WITHIN - ENDING 6 OF 7 ===', 'replace');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--
-- Erstellt am: 06. Nov 2026 um 21:59
-- Zuletzt aktualisiert: 07. Nov 2026 um 12:30
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`user_id`, `username`, `password_hash`, `created_at`, `is_admin`) VALUES
(1, 'Shinji2501', '$2y$12$J1NnxMyUdTtxkCYgqnB9Qu0F./HWUITxF0.AXUG54K0mxVOWF0NXK', '2026-11-06 22:26:57', 1);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`);

--
-- Indizes für die Tabelle `player_crew`
--
ALTER TABLE `player_crew`
  ADD PRIMARY KEY (`slot_id`,`member_name`);

--
-- Indizes für die Tabelle `player_history`
--
ALTER TABLE `player_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `chosen_choice_id` (`chosen_choice_id`);

--
-- Indizes für die Tabelle `player_slots`
--
ALTER TABLE `player_slots`
  ADD PRIMARY KEY (`slot_id`),
  ADD UNIQUE KEY `uq_slot` (`user_id`,`slot_number`),
  ADD KEY `current_node_id` (`current_node_id`);

--
-- Indizes für die Tabelle `player_visited_locations`
--
ALTER TABLE `player_visited_locations`
  ADD PRIMARY KEY (`slot_id`,`location_key`);

--
-- Indizes für die Tabelle `story_choices`
--
ALTER TABLE `story_choices`
  ADD PRIMARY KEY (`choice_id`),
  ADD KEY `from_node_id` (`from_node_id`),
  ADD KEY `next_node_id` (`next_node_id`);

--
-- Indizes für die Tabelle `story_nodes`
--
ALTER TABLE `story_nodes`
  ADD PRIMARY KEY (`node_id`);

--
-- Indizes für die Tabelle `story_node_text`
--
ALTER TABLE `story_node_text`
  ADD PRIMARY KEY (`text_id`),
  ADD KEY `node_id` (`node_id`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `uq_users_username` (`username`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT für Tabelle `player_history`
--
ALTER TABLE `player_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT für Tabelle `player_slots`
--
ALTER TABLE `player_slots`
  MODIFY `slot_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `story_node_text`
--
ALTER TABLE `story_node_text`
  MODIFY `text_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `player_crew`
--
ALTER TABLE `player_crew`
  ADD CONSTRAINT `player_crew_ibfk_1` FOREIGN KEY (`slot_id`) REFERENCES `player_slots` (`slot_id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `player_history`
--
ALTER TABLE `player_history`
  ADD CONSTRAINT `player_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `player_history_ibfk_2` FOREIGN KEY (`chosen_choice_id`) REFERENCES `story_choices` (`choice_id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `player_slots`
--
ALTER TABLE `player_slots`
  ADD CONSTRAINT `player_slots_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `player_slots_ibfk_2` FOREIGN KEY (`current_node_id`) REFERENCES `story_nodes` (`node_id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `player_visited_locations`
--
ALTER TABLE `player_visited_locations`
  ADD CONSTRAINT `player_visited_locations_ibfk_1` FOREIGN KEY (`slot_id`) REFERENCES `player_slots` (`slot_id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `story_choices`
--
ALTER TABLE `story_choices`
  ADD CONSTRAINT `story_choices_ibfk_1` FOREIGN KEY (`from_node_id`) REFERENCES `story_nodes` (`node_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `story_choices_ibfk_2` FOREIGN KEY (`next_node_id`) REFERENCES `story_nodes` (`node_id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `story_node_text`
--
ALTER TABLE `story_node_text`
  ADD CONSTRAINT `story_node_text_ibfk_1` FOREIGN KEY (`node_id`) REFERENCES `story_nodes` (`node_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
