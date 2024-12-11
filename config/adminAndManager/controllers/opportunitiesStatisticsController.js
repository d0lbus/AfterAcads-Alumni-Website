const db = require('../db');

exports.getTotalOpportunities = (req, res) => {
    const sql = `SELECT COUNT(*) AS total_opportunities FROM opportunities;`;
    db.query(sql, (err, results) => {
        if (err) {
            return res.status(500).json({ error: 'Database error: ' + err.message });
        }
        res.json({ totalOpportunities: results[0].total_opportunities });
    });
};

exports.getOpportunitiesGroupedBySchoolAndCourse = (req, res) => {
    const sql = `
        SELECT 
            s.name AS school_name, 
            c.name AS course_name, 
            COUNT(o.id) AS total_opportunities
        FROM opportunities o
        JOIN schools s ON o.school_id = s.id
        JOIN courses c ON o.course_id = c.id
        GROUP BY s.name, c.name
        ORDER BY s.name, c.name;
    `;
    db.query(sql, (err, results) => {
        if (err) {
            return res.status(500).json({ error: 'Database error: ' + err.message });
        }
        const groupedData = {};

        // Group results by school and course
        results.forEach(row => {
            if (!groupedData[row.school_name]) {
                groupedData[row.school_name] = [];
            }
            groupedData[row.school_name].push({
                course_name: row.course_name,
                total_opportunities: row.total_opportunities
            });
        });

        res.json(groupedData);
    });
};

exports.getOpportunitiesPerMonth = (req, res) => {
    const sql = `
        SELECT DATE_FORMAT(posted_date, '%Y-%m') AS month, COUNT(*) AS total_opportunities
        FROM opportunities
        GROUP BY month
        ORDER BY month ASC;
    `;
    db.query(sql, (err, results) => {
        if (err) {
            return res.status(500).json({ error: 'Database error: ' + err.message });
        }
        res.json(results);
    });
};

exports.getOpportunitiesGroupedByCompany = (req, res) => {
    const sql = `
        SELECT company_name, COUNT(*) AS total_opportunities
        FROM opportunities
        GROUP BY company_name
        ORDER BY total_opportunities DESC;
    `;
    db.query(sql, (err, results) => {
        if (err) {
            return res.status(500).json({ error: 'Database error: ' + err.message });
        }
        res.json(results);
    });
};
