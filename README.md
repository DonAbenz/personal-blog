# Personal Blog

A simple blog built with PHP and Twig, using JSON for data storage. This project provides a lightweight and easy-to-extend structure for building your own blog, inspired by the [Personal Blog Roadmap](https://roadmap.sh/projects/personal-blog). It includes basic features like user authentication, and post management.

## Features

-  **User Authentication**: Secure login and registration functionality.
-  **Post Management**: Create, edit, and delete blog posts.
-  **Category Support**: Organize blog posts by categories.
-  **Simple Design**: Clean, minimalist design based on Twig templates.
-  **Responsive Layout**: Works well on mobile and desktop devices.

## Tech Stack

-  **Backend**: PHP
-  **Frontend**: Twig (for templating)
-  **Data Storage**: JSON (for storing blog posts and user information)
-  **Other Dependencies**: Composer for dependency management

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/DonAbenz/personal-blog.git
   cd personal-blog
   ```
2. Install dependencies using Composer:
   ```bash
   composer install
   ```
3. Run the server:
   ```bash
   php -S localhost:8000 -t public/
   ```
   Access the blog at http://localhost:8000.

## Usage

-  Navigate to the login page to register or log in.

-  After logging in, you can create new blog posts, edit them, or delete them.

-  Blog posts and user data are stored in `public/assets/data.json`.

-  Browse through the categories or view all posts on the homepage.