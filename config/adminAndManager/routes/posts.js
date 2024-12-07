const express = require('express');
const router = express.Router();
const postController = require('../controllers/postController');

// Retrieve posts by status
router.get('/status', postController.getPostsByStatus);

router.get('/details/:id', postController.updatePostStatus);

router.post('/updateStatus', postController.updatePostStatus);

router.get('/statistics', postController.getPostStatistics);


module.exports = router;
