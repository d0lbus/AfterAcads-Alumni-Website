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


exports.getUsersGroupedBySchoolAndCourse = (req, res) => {
    const sql = `
        SELECT 
            s.name AS school_name, 
            c.name AS course_name, 
            COUNT(u.id) AS total
        FROM schools s
        LEFT JOIN users u ON u.school_id = s.id
        LEFT JOIN courses c ON u.course_id = c.id
        WHERE s.id != 1 AND c.id != 1
        GROUP BY s.name, c.name
        ORDER BY s.name, c.name;
    `;

    db.query(sql, (err, results) => {
        if (err) {
            return res.status(500).json({ error: 'Database error: ' + err.message });
        }

        // Group results by school name
        const groupedData = results.reduce((acc, row) => {
            const school = row.school_name;
            if (!acc[school]) {
                acc[school] = [];
            }
            acc[school].push({
                course_name: row.course_name,
                total: row.total,
            });
            return acc;
        }, {});

        res.json(groupedData);
    });
};


