const db = require('../db');

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
