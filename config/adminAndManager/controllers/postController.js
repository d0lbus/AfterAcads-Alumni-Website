const db = require('../db');


// Retrieve posts grouped by status
exports.getPostsByStatus = (req, res) => {
  const status = req.query.status;

  if (!status) {
      return res.status(400).json({ error: 'Status is required.' });
  }

  const validStatuses = ['pending', 'approved', 'rejected'];
  if (!validStatuses.includes(status)) {
      return res.status(400).json({ error: `Invalid status. Allowed values are ${validStatuses.join(', ')}.` });
  }

  const sql = `
    SELECT 
      posts.id AS post_id,
      posts.content,
      posts.created_at AS date,
      posts.status,
      users.email,
      CONCAT(users.first_name, ' ', users.last_name) AS author
    FROM posts
    JOIN users ON posts.user_id = users.id
  `;

  db.query(sql, (err, results) => {
    if (err) {
      console.error('Database error:', err);
      return res.status(500).json({ error: 'Failed to fetch posts.' });
    }

    const groupedPosts = {
      pending: results.filter(post => post.status === 'pending'),
      approved: results.filter(post => post.status === 'approved'),
      rejected: results.filter(post => post.status === 'rejected'),
    };

    res.json(groupedPosts);
  });
};

// Retrives posts details by ID
exports.getPostDetailsByID = (req, res) =>{
  const postId = req.params.id;

  if (!postId) {
    return res.status(400).json({ error: 'Post ID is required.' });
  }

  const sql = `
    SELECT 
      posts.id AS post_id,
      posts.content,
      posts.created_at AS date,
      posts.image,
      users.email,
      CONCAT(users.first_name, ' ', users.last_name) AS author
    FROM posts
    JOIN users ON posts.user_id = users.id
    WHERE posts.id = ?
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
      email: post.email,
      author: post.author,
      image: post.image ? post.image.toString('base64') : null, // Convert image blob to base64
    });
  });
}

// Update status of post
exports.updatePostStatus = (req, res) =>{
  const { id, status } = req.body;

  if (!id || !status) {
    return res.status(400).json({ error: 'Post ID and status are required.' });
  }

  const sql = `UPDATE posts SET status = ? WHERE id = ?`;
  db.query(sql, [status, id], (err, results) => {
    if (err) {
      console.error('Database error:', err);
      return res.status(500).json({ error: 'Failed to update post status.' });
    }

    res.json({ message: `Post status updated to ${status}.` });
  });
}

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
