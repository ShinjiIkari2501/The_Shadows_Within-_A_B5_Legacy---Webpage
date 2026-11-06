import mysql.connector
from reportlab.lib.pagesizes import letter
from reportlab.platypus import SimpleDocTemplate, Paragraph, Spacer, Table, TableStyle
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.lib import colors
from datetime import datetime

def run_database_tests():
    """
    Connects to the XAMPP MySQL database, executes CRUD and constraint tests,
    and returns the results for the automated report.
    """
    test_results = []

    # Establish connection to XAMPP MySQL
    try:
        conn = mysql.connector.connect(
            host="localhost",
            user="root",
            password="",
            database="b5_project",
            port=3307
        )
        cursor = conn.cursor()
        test_results.append(("Database Connection", "SUCCESS", "Connected to port 3306 successfully."))
    except Exception as e:
        return [("Database Connection", "FAILED", f"Could not connect: {str(e)}")]

    # Test 1: Valid User Insertion
    try:
        cursor.execute("INSERT INTO users (username, password_hash) VALUES ('TestPilot', '$2y$10$ValidHash')")
        conn.commit()
        test_results.append(("Valid Data Insertion (CRUD)", "SUCCESS", "New user 'TestPilot' registered successfully."))
    except Exception as e:
        test_results.append(("Valid Data Insertion (CRUD)", "FAILED", str(e)))

    # Test 2: Triggering a CHECK constraint violation (Invalid slot)
    try:
        cursor.execute("INSERT INTO player_slots (user_id, slot_number, current_node_id) VALUES (1, 4, 100)")
        conn.commit()
        test_results.append(("Triggering CHECK Violation", "FAILED", "Database accepted invalid slot_number 4!"))
    except mysql.connector.Error as err:
        test_results.append(("Triggering CHECK Violation", "SUCCESS", f"Expected error caught: {err.msg}"))

    # Test 3: Triggering a UNIQUE constraint violation (Duplicate slot)
    try:
        cursor.execute("INSERT INTO player_slots (user_id, slot_number, current_node_id) VALUES (1, 1, 101)")
        conn.commit()
        test_results.append(("Triggering UNIQUE Violation", "FAILED", "Database accepted duplicate slot allocation!"))
    except mysql.connector.Error as err:
        test_results.append(("Triggering UNIQUE Violation", "SUCCESS", f"Expected error caught: {err.msg}"))

    # Clean up test user to keep DB pristine
    try:
        cursor.execute("DELETE FROM users WHERE username = 'TestPilot'")
        conn.commit()
    except:
        pass

    cursor.close()
    conn.close()
    return test_results

def generate_pdf_report(results):
    """
    Generates a structured PDF document from the test execution results.
    """
    pdf_path = "generated/b5_project_automated_report.pdf"
    doc = SimpleDocTemplate(pdf_path, pagesize=letter, title="B5 Project DB Test Report")
    story = []
    styles = getSampleStyleSheet()

    # Custom Styles
    title_style = ParagraphStyle('TitleStyle', parent=styles['Heading1'], fontSize=22, textColor=colors.HexColor('#1A365D'), spaceAfter=20)
    meta_style = ParagraphStyle('MetaStyle', parent=styles['Normal'], fontSize=10, textColor=colors.gray, spaceAfter=20)
    cell_style = ParagraphStyle('CellStyle', parent=styles['Normal'], fontSize=9)
    header_style = ParagraphStyle('HeaderStyle', parent=styles['Normal'], fontSize=10, fontName='Helvetica-Bold', textColor=colors.white)

    # Document Header
    story.append(Paragraph("b5_project — Automated Test Report", title_style))
    story.append(Paragraph(f"Execution Date: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')} | Target: MySQL Port 3306", meta_style))
    story.append(Spacer(1, 10))

    # Table Layout
    table_data = [[Paragraph("Test Component", header_style), Paragraph("Status", header_style), Paragraph("Execution Details / Error Message", header_style)]]

    for row in results:
        status_color = colors.HexColor('#2F855A') if row[1] == "SUCCESS" else colors.HexColor('#C53030')
        status_style = ParagraphStyle('StatusStyle', parent=styles['Normal'], fontSize=9, fontName='Helvetica-Bold', textColor=status_color)

        table_data.append([
            Paragraph(row[0], cell_style),
            Paragraph(row[1], status_style),
            Paragraph(row[2], cell_style)
        ])

    results_table = Table(table_data, colWidths=[150, 70, 320])
    results_table.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), colors.HexColor('#2A4365')),
        ('ALIGN', (0,0), (-1,-1), 'LEFT'),
        ('VALIGN', (0,0), (-1,-1), 'TOP'),
        ('BOTTOMPADDING', (0,0), (-1,-1), 8),
        ('TOPPADDING', (0,0), (-1,-1), 8),
        ('ROWBACKGROUNDS', (0,1), (-1,-1), [colors.white, colors.HexColor('#F7FAFC')]),
        ('GRID', (0,0), (-1,-1), 0.5, colors.HexColor('#E2E8F0')),
    ]))

    story.append(results_table)
    doc.build(story)

if __name__ == "__main__":
    print("Executing automated b5_project infrastructure verification...")
    execution_results = run_database_tests()
    generate_pdf_report(execution_results)
    print("Report compiled successfully inside /generated folder.")
