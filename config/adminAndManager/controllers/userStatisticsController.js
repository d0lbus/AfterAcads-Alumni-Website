const db = require('../db'); // Assuming the DB connection is in a file named `db.js`

// Controller to handle user statistics
exports.getTotalApprovedUsers = (req, res) => {
    const sql = `SELECT COUNT(*) AS total FROM users WHERE status = 'approved'`;
    db.query(sql, (err, results) => {
        if (err) {
            return res.status(500).json({ error: 'Database error: ' + err.message });
        }
        res.json({ totalApprovedUsers: results[0].total });
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
        SELECT 
            batch_id, 
            COUNT(*) AS total 
        FROM users 
        WHERE batch_id != 1 
        GROUP BY batch_id`;
    db.query(sql, (err, results) => {
        if (err) {
            return res.status(500).json({ error: 'Database error: ' + err.message });
        }
        res.json(results);
    });
};

exports.getUsersGroupedBySchool = (req, res) => {
    const sql = `
        SELECT 
            school_id, 
            COUNT(*) AS total 
        FROM users 
        WHERE school_id != 1 
        GROUP BY school_id`;
    db.query(sql, (err, results) => {
        if (err) {
            return res.status(500).json({ error: 'Database error: ' + err.message });
        }
        res.json(results);
    });
};

exports.getUsersGroupedByCourse = (req, res) => {
    const sql = `
        SELECT 
            course_id, 
            COUNT(*) AS total 
        FROM users 
        WHERE course_id != 1 
        GROUP BY course_id`;
    db.query(sql, (err, results) => {
        if (err) {
            return res.status(500).json({ error: 'Database error: ' + err.message });
        }
        res.json(results);
    });
};
