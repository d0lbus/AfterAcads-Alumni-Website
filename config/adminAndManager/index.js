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

// Protect /adminAndManager routes but exclude login.html
app.use('/adminAndManager', (req, res, next) => {
    if (req.path === '/login.html' || req.path === '/register.html') {
        return next(); 
    }
    authMiddleware(req, res, next);
}, express.static(path.join(__dirname, '../../pages/adminAndManager')));

app.use('/assets', express.static(path.join(__dirname, '../../assets')));
app.use('/style', express.static(path.join(__dirname, '../../style')));

// Routes
const userRoutes = require('./routes/users');
const postRoutes = require('./routes/posts');
const eventRoutes = require('./routes/events');
const opportunityRoutes = require('./routes/opportunities');

app.use('/api/posts', postRoutes);
app.use('/api/users', userRoutes);
app.use('/api/events', eventRoutes);
app.use('/api/opportunities', opportunityRoutes);


// Start server
app.listen(PORT, () => {
    console.log(`Server is running on http://localhost:${PORT}`);
});
