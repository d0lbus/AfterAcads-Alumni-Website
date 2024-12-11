// References:
// 1. ChatGPT - Assisted with API routing and structure: https://chat.openai.com
// 2. Express.js Documentation - Routing and middleware: https://expressjs.com/en/guide/routing.html
// 3. REST API Best Practices - Structuring endpoints: https://restfulapi.net/resource-naming/
// 4. YouTube - Implementing REST APIs in Node.js:
//    - https://www.youtube.com/watch?v=pKd0Rpw7O48
//    - https://www.youtube.com/watch?v=Oe421EPjeBE

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
