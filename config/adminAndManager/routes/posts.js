const express = require('express');
const router = express.Router();
const postController = require('../controllers/postController');

router.get('/statistics', postController.getPostStatistics);

module.exports = router;
