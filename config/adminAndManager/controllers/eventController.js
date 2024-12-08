const db = require('../db');
const path = require('path');

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

// Get event details by ID
exports.getEventDetailsById = (req, res) => {
  const eventId = req.params.id;

  const sql = `
      SELECT e.id AS event_id, e.title, e.description, e.date, e.time, e.location,
            e.host, e.image_path, e.alt_text, e.school_id, s.name AS school
      FROM events e
      LEFT JOIN schools s ON e.school_id = s.id
      WHERE e.id = ?
  `;

  db.query(sql, [eventId], (err, results) => {
      if (err) {
          console.error("Database error:", err);
          return res.status(500).json({ error: "Failed to fetch event details." });
      }

      if (results.length === 0) {
          return res.status(404).json({ error: "Event not found." });
      }

      const event = results[0];
      res.json(event);
  });
};

// Update Event Functionality
exports.updateEvent = (req, res) => {
  try {
      const { eventId, title, description, date, time, location, host, school } = req.body;
      const imagePath = req.file ? req.file.filename : null;

      // Update query
      const query = `
          UPDATE events 
          SET title = ?, description = ?, date = ?, time = ?, location = ?, host = ?, school_id = ?, image_path = ? 
          WHERE id = ?
      `;

      const values = [title, description, date, time, location, host, school, imagePath, eventId];

      // Execute query
      db.query(query, values);

      res.json({ message: 'Event updated successfully' });
  } catch (error) {
      console.error('Error updating event:', error);
      res.status(500).json({ error: 'Failed to update event' });
  }
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
