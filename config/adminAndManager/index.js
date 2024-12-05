const express = require('express');
const session = require('express-session');
const bodyParser = require('body-parser');
const path = require('path');
const authMiddleware = require('./middlewares/authMiddleware');

const app = express();
const PORT = 3000;

// Middleware
app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());
app.use(
    session({
        secret: 'your_secret_key', // Replace with a strong secret key
        resave: false,
        saveUninitialized: true,
        cookie: { secure: false }, // Use true if HTTPS is enabled
    })
);

// Serve static files
app.use(express.static(path.join(__dirname, '../../pages')));
app.use('/style', express.static(path.join(__dirname, '../../style')));

// Protect routes with the authMiddleware
app.get('/adminAndManager/home.html', authMiddleware, (req, res) => {
  res.sendFile(path.join(__dirname, '../../pages/adminAndManager/home.html'));
});

app.get('/adminAndManager/Registrants.html', authMiddleware, (req, res) => {
    res.sendFile(path.join(__dirname, '../../pages/adminAndManager/Registrants.html'));
  });

// Routes
const userRoutes = require('./routes/users');
app.use('/api/users', userRoutes);

// Start server
app.listen(PORT, () => {
    console.log(`Server is running on http://localhost:${PORT}`);
   });
