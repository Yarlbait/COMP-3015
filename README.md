# COMP 3015 News

**Author:** *Earl Bate*
**Course:** COMP 3015: Web Application With PHP
**Instructor:** *Christian Fenn*

---
## OverView
This project is a simple PHP-based CRUD web application that lets users create, read/view, edit, and delete news articles.
Each article includes a title and a URL, which are stored in a local 'JSON' file for use again at the next visit.

---
## Running the application

Ensure an `articles.json` file is at the server root.

Run:

---
php -S localhost:9000 # or you could use a different port
---

Install Node (dev) dependencies:

---
npm i
---

Run the Node server for reloading CSS changes:

---
npm run dev
---

## Assumptions and Limitations
- Applications is intended to be used locally only
- No user authentication is implemented
- articles.json must remain writable by PHP
- No sorting or organization all articles load on the same page at once
- JSON format must stay valid
