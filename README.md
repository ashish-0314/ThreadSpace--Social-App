<div align="center">

# 🧵 ThreadSpace

**A modern Reddit-style social platform built for developers.**

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![MongoDB](https://img.shields.io/badge/MongoDB-Atlas-47A248?style=for-the-badge&logo=mongodb&logoColor=white)](https://mongodb.com)
[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![TailwindCSS](https://img.shields.io/badge/Tailwind-CSS-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)

[🌐 Live Demo](https://web-production-0f335.up.railway.app) · [🐛 Report Bug](https://github.com/ashish-0314/ThreadSpace--Social-App/issues) · [✨ Request Feature](https://github.com/ashish-0314/ThreadSpace--Social-App/issues)



</div>

---

## 📖 About The Project

ThreadSpace is a full-featured social discussion platform inspired by Reddit. It allows users to create communities, post content, vote, comment, follow other users, send direct messages, and much more — all wrapped in a premium dark-mode SaaS aesthetic.

### ✨ Key Features

- 🔐 **Authentication** — Email/password + Google OAuth login
- 🏘️ **Communities** — Create and join topic-based communities (`c/community`)
- 📝 **Posts** — Text, Media (image/video/audio), and Link posts with flair tagging
- 🔁 **Reposts** — Share posts across communities with custom comments
- 💬 **Comments** — Nested comment threads with best-answer marking
- ⬆️ **Voting** — Upvote/downvote system with real-time score updates
- 👥 **Networking** — Follow users and send connection requests
- 📬 **Direct Messages** — Real-time messaging between connected users
- 🔔 **Notifications** — Activity alerts (follows, comments, reposts)
- 🔍 **Global Search** — Search posts and communities
- 🤖 **AI Thread Summary** — AI-powered post summarization
- 🛡️ **Admin Panel** — Manage users and posts with a dedicated admin dashboard
- 🌐 **Google OAuth** — One-click Google sign-in

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| **Backend** | Laravel 12 (PHP 8.2) |
| **Database** | MongoDB Atlas (via `mongodb/laravel-mongodb`) |
| **Frontend** | Blade Templates, Alpine.js, Vite |
| **Styling** | Tailwind CSS + Custom CSS (Dark SaaS Theme) |
| **Media Storage** | ImageKit CDN |
| **Auth** | Laravel Breeze + Socialite (Google OAuth) |
| **Deployment** | Railway.app |

---

## 🚀 Getting Started

Follow these steps to run ThreadSpace locally on your machine.

### Prerequisites

Make sure you have the following installed:

- **PHP** >= 8.2
- **Composer** >= 2.x
- **Node.js** >= 18.x and **npm**
- **Git**

You will also need free accounts on:
- [MongoDB Atlas](https://www.mongodb.com/cloud/atlas) — for the database
- [ImageKit](https://imagekit.io) — for media/avatar storage
- [Google Cloud Console](https://console.cloud.google.com) — for Google OAuth

---

### Installation

**1. Clone the repository**
```bash
git clone https://github.com/ashish-0314/ThreadSpace--Social-App.git
cd ThreadSpace--Social-App
```

**2. Install PHP dependencies**
```bash
composer install
```

**3. Install Node dependencies**
```bash
npm install
```

**4. Set up your environment file**
```bash
cp .env.example .env
php artisan key:generate
```

**5. Configure your `.env` file**

Open `.env` and fill in the following values:

```env
APP_NAME=ThreadSpace
APP_URL=http://127.0.0.1:8000

# MongoDB Atlas Connection
DB_CONNECTION=mongodb
DB_URI=your-mongodb-atlas-connection-string
DB_DATABASE=threadSpace

# ImageKit (for avatar and post media uploads)
IMAGEKIT_PUBLIC_KEY=your-imagekit-public-key
IMAGEKIT_PRIVATE_KEY=your-imagekit-private-key
IMAGEKIT_URL_ENDPOINT=https://ik.imagekit.io/your-id

# Google OAuth
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/auth/google/callback

# Admin Account (the email that gets admin privileges)
ADMIN_EMAIL=your-admin-email@gmail.com
```

**6. Run the frontend build**
```bash
npm run dev
```

**7. Start the development server**
```bash
php artisan serve
```

🎉 Open your browser and go to **http://127.0.0.1:8000**

---

## ⚙️ Environment Variables Reference

| Variable | Description |
|---|---|
| `APP_URL` | Your app's base URL (e.g. `http://127.0.0.1:8000`) |
| `DB_URI` | Full MongoDB Atlas connection string |
| `DB_DATABASE` | MongoDB database name |
| `IMAGEKIT_PUBLIC_KEY` | ImageKit public key for media uploads |
| `IMAGEKIT_PRIVATE_KEY` | ImageKit private key |
| `IMAGEKIT_URL_ENDPOINT` | Your ImageKit URL endpoint |
| `GOOGLE_CLIENT_ID` | Google OAuth App Client ID |
| `GOOGLE_CLIENT_SECRET` | Google OAuth App Client Secret |
| `GOOGLE_REDIRECT_URI` | Must match your Google Cloud Console redirect URI |
| `ADMIN_EMAIL` | The email address that receives admin privileges |

---

## 🔑 Setting Up Google OAuth

1. Go to [Google Cloud Console](https://console.cloud.google.com)
2. Create a new project (or select an existing one)
3. Navigate to **APIs & Services → Credentials**
4. Click **Create Credentials → OAuth 2.0 Client IDs**
5. Set the **Authorized Redirect URI** to:
   ```
   http://127.0.0.1:8000/auth/google/callback
   ```
6. Copy the **Client ID** and **Client Secret** into your `.env` file

---

## 🗄️ Setting Up MongoDB Atlas

1. Create a free account at [mongodb.com/cloud/atlas](https://www.mongodb.com/cloud/atlas)
2. Create a new **free cluster**
3. Create a **database user** with a username and password
4. Whitelist your IP address (or `0.0.0.0/0` for all IPs)
5. Click **Connect → Drivers** and copy the connection string
6. Replace `<password>` in the string with your DB user's password
7. Paste the full URI into `DB_URI` in your `.env` file

---

## 🖼️ Setting Up ImageKit

1. Create a free account at [imagekit.io](https://imagekit.io)
2. From your dashboard, copy your **Public Key**, **Private Key**, and **URL Endpoint**
3. Paste them into your `.env` file

---

## 🛡️ Admin Access

The admin account is determined purely by **email address**. Set `ADMIN_EMAIL` in your `.env` to the email you use to log in, and that account will automatically get admin privileges including:

- A dedicated Admin Panel (`/admin`)
- User management (view & delete users)
- Post moderation (view & delete posts)
- Hidden social features (no follow, no post creation) to keep the interface clean

---

## 📁 Project Structure

```
ThreadSpace/
├── app/
│   ├── Http/Controllers/     # All controllers (Post, Community, Message, Admin...)
│   ├── Models/               # Eloquent MongoDB models
│   └── Http/Middleware/      # Auth, Admin middleware
├── resources/
│   ├── views/                # Blade templates
│   │   ├── admin/            # Admin panel views
│   │   ├── auth/             # Login, Register, Reset Password
│   │   ├── communities/      # Community pages
│   │   ├── messages/         # Messaging UI
│   │   ├── posts/            # Post create, show, edit
│   │   ├── profile/          # User profile & settings
│   │   └── layouts/          # Navigation, App layout
│   └── css/                  # Global styles
├── routes/
│   ├── web.php               # All web routes
│   └── auth.php              # Auth routes
└── public/
    ├── avatars/              # Default avatar image options
    └── images/               # Static images
```

---

## 📸 Screenshots

| Home Feed | Post Detail | Admin Panel |
|---|---|---|
| Dark-mode feed with sorting | Full post with comments & voting | User & post management |

---

## 🤝 Contributing

Contributions are welcome! Feel free to open an issue or submit a pull request.

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 📄 License

This project is open source and available under the [MIT License](LICENSE).

---

<div align="center">

Built with ❤️ by **Ashish Raj**

⭐ If you find this project useful, please give it a star!

</div>
