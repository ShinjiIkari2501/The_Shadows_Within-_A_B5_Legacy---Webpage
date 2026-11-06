import java.util.Scanner;

/**
 * Main_GameLoop_Backup.java
 * Central Execution Entry Point and Game Loop for 'The Shadows Within - A B5 Legacy'.
 * Orchestrates player command inputs, network sync cycles, and terminal output.
 */
public class Main_GameLoop_Backup {

    public static void main(String[] args) {
        // Initialize network infrastructure and input scanners
        GameEngine engine = new GameEngine();
        Scanner scanner = new Scanner(System.in);

        // Game state buffers (Context tracking)
        String currentGameState = "START_SESSION";
        boolean isRunning = true;

        System.out.print("""
        ==================================================
               THE SHADOWS WITHIN - A B5 LEGACY           
                Sub-Sector Terminal: Telepath War         
        ==================================================
         >> System core initialized. Establishing link to API...
        """);

        // 1. Initial baseline telemetry sync with the PHP core (Triggers 'INIT' in Game-Bridge)
        String initialResponse = engine.syncGameTelemetry("INIT", currentGameState);

        if (initialResponse != null && !initialResponse.contains("\"status\":\"ERROR\"")) {
            System.out.print("""
                    
                     >> [LINK ESTABLISHED]: Live telemetry streaming active.
                    
                    --- MISSION BRIEFING DATA ---
                    """);

            // Render the initial node nicely instead of raw JSON
            renderGameOutput(initialResponse);
            currentGameState = "STATE_SYNCED";
        } else {
            System.out.print("""
                     >> [🚨 CRITICAL COMM-FAULT]: Bridge offline or Database Sync failed.
                    """);
            if (initialResponse != null) {
                System.out.println(" Server Response: " + initialResponse);
            }
            isRunning = false;
        }

        // ==================================================================
        // CORE GAME LOOP (Handles real-time session execution)
        // ==================================================================
        while (isRunning) {
            System.out.print("\nEnter your next Choice-ID (e.g. 101), type 'FETCH_NODE' to refresh, or 'EXIT': ");
            String playerInput = scanner.nextLine().trim();

            // Intercept graceful termination command
            if (playerInput.equalsIgnoreCase("EXIT")) {
                System.out.print("""
                         >> Terminating connection to Command Deck. Goodbye, Commander.
                        """);
                isRunning = false;
                break;
            }

            if (playerInput.isEmpty()) {
                System.out.print("""
                         >> [SYSTEM NOTICE]: Empty input registered. Please state your command vector.
                        """);
                continue;
            }

            System.out.print("""
                     >> Dispatching tactical telemetry to remote bridge...
                    """);

            // 2. Format transmission based on input type
            String jsonResponse;
            if (playerInput.equalsIgnoreCase("FETCH_NODE")) {
                // Manual refresh request
                jsonResponse = engine.syncGameTelemetry("FETCH_NODE", currentGameState);
            } else {
                // The input is treated as a Choice-ID path progression
                // GameEngine internally builds EXECUTE_CHOICE request with this ID
                jsonResponse = engine.syncGameTelemetry(playerInput, currentGameState);
            }

            if (jsonResponse != null) {
                System.out.print("""
                        
                        --- SERVER DATA INTERCEPTED ---
                        """);

                if (jsonResponse.contains("\"status\":\"ERROR\"")) {
                    System.out.println(" ❌ " + jsonResponse);
                } else {
                    renderGameOutput(jsonResponse);
                    currentGameState = "STATE_SYNCED";
                }
            } else {
                System.out.print("""
                         >> [🚨 TRANSMISSION FAILURE]: Remote reactor did not respond to tactical command.
                        """);
            }
        }

        // Close thread allocations securely
        scanner.close();
    }

    /**
     * Helper method to parse the JSON response dynamically and output a structured layout
     */
    private static void renderGameOutput(String json) {
        try {
            String title = extractJsonValue(json, "title");
            String description = extractJsonValue(json, "description");
            String sector = extractJsonValue(json, "active_sector");
            String nodeId = extractJsonValue(json, "node_id");

            System.out.println("--------------------------------------------------");
            System.out.println(" 📍 LOCATION: " + sector + " (Node: " + nodeId + ")");
            System.out.println(" 🎬 SCENE:    " + title);
            System.out.println("--------------------------------------------------");
            System.out.println(description.replace("\\n", "\n"));
            System.out.println("--------------------------------------------------");

            // Look for choices signature block
            if (json.contains("\"choices\":[")) {
                System.out.println(" AVAILABLE DECISION VECTORS:");
                // Quick parsing for basic array string display
                int choicesStart = json.indexOf("\"choices\":[");
                String choicesSubstring = json.substring(choicesStart);

                int index = 0;
                while ((index = choicesSubstring.indexOf("{\"choice_id\":", index)) != -1) {
                    int idStart = choicesSubstring.indexOf(":", index) + 1;
                    int idEnd = choicesSubstring.indexOf(",", idStart);
                    String cId = choicesSubstring.substring(idStart, idEnd).trim();

                    int textStart = choicesSubstring.indexOf("\"choice_text\":\"", idEnd) + 15;
                    int textEnd = choicesSubstring.indexOf("\"", textStart);
                    String cText = choicesSubstring.substring(textStart, textEnd);

                    System.out.println("   👉 [" + cId + "] " + cText);
                    index = textEnd;
                }
            }
            System.out.println("--------------------------------------------------");
        } catch (Exception e) {
            // Fallback display if custom text parsing errors out
            System.out.println(" Raw Terminal Feed: " + json);
        }
    }

    /**
     * Extracts values from raw string stream without library dependencies
     */
    private static String extractJsonValue(String json, String key) {
        try {
            String searchPattern = "\"" + key + "\":\"";
            int start = json.indexOf(searchPattern);
            if (start == -1) {
                searchPattern = "\"" + key + "\":";
                start = json.indexOf(searchPattern);
                if (start == -1) return "N/A";
                int end = json.indexOf(",", start);
                if (end == -1) end = json.indexOf("}", start);
                return json.substring(start + searchPattern.length(), end).trim();
            }
            int end = json.indexOf("\"", start + searchPattern.length());
            return json.substring(start + searchPattern.length(), end);
        } catch (Exception e) {
            return "Data Extraction Fault";
        }
    }
}
