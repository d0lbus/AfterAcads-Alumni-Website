const db = require('../db');
const bcrypt = require('bcrypt');


exports.login = (req, res) => {
    const { email, password } = req.body;

    // Validate input
    if (!email || !password) {
        return res.status(400).json({ error: 'Email and password are required.' });
    }

    // Query to fetch the user by email
    const sql = `
        SELECT * FROM users 
        WHERE email = ? AND userType IN ('admin', 'manager')
    `;
    db.query(sql, [email], (err, results) => {
        if (err) {
            return res.status(500).json({ error: 'Database error: ' + err.message });
        }

        if (results.length === 0) {
            return res.status(401).json({ error: 'Invalid email or password.' });
        }

        const user = results[0];

        const isPasswordValid = bcrypt.compareSync(password, user.password_hash);

        if (!isPasswordValid) {
            return res.status(401).json({ error: 'Invalid email or password.' });
        }

        // Store session data
        req.session.email = user.email;
        req.session.userType = user.userType;

        // Redirect to home.html 
        return res.redirect('/adminAndManager/home.html');
    });
};

exports.getUserDetails = (req, res) => {
    if (!req.session || !req.session.email) {
        return res.status(401).json({ error: 'User not logged in' });
    }

    res.json({
        email: req.session.email,
        userType: req.session.userType, 
    });
};

exports.getUsersByStatus = (req, res) => {
    const sql = `
        SELECT first_name, last_name, email, status
        FROM users
    `;

    db.query(sql, (err, results) => {
        if (err) {
            console.error('Database error:', err);
            return res.status(500).json({ error: 'Failed to fetch users.' });
        }

        // Group users by their status
        const groupedUsers = {
            pending: results.filter(user => user.status === 'pending'),
            approved: results.filter(user => user.status === 'approved'),
            rejected: results.filter(user => user.status === 'rejected'),
        };

        res.json(groupedUsers);
    });
}

exports.getApprovedUsers = (req, res) => {
    const { status } = req.query;
  
    if (status !== "approved") {
      return res.status(400).json({ error: "Invalid status parameter" });
    }
  
    const sql = `
        SELECT 
            id,
            first_name,
            middle_name,
            last_name,
            email,
            DATE_FORMAT(created_at, '%Y-%m-%d') AS date_created,
            user_address,
            bio,
            employment_status,
            status,
            profile_picture,
            userType,
            batch_id,
            school_id,
            course_id,
            gender
        FROM 
            users
        WHERE 
            status = ?;
    `;

    db.query(sql, [status], (err, results) => {
      if (err) {
        console.error("Database error:", err);
        return res.status(500).json({ error: "Failed to fetch users" });
      }
      res.json(results);
    });
};
  

exports.updateUserStatus = (req, res) => {
    const { email, status } = req.body;

    if (!email || !status) {
        return res.status(400).json({ error: 'Email and status are required.' });
    }

    const allowedStatuses = ['approved', 'rejected', 'pending'];
    if (!allowedStatuses.includes(status)) {
        return res.status(400).json({ error: 'Invalid status value.' });
    }

    const sql = `
        UPDATE users
        SET status = ?
        WHERE email = ?
    `;

    db.query(sql, [status, email], (err, results) => {
        if (err) {
            console.error('Database error:', err);
            return res.status(500).json({ error: 'Failed to update status.' });
        }
        res.json({ message: `User status updated to ${status}.` });
    });
}

exports.getUserDetailsByEmail = (req, res) => {
    const email = req.params.email; 

    if (!email) {
        return res.status(400).json({ error: 'Email is required.' });
    }

    const sql = `
        SELECT first_name, middle_name, last_name, email, gender, alumni_photo_validation
        FROM users
        WHERE email = ?
    `;

    db.query(sql, [email], (err, results) => {
        if (err) {
            console.error('Database error:', err);
            return res.status(500).json({ error: 'Failed to fetch user details.' });
        }

        if (results.length === 0) {
            return res.status(404).json({ error: 'User not found.' });
        }

        const user = results[0];

        const base64Photo = user.alumni_photo_validation
        ? Buffer.from(user.alumni_photo_validation).toString('base64')
        : null;

        res.json({
            firstName: user.first_name,
            middleName: user.middle_name,
            lastName: user.last_name,
            email: user.email,
            gender: user.gender,
            alumniPhotoValidation: base64Photo,
        });
    });
};

exports.getUserById = (req, res) => {
    const { id } = req.params;
    const sql = `SELECT id, first_name, middle_name, last_name, email, 
                 DATE_FORMAT(created_at, '%Y-%m-%d') AS date_created, 
                 user_address, bio, employment_status, status, 
                 profile_picture, userType, batch_id, school_id, course_id, gender
                 FROM users WHERE id = ?`;
    db.query(sql, [id], (err, result) => {
      if (err) return res.status(500).json({ error: "Failed to fetch user details" });
      res.json(result[0]);
    });
};

exports.getBatches = (req, res) => {
    const sql = `
      SELECT id, batch_number 
      FROM batches 
      WHERE id != 1
    `;
  
    db.query(sql, (err, results) => {
      if (err) {
        console.error("Database error:", err);
        return res.status(500).json({ error: "Failed to fetch batches." });
      }
      res.json(results);
    });
};
  
exports.addUser = async (req, res) => {
    const {
      firstName,
      middleName,
      lastName,
      email,
      password,
      batchId, 
      school,
      course,
      gender,
      userType,
    } = req.body;
  
    try {
      // Get the batch_id (primary key) using batch_number
      const batchQuery = "SELECT id FROM batches WHERE batch_number = ?";
      const [batchRows] = await db.promise().query(batchQuery, [batchId]); 
  
      if (batchRows.length === 0) {
        return res.status(400).json({ error: "Invalid batch number provided." });
      }
  
      const batch_id = batchRows[0].id; 
  
      // Hash the password
      const hashedPassword = bcrypt.hashSync(password, 10);
  
      // Insert user data
      const sql = `
        INSERT INTO users (
          first_name, middle_name, last_name, email, password_hash,
          batch_id, school_id, course_id, gender, userType, status
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')
      `;
      const params = [
        firstName,
        middleName || null,
        lastName,
        email,
        hashedPassword,
        batch_id, // Use the correct batch_id here
        school,
        course,
        gender,
        userType,
      ];
  
      const [result] = await db.promise().query(sql, params);
      res.json({ message: "User added successfully." });
    } catch (err) {
      console.error("Database error:", err);
      res.status(500).json({ error: "Failed to add user." });
    }
};
  
exports.updateUser = (req, res) => {
    const { id, firstName, middleName, lastName, email, userAddress, bio, employmentStatus, status, userType, batchId, schoolId, courseId, gender } = req.body;
  
    const sql = `UPDATE users SET first_name = ?, middle_name = ?, last_name = ?, email = ?, 
                 user_address = ?, bio = ?, employment_status = ?, status = ?, userType = ?, 
                 batch_id = ?, school_id = ?, course_id = ?, gender = ? WHERE id = ?`;
  
    db.query(sql, [firstName, middleName, lastName, email, userAddress, bio, employmentStatus, status, userType, batchId, schoolId, courseId, gender, id], 
      (err) => {
        if (err) return res.status(500).json({ error: "Failed to update user" });
        res.json({ message: "User updated successfully" });
      });
};
  



