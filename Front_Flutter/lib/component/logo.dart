import 'package:flutter/material.dart';

class Logo extends StatelessWidget{
  const Logo({super.key});

  @override
  Widget build(BuildContext context){
    return Center(
      child: Container(
        padding: EdgeInsets.all(20),
        height: 120,
        width: 120,
        decoration: BoxDecoration(
          borderRadius: BorderRadius.circular(20),
          gradient: LinearGradient(
            colors: [
              Color(0xFF00BCD4),
              Color(0xFF26A69A),
            ],
            begin: Alignment.topLeft,
            end: Alignment.bottomRight,
          ),
          boxShadow: [
            BoxShadow(
              color: Color(0xFF00BCD4).withOpacity(0.3),
              blurRadius: 15,
              offset: Offset(0, 5),
            ),
          ],
        ),
        child: Icon(
          Icons.note_alt_rounded,
          size: 60,
          color: Colors.white,
        ),
      ),
    );
  }
}