const db = require('../db');

exports.getUserStatistics = (req, res) => {
  db.query(
    `SELECT 
       COUNT(*) AS totalUsers,
       SUM(CASE WHEN employment_status = 'employed' THEN 1 ELSE 0 END) AS employedUsers,
       SUM(CASE WHEN employment_status = 'unemployed' THEN 1 ELSE 0 END) AS unemployedUsers,
       SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pendingUsers
     FROM users`,
    (error, results) => {
      if (error) {
        return res.status(500).json({ error: error.message });
      }
      res.json(results[0]);
    }
  );
};
