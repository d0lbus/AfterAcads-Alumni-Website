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

exports.getUsersByStatus = (req, res) => {
    const sql = `
        SELECT first_name, last_name, email, status
        FROM users
    `;

    db.query(sql, (err, results) => {
        if (err) {
            console.error('Database error:', err);
            return res.status(500).json({ error: 'Failed to fetch users.' });
        }

        // Group users by their status
        const groupedUsers = {
            pending: results.filter(user => user.status === 'pending'),
            approved: results.filter(user => user.status === 'approved'),
            denied: results.filter(user => user.status === 'denied'),
        };

        res.json(groupedUsers);
    });
}

exports.updateStatus = (req, res) => {
    const { email, status } = req.body;
    const sql = `
        UPDATE users
        SET status = ?
        WHERE email = ?
    `;

    db.query(sql, [status, email], (err, results) => {
        if (err) {
            console.error('Database error:', err);
            return res.status(500).json({ error: 'Failed to update status.' });
        }
        res.json({ message: `User status updated to ${status}.` });
    });
}

exports.getUserDetailsByEmail = (req, res) => {
    const email = req.params.email; 

    if (!email) {
        return res.status(400).json({ error: 'Email is required.' });
    }

    const sql = `
        SELECT first_name, middle_name, last_name, email, gender, alumni_photo_validation
        FROM users
        WHERE email = ?
    `;

    db.query(sql, [email], (err, results) => {
        if (err) {
            console.error('Database error:', err);
            return res.status(500).json({ error: 'Failed to fetch user details.' });
        }

        if (results.length === 0) {
            return res.status(404).json({ error: 'User not found.' });
        }

        const user = results[0];

        const base64Photo = user.alumni_photo_validation
        ? Buffer.from(user.alumni_photo_validation).toString('base64')
        : null;

        res.json({
            firstName: user.first_name,
            middleName: user.middle_name,
            lastName: user.last_name,
            email: user.email,
            gender: user.gender,
            alumniPhotoValidation: base64Photo,
        });
    });
};




