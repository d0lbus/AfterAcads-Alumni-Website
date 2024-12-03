const db = require('../db');

exports.getEventStatistics = (req, res) => {
  db.query(
    `SELECT COUNT(*) AS totalEvents FROM events`,
    (error, results) => {
      if (error) {
        return res.status(500).json({ error: error.message });
      }
      res.json(results[0]);
    }
  );
};
