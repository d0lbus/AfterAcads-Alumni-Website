const db = require('../db');

exports.getAllOpportunities = (req, res) => {
    const { schoolId, courseId } = req.query;
    let sql = `
        SELECT 
            o.id, 
            o.title, 
            o.company_name, 
            o.location, 
            DATE_FORMAT(o.posted_date, '%Y-%m-%d') AS posted_date, 
            s.name AS school, 
            c.name AS course
        FROM opportunities o
        LEFT JOIN schools s ON o.school_id = s.id
        LEFT JOIN courses c ON o.course_id = c.id
        WHERE 1=1
    `;
    const params = [];

    if (schoolId) {
        sql += ' AND o.school_id = ?';
        params.push(schoolId);
    }
    if (courseId) {
        sql += ' AND o.course_id = ?';
        params.push(courseId);
    }

    db.query(sql, params, (err, results) => {
        if (err) {
            console.error('Database error:', err);
            return res.status(500).json({ error: 'Failed to fetch opportunities.' });
        }
        res.json(results);
    });
};

  
exports.getOpportunityById = (req, res) => {
    const { id } = req.params;
    db.query('SELECT * FROM opportunities WHERE id = ?', [id], (err, results) => {
      if (err) return res.status(500).json({ error: 'Failed to fetch opportunity.' });
      res.json(results[0]);
    });
};
  
exports.editOpportunity = (req, res) => {
    const { id, title, company, location, description } = req.body;
    db.query('UPDATE opportunities SET title = ?, company = ?, location = ?, description = ? WHERE id = ?', 
    [title, company, location, description, id], (err) => {
      if (err) return res.status(500).json({ error: 'Failed to edit opportunity.' });
      res.json({ message: 'Opportunity updated successfully.' });
    });
};

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

exports.getCoursesBySchool = (req, res) => {
    const { schoolId } = req.query;
  
    if (!schoolId) {
      return res.status(400).json({ error: "School ID is required" });
    }
  
    const sql = `SELECT id, name FROM courses WHERE school_id = ?`;
  
    db.query(sql, [schoolId], (err, results) => {
      if (err) {
        console.error("Database error:", err);
        return res.status(500).json({ error: "Failed to fetch courses." });
      }
  
      res.json(results);
    });
};
  