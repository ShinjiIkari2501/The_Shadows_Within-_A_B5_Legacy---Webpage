/**
 * SaveSlot.java
 * Core Data Class representing a user save slot in 'The Shadows Within - A B5 Legacy'.
 * Maps directly to the 'player_slots' relational database table.
 */
public class SaveSlot {
    // Attributes matching the relational database columns
    private int slotId;
    private int userId;
    private int slotNumber; // Enforced between 1 and 3
    private int currentNodeId;
    private String lastUpdated;

    // Default constructor for framework serialization mapping
    public SaveSlot() {}

    // Overloaded constructor to initialize a save slot directly
    public SaveSlot(int slotId, int userId, int slotNumber, int currentNodeId, String lastUpdated) {
        this.slotId = slotId;
        this.userId = userId;
        this.slotNumber = slotNumber;
        this.currentNodeId = currentNodeId;
        this.lastUpdated = lastUpdated;
    }

    // Getters and Setters (Standard Java Encapsulation)
    public int getSlotId() { return slotId; }
    public void setSlotId(int slotId) { this.slotId = slotId; }

    public int getUserId() { return userId; }
    public void setUserId(int userId) { this.userId = userId; }

    public int getSlotNumber() { return slotNumber; }
    public void setSlotNumber(int slotNumber) { this.slotNumber = slotNumber; }

    public int getCurrentNodeId() { return currentNodeId; }
    public void setCurrentNodeId(int currentNodeId) { this.currentNodeId = currentNodeId; }

    public String getLastUpdated() { return lastUpdated; }
    public void setLastUpdated(String lastUpdated) { this.lastUpdated = lastUpdated; }

    /**
     * Prints the profile details of this save slot to the console.
     */
    public void displaySlotDetails() {
        System.out.println("Slot #" + slotNumber + " -> Current Node: " + currentNodeId + " | Last Saved: " + lastUpdated);
    }
}
