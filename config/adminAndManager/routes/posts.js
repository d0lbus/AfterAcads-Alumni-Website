const express = require('express');
const router = express.Router();
const postController = require('../controllers/postController');
const postStatisticsController = require('../controllers/postStatisticsController');

// Retrieve posts by status
router.get('/status', postController.getPostsByStatus);

router.get('/details/:id', postController.getPostDetailsByID);

router.post('/updateStatus', postController.updatePostStatus);

// Routes for post statistics
router.get('/total', postStatisticsController.getTotalPosts);
router.get('/status', postStatisticsController.getPostsGroupedByStatus);
router.get('/batches', postStatisticsController.getPostsGroupedByBatch);
router.get('/school-course-group', postStatisticsController.getPostsGroupedBySchoolAndCourse);
router.get('/top-tags', postStatisticsController.getTopTags);


module.exports = router;
