USE b5_project;

-- FAILURE TEST 1: Attempting to use an invalid slot number (Should trigger CHECK constraint violation)
INSERT INTO player_slots (user_id, slot_number, current_node_id)
VALUES (1, 4, 100);

-- FAILURE TEST 2: Attempting to duplicate a slot allocation (Should trigger UNIQUE KEY unique_user_slot violation)
INSERT INTO player_slots (user_id, slot_number, current_node_id)
VALUES (1, 1, 101);
