import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_localizations/flutter_localizations.dart';
import 'package:notes/auth/login.dart';
import 'package:notes/auth/register.dart';
import 'package:notes/operations/edit.dart';
import 'package:notes/operations/addNote.dart';
import 'package:notes/operations/notedetailpage.dart';
import 'package:notes/utils/language_manager.dart';
import 'package:notes/utils/theme_manager.dart';
import 'pages/MyHomePage.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:notes/l10n/generated/app_localizations.dart';

late SharedPreferences sharedPref;

void main() async{
  WidgetsFlutterBinding.ensureInitialized();
  sharedPref = await SharedPreferences.getInstance();
  await LanguageManager.initialize();
  await ThemeManager.initialize();

  // Initialize user-specific preferences if user is logged in
  final userId = sharedPref.getString("user_id");
  if (userId != null) {
    LanguageManager.setCurrentUser(userId);
    ThemeManager.setCurrentUser(userId);
  }

  runApp(const MyApp());
}

class MyApp extends StatefulWidget {
  const MyApp({super.key});

  @override
  State<MyApp> createState() => _MyAppState();

  // Static method to change language and theme from anywhere in the app
  static _MyAppState? of(BuildContext context) =>
      context.findAncestorStateOfType<_MyAppState>();
}

class _MyAppState extends State<MyApp> {
  Locale _locale = const Locale('en');
  ThemeMode _themeMode = ThemeMode.system;

  @override
  void initState() {
    super.initState();
    _initializeUserPreferences();
  }

  void _initializeUserPreferences() {
    final userId = sharedPref.getString("user_id");
    if (userId != null) {
      LanguageManager.setCurrentUser(userId);
      ThemeManager.setCurrentUser(userId);
    }

    setState(() {
      _locale = LanguageManager.getCurrentLocale();
      _themeMode = ThemeManager.getThemeMode();
    });
  }

  void setUserAndRefreshPreferences(String userId) {
    LanguageManager.setCurrentUser(userId);
    ThemeManager.setCurrentUser(userId);
    _initializeUserPreferences();
  }

  void clearUserAndResetPreferences() {
    // Reset to default preferences when user logs out
    LanguageManager.setCurrentUser('default');
    ThemeManager.setCurrentUser('default');
    setState(() {
      _locale = const Locale('en');
      _themeMode = ThemeMode.system;
    });
  }

  void changeLanguage(String languageCode) async {
    await LanguageManager.setLanguage(languageCode);
    setState(() {
      _locale = Locale(languageCode);
    });
  }

  void changeTheme(ThemeMode themeMode) async {
    await ThemeManager.setThemeMode(themeMode);
    setState(() {
      _themeMode = themeMode;
    });

    // Update system UI overlay style based on theme
    _updateSystemUIOverlay();
  }

  void _updateSystemUIOverlay() {
    final bool isDark = _themeMode == ThemeMode.dark ||
        (_themeMode == ThemeMode.system &&
            MediaQuery.of(context).platformBrightness == Brightness.dark);

    SystemChrome.setSystemUIOverlayStyle(
      SystemUiOverlayStyle(
        statusBarColor: Colors.transparent,
        statusBarIconBrightness: isDark ? Brightness.light : Brightness.dark,
        systemNavigationBarColor: isDark ? const Color(0xFF121212) : Colors.white,
        systemNavigationBarIconBrightness: isDark ? Brightness.light : Brightness.dark,
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Notes App',
      locale: _locale,

      // Theme configuration
      theme: AppThemes.lightTheme,
      darkTheme: AppThemes.darkTheme,
      themeMode: _themeMode,

      // Localization delegates
      localizationsDelegates: const [
        AppLocalizations.delegate,
        GlobalMaterialLocalizations.delegate,
        GlobalWidgetsLocalizations.delegate,
        GlobalCupertinoLocalizations.delegate,
      ],

      // Supported locales
      supportedLocales: const [
        Locale('en'), // English
        Locale('ar'), // Arabic
      ],

      // RTL support for Arabic
      builder: (context, child) {
        return Directionality(
          textDirection: _locale.languageCode == 'ar'
              ? TextDirection.rtl
              : TextDirection.ltr,
          child: child!,
        );
      },

      debugShowCheckedModeBanner: false,

      routes: {
        "/home" : (context) => MyHomePage(),
        "/login" : (context) => login(),
        "/register" : (context) => register(),
        "/add" : (context) => AddNotePage(),
      },
      initialRoute: sharedPref.getString("user_id")==null ? "/login" : "/home",
    );
  }
}