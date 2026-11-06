USE b5_project;

-- Fetch all available narrative choices for the active slot of a specific user
SELECT u.username, n.title AS current_scene, c.choice_text AS available_action, next_node.title AS leads_to
FROM player_slots s
         JOIN users u ON s.user_id = u.user_id
         JOIN story_nodes n ON s.current_node_id = n.node_id
         JOIN story_choices c ON n.node_id = c.from_node_id
         JOIN story_nodes next_node ON c.next_node_id = next_node.node_id
WHERE u.username = 'Shinji2501' AND s.slot_number = 1;
