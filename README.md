# Short URL Service

A URL shortening service that transforms long and complex links into short, readable, and easy-to-share URLs.

## 🎯 Goals
- Make link sharing easier (email, SMS, social media)
- Reduce copy/paste errors
- Use a trusted domain
- Generate traffic and improve brand credibility

## ⚙️ How It Works
1. Validate the original URL
2. Generate a unique slug
3. Store the URL and slug in the database
4. Redirect users to the original URL when the short link is accessed

## 🧱 Tech Stack
- PHP
- MySQL / MariaDB
- Apache or Nginx
- SSL (HTTPS)
- Cron (optional, for cleanup tasks)

## 📁 Project Structure
