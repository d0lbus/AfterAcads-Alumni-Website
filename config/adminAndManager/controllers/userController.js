const db = require('../db');

exports.login = (req, res) => {
    const { email, password } = req.body;

    // Validate input
    if (!email || !password) {
        return res.status(400).json({ error: 'Email and password are required.' });
    }

    // Query to validate the user credentials
    const sql = `
        SELECT * FROM users 
        WHERE email = ? AND password_hash = ? AND userType IN ('admin', 'manager')
    `;
    db.query(sql, [email, password], (err, results) => {
        if (err) {
            return res.status(500).json({ error: 'Database error: ' + err.message });
        }

        if (results.length > 0) {
            const user = results[0];

            // Store session data
            req.session.email = user.email;
            req.session.userType = user.userType;

            // Redirect to home.html (correct path)
            return res.redirect('/adminAndManager/home.html');
        } else {
            return res.status(401).json({ error: 'Invalid email or password.' });
        }
    });
};

exports.getUserDetails = (req, res) => {
    if (!req.session || !req.session.email) {
        return res.status(401).json({ error: 'User not logged in' });
    }

    res.json({
        email: req.session.email,
        userType: req.session.userType, 
    });
};


