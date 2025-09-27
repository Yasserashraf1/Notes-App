# Notes App

A modern, feature-rich notes application built with **Flutter** and **PHP**, offering seamless note-taking experience with image attachments, user profiles, and multi-language support.

## 🌟 Features

### Core Functionality
- **📝 Create & Edit Notes** - Rich text notes with titles and content
- **📷 Image Attachments** - Add images from camera or gallery to notes
- **👤 User Profiles** - Personal profiles with custom profile pictures
- **🔐 Secure Authentication** - User registration and login system
- **🔄 Real-time Sync** - Notes sync across sessions

### Advanced Features
- **🌍 Multi-language Support** - English and Arabic with RTL support
- **🌙 Dark/Light Mode** - User-customizable themes with beautiful dark mode
- **⭐ Favorites System** - Mark and organize favorite notes
- **🎨 Modern UI/UX** - Beautiful teal theme with professional design
- **📱 Responsive Design** - Optimized for various screen sizes
- **🔒 User Isolation** - Each user's data is completely separate

## 🏗️ Architecture

### Frontend (Flutter)
```
lib/
├── auth/                   # Authentication screens
│   ├── login.dart
│   └── register.dart
├── component/              # Reusable components
│   ├── app_drawer.dart
│   ├── button.dart
│   ├── cardnote.dart
│   ├── crud.dart
│   ├── logo.dart
│   └── textformfield.dart
├── constants/              # App constants
│   └── linkapi.dart
├── l10n/                   # Localization files
│   ├── app_en.arb
│   ├── app_ar.arb
│   └── generated/
├── operations/             # Note operations
│   ├── addNote.dart
│   ├── edit.dart
│   └── notedetailpage.dart
├── pages/                  # App pages
│   ├── about_page.dart
│   ├── favorites_page.dart
│   ├── profile_page.dart
│   └── settings_page.dart
├── utils/                  # Utility classes
│   ├── language_manager.dart
│   └── theme_manager.dart
└── main.dart              # App entry point
```

### Backend (PHP)
```
newphp/
├── auth/                   # Authentication endpoints
│   ├── login.php
│   └── signup.php
├── notes/                  # Note management
│   ├── add.php
│   ├── delete.php
│   ├── edit.php
│   ├── removeimage.php
│   ├── uploadimage.php
│   └── view.php
├── profile/                # Profile management
│   ├── getprofile.php
│   ├── updateprofile.php
│   └── updateprofileimage.php
├── upload/                 # File storage
│   ├── notes/             # Note images
│   └── profiles/          # Profile images
├── connect.php            # Database connection
└── functions.php          # Utility functions
```

## 🚀 Getting Started

### Prerequisites
- **Flutter SDK** (3.1.5 or higher)
- **PHP** (7.4 or higher)
- **MySQL** (5.7 or higher)
- **Web Server** (Apache/Nginx)

### Frontend Setup

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd notes-app
   ```

2. **Install dependencies**
   ```bash
   flutter pub get
   ```

3. **Generate localization files**
   ```bash
   flutter gen-l10n
   ```

4. **Configure API endpoints**
   ```dart
   // lib/constants/linkapi.dart
   const String linkServerName = "http://YOUR_SERVER_IP:PORT/newphp";
   ```

5. **Run the application**
   ```bash
   flutter run
   ```

### Backend Setup

1. **Database Setup**
   ```sql
   CREATE DATABASE noteapp;
   USE noteapp;

   -- Users table
   CREATE TABLE users (
       user_id INT AUTO_INCREMENT PRIMARY KEY,
       user_name VARCHAR(100) NOT NULL,
       user_email VARCHAR(100) UNIQUE NOT NULL,
       user_pass VARCHAR(255) NOT NULL,
       profile_image VARCHAR(255) DEFAULT '',
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   );

   -- Notes table
   CREATE TABLE notes (
       note_id INT AUTO_INCREMENT PRIMARY KEY,
       note_title VARCHAR(255) NOT NULL,
       note_content TEXT NOT NULL,
       note_image VARCHAR(255) DEFAULT '',
       user_id INT NOT NULL,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
       FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
   );
   ```

2. **Configure database connection**
   ```php
   // newphp/connect.php
   private $host = "127.0.0.1";
   private $port = "3306";        // Your MySQL port
   private $db_name = "noteapp";  // Database name
   private $username = "root";    // MySQL username
   private $password = "";        // MySQL password
   ```

3. **Set up file permissions**
   ```bash
   mkdir -p newphp/upload/notes
   mkdir -p newphp/upload/profiles
   chmod 755 newphp/upload
   chmod 755 newphp/upload/notes
   chmod 755 newphp/upload/profiles
   ```

## 🎨 Themes

### Light Theme
- **Primary Color**: Teal (#00BCD4)
- **Secondary Color**: Dark Teal (#26A69A)
- **Background**: Clean white with subtle tints
- **Typography**: Modern, readable fonts with proper hierarchy

### Dark Theme
- **Primary Color**: Light Teal (#4DD0E1)
- **Background**: Rich dark (#121212)
- **Cards**: Dark gray (#2C2C2C)
- **Typography**: Light colors optimized for dark backgrounds

## 🌍 Internationalization

### Supported Languages
- **English** (Default)
- **Arabic** (with full RTL support)

### Adding New Languages
1. Create new `.arb` file in `lib/l10n/`
2. Add translations following existing structure
3. Update `supportedLocales` in `main.dart`
4. Add language option in `LanguageManager.getSupportedLanguages()`

## 📊 Dependencies

### Flutter Dependencies
```yaml
dependencies:
  flutter:
    sdk: flutter
  flutter_localizations:
    sdk: flutter
  intl: ^0.18.1
  shared_preferences: ^2.2.2
  http: ^1.1.0
  image_picker: ^1.0.4
  path: ^1.8.3
```

### PHP Dependencies
- **PDO** for database operations
- **GD Extension** for image processing
- **JSON Extension** for API responses

## 🔒 Security Features

- **Input Sanitization** - All user inputs are filtered and validated
- **SQL Injection Protection** - Prepared statements used throughout
- **File Upload Security** - Restricted file types and size limits
- **User Isolation** - Complete data separation between users
- **Secure Authentication** - Password hashing and session management

## 📝 API Documentation

### Authentication Endpoints
- `POST /auth/login.php` - User login
- `POST /auth/signup.php` - User registration

### Note Endpoints
- `GET /notes/view.php` - Get user notes
- `POST /notes/add.php` - Create new note
- `POST /notes/edit.php` - Update existing note
- `POST /notes/delete.php` - Delete note
- `POST /notes/uploadimage.php` - Upload note image
- `POST /notes/removeimage.php` - Remove note image

### Profile Endpoints
- `GET /profile/getprofile.php` - Get user profile
- `POST /profile/updateprofile.php` - Update profile info
- `POST /profile/updateprofileimage.php` - Update profile image

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📞 Support

For support, email [yasserashraf3142@gmail.com] or create an issue in this repository.

---

**Built with ❤️ using Flutter & PHP**
