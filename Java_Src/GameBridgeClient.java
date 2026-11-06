package Java_Src;

import java.io.IOException;
import java.net.URI;
import java.net.http.HttpClient;
import java.net.http.HttpRequest;
import java.net.http.HttpResponse;
import java.util.HashMap;
import java.util.Map;
import java.util.Scanner;
import org.json.JSONArray;
import org.json.JSONObject;

/**
 * GameBridgeClient.java - KORRIGIERTE VERSION
 * Live Interactive Tactical Terminal für 'The Shadows Within - A B5 Legacy'
 */
public class GameBridgeClient {

    // KORREKTUR: Pfad auf das Hauptverzeichnis angepasst
    private static final String BRIDGE_URL = "http://localhost/Game-Bridge.php";
    private static final HttpClient httpClient = HttpClient.newHttpClient();
    private static final Scanner scanner = new Scanner(System.in);
    private static final Map<Integer, Integer> activeChoicesMap = new HashMap<>();

    public static String sendBridgeRequest(int userId, int slotNumber, Integer choiceId, String command, String gameState) {
        JSONObject jsonPayload = new JSONObject();

        String action = command;
        if ("START_SESSION".equalsIgnoreCase(gameState) || "INIT".equalsIgnoreCase(command)) {
            action = "INIT";
        }

        jsonPayload.put("action", action);
        jsonPayload.put("user_id", userId);
        jsonPayload.put("slot_number", slotNumber);

        if (choiceId != null) {
            jsonPayload.put("choice_id", choiceId);
        }

        // KORREKTUR: Auf application/json umgestellt
        HttpRequest request = HttpRequest.newBuilder()
                .uri(URI.create(BRIDGE_URL))
                .header("Content-Type", "application/json; charset=UTF-8")
                .POST(HttpRequest.BodyPublishers.ofString(jsonPayload.toString()))
                .build();

        try {
            HttpResponse<String> response = httpClient.send(request, HttpResponse.BodyHandlers.ofString());
            return response.body();
        } catch (IOException | InterruptedException e) {
            return "{\"status\":\"ERROR\", \"message\":\"[🚨 CLIENT DATA RECOIL]: Connection to Game-Bridge.php disrupted.\"}";
        }
    }

    public static boolean renderGameScene(String jsonRawStream) {
        try {
            JSONObject response = new JSONObject(jsonRawStream);
            activeChoicesMap.clear();

            if ("ERROR".equals(response.optString("status"))) {
                System.out.println("\n--- ENGINE ERROR SYSTEM ALERT ---");
                System.out.println(response.optString("message"));
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
            System.out.println(description.replace("\\n", "\n"));
            System.out.println("\n---------------------------------------------------------------------------------");

            if (isEnding) {
                System.out.println("\n [MISSION ACCOMPLISHED / GAME OVER] ");
                System.out.println("=================================================================================\n");
                return false;
            }

            if (response.has("choices")) {
                JSONArray choices = response.getJSONArray("choices");
                System.out.println(" CHOOSE YOUR NEXT VECTOR:");
                for (int i = 0; i < choices.length(); i++) {
                    JSONObject choice = choices.getJSONObject(i);
                    int choiceIndex = i + 1;
                    int realChoiceId = choice.getInt("choice_id");

                    activeChoicesMap.put(choiceIndex, realChoiceId);
                    System.out.println(" [" + choiceIndex + "] " + choice.getString("choice_text"));
                }
            }
            System.out.println("=================================================================================\n");
            return true;

        } catch (Exception e) {
            System.out.println("[🚨 CRITICAL RENDER FAILURE]: Failed to process incoming tactical stream: " + e.getMessage());
            return false;
        }
    }

    public static void main(String[] args) {
        int userId = 1;
        int slotNumber = 1;

        System.out.println("Connecting to Sector 2264 tactical uplink...");

        String payload = sendBridgeRequest(userId, slotNumber, null, "INIT", "START_SESSION");
        boolean gameRunning = renderGameScene(payload);

        while (gameRunning) {
            System.out.print("Input vector coordinate index: ");
            String input = scanner.nextLine().trim();

            try {
                int selection = Integer.parseInt(input);

                if (activeChoicesMap.containsKey(selection)) {
                    int targetChoiceId = activeChoicesMap.get(selection);

                    System.out.println("\nTransmitting navigation metrics to bridge core...");
                    payload = sendBridgeRequest(userId, slotNumber, targetChoiceId, "EXECUTE_CHOICE", "IN_GAME");
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
