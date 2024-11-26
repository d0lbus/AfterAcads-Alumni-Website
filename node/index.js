const express = require('express');
const mysql = require('mysql');
const cors = require('cors');
const app = express();
const port = 3000;

// Connection pool to the database
const db = mysql.createPool({
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'afteracads'
});


// Enable CORS
app.use(cors());

// Endpoint 1: User Statistics
app.get('/api/users/statistics', (req, res) => {
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
        res.json(results[0]); // Single result object
      }
    );
  });
  
  // Endpoint 2: Post Statistics
  app.get('/api/posts/statistics', (req, res) => {
    db.query(
      `SELECT 
         COUNT(*) AS totalPosts
       FROM posts`,
      (error, totalResults) => {
        if (error) {
          return res.status(500).json({ error: error.message });
        }
  
        db.query(
          `SELECT 
             tag, 
             COUNT(*) AS totalPostsPerTag 
           FROM posts 
           GROUP BY tag`,
          (error, tagResults) => {
            if (error) {
              return res.status(500).json({ error: error.message });
            }
  
            res.json({
              totalPosts: totalResults[0].totalPosts,
              postsPerTag: tagResults
            });
          }
        );
      }
    );
  });
  
  // Endpoint 3: Event Statistics
  app.get('/api/events/statistics', (req, res) => {
    db.query(
      `SELECT 
         COUNT(*) AS totalEvents 
       FROM events`,
      (error, results) => {
        if (error) {
          return res.status(500).json({ error: error.message });
        }
        res.json(results[0]); // Single result object
      }
    );
  });

  
// Start the server
app.listen(port, () => {
  console.log(`Node.js server is running on port ${port}`);
});
