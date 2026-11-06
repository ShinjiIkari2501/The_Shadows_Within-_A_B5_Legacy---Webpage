USE b5_project;

-- 1. Create a new test user account
INSERT INTO users (username, password_hash)
VALUES ('IvanovaFan', '$2y$10$FakeHashForTestingPurposesOnly12345');

-- 2. Fetch the generated user ID and initialize save slot 2 at the prologue node (100)
SET @new_user_id = LAST_INSERT_ID();
INSERT INTO player_slots (user_id, slot_number, current_node_id)
VALUES (@new_user_id, 2, 100);

-- 3. Verification Query: Confirm the player's current location and active sector
SELECT u.username, s.slot_number, n.title, n.active_sector
FROM player_slots s
         JOIN users u ON s.user_id = u.user_id
         JOIN story_nodes n ON s.current_node_id = n.node_id
WHERE u.username = 'IvanovaFan';
