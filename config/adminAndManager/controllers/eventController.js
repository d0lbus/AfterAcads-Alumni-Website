const db = require('../db');




// Retrieve events grouped by status
exports.getEventsByStatus = (req, res) => {
  const sql = `
      SELECT e.id AS event_id, e.title, e.description, e.date, e.time, e.location, e.host, 
             s.name AS school, e.status
      FROM events e
      JOIN schools s ON e.school_id = s.id
  `;

  db.query(sql, (err, results) => {
      if (err) {
          console.error('Database error:', err);
          return res.status(500).json({ error: 'Failed to fetch events.' });
      }

      const groupedEvents = {
          upcoming: results.filter(event => event.status === 'upcoming'),
          finished: results.filter(event => event.status === 'finished'),
          archived: results.filter(event => event.status === 'archived'),
      };

      res.json(groupedEvents);
  });
};

// Update event status
exports.updateEventStatus = (req, res) => {
  const { event_id, status } = req.body;

  if (!event_id || !status) {
      return res.status(400).json({ error: 'Event ID and status are required.' });
  }

  const sql = `
      UPDATE events
      SET status = ?
      WHERE id = ?
  `;

  db.query(sql, [status, event_id], (err, results) => {
      if (err) {
          console.error('Database error:', err);
          return res.status(500).json({ error: 'Failed to update event status.' });
      }

      res.json({ message: `Event status updated to ${status}.` });
  });
};

// Retrieve schools for filtering
exports.getSchools = (req, res) => {
  const sql = `SELECT id, name FROM schools`;

  db.query(sql, (err, results) => {
      if (err) {
          console.error('Database error:', err);
          return res.status(500).json({ error: 'Failed to fetch schools.' });
      }

      res.json(results);
  });
};


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
