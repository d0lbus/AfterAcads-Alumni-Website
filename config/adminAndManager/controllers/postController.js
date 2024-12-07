const db = require('../db');


// Retrieve posts details by Post ID
exports.getPostDetailsByID = (req, res) => {
  const postId = req.params.id;

  if (!postId) {
      return res.status(400).json({ error: 'Post ID is required.' });
  }

  const sql = `
      SELECT 
          p.id AS post_id, 
          p.content, 
          p.created_at AS date,
          u.email, 
          CONCAT(u.first_name, ' ', u.last_name) AS author, 
          p.image,
          s.name AS school,
          c.name AS course,
          b.batch_number AS batch
      FROM posts p
      JOIN users u ON p.user_id = u.id
      JOIN schools s ON p.school_id = s.id
      JOIN courses c ON p.course_id = c.id
      JOIN batches b ON p.batch_id = b.id
      WHERE p.id = ?
  `;
  db.query(sql, [postId], (err, results) => {
      if (err) {
          console.error('Database error:', err);
          return res.status(500).json({ error: 'Failed to fetch post details.' });
      }

      if (results.length === 0) {
          return res.status(404).json({ error: 'Post not found.' });
      }

      const post = results[0];
      res.json({
          post_id: post.post_id,
          content: post.content,
          date: post.date,
          author: post.author,
          email: post.email,
          school: post.school || 'N/A',
          course: post.course || 'N/A',
          batch: post.batch || 'N/A',
          image: post.image ? post.image.toString('base64') : null, // Convert image blob to base64
      });
  });
};

// Retrieve posts grouped by status
exports.getPostsByStatus = (req, res) => {
  const sql = `
      SELECT p.id AS post_id, p.content, p.created_at AS date,
             u.email, CONCAT(u.first_name, ' ', u.last_name) AS author, p.status
      FROM posts p
      JOIN users u ON p.user_id = u.id
  `;
  db.query(sql, (err, results) => {
      if (err) {
          console.error('Database error:', err);
          return res.status(500).json({ error: 'Failed to fetch posts.' });
      }
      // Group by status
      const groupedPosts = {
          pending: results.filter(post => post.status === 'pending'),
          approved: results.filter(post => post.status === 'approved'),
          rejected: results.filter(post => post.status === 'rejected'),
      };
      res.json(groupedPosts);
  });
};

// Update post status by post ID
exports.updatePostStatus = (req, res) => {
  const { id, status } = req.body;

  if (!id || !status) {
      return res.status(400).json({ error: 'Post ID and status are required.' });
  }

  const sql = `
      UPDATE posts
      SET status = ?
      WHERE id = ?
  `;
  db.query(sql, [status, id], (err, results) => {
      if (err) {
          console.error('Database error:', err);
          return res.status(500).json({ error: 'Failed to update post status.' });
      }

      if (results.affectedRows === 0) {
          return res.status(404).json({ error: 'Post not found.' });
      }

      res.json({ message: `Post status updated to ${status}.` });
  });
};

exports.getPostStatistics = (req, res) => {
  db.query(
    `SELECT COUNT(*) AS totalPosts FROM posts`,
    (error, totalResults) => {
      if (error) {
        return res.status(500).json({ error: error.message });
      }

      db.query(
        `SELECT tag, COUNT(*) AS totalPostsPerTag FROM posts GROUP BY tag`,
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
};
