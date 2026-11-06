import java.io.IOException;
import java.net.URI;
import java.net.URLEncoder;
import java.net.http.HttpClient;
import java.net.http.HttpRequest;
import java.net.http.HttpResponse;
import java.nio.charset.StandardCharsets;
import java.util.HashMap;
import java.util.Map;
import java.util.Scanner;
import java.util.stream.Collectors;
import org.json.JSONArray;
import org.json.JSONObject;

/**
 * GameBridgeClient.java
 * Live Interactive Tactical Terminal for 'The Shadows Within - A B5 Legacy'
 * Handles the persistent game loop, user input, and state synchronization via PHP Core.
 */
public class GameBridgeClient {

    private static final String BRIDGE_URL = "http://localhost/Game-Bridge.php";
    private static final HttpClient httpClient = HttpClient.newHttpClient();
    private static final Scanner scanner = new Scanner(System.in);

    // Track active target node mapping for input processing
    private static final Map<Integer, Integer> activeChoicesMap = new HashMap<>();

    /**
     * Dispatches a structured POST payload to the PHP REST API endpoint.
     */
    public static String sendBridgeRequest(int userId, int slotNumber, Integer nodeId, String command, String gameState) {
        Map<String, String> parameters = new HashMap<>();
        parameters.put("user_id", String.valueOf(userId));
        parameters.put("slot_number", String.valueOf(slotNumber));
        parameters.put("befehl", command != null ? command : "INIT");
        parameters.put("zustand", gameState != null ? gameState : "PLAYING");

        if (nodeId != null) {
            parameters.put("node_id", String.valueOf(nodeId));
        }

        String formBody = parameters.entrySet().stream()
                .map(e -> e.getKey() + "=" + URLEncoder.encode(e.getValue(), StandardCharsets.UTF_8))
                .collect(Collectors.joining("&"));

        HttpRequest request = HttpRequest.newBuilder()
                .uri(URI.create(BRIDGE_URL))
                .header("Content-Type", "application/x-www-form-urlencoded")
                .POST(HttpRequest.BodyPublishers.ofString(formBody))
                .build();

        try {
            HttpResponse<String> response = httpClient.send(request, HttpResponse.BodyHandlers.ofString());
            return response.body();
        } catch (IOException | InterruptedException e) {
            return "{\"status\":\"error\", \"error_message\":\"[🚨 CLIENT DATA RECOIL]: Connection Link to Game-Bridge.php disrupted.\"}";
        }
    }

    /**
     * Parses the JSON stream, renders the UI, and returns true if the game continues.
     */
    public static boolean renderGameScene(String jsonRawStream) {
        try {
            JSONObject response = new JSONObject(jsonRawStream);
            activeChoicesMap.clear(); // Flush old choices from previous node

            if ("error".equals(response.optString("status"))) {
                System.out.println("\n--- ENGINE ERROR SYSTEM ALERT ---");
                System.out.println(response.getString("error_message"));
                return false;
            }

            String title = response.getString("title");
            String description = response.getString("description");
            String sector = response.getString("active_sector");
            boolean isEnding = response.getBoolean("is_ending");

            System.out.println("\n=================================================================================");
            System.out.println(" LOCATION: " + sector.toUpperCase());
            System.out.println("=================================================================================");
            System.out.println("\n>>> " + title + " <<<\n");
            System.out.println(description);
            System.out.println("\n---------------------------------------------------------------------------------");

            if (isEnding) {
                System.out.println("\n [MISSION ACCOMPLISHED / GAME OVER] ");
                System.out.println("=================================================================================\n");
                return false; // Stop the game loop on ending nodes
            }

            JSONArray choices = response.getJSONArray("choices");
            System.out.println(" CHOOSE YOUR NEXT VECTOR:");
            for (int i = 0; i < choices.length(); i++) {
                JSONObject choice = choices.getJSONObject(i);
                int choiceIndex = i + 1;
                int nextNodeId = choice.getInt("next_node_id");

                // Map local option number (1, 2, 3...) to the real database node target
                activeChoicesMap.put(choiceIndex, nextNodeId);
                System.out.println(" [" + choiceIndex + "] " + choice.getString("choice_text"));
            }
            System.out.println("=================================================================================\n");
            return true;

        } catch (Exception e) {
            System.out.println("[🚨 CRITICAL RENDER FAILURE]: Failed to process incoming tactical stream: " + e.getMessage());
            return false;
        }
    }

    public static void main(String[] args) {
        // Player setup configuration parameters
        int userId = 1;     // Fixed target for verification testing (Shinji2501)
        int slotNumber = 1; // Target save slot

        System.out.println("Connecting to Sector 2264 tactical uplink...");

        // Initial request fetches the last saved node from slot 1 (or falls back to 100)
        String payload = sendBridgeRequest(userId, slotNumber, null, "INIT", "START_SESSION");
        boolean gameRunning = renderGameScene(payload);

        // Core Terminal Execution Loop
        while (gameRunning) {
            System.out.print("Input vector coordinate index: ");
            String input = scanner.nextLine().trim();

            try {
                int selection = Integer.parseInt(input);

                if (activeChoicesMap.containsKey(selection)) {
                    int targetNodeId = activeChoicesMap.get(selection);

                    System.out.println("\nTransmitting navigation metrics to bridge core...");
                    // Send selection to bridge, updating the database slot in real-time
                    payload = sendBridgeRequest(userId, slotNumber, targetNodeId, "EXECUTE_CHOICE", "IN_GAME");
                    gameRunning = renderGameScene(payload);
                } else {
                    System.out.println("[⚠️ INVALID TARGET]: Selected vector index out of bounds. Try again.");
                }
            } catch (NumberFormatException e) {
                System.out.println("[⚠️ INPUT ERROR]: Tactical coordinates require numeric values.");
            }
        }

        System.out.println("Uplink terminated. Safe travels in Sector 2264, Commander.");
        scanner.close();
    }
}
