const db = require('../db'); // Assuming the DB connection is in a file named `db.js`

// Controller to handle user statistics
exports.getUserStatistics = (req, res) => {
    const sql = `
        SELECT 
            (SELECT COUNT(*) FROM users) AS totalUsers,
            status,
            COUNT(*) AS total
        FROM users
        GROUP BY status
    `;

    db.query(sql, (err, results) => {
        if (err) {
            return res.status(500).json({ error: 'Database error: ' + err.message });
        }

        // Process results
        const totalUsers = results[0]?.totalUsers || 0; // Total number of users
        const statusCounts = results.map(row => ({
            status: row.status,
            total: row.total,
        }));

        res.json({
            totalUsers,
            statusCounts,
        });
    });
};

exports.getEmploymentStatusCounts = (req, res) => {
    const sql = `
        SELECT 
            employment_status, 
            COUNT(*) AS total 
        FROM users 
        GROUP BY employment_status`;
    db.query(sql, (err, results) => {
        if (err) {
            return res.status(500).json({ error: 'Database error: ' + err.message });
        }
        res.json(results);
    });
};

exports.getGenderCounts = (req, res) => {
    const sql = `
        SELECT 
            gender, 
            COUNT(*) AS total 
        FROM users 
        GROUP BY gender`;
    db.query(sql, (err, results) => {
        if (err) {
            return res.status(500).json({ error: 'Database error: ' + err.message });
        }
        res.json(results);
    });
};

exports.getUsersGroupedByBatch = (req, res) => {
    const sql = `
        SELECT b.batch_number, COUNT(u.id) AS total
        FROM users u
        JOIN batches b ON u.batch_id = b.id
        WHERE u.batch_id != 1
        GROUP BY b.batch_number
    `;
    db.query(sql, (err, results) => {
        if (err) {
            return res.status(500).json({ error: 'Database error: ' + err.message });
        }
        res.json(results);
    });
};


exports.getUsersGroupedBySchool = (req, res) => {
    const sql = `
        SELECT s.name AS school_name, COUNT(u.id) AS total
        FROM users u
        JOIN schools s ON u.school_id = s.id
        WHERE u.school_id != 1
        GROUP BY s.name
    `;
    db.query(sql, (err, results) => {
        if (err) {
            return res.status(500).json({ error: 'Database error: ' + err.message });
        }
        res.json(results);
    });
};


exports.getUsersGroupedByCourse = (req, res) => {
    const sql = `
        SELECT c.name AS course_name, COUNT(u.id) AS total
        FROM users u
        JOIN courses c ON u.course_id = c.id
        WHERE u.course_id != 1
        GROUP BY c.name
    `;
    db.query(sql, (err, results) => {
        if (err) {
            return res.status(500).json({ error: 'Database error: ' + err.message });
        }
        res.json(results);
    });
};

