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
