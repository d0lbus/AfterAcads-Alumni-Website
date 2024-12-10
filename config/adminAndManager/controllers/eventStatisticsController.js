const db = require('../db');

exports.getTotalEvents = (req, res) => {
    const sql = `SELECT COUNT(*) AS total_events FROM events;`;
    db.query(sql, (err, results) => {
        if (err) {
            return res.status(500).json({ error: 'Database error: ' + err.message });
        }
        res.json({ totalEvents: results[0].total_events });
    });
};

exports.getEventsGroupedByStatus = (req, res) => {
    const sql = `
        SELECT status, COUNT(*) AS total
        FROM events
        GROUP BY status;
    `;
    db.query(sql, (err, results) => {
        if (err) {
            return res.status(500).json({ error: 'Database error: ' + err.message });
        }
        res.json(results);
    });
};

exports.getEventsGroupedBySchool = (req, res) => {
    const sql = `
        SELECT s.name AS school_name, COUNT(e.id) AS total_events
        FROM events e
        JOIN schools s ON e.school_id = s.id
        GROUP BY s.name
        ORDER BY total_events DESC;
    `;
    db.query(sql, (err, results) => {
        if (err) {
            return res.status(500).json({ error: 'Database error: ' + err.message });
        }
        res.json(results);
    });
};

exports.getEventsPerMonth = (req, res) => {
    const sql = `
        SELECT DATE_FORMAT(date, '%Y-%m') AS month, COUNT(*) AS total_events
        FROM events
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