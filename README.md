# Marcel House Website Launch Guide

## Overview
This folder contains a WordPress-based website for Marcel House. The website files have been saved with their directory structure intact, including CSS, JavaScript, HTML, and Bootstrap components.

## Directory Structure
- `marcelhouse.ee/` - Main website directory containing the WordPress files
  - `index.html` - Main entry point for the website
  - `wp-content/` - WordPress content directory (themes, plugins, uploads)
  - `wp-includes/` - WordPress core files

## How to Launch the Website

### Prerequisites
- PHP installed on your computer (version 7.4 or higher recommended)
- A web browser

### Launch Instructions

#### Option 1: Using the Batch File (Recommended for Windows)
1. Simply double-click the `launch_website.bat` file
2. A command prompt window will open and start the PHP server
3. Your default web browser will automatically open to http://localhost:8080
4. To stop the server, press Ctrl+C in the command prompt window

#### Option 2: Using PHP Directly
1. Open a command prompt or terminal
2. Navigate to this directory
3. Run: `php launch.php`
4. Open your web browser and go to http://localhost:8080

## Notes
- This is a static copy of the WordPress site. Database functionality will not work.
- Forms, comments, and other dynamic features may not function properly.
- The site is configured to run on port 8080. If this port is already in use, you can modify the port number in the `launch.php` file.

## Troubleshooting
- If you encounter a "PHP not found" error, make sure PHP is installed and added to your system PATH.
- If the server fails to start, check if port 8080 is already in use by another application.
- For Windows users, you may need to run the batch file as administrator if you encounter permission issues.