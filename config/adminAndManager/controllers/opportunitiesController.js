const db = require("../db");

exports.getAllOpportunities = (req, res) => {
  const { schoolId, courseId } = req.query;

  // Base SQL query
  let sql = `
        SELECT 
            o.id, 
            o.title, 
            o.company_name, 
            o.location, 
            DATE_FORMAT(o.posted_date, '%Y-%m-%d') AS posted_date, 
            s.name AS school, 
            c.name AS course
        FROM 
            opportunities o
        LEFT JOIN 
            schools s ON o.school_id = s.id
        LEFT JOIN 
            courses c ON o.course_id = c.id
        WHERE 
            1=1
    `;

  const params = [];

  if (schoolId) {
    sql += " AND o.school_id = ?";
    params.push(schoolId);
  }

  if (courseId) {
    sql += " AND o.course_id = ?";
    params.push(courseId);
  }

  // Execute the query
  db.query(sql, params, (err, results) => {
    if (err) {
      console.error("Database error:", err);
      return res.status(500).json({ error: "Failed to fetch opportunities." });
    }
    res.json(results);
  });
};

exports.getOpportunityById = (req, res) => {
  const { id } = req.params;
  db.query("SELECT * FROM opportunities WHERE id = ?", [id], (err, results) => {
    if (err)
      return res.status(500).json({ error: "Failed to fetch opportunity." });
    res.json(results[0]);
  });
};

exports.editOpportunity = (req, res) => {
  const {
    id,
    title,
    company_name,
    location,
    school_id,
    course_id,
    description,
    company_link,
  } = req.body;

  const sql = `
        UPDATE opportunities
        SET title = ?, company_name = ?, location = ?, school_id = ?, course_id = ?, description = ?, company_link = ?
        WHERE id = ?
    `;
  const params = [
    title,
    company_name,
    location,
    school_id,
    course_id,
    description,
    company_link,
    id,
  ];

  db.query(sql, params, (err, results) => {
    if (err) {
      console.error("Database error:", err);
      return res.status(500).json({ error: "Failed to edit opportunity." });
    }
    res.json({ message: "Opportunity updated successfully." });
  });
};

exports.getSchools = (req, res) => {
  const sql = `SELECT id, name FROM schools`;

  db.query(sql, (err, results) => {
    if (err) {
      console.error("Database error:", err);
      return res.status(500).json({ error: "Failed to fetch schools." });
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

exports.deleteOpportunity = (req, res) => {
  const { id } = req.params;
  if (!id) {
    return res.status(400).json({ error: "Opportunity ID is required." });
  }

  const sql = "DELETE FROM opportunities WHERE id = ?";

  db.query(sql, [id], (err, result) => {
    if (err) {
      console.error("Database error:", err);
      return res.status(500).json({ error: "Failed to delete opportunity." });
    }

    if (result.affectedRows === 0) {
      return res.status(404).json({ error: "Opportunity not found." });
    }

    res.json({ message: "Opportunity deleted successfully." });
  });
};
// <Citation: ChatGPT helped with the debugging of the code - (CHATGTP)
exports.addOpportunity = (req, res) => {
  const {
    title,
    company_name,
    location,
    school_id,
    course_id,
    description,
    company_link,
  } = req.body;

  if (
    !title ||
    !company_name ||
    !location ||
    !school_id ||
    !course_id ||
    !description ||
    !company_link
  ) {
    return res.status(400).json({ error: "All fields are required." });
  }

  const sql = `
      INSERT INTO opportunities (title, company_name, location, school_id, course_id, description, company_link)
      VALUES (?, ?, ?, ?, ?, ?, ?)
    `;
  const values = [
    title,
    company_name,
    location,
    school_id,
    course_id,
    description,
    company_link,
  ];

  db.query(sql, values, (err, result) => {
    if (err) {
      console.error("Database error:", err);
      return res.status(500).json({ error: "Failed to add opportunity." });
    }

    res.json({ message: "Opportunity added successfully." });
  });
};
