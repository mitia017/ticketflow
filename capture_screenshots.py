from playwright.sync_api import sync_playwright
import time
import os

def run_screenshots():
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        # Set viewport size to 1280x800 for better screenshots
        context = browser.new_context(viewport={'width': 1280, 'height': 800})
        page = context.new_page()

        # 1. Login Page
        print("Navigating to login page...")
        page.goto("http://localhost:8080/login")
        time.sleep(5) # Wait for Vue to mount and render

        # Check if we are on the login page by looking for text
        if "Welcome back" not in page.content():
            print("Warning: 'Welcome back' not found in page content. Current content snippet:")
            print(page.content()[:500])

        page.screenshot(path="public/screenshots/login.png")
        print("Captured login.png")

        # Login
        print("Filling login form...")
        page.fill('input[placeholder="your@email.com"]', 'admin@demo.com')
        page.fill('input[placeholder="••••••••"]', 'password')
        page.click('button:has-text("Login")')

        print("Waiting for dashboard...")
        time.sleep(5) # Wait for dashboard to load and charts to render

        # 2. Dashboard
        page.screenshot(path="public/screenshots/dashboard.png")
        print("Captured dashboard.png")

        # 3. Ticket List
        print("Navigating to tickets list...")
        page.click('a:has-text("Tickets")')
        time.sleep(3)
        page.screenshot(path="public/screenshots/tickets_list.png")
        print("Captured tickets_list.png")

        # 4. Ticket Details
        print("Navigating to ticket details...")
        # Click on the first ticket in the table
        page.click('table tbody tr:first-child')
        time.sleep(3)
        page.screenshot(path="public/screenshots/ticket_detail.png")
        print("Captured ticket_detail.png")

        browser.close()

if __name__ == "__main__":
    run_screenshots()
