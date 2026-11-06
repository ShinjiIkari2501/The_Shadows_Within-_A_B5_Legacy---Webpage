/**
 * StoryNode.java
 * Core Data Class representing a narrative scenario in 'The Shadows Within - A B5 Legacy'.
 * Matches the 'current_scenario' JSON object layout from the REST API.
 */
public class StoryNode {
    private int nodeId;
    private String title;
    private String description;
    private String activeSector;
    private boolean isEnding;

    // Default constructor
    public StoryNode() {}

    // Overloaded constructor
    public StoryNode(int nodeId, String title, String description, String activeSector, boolean isEnding) {
        this.nodeId = nodeId;
        this.title = title;
        this.description = description;
        this.activeSector = activeSector;
        this.isEnding = isEnding;
    }

    // Getters and Setters
    public int getNodeId() { return nodeId; }
    public void setNodeId(int nodeId) { this.nodeId = nodeId; }

    public String getTitle() { return title; }
    public void setTitle(String title) { this.title = title; }

    public String getDescription() { return description; }
    public void setDescription(String description) { this.description = description; }

    public String getActiveSector() { return activeSector; }
    public void setActiveSector(String activeSector) { this.activeSector = activeSector; }

    public boolean isEnding() { return isEnding; }
    public void setEnding(boolean ending) { isEnding = ending; }

    public void displayNodeInfo() {
        System.out.println("\n=== [" + title.toUpperCase() + "] ===");
        System.out.println("Sector: " + activeSector + " | Node ID: " + nodeId);
        System.out.println("----------------------------------------");
        System.out.println(description);
    }
}
