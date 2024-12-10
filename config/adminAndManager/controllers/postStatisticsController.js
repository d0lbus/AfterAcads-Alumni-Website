const db = require('../db'); 

// Get total posts
exports.getTotalPosts = (req, res) => {
    const sql = `
        SELECT COUNT(*) AS total_posts
        FROM posts;
    `;
    db.query(sql, (err, results) => {
        if (err) {
            return res.status(500).json({ error: 'Database error: ' + err.message });
        }
        res.json({ totalPosts: results[0].total_posts });
    });
};

// Get posts grouped by status
exports.getPostsGroupedByStatus = (req, res) => {
    const sql = `
        SELECT status, COUNT(*) AS total
        FROM posts
        GROUP BY status;
    `;
    db.query(sql, (err, results) => {
        if (err) {
            return res.status(500).json({ error: 'Database error: ' + err.message });
        }
        res.json(results);
    });
};

// Get posts grouped by school and course
exports.getPostsGroupedBySchoolAndCourse = (req, res) => {
    const sql = `
        SELECT 
            s.name AS school_name, 
            c.name AS course_name, 
            COUNT(p.id) AS total_posts
        FROM posts p
        JOIN schools s ON p.school_id = s.id
        JOIN courses c ON p.course_id = c.id
        GROUP BY s.name, c.name
        ORDER BY s.name, c.name;
    `;
    db.query(sql, (err, results) => {
        if (err) {
            return res.status(500).json({ error: 'Database error: ' + err.message });
        }

        // Group results by school name
        const groupedData = results.reduce((acc, row) => {
            const school = row.school_name;
            if (!acc[school]) {
                acc[school] = [];
            }
            acc[school].push({
                course_name: row.course_name,
                total_posts: row.total_posts,
            });
            return acc;
        }, {});

        res.json(groupedData);
    });
};

// Get posts grouped by batch
exports.getPostsGroupedByBatch = (req, res) => {
    const sql = `
        SELECT b.batch_number, COUNT(p.id) AS total_posts
        FROM posts p
        JOIN batches b ON p.batch_id = b.id
        GROUP BY b.batch_number
        ORDER BY total_posts DESC;
    `;
    db.query(sql, (err, results) => {
        if (err) {
            return res.status(500).json({ error: 'Database error: ' + err.message });
        }
        res.json(results);
    });
};

// Get top 5 most used tags
exports.getTopTags = (req, res) => {
    const sql = `
        SELECT t.name AS tag_name, COUNT(pt.tag_id) AS usage_count
        FROM post_tags pt
        JOIN tags t ON pt.tag_id = t.id
        GROUP BY t.name
        ORDER BY usage_count DESC
        LIMIT 5;
    `;
    db.query(sql, (err, results) => {
        if (err) {
            return res.status(500).json({ error: 'Database error: ' + err.message });
        }
        res.json(results);
    });
};