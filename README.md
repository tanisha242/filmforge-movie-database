# FilmForge – Movie Database Website

FilmForge is a dynamic, database-driven web platform for movie lovers to explore, rate, and organize their favourite films. Inspired by platforms like Letterboxd, it allows users to browse movies, manage watchlists, write reviews, and share opinions with others — all through a responsive and interactive PHP-based interface.

---

##  Features

- User Authentication – Sign up, log in, manage profile
- Movie Listings – Titles, descriptions, genre, year, poster
- Reviews & Ratings – Users can rate and review each movie
- Watchlist & Favourites – Save movies to personal lists
- Search & Filter – Search by name, filter by genre
- MySQL Database – Data is securely stored and retrieved using XAMPP

---

##  Objectives

- Build a responsive PHP web app using HTML, CSS, JS, PHP, and MySQL
- Implement secure login, registration, and session handling
- Store movie info, users, reviews, and watchlists in a structured database
- Provide users a personalized, engaging experience

---

##  Entities and Relationships

- Users – Stores user profile data
- Movies – Stores title, genre, description, image, etc.
- Reviews – One-to-many relationship with both users and movies
- Watchlist – Many-to-many relationship between users and movies

---

##  Topics & Technologies Used

- HTML, CSS, JavaScript (Frontend)
- PHP (Backend logic)
- MySQL with XAMPP (Database)
- SQL joins, forms, sessions, and server-side validation

---

##  How to Run the Project

1. Clone this repository or download the ZIP
2. Copy the folder to your `htdocs/` directory (XAMPP)
3. Start Apache and MySQL in XAMPP control panel
4. Import the `filmforge.sql` file into phpMyAdmin
5. Navigate to `http://localhost/filmforge` in your browser

---

##  Future Enhancements

-  Dark mode & better responsive UI
-  Analytics for personalized recommendations
-  Movie APIs for auto-fetching content
-  AI-powered recommendation engine
-  Mobile-first design
-  Social media login and sharing

---
