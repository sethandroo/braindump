# braindump
A simple and minimal brain dump written in PHP.

![image](https://raw.githubusercontent.com/sethandroo/braindump/refs/heads/main/screenshot.png)

## Features
- Integrated post editor
- Grid layout utilizing Masonry
- Lightbox image viewer
- Self-updating hashtag sidebar
- Secure login
- Fast and efficient
- Easily modified

## Requirements
- A PHP capable server
- A brain with things to dump
- That's all!

## Setting Up
- The first thing you'll want to do is set a username and password. This can be done by editting the **users.json** file. The username can be set in plain text (this is CASE SENSITIVE). The password needs to be encrypted using **BCRYPT** (this can be done using any online BCRYPT generator).
- Once saved, you can upload all files to your server!
- To log in navigate to **https://yourserver.com/login.php** and enter your username and password (the actual password, not the encrypted value).
- You're done! You can delete the pre-included posts and start using it right away.

### Note
Suggestions and ideas are welcome! I created this because I needed a place online that kind of functioned like Tumblr but only included the things I wanted to remember and/or share.
