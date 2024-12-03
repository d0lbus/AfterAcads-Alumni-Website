const express = require('express');
const router = express.Router();
const eventController = require('../controllers/eventController');

router.get('/statistics', eventController.getEventStatistics);

module.exports = router;
